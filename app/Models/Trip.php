<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_type',
        'title',
        'price',
        'event_date',
        'description',
        'gallery',
        'main_image',
        'max_people',
        'duration_minutes',
        'min_age',
        'guide_id',
        'route_id',
    ];

    protected $casts = [
        'gallery' => 'array',
        'event_date' => 'date',
    ];

    public function getMainImageUrlAttribute(): string
    {
        $image = $this->attributes['main_image'] ?? null;

        if (! $image && is_array($this->gallery) && count($this->gallery) && $this->gallery[0]) {
            $image = $this->gallery[0];
        }

        if (! $image || $image === 'img/empty.png') {
            return asset('img/empty.png');
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return asset('storage/'.ltrim($image, '/'));
    }

    public function getMainImageAttribute(): string
    {
        if (! empty($this->attributes['main_image'])) {
            return $this->attributes['main_image'];
        }

        if (is_array($this->gallery) && count($this->gallery) && $this->gallery[0]) {
            return $this->gallery[0];
        }

        return 'img/empty.png';
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
