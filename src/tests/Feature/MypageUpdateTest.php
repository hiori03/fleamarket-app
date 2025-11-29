<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MypageUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 変更項目が初期値として過去設定されていること()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profiles/dummy.jpg',
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal' => '123-4567',
            'address' => 'テスト',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage_profileform'));

        $response->assertStatus(200);

        $response->assertSee(asset('storage/'.$user->profile_image));

        $response->assertSee('テストユーザー');

        $response->assertSee('123-4567');
        $response->assertSee('テスト');
        $response->assertSee('テストビル');
    }
}
