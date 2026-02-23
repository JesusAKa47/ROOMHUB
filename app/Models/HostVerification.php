<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostVerification extends Model
{
    public const STATUS_PENDING = 'pendiente';
    public const STATUS_UNDER_REVIEW = 'en_revision';
    public const STATUS_APPROVED = 'aprobado';
    public const STATUS_REJECTED = 'rechazado';

    protected $fillable = [
        'user_id',
        'ine_photo_path',
        'answers',
        'status',
        'rejection_reason',
        'reviewed_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /** Puede completar paso 2 (crear perfil owner) solo si está aprobado. */
    public function canCompleteProfile(): bool
    {
        return $this->isApproved();
    }
}
