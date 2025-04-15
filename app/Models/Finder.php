<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finder extends Model
{
    use HasFactory;

    protected $table = 'profiles_finder';

    protected $fillable = [
        'full_name',
        'linkedin_url',
        'instagram_url',
        'overview',
        'profile_picture',
        'is_active',
    ];

    // Relationship with profielEducation
    public function profileEducation()
    {
        return $this->hasMany(ProfileEducation::class, 'id_profiles_finder');
    }

   // Many-to-Many relationship with skills through finder_skills_interests
     public function skills()
     {
         return $this->belongsToMany(Skills::class, 'finder_skills_interests', 'id_profiles_finder', 'id_skills');
                  
     }

     public function interest_areas()
     {
         return $this->belongsToMany(Course::class, 'finder_interest_areas', 'id_profiles_finder', 'id_courses');
     }
     
    public function meetingRequests()
    {
        return $this->hasMany(MeetingRequest::class, 'id_profiles_finder');
    }

    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,
            FinderInterestAreas::class,
            'id_profiles_finder', // Foreign key on interest_areas
            'id',                  // Foreign key on courses table
            'id',                  // Local key on profiles_finder
            'id_courses'           // Local key on interest_areas
        );
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}