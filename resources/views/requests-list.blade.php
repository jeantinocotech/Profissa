{{-- resources/views/meeting/requests-list.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Meeting Requests</h1>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4">
            <ul class="divide-y divide-gray-200">
                @forelse($meetingRequests as $request)
                    <li class="py-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    @if ($request->finder->profile_picture)
                                        <img src="{{ asset('storage/' . $request->finder->profile_picture) }}" 
                                             alt="{{ $request->finder->full_name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-lg text-gray-600">
                                                {{ substr($request->finder->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <h3 class="font-medium">{{ $request->finder->full_name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            Requested: {{ $request->created_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm mb-2">
                                    {{ Str::limit($request->finder_message, 100) }}
                                </p>
                                
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($request->status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    
                                    @if($request->status !== 'pending')
                                        <span class="ml-2 text-sm text-gray-500">
                                            Response sent: {{ $request->updated_at->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0 md:ml-6">
                                @if($request->status === 'pending')
                                    <a href="{{ route('meeting.respond.form', $request->id) }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm
                                              text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 
                                              focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Respond
                                    </a>
                                @else
                                    <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md
                                                   text-sm font-medium text-gray-700 bg-white hover:bg-gray-50
                                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            onclick="viewResponse('{{ $request->id }}')">
                                        View Response
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-8 text-center text-gray-500">
                        No meeting requests found.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Modal for viewing responses -->
<div id="responseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Response</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="responseContent" class="text-gray-700 mb-4"></div>
        <button onclick="closeModal()" 
                class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
            Close
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function viewResponse(requestId) {
        // In a real application, you would fetch this data from your server
        // For this example, we'll just show the modal with static content
        document.getElementById('responseModal').classList.remove('hidden');
        document.getElementById('responseContent').textContent = "Response content would be loaded here.";
        
        // Real implementation would use AJAX to fetch the response content
        // Example:
        /*
        fetch(`/api/meeting-requests/${requestId}/response`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('responseContent').textContent = data.response;
            });
        */
    }
    
    function closeModal() {
        document.getElementById('responseModal').classList.add('hidden');
    }
</script>
@endsection