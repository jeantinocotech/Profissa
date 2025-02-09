<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileEducation extends Model
{
    
    protected $fillable = [
        'id_profiles_advisor',
        'Id_education',
        'id_courses',
        'institution_name',
        'certification',
        'dt_start',
        'dt_end',
        'comments',
    ];

    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'id_profiles_advisor');
    }
    
}

