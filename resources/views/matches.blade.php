@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Top Matching Advisors</h1>
    
    <div class="grid md:grid-cols-3 gap-6">
        @forelse ($matchingAdvisors as $advisor)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center mb-4">
                        @if ($advisor->profile_picture)
                            <img src="{{ asset('storage/' . $advisor->profile_picture) }}" 
                                 alt="{{ $advisor->full_name }}"
                                 class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-2xl text-gray-600">
                                    {{ substr($advisor->full_name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold">{{ $advisor->full_name }}</h2>
                            <div class="text-sm text-gray-600">
                                Matching Score: {{ number_format($advisor->matching_score, 1) }}
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-700 mb-4">
                        {{ Str::limit($advisor->overview, 150) }}
                    </p>

                    <div class="flex space-x-4">
                        @if($advisor->linkedin_url)
                            <a href="{{ $advisor->linkedin_url }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </a>
                        @endif
                        
                        <a href="{{ route('advisor.profile', $advisor->id) }}" 
                           class="text-indigo-600 hover:text-indigo-800">
                            View Full Profile
                        </a>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50">
                    <button onclick="window.location.href='{{ route('meeting.request', $advisor->id) }}';"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 
                                   transition duration-200">
                        Request Meeting
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-600 text-lg">
                    No matching advisors found. Try adjusting your skill preferences.
                </p>
            </div>
        @endforelse
    </div>
</div>
@endsection