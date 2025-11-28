<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $matchingItem = \App\Models\Item::factory()->create([
            'item_name' => 'テスト商品',
            'user_id' => \App\Models\User::factory()->create()->id,
        ]);

        $nonMatchingItem = \App\Models\Item::factory()->create([
            'item_name' => '別の商品',
            'user_id' => \App\Models\User::factory()->create()->id,
        ]);


        $response = $this->get('/?search=テスト');

        $response->assertStatus(200);

        $response->assertSee('テスト商品');

        $response->assertDontSee('別の商品');
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $user = \App\Models\User::factory()->create();

        $matchingItem = \App\Models\Item::factory()->create([
            'item_name' => 'テスト商品',
            'user_id' => \App\Models\User::factory()->create()->id,
        ]);

        \DB::table('favorites')->insert([
            'user_id' => $user->id,
            'item_id' => $matchingItem->id,
        ]);

        $nonMatchingItem = \App\Models\Item::factory()->create([
            'item_name' => '別の商品',
            'user_id' => \App\Models\User::factory()->create()->id,
        ]);

        \DB::table('favorites')->insert([
            'user_id' => $user->id,
            'item_id' => $nonMatchingItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/?search=テスト');

        $response->assertStatus(200);

        $response->assertSee('テスト商品');

        $response->assertDontSee('別の商品');

        $response = $this->get('/?search=テスト&tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('テスト商品');

        $response->assertDontSee('別の商品');
    }
}
