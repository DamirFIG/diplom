<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::orderBy('id')->get();

        if ($items->isEmpty()) {
            $this->command->info('Нет карточек аренды для создания отзывов.');
            return;
        }

        Review::whereNotNull('item_id')->delete();

        $users = collect([
            User::firstOrCreate(
                ['email' => 'reviewer1@test.com'],
                ['login' => 'Марина', 'phone' => '+79990000001', 'password' => bcrypt('password')]
            ),
            User::firstOrCreate(
                ['email' => 'reviewer2@test.com'],
                ['login' => 'Алексей', 'phone' => '+79990000002', 'password' => bcrypt('password')]
            ),
            User::firstOrCreate(
                ['email' => 'reviewer3@test.com'],
                ['login' => 'Ирина', 'phone' => '+79990000003', 'password' => bcrypt('password')]
            ),
        ]);

        $textsByType = [
            'гидроцикл' => [
                'Гидроцикл мощный и устойчивый, инструктор всё понятно объяснил перед стартом.',
                'Катание получилось очень бодрым, техника в хорошем состоянии, эмоций много.',
                'Отличный вариант для активного отдыха, скорость чувствуется сразу, всё безопасно.',
            ],
            'банан' => [
                'Весёлое катание для компании, все смеялись и получили много эмоций.',
                'Банан удобный, маршрут понравился, инструктор аккуратно подбирал скорость.',
                'Хороший вариант для семьи и друзей, было динамично, но без лишнего риска.',
            ],
            'сапборд' => [
                'Сапборд устойчивый, спокойно погуляли по воде и сделали красивые фото.',
                'Отлично подходит для расслабленной прогулки, весло и жилет выдали сразу.',
                'Понравилось качество доски и спокойный маршрут, обязательно возьмём ещё.',
            ],
            'флайборд' => [
                'Флайборд — это восторг, инструктор помог быстро подняться над водой.',
                'Очень необычный опыт, всё прошло безопасно и с понятными подсказками.',
                'Получились яркие эмоции и классные фото, хочется повторить ещё раз.',
            ],
            'катамаран' => [
                'Катамаран удобный и чистый, спокойно покатались всей компанией.',
                'Хороший вариант для неспешной прогулки, места хватило всем.',
                'Понравился комфорт и простое управление, отличный отдых на воде.',
            ],
        ];

        $reviewsCreated = 0;

        foreach ($items as $item) {
            $texts = $textsByType[$item->activity_type] ?? [
                'Отличная аренда, всё понравилось.',
                'Хороший сервис и приятные впечатления.',
                'Рекомендую для отдыха на воде.',
            ];

            foreach ($texts as $index => $text) {
                Review::create([
                    'user_id' => $users[$index]->id,
                    'item_id' => $item->id,
                    'trip_id' => null,
                    'rating' => $index === 1 ? 4 : 5,
                    'text' => $text,
                    'likes' => 6 + $index * 3,
                    'dislikes' => $index === 1 ? 1 : 0,
                ]);

                $reviewsCreated++;
            }
        }

        $this->command->info("Отзывы для карточек аренды созданы: {$reviewsCreated}");
    }
}
