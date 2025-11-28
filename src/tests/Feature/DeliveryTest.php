<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class DeliveryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
        ]);

        $response = $this->get(route('purchase.address.form', ['item' => $item->id]));

        $response->assertStatus(200);

        $this->post(route('purchase.address.update', ['item' => $item->id]), [
            'postal' => '123-4567',
            'address' => 'テスト',
            'building' => 'テストビル',
        ]);

        $response = $this->get(route('purchaseform', ['item' => $item->id]));

        $response->assertStatus(200);

        $response->assertSee('123-4567');
        $response->assertSee('テスト');
        $response->assertSee('テストビル');
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'is_sold' => false,
        ]);

        $this->post(route('purchase.address.update', ['item' => $item->id]), [
            'postal' => '123-4567',
            'address' => 'テスト',
            'building' => 'テストビル',
        ]);

        $this->post("/purchase/{$item->id}", [
            'payment_method' => 0,
            'postal_order' => session('purchase_address.postal'),
            'address_order' => session('purchase_address.address'),
            'building_order' => session('purchase_address.building'),
        ])->assertRedirect('/');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_order' => '123-4567',
            'address_order' => 'テスト',
            'building_order' => 'テストビル',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);
    }
}
