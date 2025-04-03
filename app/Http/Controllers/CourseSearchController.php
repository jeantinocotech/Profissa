<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseSearchController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->input('term');
    
        if (!$term) {
            return response()->json([]); // ou até 5 sugestões genéricas se quiser
        }
    
        $results = Course::where('courses_name', 'LIKE', '%' . $term . '%')
            ->select('id', 'courses_name')
            ->limit(10)
            ->get();
    
        return response()->json($results);
    }
    
}
