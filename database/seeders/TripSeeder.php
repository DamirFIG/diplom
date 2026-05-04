<?php

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = [
            ['guide_id' => null, 'activity_type' => 'гидроцикл', 'title' => 'Развод мостов', 'description' => 'Ночная прогулка с просмотром разводных мостов.', 'price' => 3500, 'event_date' => now()->addDays(3), 'duration_minutes' => 120, 'max_people' => 10, 'min_age' => 12, 'main_image' => 'trips/bridges.jpg', 'gallery' => ['trips/bridges.jpg']],
            ['guide_id' => null, 'activity_type' => 'гидроцикл', 'title' => 'Финский залив', 'description' => 'Экскурсия по Финскому заливу.', 'price' => 4500, 'event_date' => now()->addDays(7), 'duration_minutes' => 180, 'max_people' => 8, 'min_age' => 14, 'main_image' => 'trips/bay.jpg', 'gallery' => ['trips/bay.jpg']],
            ['guide_id' => null, 'activity_type' => 'сапборд', 'title' => 'Закат на сапах', 'description' => 'Встреча заката на сапбордах.', 'price' => 2500, 'event_date' => now()->addDays(5), 'duration_minutes' => 150, 'max_people' => 12, 'min_age' => 12, 'main_image' => 'trips/sunset.jpg', 'gallery' => ['trips/sunset.jpg']],
            ['guide_id' => null, 'activity_type' => 'катамаран', 'title' => 'Острова Невы', 'description' => 'Прогулка по островам Невы.', 'price' => 5000, 'event_date' => now()->addDays(10), 'duration_minutes' => 240, 'max_people' => 6, 'min_age' => 8, 'main_image' => 'trips/islands.jpg', 'gallery' => ['trips/islands.jpg']],
            ['guide_id' => null, 'activity_type' => 'гидроцикл', 'title' => 'Ладожские шхеры', 'description' => 'Экспедиция на Ладожские шхеры.', 'price' => 8000, 'event_date' => now()->addDays(14), 'duration_minutes' => 360, 'max_people' => 6, 'min_age' => 16, 'main_image' => 'trips/skerry.jpg', 'gallery' => ['trips/skerry.jpg']],
            ['guide_id' => null, 'activity_type' => 'банан', 'title' => 'Банан вечеринка', 'description' => 'Весёлая прогулка на банане.', 'price' => 3000, 'event_date' => now()->addDays(4), 'duration_minutes' => 60, 'max_people' => 12, 'min_age' => 10, 'main_image' => 'trips/party.jpg', 'gallery' => ['trips/party.jpg']],
            ['guide_id' => null, 'activity_type' => 'сапборд', 'title' => 'Утренняя заря', 'description' => 'Ранняя прогулка на рассвете.', 'price' => 2000, 'event_date' => now()->addDays(6), 'duration_minutes' => 120, 'max_people' => 10, 'min_age' => 14, 'main_image' => 'trips/morning.jpg', 'gallery' => ['trips/morning.jpg']],
            ['guide_id' => null, 'activity_type' => 'гидроцикл', 'title' => 'Кронштадт', 'description' => 'Морская прогулка в Кронштадт.', 'price' => 7000, 'event_date' => now()->addDays(12), 'duration_minutes' => 300, 'max_people' => 8, 'min_age' => 16, 'main_image' => 'trips/kronstadt.jpg', 'gallery' => ['trips/kronstadt.jpg']],
            ['guide_id' => null, 'activity_type' => 'катамаран', 'title' => 'Семейный выход', 'description' => 'Прогулка для всей семьи.', 'price' => 4000, 'event_date' => now()->addDays(8), 'duration_minutes' => 180, 'max_people' => 8, 'min_age' => 5, 'main_image' => 'trips/family.jpg', 'gallery' => ['trips/family.jpg']],
            ['guide_id' => null, 'activity_type' => 'флайборд', 'title' => 'Полёт над водой', 'description' => 'Экстремальный полёт на флайборде.', 'price' => 6000, 'event_date' => now()->addDays(9), 'duration_minutes' => 90, 'max_people' => 4, 'min_age' => 16, 'main_image' => 'trips/flight.jpg', 'gallery' => ['trips/flight.jpg']],
            ['guide_id' => null, 'activity_type' => 'гидроцикл', 'title' => 'Южные берега', 'description' => 'Прогулка по южным берегам.', 'price' => 5500, 'event_date' => now()->addDays(11), 'duration_minutes' => 240, 'max_people' => 6, 'min_age' => 14, 'main_image' => 'trips/south.jpg', 'gallery' => ['trips/south.jpg']],
            ['guide_id' => null, 'activity_type' => 'сапборд', 'title' => 'Ночной город', 'description' => 'Ночная прогулка по огням города.', 'price' => 3000, 'event_date' => now()->addDays(13), 'duration_minutes' => 150, 'max_people' => 10, 'min_age' => 16, 'main_image' => 'trips/night.jpg', 'gallery' => ['trips/night.jpg']],
            ['guide_id' => null, 'activity_type' => 'банан', 'title' => 'Скоростной банан', 'description' => 'Быстрая прогулка на банане.', 'price' => 3500, 'event_date' => now()->addDays(15), 'duration_minutes' => 45, 'max_people' => 8, 'min_age' => 12, 'main_image' => 'trips/speed.jpg', 'gallery' => ['trips/speed.jpg']],
            ['guide_id' => null, 'activity_type' => 'катамаран', 'title' => 'Рыбалка на воде', 'description' => 'Рыбалка с катамарана.', 'price' => 5500, 'event_date' => now()->addDays(16), 'duration_minutes' => 300, 'max_people' => 4, 'min_age' => 14, 'main_image' => 'trips/fishing.jpg', 'gallery' => ['trips/fishing.jpg']],
            ['guide_id' => null, 'activity_type' => 'гидроцикл', 'title' => 'Дельта Невы', 'description' => 'Исследование дельты Невы.', 'price' => 6000, 'event_date' => now()->addDays(17), 'duration_minutes' => 240, 'max_people' => 6, 'min_age' => 14, 'main_image' => 'trips/delta.jpg', 'gallery' => ['trips/delta.jpg']],
            ['guide_id' => null, 'activity_type' => 'сапборд', 'title' => 'СПА на воде', 'description' => 'Йога и медитация на сапах.', 'price' => 2800, 'event_date' => now()->addDays(18), 'duration_minutes' => 120, 'max_people' => 8, 'min_age' => 16, 'main_image' => 'trips/spa.jpg', 'gallery' => ['trips/spa.jpg']],
            ['guide_id' => null, 'activity_type' => 'флайборд', 'title' => 'Обучение полёту', 'description' => 'Мастер-класс по флайборду.', 'price' => 8000, 'event_date' => now()->addDays(19), 'duration_minutes' => 120, 'max_people' => 4, 'min_age' => 14, 'main_image' => 'trips/learn.jpg', 'gallery' => ['trips/learn.jpg']],
            ['guide_id' => null, 'activity_type' => 'гидроцикл', 'title' => 'Морская крепость', 'description' => 'Прогулка к морским крепостям.', 'price' => 7500, 'event_date' => now()->addDays(20), 'duration_minutes' => 300, 'max_people' => 6, 'min_age' => 16, 'main_image' => 'trips/fortress.jpg', 'gallery' => ['trips/fortress.jpg']],
            ['guide_id' => null, 'activity_type' => 'банан', 'title' => 'Детский праздник', 'description' => 'Праздник для детей на банане.', 'price' => 4000, 'event_date' => now()->addDays(21), 'duration_minutes' => 60, 'max_people' => 10, 'min_age' => 6, 'main_image' => 'trips/kids.jpg', 'gallery' => ['trips/kids.jpg']],
            ['guide_id' => null, 'activity_type' => 'катамаран', 'title' => 'Закатный рейс', 'description' => 'Встреча заката на катамаране.', 'price' => 4500, 'event_date' => now()->addDays(22), 'duration_minutes' => 180, 'max_people' => 6, 'min_age' => 8, 'main_image' => 'trips/catamaran_sunset.jpg', 'gallery' => ['trips/catamaran_sunset.jpg']],
        ];

        foreach ($trips as $trip) {
            Trip::create($trip);
        }
    }
}
