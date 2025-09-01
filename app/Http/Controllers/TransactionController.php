<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        $this->authorizeResource(Transaction::class, 'transaction');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $accountIds = $user->accounts()->pluck('id')->merge($user->sharedAccounts()->pluck('id'));

        $transactions = Transaction::whereIn('account_id', $accountIds)
            ->latest()
            ->paginate(10);

        return TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->validated());

        return new TransactionResource($transaction);
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction = $this->transactionService->updateTransaction($transaction, $request->validated());

        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        $this->transactionService->deleteTransaction($transaction);

        return response()->noContent();
    }
}
