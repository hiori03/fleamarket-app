<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'email' => 'test@example.com'], [
                'name' => 'テスト',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

        Address::firstOrCreate([
            'user_id' => $user->id,
        ], [
            'postal' => '123-4567',
            'address' => 'テスト',
        ]);
    }
}
