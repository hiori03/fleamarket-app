<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::Create([
            'user_id' => 1,
            'postal' => '123-4567',
            'address' => 'テスト',
        ]);
    }
}
