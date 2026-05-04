<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::take(3)->get();

        if ($users->count() === 0) {
            return;
        }

        Review::create([
            'user_id'  => 'Димка',
            'text'     => 'Катались на сапбордах и гидроцикле — эмоции просто огонь. Гиды не давали заскучать.',
            'rating'   => 5,
            'likes'    => 12,
            'dislikes' => 1,
        ]);

        Review::create([
            'user_id'  => 'Димка',
            'text'     => 'Очень понравилась прогулка на катере, особенно атмосфера и рассказы гида.',
            'rating'   => 4,
            'likes'    => 8,
            'dislikes' => 0,
        ]);

        Review::create([
            'user_id'  => 'Димка',
            'text'     => 'Брали флайборд — необычно и захватывающе, обязательно попробую ещё.',
            'rating'   => 5,
            'likes'    => 15,
            'dislikes' => 2,
        ]);
    }
}
