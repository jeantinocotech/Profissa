{{-- resources/views/advisor/profile.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6 bg-gray-50">
                <div class="text-center">
                    @if ($advisor->profile_picture)
                        <img src="{{ asset('storage/' . $advisor->profile_picture) }}" 
                             alt="{{ $advisor->full_name }}"
                             class="w-32 h-32 rounded-full mx-auto object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 mx-auto flex items-center justify-center">
                            <span class="text-4xl text-gray-600">
                                {{ substr($advisor->full_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <h1 class="mt-4 text-2xl font-bold">{{ $advisor->full_name }}</h1>
                </div>

                <div class="mt-6 space-y-4">
                    @if($advisor->linkedin_url)
                        <a href="{{ $advisor->linkedin_url }}" 
                           target="_blank"
                           class="block text-center text-blue-600 hover:text-blue-800">
                            <i class="fab fa-linkedin mr-2"></i>LinkedIn Profile
                        </a>
                    @endif
                    
                    @if($advisor->instagram_url)
                        <a href="{{ $advisor->instagram_url }}" 
                           target="_blank"
                           class="block text-center text-pink-600 hover:text-pink-800">
                            <i class="fab fa-instagram mr-2"></i>Instagram Profile
                        </a>
                    @endif
                </div>
            </div>

            <div class="md:w-2/3 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Overview</h2>
                    <p class="text-gray-700">{{ $advisor->overview }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Skills & Expertise</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($advisor->skills as $skill)
                            <div class="bg-gray-100 rounded-full px-3 py-1">
                                <span class="font-medium">{{ $skill->name }}</span>
                                <span class="text-gray-600 text-sm ml-1">
                                    (Level: {{ $skill->pivot->competency_level }})
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Education</h2>
                    @foreach($advisor->education as $edu)
                        <div class="mb-4">
                            <h3 class="font-medium">{{ $edu->institution_name }}</h3>
                            <p class="text-gray-600">{{ $edu->certification }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $edu->dt_start->format('M Y') }} - 
                                {{ $edu->dt_end ? $edu->dt_end->format('M Y') : 'Present' }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    <button onclick="window.location.href='{{ route('meeting.request', $advisor->id) }}'"
                            class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 
                                   transition duration-200">
                        Request a Meeting
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
