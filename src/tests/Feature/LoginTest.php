<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $this->get('/login')->assertStatus(200);

        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->get('email');

        $this->assertContains('メールアドレスを入力してください', $errors);
    }

    /** @test */
    public function パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $this->get('/login')->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');

        $this->assertContains('パスワードを入力してください', $errors);
    }

    /** @test */
    public function 入力情報が間違っている場合、バリデーションメッセージが表示される()
    {
        $this->get('/login')->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'notexist@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->get('email');

        $this->assertContains('ログイン情報が登録されていません', $errors);
    }

    /** @test */
    public function 正しい情報が入力された場合、ログイン処理が実行される()
    {
        $email = 'login_test_'.uniqid().'@example.com';

        $user = \App\Models\User::factory()->create([
            'email' => $email,
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->get('/login')->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }
}
