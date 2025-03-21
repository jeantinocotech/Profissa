@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Request Meeting with {{ $advisor->full_name }}</h1>

            <form action="{{ route('meeting.store', $advisor->id) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Message to the Advisor
                    </label>
                    <textarea id="message" 
                              name="message" 
                              rows="6" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 
                                     focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('message') border-red-500 @enderror"
                              placeholder="Introduce yourself and explain why you'd like to meet..."
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-medium mb-2">Your Selected Skills of Interest</h2>
                    <div class="bg-gray-50 rounded-md p-4">
                        @foreach($finderSkills as $skill)
                            <div class="inline-block bg-white rounded-full px-3 py-1 text-sm font-medium 
                                        text-gray-700 mr-2 mb-2">
                                {{ $skill->name }}
                                <span class="text-gray-500">(Priority: {{ $skill->pivot->importance_level }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" 
                            onclick="window.history.back()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 
                                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                   focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection