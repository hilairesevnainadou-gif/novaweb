<?php
// app/Http/Controllers/Admin/TicketController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->paginate(10);
        $openTickets = Ticket::where('status', 'open')->count();
        $inProgressTickets = Ticket::where('status', 'in_progress')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();

        return view('admin.tickets.index', compact('tickets', 'openTickets', 'inProgressTickets', 'closedTickets'));
    }

    public function show(Ticket $ticket)
    {
        $messages = $ticket->messages()->with('user')->get();
        return view('admin.tickets.show', compact('ticket', 'messages'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority
        ]);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket mis à jour avec succès');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => true
        ]);

        // Mettre à jour le statut si nécessaire
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Réponse ajoutée avec succès');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket fermé avec succès');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket supprimé avec succès');
    }
}
