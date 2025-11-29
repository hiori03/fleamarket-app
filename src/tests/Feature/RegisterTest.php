<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が入力されていない場合、バリデーションメッセージが表示される()
    {
        $this->get('/register')->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name']);

        $errors = session('errors')->get('name');

        $this->assertContains('お名前を入力してください', $errors);
    }

    /** @test */
    public function メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $this->get('/register')->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->get('email');

        $this->assertContains('メールアドレスを入力してください', $errors);
    }

    /** @test */
    public function パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $this->get('/register')->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');

        $this->assertContains('パスワードを入力してください', $errors);
    }

    /** @test */
    public function パスワードが7文字以下の場合、バリデーションメッセージが表示される()
    {
        $this->get('/register')->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'passwor',
            'password_confirmation' => 'passwor',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');

        $this->assertContains('パスワードは8文字以上で入力してください', $errors);
    }

    /** @test */
    public function パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される()
    {
        $this->get('/register')->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password1',
            'password_confirmation' => 'password2',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');

        $this->assertContains('パスワードと一致しません', $errors);
    }

    /** @test */
    public function 全ての項目が入力されている場合、会員情報が登録され、メール認証誘導画面に遷移される()
    {
        Mail::fake();

        $email = 'register_test_'.uniqid().'@example.com';

        $this->get('/register')->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/email');

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        Mail::assertSent(\App\Mail\VerifyEmail::class);
    }
}
