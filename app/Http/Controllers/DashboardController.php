<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Advisor;
use App\Models\Finder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MeetingRequest;
use App\Models\MeetingProposal;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $completionPercentage = 0; // Default value
        $missingItems = [];
        $profileName = '';
        $hasAdvisor = \App\Models\Advisor::where('user_id', $user->id)->exists();
        $hasFinder = \App\Models\Finder::where('user_id', $user->id)->exists();
        $meetingRequests = collect(); // inicia vazio
        $meetingProposals = collect(); // inicia vazio
        $meetings = [];
        $advisorsReady = [];
     

        Log::info('Profile', [$user] );
        Log::info( 'Dashcontroler request all:',$request->all() );

        // Get the filter status from the request
        $statusFilter = $request->query('status');

        // Determine if the user is an advisor or a finder
        if ($hasAdvisor) {

            $profile = Advisor::where('user_id', $user->id)->first();

            if ($profile) {
                $completionPercentage = $this->calculateAdvisorProfileCompletion($profile, $missingItems);
                $profileName = $profile->full_name;

                 // Apply status filter if provided
                $query = MeetingRequest::where('id_profiles_advisor', $profile->id)
                ->with(['finder.user', 'advisor','proposal'])
                ->get();

                Log::info( 'Dashcontroler Meeting Request advisor:', [$query] );

                if ($statusFilter && in_array($statusFilter, ['pending', 'accepted', 'declined', 'cancellation_requested'])) {
                    $query->where('proposal.status', $statusFilter);
                }
        
                $meetingRequests = $query;

            }

        } elseif ($hasFinder) {

            $profile = Finder::where('user_id', $user->id)->first();
           
            // Apenas os com status "accepted"
            $advisorsReady = MeetingRequest::where('id_profiles_finder', $profile->id)
            ->where('status', 'accepted')
            ->with(['finder','advisor', 'proposal']) // adicionamos o proposal
            ->get();

            Log::info( 'Dashcontroler advisorsReady:',$advisorsReady->all() );
           
            if ($profile) {
                $completionPercentage = $this->calculateFinderProfileCompletion($profile, $missingItems);
                $profileName = $profile->full_name;
                
                // Apply status filter if provided
                $query = MeetingRequest::where('id_profiles_finder', $profile->id)
                ->with(['advisor.user', 'finder','proposal'])
                ->get();

                Log::info( 'Dashcontroler Meeting Request finder:', [$query] );
            
                if ($statusFilter && in_array($statusFilter, ['pending', 'accepted', 'declined', 'canceled'])) {
                    $query->where('proposal.status', $statusFilter);
                }
        
                $meetingRequests = $query;
            }
        }

        if (!isset($query->id)) {
      
            $meetingProposals = MeetingProposal::whereIn('id_meeting_request', $query->pluck('id'))
            ->with('meetingRequest.advisor','meetingRequest.finder')
            ->get();
        
            Log::info( 'Dashcontroler Meeting Proposals:', [$meetingProposals->all()] );
       

             $proposalCounts = [
                'all' => $meetingProposals->count(),
                'pending' => $meetingProposals->where('status', 'pending')->count(),
                'accepted' => $meetingProposals->where('status', 'accepted')->count(),
                'declined' => $meetingProposals->where('status', 'declined')->count(),
            ];
                if ($hasAdvisor) {
                    $proposalCounts['cancellation_requested' ] = $meetingProposals->where('status', 'cancellation_requested')->count();
                } else {
                    $proposalCounts['canceled'] = $meetingProposals->where('status', 'canceled')->count();
                }
       
            Log::info( 'Dashcontroler proposal counts:',[$proposalCounts] );
        }

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

        return view('dashboard', 
                compact('completionPercentage', 
                'missingItems', 
                'profileName',
                'meetingRequests',
                'hasAdvisor', 
                'hasFinder', 
                'calendarEvents',
                'meetingProposals',
                'advisorsReady',
                'proposalCounts'));
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

