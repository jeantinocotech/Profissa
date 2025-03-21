<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisorSkill extends Model
{
    protected $table = 'advisor_skills';

    protected $fillable = [
        'id_profiles_advisor',
        'id_skills',
        'created_at'
    ];

    // Disable timestamps if they're not in your table
    public $timestamps = false;

    // Relationship with Advisor
    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'id_profiles_advisor', 'id');
    }

    // Relationship with Skill
    public function skill()
    {
        return $this->belongsTo(Skills::class, 'id_skills', 'id');
    }    
}