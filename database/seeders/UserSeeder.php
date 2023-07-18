<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'tate@gmail.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'email' => 'admin@gmail.com',
            'is_admin' => true,
        ]);

        User::factory(5)->create();
    }
}
