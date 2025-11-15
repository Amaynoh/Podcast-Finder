<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Podcast;

class PodcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_podcasts()
    {
        Podcast::factory()->count(3)->create();

        $response = $this->getJson('/api/podcasts');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
