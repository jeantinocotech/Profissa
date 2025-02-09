<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Profiler\Profile;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'courses_name',
    ];

    public function educations()
    {
        return $this->hasMany(ProfileEducation::class, 'Id_courses', 'id');
    }
}