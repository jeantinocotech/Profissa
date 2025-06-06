<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Respond to Meeting Request') }}
        </h2>
    </x-slot>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Respond to Meeting Request</h1>

            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-4">
                    @if ($request->finder->profile_picture)
                        <img src="{{ asset('storage/' . $request->finder->profile_picture) }}" 
                             alt="{{ $request->finder->full_name }}"
                             class="w-12 h-12 rounded-full object-cover">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-xl text-gray-600">
                                {{ substr($request->finder->full_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-4">
                        <h2 class="font-medium">{{ $request->finder->full_name }}</h2>
                        <p class="text-sm text-gray-600">
                            Requested: {{ $request->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-medium mb-2">Finder's Message:</h3>
                    <p class="text-gray-700">{{ $request->finder_message }}</p>
                </div>

                <div>
                    <h3 class="font-medium mb-2">Interest Areas:</h3>
                    <div class="flex flex-wrap gap-2">
                    @if($request->finder->interest_areas->count() > 0)
                        @foreach($request->finder->interest_areas as $area)
                            <span class="inline-block bg-white rounded-full px-3 py-1 text-sm font-medium text-gray-700">
                                {{ $area->courses_name }}
                            </span>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('meeting.respond', $request->id) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Your Response
                    </label>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="radio" 
                                   id="accept" 
                                   name="status" 
                                   value="accepted"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                   {{ old('status') == 'accepted' ? 'checked' : '' }}>
                            <label for="accept" class="ml-2">Accept Request</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" 
                                   id="decline" 
                                   name="status" 
                                   value="declined"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                   {{ old('status') == 'declined' ? 'checked' : '' }}>
                            <label for="decline" class="ml-2">Decline Request</label>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="response" class="block text-sm font-medium text-gray-700 mb-2">
                        Response Message
                    </label>
                    <textarea id="response" 
                              name="advisor_response" 
                              rows="4" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 
                                     focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('response') border-red-500 @enderror"
                              placeholder="Provide your response with detailed justification...">{{ old('response') }}</textarea>
                    @error('response')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4 gap-6">
                    <button type="button" 
                            onclick="window.history.back()"
                            class="gap-6 px-4 py-2 border border-gray-300 rounded-md text-gray-700 
                                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                   focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit"
                            class="gap-6 px-4 py-2 border border-gray-300 rounded-md text-gray-700 
                                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                   focus:ring-indigo-500">
                        Submit Response
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>