<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Podcast;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'podcast_id' => Podcast::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'audio_url' => fake()->url(),
            'duration' => fake()->numberBetween(300, 3600),
        ];
    }
}
