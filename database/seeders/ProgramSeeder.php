<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['name' => 'BS Computer Science', 'is_active' => true],
            ['name' => 'BS Information Technology', 'is_active' => true],
            ['name' => 'BS Mathematics', 'is_active' => true],
            ['name' => 'BS Physics', 'is_active' => true],
            ['name' => 'BS Chemistry', 'is_active' => true],
            ['name' => 'BS English', 'is_active' => true],
            ['name' => 'BS Economics', 'is_active' => true],
            ['name' => 'MSc Computer Science', 'is_active' => true],
            ['name' => 'MSc Mathematics', 'is_active' => true],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
