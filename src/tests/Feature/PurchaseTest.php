<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Order;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 「購入する」ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal' => '123-4567',
            'address' => 'テスト',
            'building' => 'テストビル'
        ]);

        $item = Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'is_sold' => false,
        ]);

        $response = $this->get(route('purchaseform', [
            'item' => $item->id,
        ]));

        $response->assertStatus(200);

        $postResponse = $this->post("/purchase/{$item->id}", [
            'payment_method' => 0,
            'postal_order' => $address->postal,
            'address_order' => $address->address,
            'building_order' => $address->building,
        ]);

        $postResponse->assertRedirect('/');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 0,
            'postal_order' => '123-4567',
            'address_order' => 'テスト',
            'building_order' => 'テストビル',
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面にて「sold」と表示される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal' => '123-4567',
            'address' => 'テスト',
            'building' => 'テストビル'
        ]);

        $item = Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'is_sold' => false,
        ]);

        $response = $this->get(route('purchaseform', [
            'item' => $item->id,
        ]));

        $response->assertStatus(200);

        $postResponse = $this->post("/purchase/{$item->id}", [
            'payment_method' => 0,
            'postal_order' => $address->postal,
            'address_order' => $address->address,
            'building_order' => $address->building,
        ]);

        $postResponse->assertRedirect('/');

        $response->assertStatus(200);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        $listResponse = $this->get(route('home'));
        $listResponse->assertStatus(200);

        $listResponse->assertSee('<span class="sold-text">SOLD</span>', false);
    }

    /** @test */
    public function 「プロフィール、購入した商品一覧」に追加されている()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal' => '123-4567',
            'address' => 'テスト',
            'building' => 'テストビル'
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'is_sold' => false,
            'item_name' => 'テスト商品',
        ]);

        $this->get(route('purchaseform', [
            'item' => $item->id,
        ]));

        $this->post("/purchase/{$item->id}", [
            'payment_method' => 0,
            'postal_order' => $address->postal,
            'address_order' => $address->address,
            'building_order' => $address->building,
        ])->assertRedirect('/');

        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);

        $response->assertSee('テスト商品');
    }
}
