<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class  TransactionsService {

    public function store($data)
    {
        // Validation
        $amountErrorMsg = $this->validateTransferBalance($data);
        if(!is_null($amountErrorMsg)){
            return back()->withInput($data)->withErrors(['amount' => $amountErrorMsg]);
        }

        DB::beginTransaction();
        try {

            $sender = auth()->user();
            $sender->balance -= $data['amount'];
            $sender->save();

            $receiver = User::find($data['user_id']);
            $receiver->balance += $data['amount'];
            $receiver->save();

            $transaction = Transaction::create([
                'from' => auth()->id(),
                'to' => $data['user_id'],
                'amount' => $data['amount'],
            ]);

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();

            return $e->getMessage();
            // something went wrong
        }

       return $transaction;

    }

    protected function validateTransferBalance($data){
        $amountMsg = null;
        $balance = auth()->user()->balance;
        $totalTransferLastHour = Transaction::where('from', auth()->id())->where('created_at', '>=', \Carbon\Carbon::now()->subHour())->sum('amount');
        $totalTransfer = $data['amount'] + $totalTransferLastHour;

        if($balance < $data['amount'])
            return $amountMsg = 'sorry, your balance less than amount';


        if($totalTransfer > User::MAX_Transfer)
            return $amountMsg = 'sorry, try again after one hour';

        return $amountMsg;
    }

}
