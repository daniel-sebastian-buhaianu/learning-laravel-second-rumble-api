<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Video::factory(10)
            ->has(Tag::factory()->count(5))
            ->create();
    }
}
