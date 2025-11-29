<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品出品画面にて必要な情報が保存できること()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $file = UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg');

        $formData = [
            'item_image' => $file,
            'category_id' => [$category->id],
            'situation' => 0,
            'item_name' => 'テスト商品',
            'brand' => 'テストブランド',
            'content' => '商品の説明文',
            'price' => 1000,
        ];

        $response = $this->post('/sell', $formData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'item_name' => 'テスト商品',
            'brand' => 'テストブランド',
            'content' => '商品の説明文',
            'situation' => 0,
            'price' => 1000,
        ]);

        $item = Item::first();

        Storage::disk('public')->assertExists(str_replace('storage/', '', $item->item_image));

        $this->assertTrue($item->categories->contains($category));
    }
}
