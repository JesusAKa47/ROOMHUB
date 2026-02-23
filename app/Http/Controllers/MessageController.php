<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Notifications\ProblemReportedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Mensajes donde participa el usuario
        $messages = Message::with(['sender', 'receiver', 'apartment'])
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->get();

        // Agrupar por otra persona (conversaciones)
        $threads = $messages->groupBy(function (Message $m) use ($user) {
            return $m->sender_id === $user->id ? $m->receiver_id : $m->sender_id;
        });

        $partnerId = (int) $request->query('user');
        if (! $partnerId && $threads->isNotEmpty()) {
            $partnerId = (int) $threads->keys()->first();
        }

        $activePartner = $partnerId ? User::find($partnerId) : null;
        $apartmentId = $request->query('apartment');
        $apartment = $apartmentId ? Apartment::find($apartmentId) : null;

        $conversation = collect();
        if ($activePartner) {
            $conversation = Message::with('sender')
                ->where(function ($q) use ($user, $activePartner) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $activePartner->id);
                })
                ->orWhere(function ($q) use ($user, $activePartner) {
                    $q->where('sender_id', $activePartner->id)->where('receiver_id', $user->id);
                })
                ->orderBy('created_at')
                ->get();

            // Marcar como leídos los mensajes recibidos
            Message::where('sender_id', $activePartner->id)
                ->where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return view('messages.index', [
            'user' => $user,
            'threads' => $threads,
            'activePartner' => $activePartner,
            'conversation' => $conversation,
            'apartment' => $apartment,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'receiver_id' => ['required', 'exists:users,id', 'different:sender_id'],
            'apartment_id' => ['nullable', 'exists:apartments,id'],
            'body' => ['required', 'string', 'max:2000'],
        ], [
            'body.required' => 'Escribe tu mensaje.',
        ]);

        $receiverId = (int) $data['receiver_id'];
        if ($receiverId === $user->id) {
            return back()->withErrors(['body' => 'No puedes enviarte mensajes a ti mismo.']);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'apartment_id' => $data['apartment_id'] ?? null,
            'body' => $data['body'],
        ]);

        $receiver = User::find($receiverId);
        if ($receiver) {
            $receiver->notify(new NewMessageNotification($message->load('sender')));
        }

        return redirect()
            ->route('messages.index', [
                'user' => $receiverId,
                'apartment' => $data['apartment_id'] ?? null,
            ])
            ->with('ok', 'Mensaje enviado.');
    }

    /**
     * Reportar una conversación: notifica a todos los admins (ej. mensaje inapropiado).
     */
    public function report(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'receiver_id' => ['required', 'exists:users,id', \Illuminate\Validation\Rule::notIn([$user->id])],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);
        $receiverId = (int) $data['receiver_id'];

        $other = User::find($receiverId);
        $reason = trim($data['reason'] ?? '');
        $message = $other
            ? sprintf('%s reportó una conversación con %s.', $user->name, $other->name)
            : sprintf('%s reportó una conversación.', $user->name);
        if ($reason !== '') {
            $message .= ' Motivo: ' . $reason;
        }
        $message .= ' Revisa el centro de notificaciones en el panel de administración.';

        foreach (User::where('role', User::ROLE_ADMIN)->get() as $admin) {
            $admin->notify(new ProblemReportedNotification(
                'Mensaje reportado',
                $message,
                route('admin.index')
            ));
        }

        return back()->with('ok', 'Reporte enviado. El equipo de RoomHub lo revisará.');
    }
}
