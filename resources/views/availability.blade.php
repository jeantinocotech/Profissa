<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Set Your Availability
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-xl gap-6 mt-8">

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form action="{{ url('/advisor/availability') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">Type</label>
            <select name="type" id="type" class="mt-1 block w-full border rounded" onchange="toggleInputs()">
                <option value="specific">Specific Date</option>
                <option value="recurring">Recurring Weekly</option>
            </select>
        </div>

        <div id="weekday-input" class="hidden">
            <label class="block font-medium">Weekday</label>
            <select name="weekday" class="mt-1 block w-full border rounded">
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div id="date-input">
            <label class="block font-medium">Date</label>
            <input type="date" name="date" class="mt-1 block w-full border rounded">
        </div>

        <div>
            <label class="block font-medium">Start Time</label>
            <input type="time" name="start_time" class="mt-1 block w-full border rounded">
        </div>

        <div>
            <label class="block font-medium">End Time</label>
            <input type="time" name="end_time" class="mt-1 block w-full border rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </form>

    <hr class="my-6">

    <h3 class="text-lg font-bold">Your Current Availabilities</h3>
    <ul class="list-disc pl-5 mt-2">
        @foreach($availabilities as $slot)
            <li>{{ $slot->is_recurring ? $slot->weekday : $slot->available_date }} from {{ $slot->start_time }} to {{ $slot->end_time }}</li>
        @endforeach
    </ul>
</div>

<script>
    function toggleInputs() {
        const type = document.getElementById('type').value;
        document.getElementById('weekday-input').classList.toggle('hidden', type !== 'recurring');
        document.getElementById('date-input').classList.toggle('hidden', type !== 'specific');
    }
    document.addEventListener('DOMContentLoaded', toggleInputs);
</script>
</x-app-layout>
