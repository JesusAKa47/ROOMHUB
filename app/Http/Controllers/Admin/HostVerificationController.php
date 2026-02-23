<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostVerification;
use App\Notifications\VerificationApprovedNotification;
use App\Notifications\VerificationRejectedNotification;
use App\Notifications\VerificationUnderReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostVerificationController extends Controller
{
    public function index()
    {
        $verifications = HostVerification::with('user')
            ->orderByRaw("CASE status WHEN 'pendiente' THEN 1 WHEN 'en_revision' THEN 2 WHEN 'aprobado' THEN 3 WHEN 'rechazado' THEN 4 ELSE 5 END")
            ->orderByDesc('submitted_at')
            ->get();

        $pendientes = $verifications->whereIn('status', ['pendiente', 'en_revision'])->count();

        return view('admin.verifications.index', compact('verifications', 'pendientes'));
    }

    public function show(HostVerification $verification)
    {
        $verification->load('user');
        return view('admin.verifications.show', compact('verification'));
    }

    public function approve(HostVerification $verification): \Illuminate\Http\RedirectResponse
    {
        if (! in_array($verification->status, ['pendiente', 'en_revision'], true)) {
            return back()->withErrors(['general' => 'Esta verificación ya fue revisada.']);
        }

        $verification->update([
            'status' => HostVerification::STATUS_APPROVED,
            'rejection_reason' => null,
            'reviewed_at' => now(),
        ]);

        $verification->user->notify(new VerificationApprovedNotification($verification));

        return back()->with('ok', 'Verificación aprobada. El usuario ya puede completar su perfil de anfitrión.');
    }

    public function reject(Request $request, HostVerification $verification): \Illuminate\Http\RedirectResponse
    {
        if (! in_array($verification->status, ['pendiente', 'en_revision'], true)) {
            return back()->withErrors(['general' => 'Esta verificación ya fue revisada.']);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => 'Indica el motivo del rechazo (ej.: la imagen del INE no se ve bien).',
        ]);

        $verification->update([
            'status' => HostVerification::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
        ]);

        $verification->user->notify(new VerificationRejectedNotification($verification));

        return back()->with('ok', 'Verificación rechazada. El usuario verá el motivo y podrá volver a enviar.');
    }

    public function setUnderReview(HostVerification $verification): \Illuminate\Http\RedirectResponse
    {
        if ($verification->status !== 'pendiente') {
            return back()->withErrors(['general' => 'Solo se puede marcar "en revisión" si está pendiente.']);
        }

        $verification->update([
            'status' => HostVerification::STATUS_UNDER_REVIEW,
        ]);

        $verification->user->notify(new VerificationUnderReviewNotification($verification));

        return back()->with('ok', 'Marcada como en revisión.');
    }
}
