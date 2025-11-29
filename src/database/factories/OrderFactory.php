<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
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
            'item_id' => \App\Models\Item::factory(),
            'payment_method' => $this->faker->numberBetween(0, 1),
            'postal_order' => $this->faker->postcode,
            'address_order' => $this->faker->address,
            'building_order' => $this->faker->secondaryAddress,
        ];
    }
}
