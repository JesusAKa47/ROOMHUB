<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, MustVerifyEmailTrait, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_OWNER = 'owner';
    public const ROLE_CLIENT = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'owner_id',
        'client_id',
        'last_login_at',
        'postal_code',
        'state',
        'city',
        'municipality',
        'locality',
        'avatar',
        'privacy_show_name_public',
        'privacy_show_location_public',
        'privacy_show_last_login',
        'locale',
        'timezone',
        'stripe_customer_id',
        'two_factor_secret',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'privacy_show_name_public' => 'boolean',
            'privacy_show_location_public' => 'boolean',
            'privacy_show_last_login' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /** Verificación en dos pasos (2FA) activada. */
    public function hasTwoFactorEnabled(): bool
    {
        return ! empty($this->two_factor_secret) && $this->two_factor_confirmed_at !== null;
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function hostVerification()
    {
        return $this->hasOne(HostVerification::class);
    }

    public function apartmentComments()
    {
        return $this->hasMany(ApartmentComment::class);
    }

    /** Alojamientos que el usuario tiene en favoritos. */
    public function favoritedApartments()
    {
        return $this->belongsToMany(Apartment::class, 'favorites')->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class)->orderByDesc('created_at');
    }

    public function isActive(): bool
    {
        return $this->status !== self::STATUS_SUSPENDED;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    /** Usuario que aún no es anfitrión puede convertirse (estilo Airbnb). */
    public function canBecomeHost(): bool
    {
        return ! $this->isAdmin() && ! $this->owner_id;
    }

    /** Puede ver y rentar cuartos (tiene client_id o puede crear uno). */
    public function canRent(): bool
    {
        return ! $this->isAdmin() && ($this->client_id || $this->canActivateClientMode());
    }

    /** Puede activar modo cliente (crear Client si no existe). */
    public function canActivateClientMode(): bool
    {
        return ! $this->isAdmin() && ! $this->client_id;
    }

    /** Tiene ambos perfiles activos (cliente y anfitrión). */
    public function hasBothProfiles(): bool
    {
        return $this->client_id && $this->owner_id;
    }

    /** URL del avatar (servida por Laravel vía route files/, sin depender del symlink storage). */
    public function avatarUrl(): ?string
    {
        if (! $this->avatar) {
            return null;
        }
        $path = ltrim($this->avatar, '/');
        return url('files/' . $path);
    }

    /** Nombre para mostrar según privacidad (para anfitriones/otros usuarios). */
    public function publicName(): string
    {
        return $this->privacy_show_name_public ? $this->name : __('Usuario');
    }

    /** Ubicación para mostrar según privacidad (ciudad/estado). */
    public function publicLocation(): ?string
    {
        if (! $this->privacy_show_location_public) {
            return null;
        }
        $parts = array_filter([$this->city, $this->state]);
        return $parts ? implode(', ', $parts) : null;
    }

    /** Registrar una actividad del usuario (login, logout, etc.). */
    public function logActivity(string $action, ?array $metadata = null, ?string $ip = null): UserActivity
    {
        return $this->activities()->create([
            'action' => $action,
            'metadata' => $metadata,
            'ip_address' => $ip ?? request()?->ip(),
        ]);
    }
}
