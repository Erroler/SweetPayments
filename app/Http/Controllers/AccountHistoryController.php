<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccountLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AccountHistoryController extends Controller
{
    public function main() 
    {
        $logs = Auth::user()->account_history()->paginate(6);

        $last_action = Auth::user()->getLastAccountLog();
        $cannot_withdraw_reason = Auth::user()->cannotWithdrawReason();
        
        return view('pages.dashboard.withdrawls', compact('logs', 'last_action', 'cannot_withdraw_reason'));
    }

    public function paypalWithdraw(Request $request)   
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10|max:100',
            'paypal_address' => 'required|email|confirmed',
        ]);
        
        DB::beginTransaction();
        try {
            $balance = User::find(Auth::user()->id)->balance;
            if($balance < 10) {
                abort(403);
            }
            Auth::user()->update([
                'balance' => $balance - $validated['amount']
            ]);
            AccountLog::create([
                'user_id' => \Auth::user()->id,
                'action' => 'WITHDRAWL_REQUEST',
                'value' => $validated['amount'],
                'payment_method' => 'paypal',
                'extra_info' => [
                    'email' => $validated['paypal_address']
                ]
            ]);
            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollBack();
            return abort(403);
        }

        return redirect()->back(); //->withStatus('Withdraw requested successfully.');
    }
}
