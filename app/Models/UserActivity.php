<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'metadata',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Etiqueta legible para la acción (para la vista). */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'login' => 'Inicio de sesión',
            'logout' => 'Cierre de sesión',
            'cuenta_bloqueada' => 'Cuenta bloqueada por administrador',
            'cuenta_desbloqueada' => 'Cuenta desbloqueada por administrador',
            'password_reset_requested' => 'Solicitud de restablecer contraseña',
            default => $this->action,
        };
    }
}
