<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Advisor;
use App\Models\Finder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MeetingRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $completionPercentage = 0; // Default value
        $missingItems = [];
        $profileName = '';
        $hasAdvisor = \App\Models\Advisor::where('user_id', $user->id)->exists();
        $hasFinder = \App\Models\Finder::where('user_id', $user->id)->exists();
        $meetingRequests = collect(); // inicia vazio
     

        Log::info('Profile', [$user] );

        // Determine if the user is an advisor or a finder
        if ($hasAdvisor) {
            $profile = Advisor::where('user_id', $user->id)->first();
            if ($profile) {
                $completionPercentage = $this->calculateAdvisorProfileCompletion($profile, $missingItems);
                $profileName = $profile->full_name;
                $meetingRequests = MeetingRequest::with(['finder.user'])
                ->where('id_profiles_advisor', $profile->id)
                ->latest()
                ->get();
            }
        } elseif ($hasFinder) {
            $profile = Finder::where('user_id', $user->id)->first();
            if ($profile) {
                $completionPercentage = $this->calculateFinderProfileCompletion($profile, $missingItems);
                $profileName = $profile->full_name;
                $meetingRequests = MeetingRequest::with(['advisor.user'])
                ->where('id_profiles_finder', $profile->id)
                ->latest()
                ->get();
            }
        }

        $statusCounts = [
            'pending' => $meetingRequests->where('status', 'pending')->count(),
            'accepted' => $meetingRequests->where('status', 'accepted')->count(),
            'declined' => $meetingRequests->where('status', 'declined')->count(),
        ];

        $calendarEvents = $meetingRequests
        ->where('status', 'accepted')
        ->filter(fn($m) => $m->scheduled_at)
        ->map(function ($m) use ($user) {
            return [
                'title' => $user->is_advisor ? $m->finder->user->name : $m->advisor->user->name,
                'start' => $m->scheduled_at->toIso8601String(),
                'url' => route('meeting.respond.form', $m->id),
            ];
        })->values();

        return view('dashboard', compact('completionPercentage', 'missingItems', 'profileName','meetingRequests', 'hasAdvisor', 'hasFinder', 'statusCounts', 'calendarEvents'));
    }

    private function calculateAdvisorProfileCompletion($profile,  &$missing = [])
    {
        $requiredFields = [
            'Full Name' => !empty($profile->full_name),
            'Picture' => !empty($profile->profile_picture),
            'Linkedin' => !empty($profile->linkedin_url),
            'Instagram' => !empty($profile->instagram_url),
            'Overview' => !empty($profile->overview),
            'Skills' => DB::table('advisor_skills')->where('id_profiles_advisor', $profile->id)->exists(),
            'Education' => DB::table('profile_education')->where('id_profiles_advisor', $profile->id)->exists(),
        ];

        $missing = array_keys(array_filter($requiredFields, fn($v) => !$v));
        $totalRequiredFields = count($requiredFields);
        $completedFields = array_sum($requiredFields);

        return ($totalRequiredFields > 0) ? round(($completedFields / $totalRequiredFields) * 100) : 0;
    }

    private function calculateFinderProfileCompletion($profile,  &$missing = [])
{
    $requiredFields = [
        'Full Name' => !empty($profile->full_name),
        'Picture' => !empty($profile->profile_picture),
        'Linkedin' => !empty($profile->linkedin_url),
        'Instagram' => !empty($profile->instagram_url),
        'Overview' => !empty($profile->overview),
        'Education' => DB::table('profile_education')->where('id_profiles_finder', $profile->id)->exists(),
        'Areas of interest' => DB::table('finder_interest_areas')->where('id_profiles_finder', $profile->id)->exists(),
    ];

    $missing = array_keys(array_filter($requiredFields, fn($v) => !$v));
    $totalRequiredFields = count($requiredFields);
    $completedFields = array_sum($requiredFields);

    return ($totalRequiredFields > 0) ? round(($completedFields / $totalRequiredFields) * 100) : 0;
}

}

