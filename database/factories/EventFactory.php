<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'begins_at' => Carbon::now()->addDay()->format('Y-m-d H:m:s'),
            'ends_at' => Carbon::now()->addDay()->addHour()->format('Y-m-d H:m:s'),
            'place' => $this->faker->streetAddress
        ];
    }
}
