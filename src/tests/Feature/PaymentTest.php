<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 小計画面で変更が反映される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal' => '123-4567',
            'address' => 'テスト',
            'building' => 'テストビル',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'is_sold' => false,
        ]);

        $response = $this->get(route('purchaseform', [
            'item' => $item->id,
            'payment_method' => 0,
        ]));

        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');

        $response2 = $this->get(route('purchaseform', [
            'item' => $item->id,
            'payment_method' => 1,
        ]));

        $response2->assertStatus(200);
        $response2->assertSee('カード支払い');
    }
}
