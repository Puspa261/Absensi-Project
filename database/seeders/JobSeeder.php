<?php

namespace Database\Seeders;

use App\Models\JobTitles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobTitles::create([
            'position' => 'Instruktur',
            'division' => 'Web Development'
        ]);
    }
}
