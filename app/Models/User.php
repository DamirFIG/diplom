<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'name',
        'phone',
        'email',
        'password',
        'is_banned',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_banned' => 'boolean',
        'banned_at' => 'datetime',
        'failed_login_attempts' => 'integer',
        'locked_until' => 'datetime',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id')
            ->withTimestamps();
    }

    /**
     * Проверить, является ли элемент избранным
     */
    public function isFavorite($itemId): bool
    {
        return $this->favorites()->where('item_id', $itemId)->exists();
    }

    /**
     * Забанить пользователя
     */
    public function ban(): bool
    {
        if ($this->role === 'admin') {
            return false;
        }

        $this->is_banned = true;
        $this->banned_at = now();
        $this->save();

        return true;
    }

    /**
     * Разбанить пользователя
     */
    public function unban(): bool
    {
        $this->is_banned = false;
        $this->banned_at = null;
        $this->save();

        return true;
    }

    /**
     * Проверка, забанен ли пользователь
     */
    public function isBanned(): bool
    {
        return $this->is_banned;
    }

    /**
     * Проверка, заблокирован ли пользователь временно
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Оставшееся время блокировки в секундах
     */
    public function getLockRemainingSeconds(): int
    {
        if (!$this->isLocked()) {
            return 0;
        }
        return max(0, now()->diffInSeconds($this->locked_until, false));
    }

    /**
     * Увеличить счетчик неудачных попыток
     */
    public function incrementFailedLoginAttempts(int $lockDurationSeconds = 30, int $maxAttempts = 3): void
    {
        $this->increment('failed_login_attempts');

        if ($this->failed_login_attempts >= $maxAttempts) {
            $this->locked_until = now()->addSeconds($lockDurationSeconds);
            $this->save();
        }
    }

    /**
     * Сбросить счетчик неудачных попыток
     */
    public function resetFailedLoginAttempts(): void
    {
        $this->failed_login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }
}
