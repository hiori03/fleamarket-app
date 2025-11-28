<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィールページで必要な情報が取得できる()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'テスト',
            'profile_image' => 'profiles/dummy.jpg',
        ]);
        $this->actingAs($user);

        $sellItem = \App\Models\Item::factory()->create([
            'user_id' => $user->id,
            'item_name' => '出品商品',
        ]);

        $otherUser = \App\Models\User::factory()->create();
        $buyItem = \App\Models\Item::factory()->create([
            'user_id' => $otherUser->id,
            'item_name' => '購入商品',
            'is_sold' => true,
        ]);

        \App\Models\Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'payment_method' => 0,
            'postal_order' => '123-4567',
            'address_order' => 'テスト',
            'building_order' => 'テストビル',
        ]);

        $response = $this->get(route('mypage', ['page' => 'sell']));
        $response->assertStatus(200);
        $response->assertSee('テスト');
        $response->assertSee('出品商品');
        $response->assertSee('profiles/dummy.jpg');

        $response = $this->get(route('mypage', ['page' => 'buy']));
        $response->assertStatus(200);
        $response->assertSee('購入商品');
    }
}
