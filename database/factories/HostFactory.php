<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Host>
 */
class HostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'bio' => fake()->paragraph(),
            'photo_url' => fake()->imageUrl(400, 400, 'people', true),
        ];
    }
}
