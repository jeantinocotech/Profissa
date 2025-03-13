<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\Education;
use App\Models\ProfileEducation;
use App\Models\Skills;
use App\Models\AdvisorSkill;

class AdvisorProfileController extends Controller
{
    public function show()
    {
        //$user = Auth::user();

        //$advisor = Advisor::findOrFail($user->id);
        //$educationData = $advisor->profileEducation()->with('education.course')->get();
        //$courses = DB::table('courses')->get();

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
            
            // More precise error handling
            if (!$profile) {
                Log::warning('No advisor profile found', ['user_id' => $user->id]);
                return redirect()->route('advisor-profile.create')
                    ->with('error', 'Please complete your advisor profile');
            }
    
           //$skillsData = AdvisorSkill::with('skill')
           //->where('id_profiles_advisor', $profile->id)
           //->get();
    
            // Modify this part to get both skill ID and name
            $skillsData = DB::table('advisor_skills')
            ->join('skills', 'advisor_skills.id_skills', '=', 'skills.id')
            ->where('advisor_skills.id_profiles_advisor', $profile->id)
            ->select('skills.id', 'skills.name')
            ->get();

           //dd('Reached show method - profile', $profile); 
           //dd('Reached show method 1', $skillsData); // Immediate debugging

            $educationData = DB::table('profile_education')
            ->join('courses', 'profile_education.id_courses', '=', 'courses.id')
            ->where('profile_education.id_profiles_advisor', $profile->id)
            ->select('profile_education.*','courses.courses_name')
            ->get(); // get() to fetch all records

            $allSkills = Skills::all(); // Get all available skills for the form
            $courses = Course::all();
    
            //dd('Reached show method - profile', $profile, $skillsData, $educationData); 

            //dd('Reached show method 1', $profile->id); // Immediate debugging
            //dd('Reached show method edu:', $educationData); // Immediate debugging
            //dd('Reached show method edu:', $courses); // Immediate debugging

            return view('advisor-profile', [
                'profile' => $profile, 
                'educationData' => $educationData,
                'courses' => $courses,
                'skillsData' => $skillsData,
                'allSkills' => $allSkills // Add this line
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
            'is_active' => 'required|boolean', 
            'skills' => 'array|nullable',
            'skills.*.id' => 'exists:skills,id'
        ]);
    
        // Add debugging here
        //dd($data); // Check validated data
        // dd(Auth::id()); // Check authenticated user ID

        try {

            DB::beginTransaction();

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

            // Process skills
            //dd('Store - antes de processar Skikks', $advisorProfileId); // Check generated ID
            
            if ($request->has('skills')) {
                $skills = collect($request->skills)->map(function($skillId) {
                    if (str_starts_with($skillId, 'new_')) {
                        // Create new skill if it doesn't exist
                        $skillName = str_replace('new_', '', $skillId);
                        $skill = Skills::firstOrCreate(
                            ['name' => $skillName],
                            ['created_at' => now()]
                        );
                        return $skill->id;
                    }
                    return $skillId;
                });
            
                // Insert all skills
                $skillsToInsert = $skills->map(function($skillId) use ($advisorProfileId) {
                    return [
                        'id_profiles_advisor' => $advisorProfileId,
                        'id_skills' => $skillId,
                        'created_at' => now()
                    ];
                })->all();
            
                AdvisorSkill::insert($skillsToInsert);
            }
            
            DB::commit();

            return redirect()
            ->route('advisor-profile.show')
            ->with('success', 'Profile created successfully!')
            ->with('profile_picture', $profilePicturePath); // Pass the image path to the next request

            //return redirect()->route('dashboard')->with('success', 'Profile created successfully!');
        } catch (\Exception $e) {
            
            DB::rollBack();
            
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
        'skills' => 'array|nullable',
        'skills.*' => 'exists:skills,id'
    ]);

