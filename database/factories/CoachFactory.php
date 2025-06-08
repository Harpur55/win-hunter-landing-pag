<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class CoachFactory extends Factory
{
    public function definition(): array
    {
        return [
            'foto' => $faker->image(public_path('assets/images'), 300, 300, 'people', false),
            'nama' => $this->faker->name,
            'sabuk' => Str::random(10),
        ];
    }
}
