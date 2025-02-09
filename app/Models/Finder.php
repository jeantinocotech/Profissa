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
}