    try {
        
    DB::beginTransaction();
    
    // Find the advisor profile
    $advisor = Advisor::findOrFail($id);
     // Update profiles_advisor table directly
     
    // $advisor = DB::table('profiles_advisor')->where('id', $id)->first();
    // if (!$advisor) {
    //     throw new \Exception('Advisor profile not found');
    // }

    //dd($data); // Check overview
    //dd($advisor->full_name); // Check overview

    // Update profile details
    $advisor->full_name = $data['full_name'];
    $advisor->linkedin_url = $data['linkedin_url'];
    $advisor->instagram_url = $data['instagram_url'];
    $advisor->overview = $data['overview'];
    $advisor->is_active = $data['is_active']; // Update active status

    $profilePicturePath = $advisor->profile_picture;
    $oldProfilePicture = $advisor->profile_picture;

    //dd('antes pict',$data);

    // Handle profile picture upload

        if ($request->hasFile('profile_picture')) {

            // Delete the old picture if it exists
            if ($oldProfilePicture && Storage::disk('public')->exists($oldProfilePicture)) {
                //dd($profilePicturePath); // Check overview
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
       
        //dd($profilePicturePath); // Check overview
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

        //dd('antes do IF de skills', $id, $data);

        // Delete existing skills before inserting new ones
        AdvisorSkill::where('id_profiles_advisor', $id)->delete();

       
        if ($request->has('skills')) {


            $skills = collect($request->skills)->map(function($skillId) {
                //dd('IF de apagar skills', $skillId);
                if (str_starts_with($skillId, 'new_')) {
                
                    // Create new skill if it doesn't exist
                    $skillName = str_replace('new_', '', $skillId);
                   
                   
                    $skill = Skills::firstOrCreate(
                        ['name' => $skillName],
                        ['created_at' => now()]
                    );

                    //dd('IF de novo skills', $skillId);

                    Log::info('New Skill Created', ['name' => $skillName, 'id' => $skill->id]);
                    
                    return $skill->id;
                }

                return $skillId;
            });

         // Insert all skills
         $skillsToInsert = $skills->map(function($skillId) use ($id) {
            return [
                'id_profiles_advisor' => $id,
                'id_skills' => $skillId,
                'created_at' => now()
            ];
        })->all();

        AdvisorSkill::insert($skillsToInsert);
        //dd('depois do insert skills', $id, $data);
        }
         DB::commit();
         
         return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');

    } catch (\Exception $e) {
    
        //dd('error insert skills', $id, $data);

        Log::error('Advisor Profile Update Failed', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'data' => $data
        ]);

        // More detailed logging
        Log::info('Storing advisor profile', [
            'user_id' => Auth::id(),
            'data' => $data
        ]);
        
        DB::rollBack();

        return redirect()->back()->with('error', 'Failed to save profile: ' . $e->getMessage());

    }
}

public function create()
{
    $courses = Course::all();
    $educationData = []; 
    $profile = null; // Explicitly pass null profile
    $skillsData = collect([]); // Add empty collection for skills


    return view('advisor-profile', [
        'courses' => $courses,
        'educationData' => $educationData,
        'profile' => $profile,
        'skillsData' => $skillsData
    ]);
}

public function edit($id)
{
    $profile = Advisor::with('profileEducation.education.course')->findOrFail($id);
    $courses = Course::all();
    $skillsData =  Skills::all();

    return view('advisor-profile', compact('profile', 'courses'));
}

public function searchSkills(Request $request)
{
    $term = $request->get('term');
    
    $skills = DB::table('skills')
        ->where('name', 'LIKE', "%{$term}%")
        ->select('id', 'name')
        ->limit(10)
        ->get();

    if ($skills->isEmpty() && strlen($term) >= 2) {
        // If no existing skill found, return the search term as a new skill option
        $skills = collect([
            [
                'id' => 'new_' . Str::slug($term),
                'name' => $term . ' (Create New)',
                'is_new' => true
            ]
        ]);
    }

    return response()->json($skills);
}

}