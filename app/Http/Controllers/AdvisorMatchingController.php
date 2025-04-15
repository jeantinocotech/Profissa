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

        return redirect()->route('requests.index');
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