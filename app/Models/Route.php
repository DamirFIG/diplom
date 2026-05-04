<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'title',
        'description',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
    ];

    protected $casts = [
        'start_lat' => 'decimal:7',
        'start_lng' => 'decimal:7',
        'end_lat' => 'decimal:7',
        'end_lng' => 'decimal:7',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(RoutePoint::class)->orderBy('sort_order');
    }

    public function getStartPointAttribute(): ?array
    {
        if ($this->start_lat && $this->start_lng) {
            return ['lat' => (float)$this->start_lat, 'lng' => (float)$this->start_lng];
        }
        return null;
    }

    public function getEndPointAttribute(): ?array
    {
        if ($this->end_lat && $this->end_lng) {
            return ['lat' => (float)$this->end_lat, 'lng' => (float)$this->end_lng];
        }
        return null;
    }

    public function getAllPointsAttribute(): array
    {
        $points = [];

        foreach ($this->points as $point) {
            if ($point->is_visible && $point->lat && $point->lng) {
                $points[] = [
                    'lat' => (float)$point->lat,
                    'lng' => (float)$point->lng,
                    'title' => $point->title,
                    'description' => $point->description,
                    'image' => $point->image,
                    'type' => $point->type ?? 'stop',
                ];
            }
        }

        return $points;
    }

    public function getAllLinePointsAttribute(): array
    {
        $linePoints = [];

        foreach ($this->points as $point) {
            if ($point->lat && $point->lng) {
                $linePoints[] = ['lat' => (float)$point->lat, 'lng' => (float)$point->lng];
            }
        }

        return $linePoints;
    }
}
