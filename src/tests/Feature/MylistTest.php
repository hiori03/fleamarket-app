<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = \App\Models\User::factory()->create();

        $likedItem = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'item_name' => 'いいねした商品',
        ]);

        $notLikedItem = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'item_name' => 'いいねしていない商品',
        ]);

        \DB::table('favorites')->insert([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('いいねした商品');

        $response->assertDontSee('いいねしていない商品');
    }

    /** @test */
    public function 購入済み商品は「Sold」と表示される()
    {
        $user = \App\Models\User::factory()->create();

        $likedItem = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'is_sold' => true,
        ]);

        \DB::table('favorites')->insert([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('<span class="sold-text">SOLD</span>', false);
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('<div class="list">', false);
    }
}
