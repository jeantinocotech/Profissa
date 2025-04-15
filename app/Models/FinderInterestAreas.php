<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinderInterestAreas extends Model
{
    
    use HasFactory;
    
    protected $table = 'finder_interest_areas';

    public $timestamps = false;

    protected $fillable = ['id_courses',
                           'id_profiles_finder',];                   
    

    public function finder()
    {
        return $this->belongsTo(Finder::class, 'id_profiles_finder');
    }
     
}