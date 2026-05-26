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

    public function getGalleryAttribute($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    public function getMainImageAttribute(): string
    {
        $gallery = $this->gallery;

        if (is_array($gallery) && !empty($gallery[0])) {
            return $gallery[0];
        }

        if (!empty($this->attributes['main_image'])) {
            return $this->attributes['main_image'];
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
