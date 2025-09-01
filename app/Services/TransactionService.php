<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $account = Account::findOrFail($data['account_id']);

            return $account->transactions()->create($data);
        });
    }

    public function updateTransaction(Transaction $transaction, array $data): Transaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            $transaction->update($data);

            return $transaction;
        });
    }

    public function deleteTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $transaction->delete();
        });
    }
}
