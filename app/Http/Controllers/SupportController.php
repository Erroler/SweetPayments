<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index() 
    {
        $tickets = Auth::user()->tickets()->orderBy('id', 'DESC')->paginate(8);
        return view('pages.dashboard.tickets', compact('tickets'));
    }

    public function show(Ticket $ticket) 
    {
        if($ticket->user_id !== Auth::user()->id)
            return abort(403);
        
        dd($ticket->messages);
        
        // $messages = $ticket->messagesl
        //return view('pages.dashboard.tickets', compact('tickets'));
    }
}
