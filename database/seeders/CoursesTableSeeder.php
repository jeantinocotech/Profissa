<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
    
class CoursesTableSeeder extends Seeder
    {
        public function run(): void
        {
            DB::table('courses')->insert([
                ['id' => 1, 'course_name' => 'Computer Science'],
                ['id' => 2, 'course_name' => 'Business Administration'],
                ['id' => 3, 'course_name' => 'Marketing'],
                ['id' => 4, 'course_name' => 'Mechanical Engineering'],
                ['id' => 5, 'course_name' => 'Psychology'],
                ['id' => 6, 'course_name' => 'Graphic Design'],
                ['id' => 7, 'course_name' => 'Environmental Science'],
                ['id' => 8, 'course_name' => 'Law'],
                ['id' => 9, 'course_name' => 'Economics'],
                ['id' => 10, 'course_name' => 'Data Science'],
            ]);
        }
    }
    

