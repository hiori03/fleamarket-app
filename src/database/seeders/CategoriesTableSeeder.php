<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['category' => 'ファッション'],
            ['category' => '家電'],
            ['category' => 'インテリア'],
            ['category' => 'レディース'],
            ['category' => 'メンズ'],
            ['category' => 'コスメ'],
            ['category' => '本'],
            ['category' => 'ゲーム'],
            ['category' => 'スポーツ'],
            ['category' => 'キッチン'],
            ['category' => 'ハンドメイド'],
            ['category' => 'アクセサリー'],
            ['category' => 'おもちゃ'],
            ['category' => 'ベビー・キッズ'],
        ];
        foreach ($params as $param) {
            Category::firstOrCreate($param);
        }
    }
}
