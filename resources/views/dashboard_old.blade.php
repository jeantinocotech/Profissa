<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />

    <div class="p-6 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Block 1: Profile Completion -->
                <div class="bg-white rounded-lg shadow p-6 col-span-1">
                    <div class="text-gray-900 dark:text-gray-100 mb-4">
                            {{ __("Welcome ")}} {{ Auth::user()->name }}!
                    </div>
                    <h2 class="text-lg font-semibold mb-4">Profile</h2>
                    <div class="text-gray-900 mb-4">
                            <strong class="font-medium">Profile Completion:</strong>
                            <span class="text-blue-500">{{ $completionPercentage }}%</span>
                    </div>
                    <div class="mb-4">
                            <div class="w-40 h-40">
                                <canvas id="completionChart"></canvas>
                            </div>
                    </div>
                    @if (!empty($missingItems))
                            <div class="mb-4 text-gray-900">
                                <h4 class="text-gray-600 font-semibold">Pending Items:</h4>
                                <ul class="list-disc list-inside text-gray-500 text-sm mt-2">
                                    @foreach ($missingItems as $item)
                                        <li>{{ $loop->iteration }}. {{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                </div>

                <!-- Block 2: Meeting Status Summary -->
                <div class="bg-white rounded-lg shadow p-6 col-span-1">
                    <h2 class="text-lg font-semibold mb-4">
                        {{ $hasAdvisor ? 'Meeting Requested to me' : 'My meetings resquests' }}
                    </h2>
                    <ul class="text-sm space-y-2">
                        <li>Pending: {{ $statusCounts['pending'] ?? 0 }}</li>
                        <li>Accepted: {{ $statusCounts['accepted'] ?? 0 }}</li>
                        <li>Declined: {{ $statusCounts['declined'] ?? 0 }}</li>
                        @if(!$hasAdvisor)
                            <li>Canceled: {{ $statusCounts['canceled'] ?? 0 }}</li>
                        @else
                            <li>Cancellation Requested: {{ $statusCounts['cancellation_requested'] ?? 0 }}</li>
                        @endif
                    </ul>
                    <div class="mt-4 flex gap-2">

                        <!-- 
                        <a href="?status=pending" class="px-4 py-2 bg-yellow-400 text-white rounded">Pending</a>
                        <a href="?status=accepted" class="px-4 py-2 bg-green-500 text-white rounded">Accepted</a>
                        <a href="?status=declined" class="px-4 py-2 bg-red-500 text-white rounded">Declined</a>
                        Lista -->

                        <a href="{{ route('dashboard') }}" class="px-4 py-2 {{ !request('status') ? 'bg-blue-600' : 'bg-blue-400' }} text-white rounded">All</a>
                        <a href="{{ route('dashboard', ['status' => 'pending']) }}" class="px-4 py-2 {{ request('status') == 'pending' ? 'bg-yellow-600' : 'bg-yellow-400' }} text-white rounded">Pending</a>
                        <a href="{{ route('dashboard', ['status' => 'accepted']) }}" class="px-4 py-2 {{ request('status') == 'accepted' ? 'bg-green-600' : 'bg-green-500' }} text-white rounded">Accepted</a>
                        <a href="{{ route('dashboard', ['status' => 'declined']) }}" class="px-4 py-2 {{ request('status') == 'declined' ? 'bg-red-600' : 'bg-red-500' }} text-white rounded">Declined</a>
                        @if($hasAdvisor)
                            <a href="{{ route('dashboard', ['status' => 'cancellation_requested']) }}" class="px-4 py-2 {{ request('status') == 'cancellation_requested' ? 'bg-orange-600' : 'bg-orange-400' }} text-white rounded">Cancellation R.</a>
                        @else
                            <a href="{{ route('dashboard', ['status' => 'canceled']) }}" class="px-4 py-2 {{ request('status') == 'canceled' ? 'bg-gray-600' : 'bg-gray-400' }} text-white rounded">Canceled</a>
                        @endif
            
                    </div>
                </div>
                        
                <!-- Block 3: Meeting List -->
                <div class="bg-white rounded-lg shadow p-6 col-span-2">
                    <h2 class="text-lg font-semibold mb-4">My Meetings</h2>

                    @if($meetingRequests->count() > 0)
                    <div class="not-prose">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 text-left">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="w-1/5 px-2 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data da Solicitação
                                    </th>
                                    <th class="w-1/5 px-2 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ Auth::user()->is_finder ? 'Consultor' : 'Solicitante' }}
                                    </th>
                                    <th class="w-1/5 px-2 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                    </th>
                                    <th class="w-1/5 px-2 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mensagem
                                    </th>
                                    <th class="w-1/5 px-2 py-4text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                    </th>
                                </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($meetingRequests as $request)
                                        <tr>
                                            <td class="w-1/5 px-2 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                                {{ $request->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="w-1/5 px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500 text-left">
                                                @if(Auth::user()->is_finder)
                                                    {{ $request->advisor->user->name }}
                                                @else
                                                    {{ $request->finder->user->name }}
                                                @endif
                                            </td>
                                            <td class="w-1/5 px-2 py-4 whitespace-nowrap text-left">
                                                <span class="inline-flex text-sm font-medium rounded-full text-gray-500">
                                                    {{$request->status}}
                                                </span>
                                            </td>

                                            <td class="w-1/5 px-2 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openMessageModal('message-modal-{{ $request->id }}')">
                                                    Ver mensagem
                                                </button>
                                                
                                                <!-- Modal para exibir a mensagem -->
                                                <div id="message-modal-{{ $request->id }}" class="fixed inset-0 z-10 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                <div class="sm:flex sm:items-start">
                                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                                            Detalhes da Solicitação
                                                                        </h3>
                                                                        <div class="mt-4">
                                                                            <h4 class="text-md font-medium text-gray-700">Mensagem do solicitante:</h4>
                                                                            <p class="text-sm text-gray-500 mt-2">{{ $request->finder_message }}</p>
                                                                            
                                                                            @if($request->status == 'accepted' || $request->status == 'declined')
                                                                                <h4 class="text-md font-medium text-gray-700 mt-4">Resposta do consultor:</h4>
                                                                                <p class="text-sm text-gray-500 mt-2">{{ $request->advisor_response }}</p>
                                                                            @endif
                                                                            
                                                                            @if($request->status == 'cancellation_requested')
                                                                                <h4 class="text-md font-medium text-gray-700 mt-4">Justificativa para cancelamento:</h4>
                                                                                <p class="text-sm text-gray-500 mt-2">{{ $request->cancellation_reason }}</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeMessageModal('message-modal-{{ $request->id }}')">
                                                                    Fechar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="w-1/5 px-2 py-4 whitespace-nowrap text-left text-sm font-medium">
                                                {{-- Ações para Finder --}}
                                                @if(Auth::user()->is_finder)
                                                    @if($request->status == 'pending')
                                                        <form action="{{ route('meeting.cancel', $request->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-sm" 
                                                                    onclick="openConfirmCancelModal('confirm-cancel-modal-{{ $request->id }}')">
                                                                Cancelar
                                                            </button>

                                                            <!-- Modal de confirmação para cancelamento -->
                                                            <div id="confirm-cancel-modal-{{ $request->id }}" class="fixed inset-0 z-10 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                            <div class="sm:flex sm:items-start">
                                                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div>
                                                                                    <h3 class="text-lg font-medium text-gray-900">Cancelar solicitação</h3>
                                                                                    <p class="text-sm text-gray-600 mt-2 break-words whitespace-normal max-w-md">
                                                                                        Tem certeza que deseja cancelar esta solicitação de reunião? Esta ação não pode ser desfeita.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                            <form action="{{ route('meeting.cancel', $request->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                                    Confirmar Cancelamento
                                                                                </button>
                                                                            </form>
                                                                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeConfirmCancelModal('confirm-cancel-modal-{{ $request->id }}')">
                                                                                Voltar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @elseif($request->status == 'accepted')
                                                        
                                                        @if($request->proposal)
                                                            <span class="text-sm font-semibold 
                                                                @if($request->proposal->status == 'pending') text-yellow-600 
                                                                @elseif($request->proposal->status == 'accepted') text-green-600 
                                                                @elseif($request->proposal->status == 'declined') text-red-600 
                                                                @endif">
                                                                Proposta: {{ ucfirst($request->proposal->status) }}
                                                            </span>
                                                        @else
                                                            <a href="{{ route('meeting-proposal.create', ['id' => $request->id]) }}"
                                                                class="mt-3 bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                                                                Propor Reunião
                                                            </a>  
                                                        @endif

                                                        <button type="button" class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-md text-sm" onclick="openCancelRequestModal('cancel-request-modal-{{ $request->id }}')">
                                                            Solicitar Cancelamento
                                                        </button>
                                                        
                                                        <!-- Modal para solicitar cancelamento -->
                                                        <div id="cancel-request-modal-{{ $request->id }}" class="fixed inset-0 z-10 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                                    <form action="{{ route('meeting.cancel.request', $request->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                            <div class="sm:flex sm:items-start">
                                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                                                        Solicitar Cancelamento
                                                                                    </h3>
                                                                                    <div class="mt-4">
                                                                                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">
                                                                                            Justificativa para o cancelamento:
                                                                                        </label>
                                                                                        <div class="mt-1">
                                                                                            <textarea id="cancellation_reason" name="cancellation_reason" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                                Enviar Solicitação
                                                                            </button>
                                                                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeCancelRequestModal('cancel-request-modal-{{ $request->id }}')">
                                                                                Cancelar
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                
                                                {{-- Ações para Advisor --}}  
                                                @elseif(Auth::user()->is_advisor)
                                                    @if($request->status == 'pending')
                                                        <a href="{{ route('meeting.response.form', $request->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-md text-sm">
                                                            Responder
                                                        </a>
                                                    @elseif($request->status == 'cancellation_requested')
                                                        <div class="flex space-x-2">
                                                            <form action="{{ route('meeting.cancel.approve', $request->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded-md text-sm">
                                                                    Aprovar
                                                                </button>
                                                            </form>
                                                            
                                                            <form action="{{ route('meeting.cancel.deny', $request->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-sm">
                                                                    Rejeitar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Você ainda não tem solicitações de reunião.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>        

                <!-- Block 4: Calendar -->
                <div class="bg-white rounded-lg shadow p-6 col-span-2">
                    <h2 class="text-lg font-semibold mb-4">Meeting Calendar</h2>
                    <div id="calendar" class="h-96"></div>
                </div>      

            </div>
        </div>
    </div>
</x-app-layout>


<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openConfirmCancelModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeConfirmCancelModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    function openMessageModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeMessageModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    
    function openCancelRequestModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeCancelRequestModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart.js: gráfico de completude
        const ctx = document.getElementById('completionChart');
        if (ctx) {
            const percent = {{ $completionPercentage ?? 0 }};
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completo', 'Faltando'],
                    datasets: [{
                        data: [percent, 100 - percent],
                        backgroundColor: ['#3B82F6', '#E5E7EB'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // FullCalendar: calendário de reuniões
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'en', // ou 'pt-br'
                events: @json($calendarEvents),
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                }
            });
            calendar.render();
        }
    });
</script>

