<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-xl mt-6">
        <h2 class="text-xl font-bold mb-4">Propose a Meeting with Advisor</h2>

        <form method="POST" action="{{ url('/meeting-request/' . $meetingRequest->id . '/propose') }}">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-2">Select a Date & Time</label>
                <select name="proposed_datetime" required class="w-full border rounded p-2">
                    @foreach($availabilities as $slot)
                        @php
                            $label = $slot->is_recurring
                                ? "{$slot->weekday} (Recurring) — {$slot->start_time} to {$slot->end_time}"
                                : "{$slot->available_date} — {$slot->start_time} to {$slot->end_time}";
                        @endphp
                        <option value="{{ $slot->available_date ?? $slot->weekday }}|{{ $slot->start_time }}">
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Comment (optional)</label>
                <textarea name="finder_comment" class="w-full border rounded p-2" rows="4"></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Send Proposal
            </button>
        </form>
    </div>
</x-app-layout>
