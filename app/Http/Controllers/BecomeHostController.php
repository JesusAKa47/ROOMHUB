<?php

namespace App\Http\Controllers;

use App\Models\HostVerification;
use App\Models\Owner;
use App\Models\User;
use App\Notifications\NewVerificationSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BecomeHostController extends Controller
{
    /** Preguntas de verificación legal (claves q1, q2, ...). */
    public static function verificationQuestions(): array
    {
        return [
            'q1' => '¿Confirmas que la información y documentos proporcionados son verídicos y corresponden a tu identidad?',
            'q2' => '¿Aceptas los términos y condiciones para anfitriones de RoomHub?',
            'q3' => '¿Eres el titular del inmueble que deseas publicar o tienes autorización expresa del propietario?',
        ];
    }

    /**
     * Paso 1: si no tiene verificación → formulario verificación. Si ya tiene → paso 2 (completar perfil).
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->canBecomeHost()) {
            if ($user->isOwner()) {
                return redirect()->route('owner.dashboard');
            }
            return redirect()->route('dashboard');
        }

        $verification = $user->hostVerification;

        if (! $verification) {
            return view('become-host.verification', [
                'questions' => self::verificationQuestions(),
            ]);
        }

        if ($verification->isRejected()) {
            if ($request->boolean('retry')) {
                return view('become-host.verification', [
                    'questions' => self::verificationQuestions(),
                    'rejected' => true,
                    'message' => $verification->rejection_reason
                        ?: 'Tu verificación fue rechazada. Revisa que la foto del INE se vea clara y vuelve a enviar.',
                ]);
            }
            return view('become-host.rejected', ['verification' => $verification]);
        }

        if ($verification->isPending() || $verification->isUnderReview()) {
            return view('become-host.waiting', ['verification' => $verification]);
        }

        return redirect()->route('become-host.complete');
    }

    /**
     * Guardar verificación legal (foto INE + respuestas).
     */
    public function storeVerification(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->canBecomeHost()) {
            return redirect()->route('dashboard');
        }

        $questions = self::verificationQuestions();
        $rules = [
            'ine_photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
        foreach (array_keys($questions) as $key) {
            $rules[$key] = ['required', 'in:si,no'];
        }
        $request->validate($rules);

        foreach (array_keys($questions) as $key) {
            if ($request->input($key) !== 'si') {
                return redirect()->back()->withInput()->withErrors([
                    $key => 'Debes aceptar todas las declaraciones para continuar como anfitrión.',
                ]);
            }
        }

        $answers = [];
        foreach (array_keys($questions) as $key) {
            $answers[$key] = $request->input($key);
        }

        $path = $request->file('ine_photo')->store('verifications/ine', 'public');

        $verification = $user->hostVerification;
        if ($verification) {
            if ($verification->ine_photo_path) {
                Storage::disk('public')->delete($verification->ine_photo_path);
            }
            $verification->update([
                'ine_photo_path' => $path,
                'answers' => $answers,
                'status' => 'pendiente',
                'rejection_reason' => null,
                'reviewed_at' => null,
                'submitted_at' => now(),
            ]);
        } else {
            HostVerification::create([
                'user_id' => $user->id,
                'ine_photo_path' => $path,
                'answers' => $answers,
                'status' => 'pendiente',
                'submitted_at' => now(),
            ]);
        }

        $verification = $user->hostVerification()->first();
        if ($verification) {
            foreach (User::where('role', User::ROLE_ADMIN)->get() as $admin) {
                $admin->notify(new NewVerificationSubmittedNotification($verification));
            }
        }

        return redirect()->route('become-host.show')->with('ok', 'Tu verificación fue enviada. Un administrador la revisará pronto. Te avisaremos cuando esté aprobada para que puedas completar tu perfil de anfitrión.');
    }

    /**
     * Paso 2: formulario para completar perfil de anfitrión (solo si tiene verificación).
     */
    public function showComplete(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->canBecomeHost()) {
            return redirect()->route('dashboard');
        }

        $verification = $user->hostVerification;
        if (! $verification || $verification->isRejected()) {
            return redirect()->route('become-host.show');
        }
        if (! $verification->canCompleteProfile()) {
            return redirect()->route('become-host.show')->with('info', 'Tu verificación aún está en revisión. Podrás completar tu perfil de anfitrión cuando un administrador la apruebe.');
        }

        return view('become-host.form');
    }

    /**
     * Crear Owner y convertir en anfitrión (paso 2 submit).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->canBecomeHost()) {
            return redirect()->route('dashboard');
        }

        $verification = $user->hostVerification;
        if (! $verification || ! $verification->canCompleteProfile()) {
            return redirect()->route('become-host.show')->withErrors(['general' => 'Debes tener la verificación aprobada por un administrador para completar tu perfil de anfitrión.']);
        }

        $request->validate([
            'phone_country' => ['required', 'string', 'in:+52,+1,+34'],
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'type' => ['required', 'in:persona,empresa'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'phone_country.required' => 'Selecciona la lada internacional.',
            'phone_country.in' => 'La lada seleccionada no es válida.',
            'phone_number.required' => 'Escribe tu número de teléfono.',
            'phone_number.regex' => 'El número solo debe contener dígitos (sin espacios ni signos).',
        ]);

        $country = $request->phone_country;
        $rawNumber = preg_replace('/\D+/', '', $request->phone_number);

        $expectedLengthOk = match ($country) {
            '+52', // México: 10 dígitos
            '+1'   // EE.UU./Canadá: 10 dígitos
                => strlen($rawNumber) === 10,
            '+34'  // España: 9 dígitos
                => strlen($rawNumber) === 9,
            default => strlen($rawNumber) >= 6 && strlen($rawNumber) <= 15,
        };

        if (! $expectedLengthOk) {
            return back()
                ->withInput()
                ->withErrors([
                    'phone_number' => 'El número no coincide con la longitud esperada para la lada seleccionada.',
                ]);
        }

        $fullPhone = trim($country . ' ' . $rawNumber);

        DB::transaction(function () use ($request, $user, $fullPhone) {
            $owner = Owner::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $fullPhone,
                'type' => $request->type,
                'notes' => $request->notes ?? '',
            ]);

            $user->update([
                'role' => User::ROLE_OWNER,
                'owner_id' => $owner->id,
            ]);
        });

        return redirect()->route('owner.dashboard')->with('ok', '¡Bienvenido como anfitrión! Ya puedes gestionar tus alojamientos. También puedes seguir usando RoomHub como huésped.');
    }
}
