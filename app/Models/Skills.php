<?php
// app/Models/Skill.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name'];

    public function advisors()
    {
        return $this->belongsToMany(Advisor::class, 'advisor_skills', 'id_skills', 'id_profiles_advisor')
            ->withPivot('competency_level');
    }

    public function finders()
    {
        return $this->belongsToMany(Finder::class, 'finder_skills_interests', 'id_skills', 'id_profiles_finder')
            ->withPivot('importance_level');
    }
}