<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品を取得できる()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $allProducts = \App\Models\Item::all();
        foreach ($allProducts as $product) {
            $response->assertSee($product->name);
        }
    }

    /** @test */
    public function 購入済み商品は「_sold」と表示される()
    {
        $seller = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => true,
            'item_name' => 'テスト商品',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('<span class="sold-text">SOLD</span>', false);
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $userA = \App\Models\User::factory()->create();
        $this->actingAs($userA);

        $myItem = \App\Models\Item::factory()->create([
            'user_id' => $userA->id,
            'item_name' => '自分の商品',
        ]);

        $userB = \App\Models\User::factory()->create();
        $otherItem = \App\Models\Item::factory()->create([
            'user_id' => $userB->id,
            'item_name' => '他人の商品',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertDontSee('自分の商品');

        $response->assertSee('他人の商品');
    }
}
