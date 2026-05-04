<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Item;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $items = Item::all();
        $trips = Trip::all();

        if ($items->isEmpty() && $trips->isEmpty()) {
            $this->command->info('Нет товаров или поездок для создания отзывов.');
            return;
        }

        // Если нет пользователей, создадим тестового
        if ($users->isEmpty()) {
            $user = User::create([
                'login' => 'reviewer',
                'name' => 'Тестовый пользователь',
                'email' => 'reviewer@test.com',
                'phone' => '+79990000000',
                'password' => bcrypt('password'),
            ]);
            $users = collect([$user]);
            $this->command->info('Создан тестовый пользователь для отзывов.');
        }

        $reviewTexts = [
            'Отличная прогулка! Всё понравилось, особенно виды на воде.',
            'Очень понравилось! Инструктор был внимательный, всё объяснил.',
            'Хорошее место для отдыха с семьёй. Дети в восторге!',
            'Прекрасный маршрут, красивые виды. Рекомендую!',
            'Всё супер! Обязательно приду ещё раз.',
            'Понравилось, но хотелось бы больше времени на маршруте.',
            'Отличный сервис, вежливый персонал. Всё понравилось!',
            'Замечательная поездка! Получили массу эмоций.',
            'Всё хорошо, но было немного холодно. Взяли бы тёплые вещи.',
            'Очень круто! Советую всем любителям водного отдыха.',
            'Прекрасно провели время! Гид был очень опытный.',
            'Всё понравилось, кроме того что было много людей.',
            'Отличная организация, всё чётко по времени.',
            'Супер! Давно хотел попробовать, теперь буду постоянным клиентом.',
            'Хорошее соотношение цены и качества.',
        ];

        $reviewsCreated = 0;

        // Создаём отзывы для Items
        foreach ($items as $item) {
            $reviewCount = rand(2, 5);

            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->random();

                Review::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'trip_id' => null,
                    'rating' => rand(4, 5),
                    'text' => $reviewTexts[array_rand($reviewTexts)],
                    'likes' => rand(0, 10),
                    'dislikes' => rand(0, 2),
                ]);
                $reviewsCreated++;
            }
        }

        // Создаём отзывы для Trips
        foreach ($trips as $trip) {
            $reviewCount = rand(2, 5);

            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->random();

                Review::create([
                    'user_id' => $user->id,
                    'item_id' => null,
                    'trip_id' => $trip->id,
                    'rating' => rand(4, 5),
                    'text' => $reviewTexts[array_rand($reviewTexts)],
                    'likes' => rand(0, 10),
                    'dislikes' => rand(0, 2),
                ]);
                $reviewsCreated++;
            }
        }

        $this->command->info("Отзывы успешно созданы! Всего: {$reviewsCreated}");
    }
}
