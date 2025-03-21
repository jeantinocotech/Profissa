
public function createForm(ProfilesAdvisor $advisor)
{
    $finder = auth()->user()->finderProfile;
    $finderSkills = $finder->skills;
    
    return view('meeting.request-form', compact('advisor', 'finderSkills'));
}

public function store(Request $request, ProfilesAdvisor $advisor)
{
    $request->validate([
        'message' => 'required|string|max:500'
    ]);

    MeetingRequest::create([
        'id_profiles_finder' => auth()->user()->finderProfile->id,
        'id_profiles_advisor' => $advisor->id,
        'finder_message' => $request->message,
        'status' => 'pending'
    ]);

    return redirect()->route('advisor.matches')
        ->with('success', 'Meeting request sent successfully!');
}

public function index()
{
    // For advisors viewing their requests
    if (auth()->user()->isAdvisor()) {
        $meetingRequests = MeetingRequest::where('id_profiles_advisor', auth()->user()->advisorProfile->id)
            ->with('finder')
            ->orderBy('created_at', 'desc')
            ->get();
    } 
    // For finders viewing their sent requests
    else {
        $meetingRequests = MeetingRequest::where('id_profiles_finder', auth()->user()->finderProfile->id)
            ->with('advisor')
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    return view('meeting.requests-list', compact('meetingRequests'));
}

public function respondForm(MeetingRequest $meetingRequest)
{
    // Ensure the logged-in advisor is the one receiving this request
    if ($meetingRequest->id_profiles_advisor != auth()->user()->advisorProfile->id) {
        abort(403, 'Unauthorized');
    }
    
    return view('meeting.response-form', compact('meetingRequest'));
}

public function respond(Request $request, MeetingRequest $meetingRequest)
{
    // Ensure the logged-in advisor is the one receiving this request
    if ($meetingRequest->id_profiles_advisor != auth()->user()->advisorProfile->id) {
        abort(403, 'Unauthorized');
    }
    
    $request->validate([
        'status' => 'required|in:accepted,declined',
        'response' => 'required|string|max:500'
    ]);

    $meetingRequest->update([
        'status' => $request->status,
        'advisor_response' => $request->response,
        'updated_at' => now()
    ]);

    return redirect()->route('meeting.requests')
        ->with('success', 'Response sent successfully!');
}
