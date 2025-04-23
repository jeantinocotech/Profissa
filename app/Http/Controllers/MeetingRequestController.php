<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingRequest;
use App\Models\MeetingProposal;
use App\Models\Advisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Finder;
use Illuminate\Support\Facades\DB;

class MeetingRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $meetingRequests = collect(); // inicia vazio
      
    
        if ($user->is_advisor) {
            $advisor = $user->advisor;
    
            if (!$advisor) {
                abort(403, 'Perfil Advisor não encontrado.');
            }
    
            // Advisor vê reuniões recebidas
            $meetingRequests = MeetingRequest::with(['finder.user', 'proposal'])
                ->where('id_profiles_advisor', $advisor->id)
                ->latest()
                ->get();
    
        } elseif ($user->is_finder) {
            $finder = $user->finder;
    
            if (!$finder) {
                abort(403, 'Perfil Finder não encontrado.');
            }
    
            // Finder vê apenas as reuniões que ele solicitou
            $meetingRequests = MeetingRequest::with(['advisor.user', 'proposal'])
                ->where('id_profiles_finder', $finder->id)
                ->latest()
                ->get();
        } else {
            abort(403, 'Você precisa ter um perfil ativo (Finder ou Advisor) para acessar esta área.');
        }
    
        return view('requests-list', ['meetingRequests' => $meetingRequests]);
    }
    

    public function create($advisorId)
    {
        $advisor = Advisor::with('user')->findOrFail($advisorId);
        return view('request-form', compact('advisor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'advisor_id' => 'required|exists:profiles_advisor,id',
            'message' => 'required|string|max:1000',
        ]);

        Log::info('Finder ID: ' . auth()->user()->finder->id); // Log the finder ID for debugging
        Log::info('advisor ID: ' . $validated['advisor_id']); // Log the advisorID for debugging

        MeetingRequest::create([
            'id_profiles_finder' => auth()->user()->finder->id,
            'id_profiles_advisor' => $validated['advisor_id'],
            'finder_message' => $validated['message'], // Assuming this is the correct column name
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Response sent successfully!');
    }

    public function show($id)
    {
        $request = MeetingRequest::with(['advisor.user', 'finder.user'])->findOrFail($id);

        return view('request-show', compact('request'));
    }

    public function update(Request $request, $id)
    {
        // implementação futura
    }

    public function destroy($id)
    {
        // implementação futura
    }

    public function createForm(Advisor $advisor)
    {
        
        $finder = auth()->user()->finderProfile;
        $finderSkills = $finder->skills;
        
        return view('meeting.request-form', compact('advisor', 'finderSkills'));
    }

    public function respondForm(MeetingRequest $meetingRequest)
    {
        // Ensure the logged-in advisor is the one receiving this request
        if ($meetingRequest->id_profiles_advisor != auth()->user()->advisorProfile->id) {
            abort(403, 'Unauthorized');
        }
        
        return view('meeting.response-form', compact('meetingRequest'));
    }

    public function respond(Request $request, MeetingRequest $meetingRequest)
    {
        // Ensure the logged-in advisor is the one receiving this request
        if ($meetingRequest->id_profiles_advisor != auth()->user()->advisorProfile->id) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'status' => 'required|in:accepted,declined',
            'response' => 'required|string|max:500'
        ]);

        $meetingRequest->update([
            'status' => $request->status,
            'advisor_response' => $request->response,
            'updated_at' => now()
        ]);

        return redirect()->route('meeting.requests')
            ->with('success', 'Response sent successfully!');
    }
    
    public function responseForm($meetingsRequest)
    {
        $request = MeetingRequest::with(['advisor.user', 'finder.user',   'finder.interest_areas'])->findOrFail($meetingsRequest);
       
        Log::info('Request: ' , [$request]); // Log the advisorID for debugging
       
        return view('response-form', compact('request'));
    }

   

    public function response(Request $request, MeetingRequest $meetingRequest)
    {
        
        Log::info('response - Request: ' , [$request->all()]); // Log the advisorID for debugging
        Log::info('response - MeetingRequest: ' , [$meetingRequest]); // Log the advisorID for debugging
        
        $request->validate([
            'status' => 'required|in:accepted,declined',
            'advisor_response' => 'required|string|max:500'
        ]);

        Log::info('Response - Resposta recebida:', [
            'status' => $request->status,
            'advisor_response' => $request->advisor_response,
        ]);

        $meetingRequest->update([
            'status' => $request->status,
            'advisor_response' => $request->advisor_response
        ]);

        return redirect()->route('dashboard')->with('success', 'Response sent successfully!');

    }

    // Método para cancelamento direto de reuniões pendentes
    public function cancel(MeetingRequest $meetingRequest)
    {
        $user = auth()->user();
        
        // Verificar se o usuário é um Finder e é o dono da solicitação
        if (!$user->is_finder || $meetingRequest->id_profiles_finder != $user->finder->id) {
            abort(403, 'Você não tem permissão para cancelar esta solicitação de reunião.');
        }
        
        // Verificar se a reunião está com status pendente
        if ($meetingRequest->status !== 'pending') {
            abort(400, 'Apenas solicitações pendentes podem ser canceladas diretamente.');
        }

        
        // Cancelar a reunião
        $meetingRequest->update([
            'status' => 'canceled',
            'canceled_at' => now()
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Solicitação de reunião cancelada com sucesso!');
    }

    // Método para exibir o formulário de solicitação de cancelamento para reuniões aceitas
    public function cancelRequestForm(MeetingRequest $meetingRequest)
    {
        $user = auth()->user();
        
        // Verificar se o usuário é um Finder e é o dono da solicitação
        if (!$user->is_finder || $meetingRequest->id_profiles_finder != $user->finder->id) {
            abort(403, 'Você não tem permissão para solicitar cancelamento desta reunião.');
        }
        
        // Verificar se a reunião está com status aceito
        if ($meetingRequest->status !== 'accepted') {
            abort(400, 'Apenas reuniões aceitas podem receber solicitações de cancelamento.');
        }
        
        return view('meeting.cancel-request-form', compact('meetingRequest'));
    }

    // Método para processar a solicitação de cancelamento
    public function requestCancellation(Request $request, MeetingRequest $meetingRequest)
    {
        $user = auth()->user();
        
        // Verificar se o usuário é um Finder e é o dono da solicitação
        if (!$user->is_finder || $meetingRequest->id_profiles_finder != $user->finder->id) {
            abort(403, 'Você não tem permissão para solicitar cancelamento desta reunião.');
        }
        
        // Verificar se a reunião está com status aceito
        if ($meetingRequest->status !== 'accepted') {
            abort(400, 'Apenas reuniões aceitas podem receber solicitações de cancelamento.');
        }
        
        // Validar a justificativa
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        Log::info('Cancellation request received - antes update:', [
            'meeting details' => [$meetingRequest],
            'request details' => [$request]
        ]);
        
        // Atualizar o status da reunião para "cancellation_requested"
        $meetingRequest->update([
            'status' => 'cancellation_requested',
            'cancellation_reason' => $request->cancellation_reason,
            'cancellation_requested_at' => now()
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Solicitação de cancelamento enviada com sucesso! Aguarde a resposta do consultor.');
    }

    // Método para o Advisor aprovar a solicitação de cancelamento
    public function approveCancellation(MeetingRequest $meetingRequest)
    {
        $user = auth()->user();
        
        // Verificar se o usuário é um Advisor e é o destinatário da solicitação
        if (!$user->is_advisor || $meetingRequest->id_profiles_advisor != $user->advisor->id) {
            abort(403, 'Você não tem permissão para aprovar o cancelamento desta reunião.');
        }
        
        // Verificar se existe uma solicitação de cancelamento
        if ($meetingRequest->status !== 'cancellation_requested') {
            abort(400, 'Esta reunião não possui uma solicitação de cancelamento pendente.');
        }
        
        // Aprovar o cancelamento
        $meetingRequest->update([
            'status' => 'cancelled',
            'canceled_at' => now()
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Solicitação de cancelamento aprovada com sucesso!');
    }

    // Método para o Advisor rejeitar a solicitação de cancelamento
    public function denyCancellation(MeetingRequest $meetingRequest)
    {
        $user = auth()->user();
        
        // Verificar se o usuário é um Advisor e é o destinatário da solicitação
        if (!$user->is_advisor || $meetingRequest->id_profiles_advisor != $user->advisor->id) {
            abort(403, 'Você não tem permissão para rejeitar o cancelamento desta reunião.');
        }
        
        // Verificar se existe uma solicitação de cancelamento
        if ($meetingRequest->status !== 'cancellation_requested') {
            abort(400, 'Esta reunião não possui uma solicitação de cancelamento pendente.');
        }
        
        // Rejeitar o cancelamento - volta para o status aceito
        $meetingRequest->update([
            'status' => 'accepted',
            'cancellation_reason' => null,
            'cancellation_requested_at' => null
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Solicitação de cancelamento rejeitada. A reunião continua agendada.');
    }

}
