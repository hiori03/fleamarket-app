<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $data = [
            'comment' => 'テストコメント',
        ];

        $response = $this->post("/items/{$item->id}/comment", $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        $this->assertEquals(1, $item->comments()->count());
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $data = [
            'comment' => 'テストコメント',
        ];

        $response = $this->post("/items/{$item->id}/comment", $data);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        $this->assertEquals(0, $item->comments()->count());
    }

    /** @test */
    public function コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->post("/items/{$item->id}/comment", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);

        $errors = session('errors')->get('comment');

        $this->assertContains('商品コメントを入力してください', $errors);
    }

    /** @test */
    public function コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $comment = str_repeat('あ', 256);

        $response = $this->post("/items/{$item->id}/comment", [
            'comment' => $comment,
        ]);

        $response->assertSessionHasErrors(['comment']);

        $errors = session('errors')->get('comment');

        $this->assertContains('255文字以内で入力してください', $errors);
    }
}
