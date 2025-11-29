<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねアイコンを押下することによって、いいねした商品として登録することができる()
    {
        $user = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'item_name' => 'テスト商品',
        ]);

        $this->actingAs($user);

        $this->get("/item/{$item->id}")
            ->assertStatus(200)
            ->assertSee('テスト商品');

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->post(route('items.favorite', $item->id));

        $response->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('<span class="count">1</span>', false);
    }

    /** @test */
    public function 追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertDontSee('liked');

        $item->favoritedByUsers()->attach($user->id);

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertSee('class="star_button  liked"', false);
    }

    /** @test */
    public function 再度いいねアイコンを押下することによって、いいねを解除することができる()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create();

        $this->post("/items/{$item->id}/favorite");

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->post("/items/{$item->id}/favorite");

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
