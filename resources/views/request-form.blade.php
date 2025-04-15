<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Meeting ') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-16">
    <div class="container mx-auto max-w-2xl px-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">

                @if (isset($advisor))
                    <h2 class="text-xl font-semibold mb-4">Request Meeting with {{ $advisor->user->name }}</h2>
                @endif

                <form action="{{ route('requests.store', $advisor->id) }}" method="POST">

                    @if (isset($advisor))
                        <input type="hidden" name="advisor_id" value="{{ $advisor->id }}">
                    @endif

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
                            Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>