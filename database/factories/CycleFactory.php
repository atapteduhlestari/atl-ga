<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CycleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cycle_name' => $this->faker->name(),
            'cycle_type' => '1',
            'qty' => 365,
            'created_at' => now(),
        ];
    }
}
