<?php
// app/Models/Skill.php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Skills extends Model
{
    
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = ['name',
                           'created_at'];


    public $timestamps = false;                       
    
    public function advisors()
    {
        return $this->hasMany(Advisor::class, 'advisor_skills', 'id_skills', 'id_profiles_advisor');

    }
    

    
    public function finders()
    {
        return $this->belongsToMany(Finder::class, 'finder_skills_interests', 'id_skills', 'id_profiles_finder')
            ->withPivot('importance_level');
    }

     // Direct relationship with advisor_skills
     public function advisorSkills()
     {
         return $this->hasMany(AdvisorSkill::class, 'id_skills', 'id');
     }
     
}