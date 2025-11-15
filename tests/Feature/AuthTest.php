<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'Amina',
            'last_name' => 'Test',
            'email' => 'amina@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'created_at',
                    ]
                 ]);
    }
}
