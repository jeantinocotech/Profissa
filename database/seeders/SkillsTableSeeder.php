<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('skills')->insert([
            ['id' => 1, 'name' => 'Leadership', 'created_at' => now()],
            ['id' => 2, 'name' => 'Communication', 'created_at' => now()],
            ['id' => 3, 'name' => 'Project Management', 'created_at' => now()],
            ['id' => 4, 'name' => 'SQL', 'created_at' => now()],
            ['id' => 5, 'name' => 'Graphic Design', 'created_at' => now()],
            ['id' => 6, 'name' => 'Teamwork', 'created_at' => now()],
            ['id' => 7, 'name' => 'Python', 'created_at' => now()],
            ['id' => 8, 'name' => 'Public Speaking', 'created_at' => now()],
            ['id' => 9, 'name' => 'Critical Thinking', 'created_at' => now()],
            ['id' => 10, 'name' => 'Time Management', 'created_at' => now()],
        ]);
    }

}
