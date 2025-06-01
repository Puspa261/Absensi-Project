<?php

namespace Database\Seeders;

use App\Models\JobTitles;
use App\Models\User;
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

        User::create([
            'name' => 'puspa',
            'id_job' => 1,
            'employee_number' => '0000',
            'email' => 'puspa@gmail.com',
            'password' => bcrypt('0000')
        ]);
    }
}
