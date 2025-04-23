<?php



    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\AdvisorAvailability;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\DB;
    
    class AdvisorAvailabilityController extends Controller
    {
        public function index()
        {
            $availabilities = AdvisorAvailability::where('id_profiles_advisor', Auth::user()->advisor->id)->get();
            return view('availability', compact('availabilities'));
        }
    
        public function store(Request $request)
        {

            Log::info('Advisor availability Initialized. Data:', [
                $request->all()
            ]);

            $request->validate([
                'type' => ['required', 'in:recurring,specific'],
                'weekday' => ['required_if:type,recurring'],
                'date' => ['required_if:type,specific', 'nullable', 'date'],
                'start_time' => ['required', 'date_format:H:i'],
                'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            ]);
            
    
            Log::info('Advisor availability request received. Data:', [
                $request->all()
            ]);

            AdvisorAvailability::create([
                'id_profiles_advisor' => Auth::user()->advisor->id,
                'available_date' => $request->type === 'specific' ? $request->date : null,
                'weekday' => $request->type === 'recurring' ? $request->weekday : null,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_recurring' => $request->type === 'recurring',
            ]);
    
            return redirect()->back()->with('success', 'Availability saved!');
        }
    }
    

