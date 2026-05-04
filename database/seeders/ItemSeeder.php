<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['activity_type' => 'гидроцикл', 'title' => 'Аренда гидроцикла', 'price' => 5000, 'description' => 'Мощный гидроцикл для активного отдыха на воде.', 'max_people' => 2, 'duration_minutes' => 60, 'min_age' => 18, 'main_image' => 'items/jetski1.jpg', 'gallery' => ['items/jetski1.jpg', 'items/jetski2.jpg', 'items/jetski3.jpg']],
            ['activity_type' => 'гидроцикл', 'title' => 'Гидроцикл Yamaha', 'price' => 6000, 'description' => 'Скоростной гидроцикл Yamaha.', 'max_people' => 2, 'duration_minutes' => 60, 'min_age' => 18, 'main_image' => 'items/jetski2.jpg', 'gallery' => ['items/jetski2.jpg']],
            ['activity_type' => 'гидроцикл', 'title' => 'Гидроцикл Sea-Doo', 'price' => 7000, 'description' => 'Премиум гидроцикл Sea-Doo.', 'max_people' => 3, 'duration_minutes' => 60, 'min_age' => 18, 'main_image' => 'items/jetski3.jpg', 'gallery' => ['items/jetski3.jpg']],
            ['activity_type' => 'банан', 'title' => 'Катание на банане', 'price' => 3000, 'description' => 'Весёлое катание на банане для компании.', 'max_people' => 6, 'duration_minutes' => 20, 'min_age' => 10, 'main_image' => 'items/banana1.jpg', 'gallery' => ['items/banana1.jpg', 'items/banana2.jpg']],
            ['activity_type' => 'банан', 'title' => 'Банан экстрим', 'price' => 3500, 'description' => 'Экстремальное катание на банане.', 'max_people' => 8, 'duration_minutes' => 25, 'min_age' => 12, 'main_image' => 'items/banana2.jpg', 'gallery' => ['items/banana2.jpg']],
            ['activity_type' => 'флайборд', 'title' => 'Полёт на флайборде', 'price' => 8000, 'description' => 'Незабываемый полёт над водой.', 'max_people' => 1, 'duration_minutes' => 15, 'min_age' => 16, 'main_image' => 'items/flyboard1.jpg', 'gallery' => ['items/flyboard1.jpg']],
            ['activity_type' => 'флайборд', 'title' => 'Флайборд PRO', 'price' => 10000, 'description' => 'Профессиональный флайборд для опытных.', 'max_people' => 1, 'duration_minutes' => 20, 'min_age' => 18, 'main_image' => 'items/flyboard2.jpg', 'gallery' => ['items/flyboard2.jpg']],
            ['activity_type' => 'сапборд', 'title' => 'Аренда сапборда', 'price' => 1500, 'description' => 'Спокойный отдых и прогулки по воде.', 'max_people' => 1, 'duration_minutes' => 120, 'min_age' => 12, 'main_image' => 'items/sup1.jpg', 'gallery' => ['items/sup1.jpg', 'items/sup2.jpg']],
            ['activity_type' => 'сапборд', 'title' => 'Сапборд премиум', 'price' => 2000, 'description' => 'Премиум сапборд с веслом.', 'max_people' => 1, 'duration_minutes' => 120, 'min_age' => 12, 'main_image' => 'items/sup2.jpg', 'gallery' => ['items/sup2.jpg']],
            ['activity_type' => 'сапборд', 'title' => 'Сапборд для двоих', 'price' => 2500, 'description' => 'Большой сапборд для двоих.', 'max_people' => 2, 'duration_minutes' => 90, 'min_age' => 14, 'main_image' => 'items/sup3.jpg', 'gallery' => ['items/sup3.jpg']],
            ['activity_type' => 'катамаран', 'title' => 'Аренда катамарана', 'price' => 4000, 'description' => 'Уютный катамаран для семьи.', 'max_people' => 4, 'duration_minutes' => 120, 'min_age' => 8, 'main_image' => 'items/catamaran1.jpg', 'gallery' => ['items/catamaran1.jpg']],
            ['activity_type' => 'катамаран', 'title' => 'Катамаран большой', 'price' => 5000, 'description' => 'Большой катамаран для компании.', 'max_people' => 6, 'duration_minutes' => 120, 'min_age' => 8, 'main_image' => 'items/catamaran2.jpg', 'gallery' => ['items/catamaran2.jpg']],
            ['activity_type' => 'гидроцикл', 'title' => 'Гидроцикл Sport', 'price' => 5500, 'description' => 'Спортивный гидроцикл.', 'max_people' => 2, 'duration_minutes' => 60, 'min_age' => 18, 'main_image' => 'items/jetski1.jpg', 'gallery' => ['items/jetski1.jpg']],
            ['activity_type' => 'гидроцикл', 'title' => 'Гидроцикл Touring', 'price' => 6500, 'description' => 'Туристический гидроцикл.', 'max_people' => 3, 'duration_minutes' => 90, 'min_age' => 18, 'main_image' => 'items/jetski2.jpg', 'gallery' => ['items/jetski2.jpg']],
            ['activity_type' => 'банан', 'title' => 'Банан семейный', 'price' => 4000, 'description' => 'Семейное катание на банане.', 'max_people' => 10, 'duration_minutes' => 30, 'min_age' => 6, 'main_image' => 'items/banana1.jpg', 'gallery' => ['items/banana1.jpg']],
            ['activity_type' => 'флайборд', 'title' => 'Флайборд старт', 'price' => 6000, 'description' => 'Вводный полёт на флайборде.', 'max_people' => 1, 'duration_minutes' => 10, 'min_age' => 14, 'main_image' => 'items/flyboard1.jpg', 'gallery' => ['items/flyboard1.jpg']],
            ['activity_type' => 'сапборд', 'title' => 'Сапборд йога', 'price' => 1800, 'description' => 'Сапборд для йоги на воде.', 'max_people' => 1, 'duration_minutes' => 120, 'min_age' => 16, 'main_image' => 'items/sup1.jpg', 'gallery' => ['items/sup1.jpg']],
            ['activity_type' => 'катамаран', 'title' => 'Катамаран спорт', 'price' => 4500, 'description' => 'Спортивный катамаран.', 'max_people' => 4, 'duration_minutes' => 120, 'min_age' => 12, 'main_image' => 'items/catamaran1.jpg', 'gallery' => ['items/catamaran1.jpg']],
            ['activity_type' => 'гидроцикл', 'title' => 'Гидроцикл Deluxe', 'price' => 8000, 'description' => 'Люксовый гидроцикл.', 'max_people' => 2, 'duration_minutes' => 60, 'min_age' => 21, 'main_image' => 'items/jetski3.jpg', 'gallery' => ['items/jetski3.jpg']],
            ['activity_type' => 'банан', 'title' => 'Банан детский', 'price' => 2000, 'description' => 'Детское катание на банане.', 'max_people' => 4, 'duration_minutes' => 15, 'min_age' => 5, 'main_image' => 'items/banana2.jpg', 'gallery' => ['items/banana2.jpg']],
            ['activity_type' => 'флайборд', 'title' => 'Флайборд мастер', 'price' => 12000, 'description' => 'Мастер класс на флайборде.', 'max_people' => 1, 'duration_minutes' => 30, 'min_age' => 18, 'main_image' => 'items/flyboard2.jpg', 'gallery' => ['items/flyboard2.jpg']],
            ['activity_type' => 'сапборд', 'title' => 'Сапборд туринг', 'price' => 2200, 'description' => 'Сапборд для долгих прогулок.', 'max_people' => 1, 'duration_minutes' => 180, 'min_age' => 14, 'main_image' => 'items/sup2.jpg', 'gallery' => ['items/sup2.jpg']],
            ['activity_type' => 'катамаран', 'title' => 'Катамаран комфорт', 'price' => 5500, 'description' => 'Катамаран с комфортом.', 'max_people' => 5, 'duration_minutes' => 150, 'min_age' => 8, 'main_image' => 'items/catamaran2.jpg', 'gallery' => ['items/catamaran2.jpg']],
            ['activity_type' => 'гидроцикл', 'title' => 'Гидроцикл Rental', 'price' => 4500, 'description' => 'Бюджетный гидроцикл.', 'max_people' => 2, 'duration_minutes' => 45, 'min_age' => 18, 'main_image' => 'items/jetski1.jpg', 'gallery' => ['items/jetski1.jpg']],
            ['activity_type' => 'банан', 'title' => 'Банан VIP', 'price' => 5000, 'description' => 'VIP катание на банане.', 'max_people' => 6, 'duration_minutes' => 40, 'min_age' => 12, 'main_image' => 'items/banana1.jpg', 'gallery' => ['items/banana1.jpg']],
            ['activity_type' => 'флайборд', 'title' => 'Флайборд дуэт', 'price' => 15000, 'description' => 'Полёт вдвоём на флайборде.', 'max_people' => 2, 'duration_minutes' => 20, 'min_age' => 16, 'main_image' => 'items/flyboard1.jpg', 'gallery' => ['items/flyboard1.jpg']],
            ['activity_type' => 'сапборд', 'title' => 'Сапборд фиш', 'price' => 1700, 'description' => 'Сапборд для рыбалки.', 'max_people' => 1, 'duration_minutes' => 180, 'min_age' => 16, 'main_image' => 'items/sup3.jpg', 'gallery' => ['items/sup3.jpg']],
            ['activity_type' => 'катамаран', 'title' => 'Катамаран рыбалка', 'price' => 6000, 'description' => 'Катамаран для рыбалки.', 'max_people' => 4, 'duration_minutes' => 240, 'min_age' => 14, 'main_image' => 'items/catamaran1.jpg', 'gallery' => ['items/catamaran1.jpg']],
            ['activity_type' => 'гидроцикл', 'title' => 'Гидроцикл Race', 'price' => 9000, 'description' => 'Гоночный гидроцикл.', 'max_people' => 1, 'duration_minutes' => 45, 'min_age' => 21, 'main_image' => 'items/jetski2.jpg', 'gallery' => ['items/jetski2.jpg']],
            ['activity_type' => 'банан', 'title' => 'Банан марафон', 'price' => 6000, 'description' => 'Долгое катание на банане.', 'max_people' => 8, 'duration_minutes' => 60, 'min_age' => 14, 'main_image' => 'items/banana2.jpg', 'gallery' => ['items/banana2.jpg']],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
