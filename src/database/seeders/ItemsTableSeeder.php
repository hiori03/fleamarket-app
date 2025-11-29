<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'item_image' => 'Armani+Mens+Clock.jpg',
                'item_name' => '腕時計',
                'brand' => 'Rolax',
                'content' => 'スタイリッシュなデザインのメンズ腕時計',
                'situation' => 0,
                'price' => 15000,
                'categories' => ['ファッション'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'HDD+Hard+Disk.jpg',
                'item_name' => 'HDD',
                'brand' => '西芝',
                'content' => '高速で信頼性の高いハードディスク',
                'situation' => 1,
                'price' => 5000,
                'categories' => ['家電'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'iLoveIMG+d.jpg',
                'item_name' => '玉ねぎ3束',
                'brand' => 'なし',
                'content' => '新鮮な玉ねぎ3束のセット',
                'situation' => 2,
                'price' => 300,
                'categories' => ['キッチン'],
            ],
            [
                'user_id' => '1',
                'item_image' => 'Leather+Shoes+Product+Photo.jpg',
                'item_name' => '革靴',
                'brand' => '',
                'content' => 'クラシックなデザインの革靴',
                'situation' => 3,
                'price' => 4000,
                'categories' => ['ファッション', 'メンズ'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'Living+Room+Laptop.jpg',
                'item_name' => 'ノートPC',
                'brand' => '',
                'content' => '高性能なノートパソコン',
                'situation' => 0,
                'price' => 45000,
                'categories' => ['家電'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'Music+Mic+4632231.jpg',
                'item_name' => 'マイク',
                'brand' => 'なし',
                'content' => '高音質のレコーディング用マイク',
                'situation' => 1,
                'price' => 8000,
                'categories' => ['おもちゃ'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'Purse+fashion+pocket.jpg',
                'item_name' => 'ショルダーバッグ',
                'brand' => '',
                'content' => 'おしゃれなショルダーバッグ',
                'situation' => 2,
                'price' => 3500,
                'categories' => ['ファッション'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'Tumbler+souvenir.jpg',
                'item_name' => 'タンブラー',
                'brand' => 'なし',
                'content' => '使いやすいタンブラー',
                'situation' => 3,
                'price' => 500,
                'categories' => ['キッチン'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'Waitress+with+Coffee+Grinder.jpg',
                'item_name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'content' => '手動のコーヒーミル',
                'situation' => 0,
                'price' => 4000,
                'categories' => ['キッチン'],
            ],
            [
                'user_id' => 1,
                'item_image' => 'makeup_set.jpg',
                'item_name' => 'メイクセット',
                'brand' => '',
                'content' => '便利なメイクアップセット',
                'situation' => 1,
                'price' => 2500,
                'categories' => ['レディース', 'コスメ'],
            ],
        ];

        foreach ($items as $itemData) {
            $image = $itemData['item_image'];
            $source = base_path('database/seeders/images/'.$image);
            $destination = 'products/'.$image;

            Storage::disk('public')->put($destination, file_get_contents($source));

            $createdItem = Item::create([
                'user_id' => $itemData['user_id'],
                'item_image' => 'storage/'.$destination,
                'item_name' => $itemData['item_name'],
                'brand' => $itemData['brand'],
                'content' => $itemData['content'],
                'situation' => $itemData['situation'],
                'price' => $itemData['price'],
            ]);

            if (isset($itemData['categories']) && ! empty($itemData['categories'])) {
                $categoryIds = Category::whereIn('category', $itemData['categories'])->pluck('id');
                $createdItem->categories()->attach($categoryIds);
            }
        }
    }
}
