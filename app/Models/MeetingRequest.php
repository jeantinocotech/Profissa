<?php
// app/Models/Skill.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class MeetingRequest extends Model
{
    
    protected $table = 'meeting_requests';
    
    protected $fillable = [
        'id_profiles_finder', 'id_profiles_advisor', 'status',
        'finder_message', 'advisor_response'
    ];

    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'id_profiles_advisor');
    }

    public function finder()
    {
        return $this->belongsTo(Finder::class, 'id_profiles_finder');
    }
}