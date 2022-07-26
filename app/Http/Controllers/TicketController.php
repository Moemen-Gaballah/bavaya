<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Mail\TicketMail;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tickets.index');
    }

    public function datatable()
    {
        $data = Ticket::select('*');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $show_tag = '<a href="'. route('tickets.show', $row->id) . '" > <img style="height:20px; width:20px;" src="'. asset('img/eye.png') .'"></a>';

                $edit_tag = '<a  href="' . url("tickets/" . $row->id . "/edit") . '" ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
</svg>  </a>';

                $delete_tag = '<a  onclick="deleteTicket(\'' . $row->id . '\')" style="cursor: pointer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </a>';


                return '<div class="rowsTableAction">'. $show_tag . $edit_tag . ' ' . $delete_tag . '</div>';

            })
            ->rawColumns(['action' => 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        $data = $request->validated();
        if(auth()->check())
            $data['user_id'] = auth()->id();

        Ticket::create($data);

        // TODO Send Mail use Queue
        $details = [
            'title' => 'done create your ticket ' . $request->subject,
            'content' => $request->content
        ];

        \Mail::to($request->email)->send(new TicketMail($details));


        Session::flash('success', "done create ticket successfully.");
        return redirect()->route('tickets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::where('id', $id)->with('replies')->firstOrFail();

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $request, Ticket $ticket)
    {
        $data = $request->validated();
        $ticket->update($data);

        Session::flash('success', "done update ticket successfully.");
        return redirect()->route('tickets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->replies()->delete();
        $ticket->delete();

        Session::flash('success', "done delete ticket successfully.");
        return redirect()->route('tickets.index');
    }
}
