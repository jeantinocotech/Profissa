<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\Education;
use App\Models\ProfileEducation;

class AdvisorProfileController extends Controller
{
    public function show()
    {
        //$user = Auth::user();

        //$advisor = Advisor::findOrFail($user->id);
        //$educationData = $advisor->profileEducation()->with('education.course')->get();
        //$courses = DB::table('courses')->get();

        // Fetch profile data
        //$profile = DB::table('profiles_advisor')
        //->where('user_id', $user->id)
        //->first();

        try {
            $user = Auth::user();
            
            // Debugging: Log user details
            Log::info('User Details', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
            $profile = DB::table('profiles_advisor')
            ->where('user_id', $user->id)
            ->first();
            
            //$advisor = Advisor::where('user_id', $user->id)->first();
            
            //dd('Reached show method 1', $profile); // Immediate debugging

            // More precise error handling
            if (!$profile) {
                Log::warning('No advisor profile found', ['user_id' => $user->id]);
                return redirect()->route('advisor-profile.create')
                    ->with('error', 'Please complete your advisor profile');
            }
    
            $educationData = DB::table('profile_education')
            ->join('courses', 'profile_education.id_courses', '=', 'courses.id')
            ->where('profile_education.id_profiles_advisor', $profile->id)
            ->select('profile_education.*','courses.courses_name')
            ->get(); // get() to fetch all records

            $courses = Course::all();
    
            //dd('Reached show method 1', $profile->id); // Immediate debugging
            //dd('Reached show method edu:', $educationData); // Immediate debugging
            //dd('Reached show method edu:', $courses); // Immediate debugging

            return view('advisor-profile', [
                'profile' => $profile, 
                'educationData' => $educationData,
                'courses' => $courses,
            ]);
    
        } catch (\Exception $e) {
            Log::error('Advisor Profile Show Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()->back()->with('error', 'An unexpected error occurred');
        }
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $data = $request->validate([
            'full_name' => 'required|string|max:45',
            'profile_picture' => 'nullable|image|max:5120', // Max 5MB
            'linkedin_url' => 'nullable|url|max:45',
            'instagram_url' => 'nullable|url|max:45',
            'overview' => 'nullable|string',
            'course' => 'required|array',
            'course.*' => 'exists:courses,id',
            'institution' => 'required|array|min:1',
            'institution.*' => 'required|string|max:255',
            'certification' => 'nullable|array',
            'start_date' => 'required|array',
            'start_date.*' => 'required|date',
            'end_date' => 'nullable|array',
            'comments' => 'nullable|array',
            'is_active' => 'required|boolean', // Add this to your validation
        ]);
    
        // Add debugging here
        // dd($data); // Check validated data
        // dd(Auth::id()); // Check authenticated user ID

        try {

            // Handle profile picture upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                
                // Remove old picture if it exists
                if (isset($advisor) && $advisor->profile_picture) {
                    Storage::disk('public')->delete($advisor->profile_picture);
                }
                
                $file = $request->file('profile_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $profilePicturePath = $file->storeAs('profiles', $filename, 'public');
                
                // Store the path and immediately make it available for the view
                session()->flash('temp_profile_picture', $profilePicturePath);

                // Store the new picture and get the path relative to the storage/app/public directory
                //$profilePicturePath = $request->file('profile_picture')
                //    ->storeAs('profiles', 
                //        time() . '_' . $request->file('profile_picture')->getClientOriginalName(),
                //        'public'
                //    );
                
                // Log the file storage information
                Log::info('Profile picture stored', [
                    'original_name' => $request->file('profile_picture')->getClientOriginalName(),
                    'stored_path' => $profilePicturePath,
                    'full_path' => Storage::disk('public')->path($profilePicturePath)
                ]);
            }

            // More detailed logging
            Log::info('picture path', [
                'Path' => $profilePicturePath
            ]);

            // More detailed logging
            Log::info('Storing advisor profile', [
                'user_id' => Auth::id(),
                'data' => $data
            ]);
            
             // Add more debugging
            DB::enableQueryLog();

            // More debugging
            //dd(DB::getQueryLog()); // Show executed query
            //dd($advisorProfileId); // Check generated ID
            
            // Dump and die to inspect request
            //dd($request->all());

            // Or use more subtle logging
            Log::info('Received profile data', $request->all());

            //dd('Reached show method 1', $data); // Immediate debugging

            // Insert data into `profiles_advisor` table
            $advisorProfileId = DB::table('profiles_advisor')->insertGetId([
                'user_id' => Auth::id(),
                'full_name' => $data['full_name'],
                'profile_picture' => $profilePicturePath,
                'linkedin_url' => $data['linkedin_url'] ?? null,
                'instagram_url' => $data['instagram_url'] ?? null,
                'overview' => $data['overview'] ?? null,
                'profile_completed' => 0,
                'is_active' => $data['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Process education entries and insert into `profile_education`
            foreach ($data['course'] as $index => $courseId) {
    
                // Insert profile education entry
                DB::table('profile_education')->insert([
                    'id_profiles_advisor' => $advisorProfileId,
                    'Institution_name' => $data['institution'][$index],
                    'id_courses' => $courseId,
                    'certification' => $data['certification'][$index] ?? null,
                    'dt_start' => $data['start_date'][$index],
                    'dt_end' => $data['end_date'][$index] ?? null,
                    'comments' => $data['comments'][$index] ?? null,
                ]);
            }
            
            return redirect()
            ->route('advisor-profile.show')
            ->with('success', 'Profile created successfully!')
            ->with('profile_picture', $profilePicturePath); // Pass the image path to the next request

            //return redirect()->route('dashboard')->with('success', 'Profile created successfully!');
        } catch (\Exception $e) {
            // More comprehensive error logging
            Log::error('Advisor Profile Creation Failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data
            ]);

            return redirect()->back()->with('error', 'Failed to save profile: ' . $e->getMessage());
        }
    }

public function update(Request $request, $id)
{
    // Validate the incoming request data
    $data = $request->validate([
        'full_name' => 'required|string|max:255',
        'linkedin_url' => 'nullable|url|max:255',
        'instagram_url' => 'nullable|url|max:255',
        'overview' => 'required|string|max:1000',
        'profile_picture' => 'nullable|image|max:5120', // Max 5MB
        'is_active' => 'required|boolean', // Added validation for active status
        'course' => 'array',
        'course.*' => 'exists:courses,id',
        'institution' => 'array',
        'institution.*' => 'required|string|max:255',
        'certification' => 'array',
        'certification.*' => 'nullable|string|max:255',
        'start_date' => 'array',
        'start_date.*' => 'required|date',
        'end_date' => 'array',
        'end_date.*' => 'nullable|date',
        'comments' => 'array',
        'comments.*' => 'nullable|string|max:500',
    ]);

    try {
        
    // Find the advisor profile
    $advisor = Advisor::findOrFail($id);


    //dd($data['full_name']); // Check overview
    //dd($advisor->full_name); // Check overview

    // Update profile details
    $advisor->full_name = $data['full_name'];
    $advisor->linkedin_url = $data['linkedin_url'];
    $advisor->instagram_url = $data['instagram_url'];
    $advisor->overview = $data['overview'];
    $advisor->is_active = $data['is_active']; // Update active status

    $profilePicturePath = $advisor->profile_picture;
    $oldProfilePicture = $advisor->profile_picture;

    //dd($advisor->full_name); // Check overview

    // Handle profile picture upload

        if ($request->hasFile('profile_picture')) {

            // Delete the old picture if it exists
            if ($oldProfilePicture && Storage::disk('public')->exists($oldProfilePicture)) {
                Storage::disk('public')->delete($oldProfilePicture);
            }

            // Store the new picture
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $profilePicturePath = $file->storeAs('profiles', $filename, 'public');
        
            $advisor->profile_picture = $profilePicturePath;
            
            // Make the new image immediately available
            session()->flash('temp_profile_picture', $profilePicturePath);
        }
         
        //dd($advisor->full_name); // Check overview
        //dd($data['full_name']); // Check overview
       
        $advisor->save();

        // Update education details
        // Clear old education records
        $advisor->profileEducation()->delete();
        //dd('Reached show method 1', $data); // Immediate debugging
        // Add new education records
        foreach ($data['course'] as $index => $courseId) {
            $advisor->profileEducation()->create([
                'institution_name' => $data['institution'][$index],
                'id_courses' => $courseId,
                'certification' => $data['certification'][$index] ?? null,
                'dt_start' => $data['start_date'][$index],
                'dt_end' => $data['end_date'][$index] ?? null,
                'comments' => $data['comments'][$index] ?? null,
            ]);
    }
} catch (\Exception $e) {
    Log::error('Advisor Profile Update Failed', [
        'user_id' => Auth::id(),
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'data' => $data
    ]);

    return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
}

    //'id_profiles_advisor' => $advisorProfileId,    --check if this is needed

    // More detailed logging
    Log::info('Storing advisor profile', [
        'user_id' => Auth::id(),
        'data' => $data
    ]);

    return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');

    redirect()->route('advisor-profile.edit')->with('success', 'Profile created successfully!');

}

public function create()
{
    $courses = Course::all();
    $educationData = []; 
    $profile = null; // Explicitly pass null profile

    return view('advisor-profile', [
        'courses' => $courses,
        'educationData' => $educationData,
        'profile' => $profile
    ]);
}

public function edit($id)
{
    $profile = Advisor::with('profileEducation.education.course')->findOrFail($id);
    $courses = Course::all();

    return view('advisor-profile', compact('profile', 'courses'));
}

}