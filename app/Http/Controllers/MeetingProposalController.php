<?php

namespace App\Http\Controllers;
use App\Models\MeetingRequest;
use App\Models\AdvisorAvailability;
use App\Models\MeetingProposal;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeetingProposalController extends Controller
{    
    public function create($id)
    {
        $meetingRequest = MeetingRequest::findOrFail($id);
        $advisorId = $meetingRequest->id_profiles_advisor;
    
        // Buscar todas as disponibilidades futuras do Advisor
        $availabilities = AdvisorAvailability::where('id_profiles_advisor', $advisorId)
            ->where(function ($query) {
                $query->where('available_date', '>=', now())
                      ->orWhere('is_recurring', true);
            })
            ->get();
    
        return view('propose_meeting', compact('meetingRequest', 'availabilities'));
    }



    public function store(Request $request, $id)
    {
        $request->validate([
            'proposed_datetime' => 'required|string',
            'finder_comment' => 'nullable|string',
        ]);

        $meetingRequest = MeetingRequest::findOrFail($id);

        // Parse a string do tipo "2025-04-20|14:00" ou "Monday|14:00"
        [$dateOrWeekday, $startTime] = explode('|', $request->proposed_datetime);

        // Se for weekday recorrente, calcular próxima data correspondente
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOrWeekday)) {
            $date = Carbon::now()->next($dateOrWeekday)->format('Y-m-d');
        } else {
            $date = $dateOrWeekday;
        }
       
        try {
            $datetime = Carbon::createFromFormat('Y-m-d H:i', trim("{$date} " . substr($startTime, 0, 5)));
        } catch (\Exception $e) {
            Log::error('Erro ao converter data/hora: ' . $e->getMessage());
            return redirect()->back()->withErrors(['datetime' => 'Erro ao interpretar a data e hora.']);
        }

        Log::info('responseProposal Store' , [$request]); // Log the advisorID for debugging
        
        MeetingProposal::create([
            'id_meeting_request' => $meetingRequest->id,
            'proposed_datetime' => $datetime,
            'finder_comment' => $request->finder_comment,
            'status' => 'pending',
        ]);

        return redirect('/dashboard')->with('success', 'Meeting proposal sent!');
    }

    public function responseProposalForm($meetingsRequest)
    {
        $request = MeetingRequest::with(['advisor.user', 'finder.user',   'finder.interest_areas', 'proposal'])->findOrFail($meetingsRequest);
       
        Log::info('Proposal Request Form' , [$request]); // Log the advisorID for debugging
       
        return view('responseproposal-form', compact('request'));
    }

    public function confirm(Request $request, $meetingRequest)
    {
        
        Log::info('responseProposal - Request: ' , [$request]); // Log the advisorID for debugging
        Log::info('responseProposal - MeetingRequest: ' , [$meetingRequest]); // Log the advisorID for debugging
        
        $request->validate([
            'status' => 'required|in:accepted,declined',
            'advisor_response' => 'required|string|max:500'
        ]);

        Log::info('ResponseProposal - Resposta recebida:', [
            'status' => $request->status,
            'advisor_response' => $request->advisor_response,
        ]);

        $proposal = MeetingProposal::findOrFail($meetingRequest);
      
        $proposal->status = $request->status;
        $proposal->advisor_comment = $request->advisor_response;
        $proposal->save();

        return redirect()->route('dashboard')->with('success', 'Response sent successfully!');

    }

    public function requestCancellation(Request $request, $meetingRequest)
    {
        Log::info('requestcancellation - Request: ' , [$request->all()]); // Log the advisorID for debuggingProposal - Request: ' , [$request]); // Log the advisorID for debugging
        Log::info('requestcancellation - MeetingRequest: ' , [$meetingRequest]); // Log the advisorID for debugging
        
        // Validar a justificativa
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        Log::info('Cancellation request received - antes update:', [
            'meeting ID' => [$meetingRequest],
            'request details' => [$request]
        ]);
        
        $proposal = MeetingProposal::findOrFail($meetingRequest);

        // Atualizar o status da reunião para "cancellation_requested"
        $proposal->status = 'cancellation_requested';
        $proposal->finder_comment = $request->cancellation_reason;
        $proposal->cancellation_requested_at = now();
        $proposal->save();
        
        return redirect()->route('dashboard')->with('success', 'Solicitação de cancelamento enviada com sucesso! Aguarde a resposta do consultor.');
    }

    public function approveCancellation($meetingRequest)
    {
        $proposal = MeetingProposal::findOrFail($meetingRequest);

        // Atualizar o status da reunião para "cancellation_requested"
        $proposal->confirmed_by_advisor  = true ;
        $proposal->advisor_comment = 'OK';
        $proposal->status = 'cancelled';
        $proposal->save(); 
        
        
        return redirect()->route('dashboard')->with('success', 'Cancellation confirmed successfully!');
    }

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
            'status' => 'cancelled',
            'canceled_at' => now()
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Solicitação de reunião cancelada com sucesso!');
    }

}