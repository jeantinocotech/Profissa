public function finderSkillForm()
{
    $skills = Skill::orderBy('name')->get();
    $finder = auth()->user()->finderProfile;
    
    // Get currently selected skills
    $selectedSkills = $finder->skills->pluck('id')->toArray();
    
    // Get importance levels
    $skillImportance = [];
    foreach ($finder->skills as $skill) {
        $skillImportance[$skill->id] = $skill->pivot->importance_level;
    }
    
    return view('finder.skill-select', compact('skills', 'selectedSkills', 'skillImportance'));
}

public function storeFinderSkills(Request $request)
{
    $request->validate([
        'skills' => 'required|array',
        'skills.*' => 'exists:skills,id',
        'importance' => 'required|array',
        'importance.*' => 'integer|min:1|max:5'
    ]);
    
    $finder = auth()->user()->finderProfile;
    
    // Detach all existing skills
    $finder->skills()->detach();
    
    // Attach new skills with importance
    foreach ($request->skills as $skillId) {
        $finder->skills()->attach($skillId, [
            'importance_level' => $request->importance[$skillId]
        ]);
    }
    
    return redirect()->route('advisor.matches')
        ->with('success', 'Skills updated successfully!');
}

public function advisorSkillForm()
{
    $skills = Skill::orderBy('name')->get();
    $advisor = auth()->user()->advisorProfile;
    
    // Get currently selected skills
    $selectedSkills = $advisor->skills->pluck('id')->toArray();
    
    // Get competency levels
    $skillCompetency = [];
    foreach ($advisor->skills as $skill) {
        $skillCompetency[$skill->id] = $skill->pivot->competency_level;
    }
    
    return view('advisor.skill-select', compact('skills', 'selectedSkills', 'skillCompetency'));
}

public function storeAdvisorSkills(Request $request)
{
    $request->validate([
        'skills' => 'required|array',
        'skills.*' => 'exists:skills,id',
        'competency' => 'required|array',
        'competency.*' => 'integer|min:1|max:5'
    ]);
    
    $advisor = auth()->user()->advisorProfile;
    
    // Detach all existing skills
    $advisor->skills()->detach();
    
    // Attach new skills with competency levels
    foreach ($request->skills as $skillId) {
        $advisor->skills()->attach($skillId, [
            'competency_level' => $request->competency[$skillId]
        ]);
    }
    
    return redirect()->route('advisor.profile', $advisor->id)
        ->with('success', 'Skills updated successfully!');
}