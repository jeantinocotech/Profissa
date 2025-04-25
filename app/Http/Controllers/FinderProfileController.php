<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Finder;
use App\Models\Course;
use App\Models\Education;
use App\Models\ProfileEducation;
use App\Models\FinderInterestArea;
use App\Models\FinderInterestAreas;

class FinderProfileController extends Controller
{
    public function show()
    {
        //$user = Auth::user();

        //$finder = Finder::findOrFail($user->id);
        //$educationData = $finder->profileEducation()->with('education.course')->get();
        //$courses = DB::table('courses')->get();

        // Fetch profile data
        //$profile = DB::table('profiles_finder')
        //->where('user_id', $user->id)
        //->first();
        Log::info('SHOW METHOD INICIO');

        try {
            $user = Auth::user();
            
            // Debugging: Log user details
            Log::info('SHOW METHOD 1', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
            $profile = DB::table('profiles_finder')
            ->where('user_id', $user->id)
            ->first();
            
            //$finder = Finder::where('user_id', $user->id)->first();
            
            //dd('Reached show method 1', $profile); // Immediate debugging

            // More precise error handling
            if (!$profile) {
                Log::warning('No finder profile found', ['user_id' => $user->id]);
                return redirect()->route('finder-profile.create')
                    ->with('error', 'Please complete your finder profile');
            }
    
            $educationData = DB::table('profile_education')
            ->join('courses', 'profile_education.id_courses', '=', 'courses.id')
            ->where('profile_education.id_profiles_finder', $profile->id)
            ->select('profile_education.*','courses.courses_name')
            ->get(); // get() to fetch all records

            // Fetch interest areas for this profile
            $interestAreas = DB::table('finder_interest_areas')
            ->join('courses', 'finder_interest_areas.id_courses', '=', 'courses.id')
            ->where('finder_interest_areas.id_profiles_finder', $profile->id)
            ->select('finder_interest_areas.*','courses.courses_name')
            ->get();
            
            //->pluck('id_courses')
            //->toArray();
            //dd('Reached show method  - antes loaded courses', $profile->id); // Immediate debugging

            $courses = Course::all();
    
             // Debugging: Log user details
             Log::info('SHOW METHOD INTEREST AREAS', [
                $interestAreas
            ]);

            //dd('Reached show method  - loaded courses', $profile->id); // Immediate debugging
            //dd('Reached show method edu:', $educationData); // Immediate debugging
            //dd('Reached show method edu:', $courses); // Immediate debugging

            return view('finder-profile', [
                'profile' => $profile, 
                'educationData' => $educationData,
                'courses' => $courses,
                'interestAreas' => $interestAreas
            ]);
    
        } catch (\Exception $e) {
            Log::error('Finder Profile Show Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()->back()->with('error', 'An unexpected error occurred');
        }
    }

    public function store(Request $request)
    {
        
        Log::info('Store metodo INICIO');

        Log::info('Store finder profile', [
            'user_id' => Auth::id(),
            'data' => $request->all()
        ]);
        
        //dd('Store inicial',$request->all());

        // Validate incoming request data
        $data = $request->validate([
            'full_name' => 'required|string|max:155',
            'profile_picture' => 'nullable|image|max:5120', // Max 5MB
            'linkedin_url' => 'nullable|url|max:155',
            'instagram_url' => 'nullable|url|max:155',
            'overview' => 'nullable|string',
            'course' => 'nullable|array',
            'course.*' => 'exists:courses,id',
            'institution' => 'nullable|array',
            'institution.*' => 'nullable|string|max:255',
            'certification' => 'nullable|array',
            'start_date' => 'nullable|array',
            'start_date.*' => 'nullable|date',
            'end_date' => 'nullable|array',
            'comments' => 'nullable|array',
            'is_active' => 'required|boolean', // Add this to your validation
            'interest_areas' => 'nullable|array',
            'interest_areas.*' => 'exists:courses,id'
        ]);
    
        // Add debugging here
        //dd('Read data from Store', $data); // Check validated data
        // dd(Auth::id()); // Check authenticated user ID

        Log::info('After Validate - Store finder profile');


        try {

            Log::info('Picture finder profile', [
                'user_id' => Auth::id(),
                'data' => $request
            ]);

            // Handle profile picture upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                
                // Remove old picture if it exists
                if (isset($finder) && $finder->profile_picture) {
                    Storage::disk('public')->delete($finder->profile_picture);
                }
                
                $file = $request->file('profile_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $profilePicturePath = $file->storeAs('profiles', $filename, 'public');
                
                // Store the path and immediately make it available for the view
                session()->flash('temp_profile_picture', $profilePicturePath);

                // Log the file storage information
                Log::info('FINDER PROFILE PATH STORED', [
                    'original_name' => $request->file('profile_picture')->getClientOriginalName(),
                    'stored_path' => $profilePicturePath,
                    'full_path' => Storage::disk('public')->path($profilePicturePath)
                ]);
            }

            // More detailed logging
            Log::info('Finder picture path', [
                'Path' => $profilePicturePath
            ]);

            // More detailed logging
            Log::info('Storing finder profile', [
                'user_id' => Auth::id(),
                'data' => $data
            ]);
            
             // Add more debugging
            DB::enableQueryLog();

            // More debugging
            //dd(DB::getQueryLog()); // Show executed query
            //dd($finderProfileId); // Check generated ID
            
            // Dump and die to inspect request
            //dd($request->all());

            // Or use more subtle logging
            Log::info('Received profile data', $request->all());

            //dd('Reached show method 1', $data); // Immediate debugging

            DB::beginTransaction();

            // Insert data into `profiles_finder` table
            $finderProfileId = DB::table('profiles_finder')->insertGetId([
                'user_id' => Auth::id(),
                'full_name' => $data['full_name'],
                'profile_picture' => $profilePicturePath,
                'linkedin_url' => $data['linkedin_url'] ?? null,
                'instagram_url' => $data['instagram_url'] ?? null,
                'overview' => $data['overview'] ?? null,
                'is_active' => $data['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Process education entries and insert into `profile_education`

            if (!empty($data['course']) && !empty($data['institution']) && !empty($data['start_date'])) {
                foreach ($data['course'] as $index => $courseId) {
        
                    // Insert profile education entry
                    DB::table('profile_education')->insert([
                        'id_profiles_finder' => $finderProfileId,
                        'Institution_name' => $data['institution'][$index],
                        'id_courses' => $courseId,
                        'certification' => $data['certification'][$index] ?? null,
                        'dt_start' => $data['start_date'][$index],
                        'dt_end' => $data['end_date'][$index] ?? null,
                        'comments' => $data['comments'][$index] ?? null,
                    ]);
                }
            }

            // Store interest areas
            if (!empty($data['interest_areas'])) {
                foreach ($data['interest_areas'] as $courseId) {
                    DB::table('finder_interest_areas')->insert([
                        'id_profiles_finder' => $finderProfileId,
                        'id_courses' => $courseId,
                    ]);
                }
            }

            DB::commit();

            return redirect()
            ->route('finder-profile.show')
            ->with('success', 'Profile created successfully!')
            ->with('profile_picture', $profilePicturePath); // Pass the image path to the next request

            //return redirect()->route('dashboard')->with('success', 'Profile created successfully!');
        } catch (\Exception $e) {
            // More comprehensive error logging
            Log::error('Finder Profile Creation Failed', [
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
 
    Log::info('UPDATE metodo INICIO');

    Log::info('INICIAL UPDATE PROFILE', [
        'user_id' => Auth::id(),
        'data' => $request
    ]);
    
    Log::info('Raw request data', $request->all());

    // Validate incoming request data
    $data = $request->validate([
        'full_name' => 'nullable|string|max:155',  // Changed from required to nullable
        'profile_picture' => 'nullable|image|max:5120',
        'linkedin_url' => 'nullable|string|max:155',  // Changed from url to string
        'instagram_url' => 'nullable|string|max:155',  // Changed from url to string
        'overview' => 'nullable|string',
        'course' => 'nullable|array',  // Changed from sometimes to nullable
        'course.*' => 'nullable',  // Removed exists check
        'institution' => 'nullable|array',  // Removed min:1
        'institution.*' => 'nullable|string|max:255',  // Changed from required to nullable
        'certification' => 'nullable|array',
        'start_date' => 'nullable|array',  // Changed from sometimes to nullable
        'start_date.*' => 'nullable',  // Removed date check
        'end_date' => 'nullable|array',
        'comments' => 'nullable|array',
        'is_active' => 'nullable|boolean',  // Changed from required to nullable
        'interest_areas' => 'nullable|array',
        'interest_areas.*' => 'nullable',  // Removed exists check
    ]);

    Log::info('Update pos validation', $data);
    
    //dd($data); // Check overview

    try {
        
    DB::beginTransaction();
    
    // Find the finder profile
    $finder = Finder::findOrFail($id);


    //dd($data['full_name']); // Check overview
    //dd($finder->full_name); // Check overview

    // Update profile details
    $finder->full_name = $data['full_name'];
    $finder->linkedin_url = $data['linkedin_url'];
    $finder->instagram_url = $data['instagram_url'];
    $finder->overview = $data['overview'];
    $finder->is_active = $data['is_active']; // Update active status

    $profilePicturePath = $finder->profile_picture;
    $oldProfilePicture = $finder->profile_picture;

    //dd($finder->full_name); // Check overview

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

        Log::info('FINDER PROFILE PATH UPDATED', [
            'original_name' => $request->file('profile_picture')->getClientOriginalName(),
            'stored_path' => $profilePicturePath,
            'full_path' => Storage::disk('public')->path($profilePicturePath)
        ]);
    
        $finder->profile_picture = $profilePicturePath;
        
        // Make the new image immediately available
        session()->flash('temp_profile_picture', $profilePicturePath);
    }
        
    //dd($finder->full_name); // Check overview
    //dd($data['full_name']); // Check overview
    
    $finder->save();

    // Update education details
    // First, log the ID we're trying to update
    Log::info('Deleting education records for profile', ['profile_id' => $id]);
    
    // Clear old education records
    //$finder->profileEducation()->delete();
    //dd('Reached show method 1', $data); // Immediate debugging
    // Explicitly delete via direct query for better debugging
    $deletedCount = DB::table('profile_education')
    ->where('id_profiles_finder', $id)
    ->delete();

    Log::info('Deleted education records', ['count' => $deletedCount]);
    // Now add new education records
    if (!empty($data['course'])) {
        foreach ($data['course'] as $index => $courseId) {
            DB::table('profile_education')->insert([
                'id_profiles_finder' => $id,
                'Institution_name' => $data['institution'][$index],
                'id_courses' => $courseId,
                'certification' => $data['certification'][$index] ?? null,
                'dt_start' => $data['start_date'][$index],
                'dt_end' => $data['end_date'][$index] ?? null,
                'comments' => $data['comments'][$index] ?? null,
            ]);
        }
        
        Log::info('Added new education records', ['count' => count($data['course'])]);
    }
        
     // Handle interest areas

     $data = $request->all();

     Log::info('Antes do IF - Processing Interest Areas', $data);

      // Apaga todas as áreas de interesse anteriores do Finder
     FinderInterestAreas::where('id_profiles_finder', $id)->delete();
     Log::info('Deleting interest areas for profile', [$id]);

     if (isset($data['interest_areas'])) {

        Log::info('Dentro do IF - Processing Interest Areas', $data);

        if (isset($data['interest_areas'])) {
            foreach ($data['interest_areas'] as $area) {
                $courseId = is_array($area) && isset($area['id_courses']) ? $area['id_courses']
                          : (is_array($area) && isset($area['id']) ? $area['id']
                          : (is_string($area) ? $area : null));
        
                if ($courseId) {
                    FinderInterestAreas::create([
                        'id_profiles_finder' => $id,
                        'id_courses' => $courseId,
                    ]);
                }
            }
        }

     }

} catch (\Exception $e) {
    Log::error('Finder Profile Update Failed', [
        'user_id' => Auth::id(),
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'data' => $data
    ]);

    return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
}

//'id_profiles_finder' => $finderProfileId,    --check if this is needed

// More detailed logging
Log::info('Storing finder profile', [
    'user_id' => Auth::id(),
    'data' => $data
]);

DB::commit();

Log::info('FIM UPDATE PROFILE', [
    'user_id' => Auth::id(),
    'data' => $request
]);

return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');

redirect()->route('finder-profile.edit')->with('success', 'Profile created successfully!');

}

public function create()
{
    $courses = Course::all();
    $educationData = []; 
    $profile = null; // Explicitly pass null profile

    Log::info('Create finder profile', [
        'user_id' => Auth::id()
    ]);
    
    $user = Auth::user();

    if (\App\Models\Advisor::where('user_id', $user->id)->exists()) {
        abort(403, 'You already have an advisor assigned to you.');
    }

    Log::info('Create finder profile depois de validar');
    
    return view('finder-profile', [
        'courses' => $courses,
        'educationData' => $educationData,
        'profile' => $profile
    ]);
}

public function edit($id = null)
{
    $profile = null;
    $educationData = [];
    $interestAreas = [];
    
    Log::info('Edit metodo INICIO');
    
    if ($id) {
        $profile = Finder::find($id);
        
        if ($profile) {
            // Get education data
            $educationData = ProfileEducation::where('id_profiles_finder', $profile->id)->get();
            
            // Get interest areas - join with courses to get the course names
            $interestAreas = DB::table('finder_interest_areas')
                ->join('courses', 'finder_interest_areas.id_courses', '=', 'courses.id')
                ->where('finder_interest_areas.id_profiles_finder', $profile->id)
                ->select('courses.id', 'courses.courses_name')
                ->get();
        }
    }
    
    $courses = Course::all();
    
    return view('finder-profile', compact('profile', 'courses', 'educationData', 'interestAreas'));
}

}