<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Skills;
use App\Models\Advisor;
use App\Models\ProfileEducation;

class FinderSearchController extends Controller
{
    /**
     * Display the search form for finding advisors.
     *
     * @return \Illuminate\View\View
     */
 

public function index()
{
    $courses = Course::orderBy('courses_name')->get();
    $skills = Skills::orderBy('name')->get(); // assuming the column is 'name'
    return view('finder-search', compact('courses', 'skills'));
}

public function search(Request $request)
{
    $selectedCourseIds = $request->input('courses', []);
    $selectedSkillIds = $request->input('skills', []);

    // Step 1: Match by Courses
    $courseMatches = DB::table('profiles_advisor')
        ->join('profile_education', 'profiles_advisor.id', '=', 'profile_education.id_profiles_advisor')
        ->whereIn('profile_education.id_courses', $selectedCourseIds)
        ->select('profiles_advisor.id', DB::raw('COUNT(*) * 100 as course_score'))
        ->groupBy('profiles_advisor.id');

    // Step 2: Match by Skills
    $skillMatches = DB::table('profiles_advisor')
        ->join('advisor_skills', 'profiles_advisor.id', '=', 'advisor_skills.id_profiles_advisor')
        ->whereIn('advisor_skills.id_skills', $selectedSkillIds)
        ->select('profiles_advisor.id', DB::raw('COUNT(*) * 10 as skill_score'))
        ->groupBy('profiles_advisor.id');

    // Step 3: Combine scores
    $combined = DB::table('profiles_advisor')
    ->leftJoinSub($courseMatches, 'course_match', 'profiles_advisor.id', '=', 'course_match.id')
    ->leftJoinSub($skillMatches, 'skill_match', 'profiles_advisor.id', '=', 'skill_match.id')
    ->select(
        'profiles_advisor.*',
        DB::raw('COALESCE(course_match.course_score, 0) + COALESCE(skill_match.skill_score, 0) as matching_score')
    )
    ->having('matching_score', '>', 0)
    ->orderByDesc('matching_score')
    ->limit(5)
    ->get();

    $advisorIds = $combined->pluck('id')->toArray();

    $advisorsWithRelations = Advisor::with(['courses', 'skills'])
        ->whereIn('id', $advisorIds)
        ->get()
        ->map(function ($advisor) use ($combined) {
            $score = $combined->firstWhere('id', $advisor->id)->matching_score ?? 0;
            $advisor->matching_score = $score;
            return $advisor;
        });
    
    $maxScore = count($selectedCourseIds) * 100 + count($selectedSkillIds) * 10;

    $percentage = 100;

    return view('finder-search', [
        'courses' => Course::orderBy('courses_name')->get(),
        'skills' => Skills::orderBy('name')->get(),
        'matchingAdvisors' => $advisorsWithRelations,
        'selectedCourses' => $selectedCourseIds,
        'selectedSkills' => $selectedSkillIds,
        'maxScore' => $maxScore,
        'percentage' => $percentage,
    ]);
}

}


