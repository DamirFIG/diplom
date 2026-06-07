<?php

namespace App\Models;

use App\Models\Concerns\HasCardImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasCardImages, HasFactory;

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
