<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'item_name' => $this->faker->word,
            'item_image' => 'dummy.jpg',
            'content' => $this->faker->sentence,
            'situation' => '0',
            'price' => $this->faker->numberBetween(100, 10000),
            'is_sold' => false,
        ];
    }
}
