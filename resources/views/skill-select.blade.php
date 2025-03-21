{{-- resources/views/finder/skill-select.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Select Your Skills of Interest</h1>
            <p class="mb-6 text-gray-600">
                Choose the skills you're looking for in an advisor. Rate each skill's importance to help us match you with the best advisors.
            </p>

            <form action="{{ route('finder.skills.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($skills as $skill)
                            <div class="border rounded-md p-4 hover:bg-gray-50">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" 
                                           id="skill-{{ $skill->id }}" 
                                           name="skills[]" 
                                           value="{{ $skill->id }}"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                           {{ in_array($skill->id, old('skills', $selectedSkills ?? [])) ? 'checked' : '' }}>
                                    <label for="skill-{{ $skill->id }}" class="ml-2 text-gray-700">
                                        {{ $skill->name }}
                                    </label>
                                </div>
                                
                                <div class="pl-6">
                                    <label for="importance-{{ $skill->id }}" class="block text-sm text-gray-600 mb-1">
                                        Importance (1-5)
                                    </label>
                                    <select id="importance-{{ $skill->id }}" 
                                            name="importance[{{ $skill->id }}]" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 
                                                   focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" 
                                                {{ old("importance.$skill->id", $skillImportance[$skill->id] ?? 3) == $i ? 'selected' : '' }}>
                                                {{ $i }} - {{ $i == 1 ? 'Nice to have' : ($i == 3 ? 'Important' : ($i == 5 ? 'Critical' : '')) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Skills & Find Advisors
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection