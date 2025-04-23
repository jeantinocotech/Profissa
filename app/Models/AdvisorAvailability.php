<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisorAvailability extends Model
{
    protected $fillable = [
        'id_profiles_advisor',
        'available_date',
        'weekday',
        'start_time',
        'end_time',
        'is_recurring',
    ];
    
}
