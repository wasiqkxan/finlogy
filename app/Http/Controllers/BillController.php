<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->bills;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'frequency' => 'required|string|in:monthly,quarterly,annually',
        ]);

        $bill = $request->user()->bills()->create($validatedData);

        return response()->json($bill, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        $this->authorize('view', $bill);

        return $bill;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        $this->authorize('update', $bill);

        $validatedData = $request->validate([
            'description' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric',
            'due_date' => 'sometimes|required|date',
            'frequency' => 'sometimes|required|string|in:monthly,quarterly,annually',
        ]);

        $bill->update($validatedData);

        return response()->json($bill);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        $this->authorize('delete', $bill);

        $bill->delete();

        return response()->json(null, 204);
    }

    /**
     * Mark the specified bill as paid.
     */
    public function pay(Request $request, Bill $bill)
    {
        $this->authorize('update', $bill);

        $validatedData = $request->validate([
            'account_id' => 'required|exists:accounts,id',
        ]);

        $bill->transactions()->create([
            'account_id' => $validatedData['account_id'],
            'user_id' => $request->user()->id,
            'type' => 'debit',
            'amount' => $bill->amount,
            'description' => $bill->description,
        ]);

        $bill->update(['due_date' => $bill->next_due_date]);

        return response()->json($bill, 200);
    }
}
