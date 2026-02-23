<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /** Comisión de la plataforma RoomHub (10%). */
    public const PLATFORM_COMMISSION_RATE = 0.10;

    public const PAYMENT_STRIPE = 'stripe';
    public const PAYMENT_TRANSFER = 'transfer';
    public const PAYMENT_CASH = 'cash';
    public const PAYMENT_OTHER = 'other';

    protected $fillable = [
        'apartment_id', 'user_id', 'client_id',
        'check_in', 'check_out', 'rent_type',
        'total_amount_cents', 'currency', 'payment_method',
        'stripe_session_id', 'stripe_payment_intent_id',
        'status', 'guest_notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
        ];
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getTotalAmountAttribute(): float
    {
        return $this->total_amount_cents / 100;
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /** Folio de la transacción: ID de Stripe si existe, si no RES-{id}. */
    public function getTransactionFolioAttribute(): string
    {
        if (! empty($this->stripe_payment_intent_id)) {
            return $this->stripe_payment_intent_id;
        }
        return 'RES-' . $this->id;
    }

    /** Comisión de la plataforma en centavos (10% del total). */
    public function getCommissionCentsAttribute(): int
    {
        return (int) round($this->total_amount_cents * self::PLATFORM_COMMISSION_RATE);
    }

    /** Monto para el dueño en centavos (total menos comisión). */
    public function getOwnerAmountCentsAttribute(): int
    {
        return $this->total_amount_cents - $this->commission_cents;
    }

    /** Etiqueta del método de pago para vistas. */
    public static function paymentMethodLabel(string $method): string
    {
        return match ($method) {
            self::PAYMENT_STRIPE => 'Tarjeta (Stripe)',
            self::PAYMENT_TRANSFER => 'Transferencia',
            self::PAYMENT_CASH => 'Efectivo',
            self::PAYMENT_OTHER => 'Otro',
            default => $method,
        };
    }
}
