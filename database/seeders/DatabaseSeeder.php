<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'email' => 'tate@gmail.com',
            'is_admin' => true,
        ]);

        \App\Models\User::factory()->create([
            'email' => 'admin@gmail.com',
            'is_admin' => true,
        ]);

        \App\Models\User::factory(10)->create();

        \App\Models\Channel::factory(10)->create();
    }
}
