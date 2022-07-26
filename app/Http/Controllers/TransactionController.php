<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\User;
use App\Services\TransactionsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transactions.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function getTransactionsDataTable()
    {
        $data = Transaction::where('to', auth()->id())->orWhere('from', auth()->id())->with(['fromUser', 'toUser']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('type', function($row){
                return ($row->from == auth()->id()) ? 'Transfer' : 'Income';
            })
            ->addColumn('from', function($row){
                return ($row->from == auth()->id()) ? 'Me' : $row->fromUser->name;
            })
            ->addColumn('to', function($row){
                return ($row->to == auth()->id()) ? 'Me' : $row->toUser->name;
            })
            ->addColumn('created_at', function($row){
                return date('d-m-Y g:i A', strtotime($row->created_at));
            })
            ->rawColumns(['type','from', 'to'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO Select Ajax with pagination for performance
        $users = User::select('id', 'name')->where('id', '!=', auth()->id())->get();

        return view('transactions.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request, TransactionsService $service)
    {
        $transaction = $service->store($request->all());
        if($transaction instanceof RedirectResponse)
            return $transaction;

        Session::flash('success', "done transfer successfully.");
        return redirect()->route('transactions.index');

    }




}
