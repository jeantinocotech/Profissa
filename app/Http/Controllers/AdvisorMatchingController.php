<?php
// app/Http/Controllers/AdvisorMatchingController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\Advisor;
use App\Models\Finder;
use App\Models\MeetingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvisorMatchingController extends Controller
{
    public function findMatchingAdvisors(Request $request)
    {
        $finder = Auth::user()->Finder;

        $finderSkills = $finder->skills()->with('importance_level')->get();

        // Calculate matching scores using SQL for better performance
        $matchingAdvisors = DB::table('profiles_advisor as pa')
            ->select([
                'pa.id',
                'pa.full_name',
                'pa.profile_picture',
                'pa.overview',
                DB::raw('SUM(
                    CASE 
                        WHEN fs.id_skills IS NOT NULL 
                        THEN (aks.competency_level * fs.importance_level)
                        ELSE 0 
                    END
                ) as matching_score')
            ])
            ->leftJoin('advisor_skills as aks', 'pa.id', '=', 'aks.id_profiles_advisor')
            ->leftJoin('finder_skills_interests as fs', function($join) use ($finder) {
                $join->on('aks.id_skills', '=', 'fs.id_skills')
                    ->where('fs.id_profiles_finder', '=', $finder->id);
            })
            ->where('pa.is_active', '=', 1)
            ->groupBy('pa.id')
            ->orderByDesc('matching_score')
            ->limit(3)
            ->get();

        return view('advisor.matches', compact('matchingAdvisors'));
    }

    public function createMeetingRequest(Request $request, $advisorId)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $meetingRequest = MeetingRequest::create([
            'id_profiles_finder' => Auth::user()->finderProfile->id,
            'id_profiles_advisor' => $advisorId,
            'finder_message' => $request->message,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Meeting request sent successfully!');
    }

    public function respondToRequest(Request $request, MeetingRequest $meetingRequest)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
            'response' => 'required|string|max:500'
        ]);

        $meetingRequest->update([
            'status' => $request->status,
            'advisor_response' => $request->response
        ]);

        return redirect()->back()->with('success', 'Response sent successfully!');
    }
}