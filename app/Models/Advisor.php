<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
    use HasFactory;

    protected $table = 'profiles_advisor';

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
        return $this->hasMany(ProfileEducation::class, 'id_profiles_advisor', 'id');
    }

    // Many-to-Many relationship with skills through advisor_skills
    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'advisor_skills', 'id_profiles_advisor', 'id_skills');
                 
    }

    // Direct relationship with advisor_skills
    public function advisorSkills()
    {
        return $this->hasMany(AdvisorSkill::class, 'id_profiles_advisor', 'id');
    }

    public function meetingRequests()
    {
        return $this->hasMany(MeetingRequest::class, 'id_profiles_advisor');
    }

}