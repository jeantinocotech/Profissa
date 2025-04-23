<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingProposal extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELLATION_REQUESTED = 'cancellation_requested';

    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_ACCEPTED,
        self::STATUS_DECLINED,
        self::STATUS_CANCELLATION_REQUESTED,
    ];

    protected $table = 'meeting_proposals';
   
    protected $fillable = [
        'id_meeting_request',
        'proposed_datetime',
        'finder_comment',
        'advisor_comment',
        'cancellation_requested_at',
        'status'
    ];
    

    public function meetingRequest()
{
    return $this->belongsTo(MeetingRequest::class, 'id_meeting_request');
}

}
