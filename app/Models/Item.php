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

    public function getMainImageAttribute(): string
    {
        if (is_array($this->gallery) && count($this->gallery) && $this->gallery[0]) {
            return $this->gallery[0];
        }
        return 'img/empty.png';
    }
}
