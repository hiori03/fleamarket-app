<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が表示される()
    {
        $user = \App\Models\User::factory()->create();

        $commentUser = \App\Models\User::factory()->create([
            'name' => 'コメントユーザー',
            'profile_image' => 'dummy.jpg'
        ]);

        $category = \App\Models\Category::factory()->create([
            'category' => 'ファッション'
        ]);

        $item = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'item_image' => 'dummy.jpg',
            'item_name' => 'テスト商品',
            'brand' => 'テストブランド',
            'situation' => 0,
            'price' => 1000,
            'content' => '商品の説明です',
        ]);

        $item->categories()->attach($category->id);

        $item->comments()->create([
            'user_id' => $commentUser->id,
            'comment' => 'こんにちは'
        ]);

        \DB::table('favorites')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('dummy.jpg');
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('1,000');
        $response->assertSee('商品の説明です');
        $response->assertSee('ファッション');
        $response->assertSee('良好');
        $response->assertSee('コメントユーザー');
        $response->assertSee('こんにちは');
        $response->assertSee('1');
        $response->assertSee('1');
    }

    /** @test */
    public function 複数選択されたカテゴリが表示されているか()
    {
        $user = \App\Models\User::factory()->create();

        $category1 = \App\Models\Category::factory()->create(['category' => 'ファッション']);
        $category2 = \App\Models\Category::factory()->create(['category' => '家電']);

        $item = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'item_name' => 'テスト商品',
        ]);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('ファッション');
        $response->assertSee('家電');
    }
}
