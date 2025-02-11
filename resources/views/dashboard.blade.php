<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
                 <!-- Add this button -->
                 <div class="p-6">
                    <a href="{{ route('advisor-profile.show') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Go to Advisor Profile
                    </a>
                </div>
                   <!-- Add this button -->
                   <div class="p-6">
                    <a href="{{ route('finder-profile.show') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Go to Finder Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
