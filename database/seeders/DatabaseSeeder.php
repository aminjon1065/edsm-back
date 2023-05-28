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
        \App\Models\User::factory(10)->create();
//        \App\Models\Document::factory(200)->create();
//        \App\Models\File::factory(2000)->create();
//        \App\Models\Mail::factory(200)->create();
//        \App\Models\OpenedMail::factory(200)->create();
    }
}
