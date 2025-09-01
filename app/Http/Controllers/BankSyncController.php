<?php

namespace App\Http\Controllers;

use App\Models\BankConnection;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSyncController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'access_token' => 'required|string',
        ]);

        $bankConnection = BankConnection::create([
            'user_id' => Auth::id(),
            'bank_name' => $request->bank_name,
            'access_token' => $request->access_token,
        ]);

        return response()->json($bankConnection, 201);
    }

    /**
     * Simulate fetching and adding transactions from a bank service.
     */
    public function sync(Request $request)
    {
        $user = Auth::user();
        $bankConnection = $user->bankConnections()->first();

        if (!$bankConnection) {
            return response()->json(['message' => 'No bank connection found.'], 404);
        }

        // Simulate fetching transactions
        $transactions = [
            [
                'account_id' => Account::where('user_id', $user->id)->first()->id,
                'amount' => -100.50,
                'description' => 'Grocery Store',
                'transaction_date' => now(),
            ],
            [
                'account_id' => Account::where('user_id', $user->id)->first()->id,
                'amount' => 2000,
                'description' => 'Paycheck',
                'transaction_date' => now(),
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }

        $bankConnection->update(['last_sync_at' => now()]);

        return response()->json(['message' => 'Bank sync completed.']);
    }
}
