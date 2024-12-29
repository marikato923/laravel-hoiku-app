<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kindergarten;
use App\Models\Term;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ChildSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(KindergartenSeeder::class);
        $this->call(TermSeeder::class);
    }
}
