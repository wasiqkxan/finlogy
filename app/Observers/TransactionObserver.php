<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        if ($transaction->type === 'credit') {
            $transaction->account->increment('balance', $transaction->amount);
        } else {
            $transaction->account->decrement('balance', $transaction->amount);
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        $originalAmount = $transaction->getOriginal('amount');
        $originalType = $transaction->getOriginal('type');

        if ($originalType === 'credit') {
            $transaction->account->decrement('balance', $originalAmount);
        } else {
            $transaction->account->increment('balance', $originalAmount);
        }

        if ($transaction->type === 'credit') {
            $transaction->account->increment('balance', $transaction->amount);
        } else {
            $transaction->account->decrement('balance', $transaction->amount);
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        if ($transaction->type === 'credit') {
            $transaction->account->decrement('balance', $transaction->amount);
        } else {
            $transaction->account->increment('balance', $transaction->amount);
        }
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
