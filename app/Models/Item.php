<?php

namespace App\Models;

use App\Models\Concerns\HasCardImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasCardImages, HasFactory;

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
}
