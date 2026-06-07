<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'activity_type',
        'gallery',
        'main_image',
        'description',
        'max_people',
        'duration_minutes',
        'min_age',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function usersWhoFavorite()
    {
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

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
}
