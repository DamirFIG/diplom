<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reviews')->whereNotNull('item_id')->delete();
        DB::table('favorites')->delete();
        DB::table('bookings')->whereNotNull('item_id')->delete();
        Item::query()->delete();

        $items = [
            [
                'activity_type' => 'гидроцикл',
                'title' => 'Гидроцикл Storm X',
                'price' => 6500,
                'description' => 'Мощный гидроцикл для динамичного катания по акватории и быстрых водных прогулок.',
                'max_people' => 2,
                'duration_minutes' => 60,
                'min_age' => 18,
                'main_image' => 'items/gidro1.jpg',
                'gallery' => ['items/gidro1.jpg'],
            ],
            [
                'activity_type' => 'гидроцикл',
                'title' => 'Гидроцикл Wave Runner',
                'price' => 6000,
                'description' => 'Удобный гидроцикл для прогулок вдвоём, подходит для спокойного и уверенного катания.',
                'max_people' => 2,
                'duration_minutes' => 60,
                'min_age' => 18,
                'main_image' => 'items/gidro2.jpg',
                'gallery' => ['items/gidro2.jpg'],
            ],
            [
                'activity_type' => 'гидроцикл',
                'title' => 'Гидроцикл Green Speed',
                'price' => 7000,
                'description' => 'Спортивный гидроцикл для ярких эмоций, скорости и активного отдыха на воде.',
                'max_people' => 2,
                'duration_minutes' => 45,
                'min_age' => 18,
                'main_image' => 'items/gidro3.jpg',
                'gallery' => ['items/gidro3.jpg'],
            ],
            [
                'activity_type' => 'банан',
                'title' => 'Банан Classic',
                'price' => 3000,
                'description' => 'Классическое катание на банане для компании друзей и семейного отдыха.',
                'max_people' => 6,
                'duration_minutes' => 20,
                'min_age' => 8,
                'main_image' => 'items/banan.jpg',
                'gallery' => ['items/banan.jpg'],
            ],
            [
                'activity_type' => 'банан',
                'title' => 'Банан Adventure',
                'price' => 3500,
                'description' => 'Весёлый маршрут на банане с поворотами и безопасным сопровождением инструктора.',
                'max_people' => 7,
                'duration_minutes' => 25,
                'min_age' => 10,
                'main_image' => 'items/banan2.jpg',
                'gallery' => ['items/banan2.jpg'],
            ],
            [
                'activity_type' => 'банан',
                'title' => 'Банан Family',
                'price' => 4000,
                'description' => 'Просторный банан для большой компании, отличный вариант для праздника на воде.',
                'max_people' => 8,
                'duration_minutes' => 30,
                'min_age' => 8,
                'main_image' => 'items/banan3.jpg',
                'gallery' => ['items/banan3.jpg'],
            ],
            [
                'activity_type' => 'сапборд',
                'title' => 'Сапборд Winter Blue',
                'price' => 1500,
                'description' => 'Устойчивый сапборд для спокойных прогулок, тренировок баланса и фото на воде.',
                'max_people' => 1,
                'duration_minutes' => 120,
                'min_age' => 12,
                'main_image' => 'items/sup1.jpg',
                'gallery' => ['items/sup1.jpg'],
            ],
            [
                'activity_type' => 'сапборд',
                'title' => 'Сапборд Sunset',
                'price' => 1800,
                'description' => 'Сапборд для прогулок на закате и размеренного отдыха на спокойной воде.',
                'max_people' => 1,
                'duration_minutes' => 120,
                'min_age' => 12,
                'main_image' => 'items/sup2.jpg',
                'gallery' => ['items/sup2.jpg'],
            ],
            [
                'activity_type' => 'сапборд',
                'title' => 'Сапборд Touring',
                'price' => 2000,
                'description' => 'Туринговый сапборд для длительных водных прогулок и уверенного хода по маршруту.',
                'max_people' => 1,
                'duration_minutes' => 180,
                'min_age' => 14,
                'main_image' => 'items/sup3.jpg',
                'gallery' => ['items/sup3.jpg'],
            ],
            [
                'activity_type' => 'флайборд',
                'title' => 'Флайборд Start',
                'price' => 6000,
                'description' => 'Первый полёт на флайборде с инструктором и полным комплектом экипировки.',
                'max_people' => 1,
                'duration_minutes' => 15,
                'min_age' => 14,
                'main_image' => 'items/fly1.jpg',
                'gallery' => ['items/fly1.jpg'],
            ],
            [
                'activity_type' => 'флайборд',
                'title' => 'Флайборд City Flight',
                'price' => 7500,
                'description' => 'Зрелищный полёт над водой с видом на город и поддержкой опытного инструктора.',
                'max_people' => 1,
                'duration_minutes' => 20,
                'min_age' => 16,
                'main_image' => 'items/fly2.jpg',
                'gallery' => ['items/fly2.jpg'],
            ],
            [
                'activity_type' => 'флайборд',
                'title' => 'Флайборд Pro',
                'price' => 9000,
                'description' => 'Продвинутый формат флайборда для тех, кто хочет попробовать более высокие подъёмы.',
                'max_people' => 1,
                'duration_minutes' => 25,
                'min_age' => 18,
                'main_image' => 'items/fly3.jpg',
                'gallery' => ['items/fly3.jpg'],
            ],
            [
                'activity_type' => 'катамаран',
                'title' => 'Катамаран Yellow Car',
                'price' => 3500,
                'description' => 'Яркий катамаран для спокойной прогулки по воде и отдыха всей семьёй.',
                'max_people' => 4,
                'duration_minutes' => 120,
                'min_age' => 8,
                'main_image' => 'items/katamaran1.jpg',
                'gallery' => ['items/katamaran1.jpg'],
            ],
            [
                'activity_type' => 'катамаран',
                'title' => 'Катамаран Red Boat',
                'price' => 4000,
                'description' => 'Комфортный катамаран у причала для прогулок без спешки и лишнего шума.',
                'max_people' => 4,
                'duration_minutes' => 120,
                'min_age' => 8,
                'main_image' => 'items/katamaran2.jpg',
                'gallery' => ['items/katamaran2.jpg'],
            ],
            [
                'activity_type' => 'катамаран',
                'title' => 'Катамаран Blue Lagoon',
                'price' => 4500,
                'description' => 'Просторный катамаран для компании, лёгкого маршрута и красивых фотографий.',
                'max_people' => 5,
                'duration_minutes' => 150,
                'min_age' => 10,
                'main_image' => 'items/katamaran3.jpg',
                'gallery' => ['items/katamaran3.jpg'],
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
