<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'first_name' => 'Default',
            'last_name'  => 'Host',
            'email' => 'host@example.com',
            'role' => 'host',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);
    }
}

