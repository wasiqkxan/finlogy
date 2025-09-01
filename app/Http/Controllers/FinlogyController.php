<?php

namespace App\Http\Controllers;

use App\Models\FinancialEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinlogyController extends Controller
{
    public function index()
    {
        return FinancialEntry::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:income,expense',
            'date' => 'required|date',
        ]);

        $entry = FinancialEntry::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $request->date,
        ]);

        return response()->json($entry, 201);
    }

    public function show(FinancialEntry $financialEntry)
    {
        $this->authorize('view', $financialEntry);
        return $financialEntry;
    }

    public function update(Request $request, FinancialEntry $financialEntry)
    {
        $this->authorize('update', $financialEntry);

        $request->validate([
            'description' => 'string|max:255',
            'amount' => 'numeric',
            'type' => 'string|in:income,expense',
            'date' => 'date',
        ]);

        $financialEntry->update($request->all());

        return response()->json($financialEntry);
    }

    public function destroy(FinancialEntry $financialEntry)
    {
        $this->authorize('delete', $financialEntry);
        $financialEntry->delete();
        return response()->json(null, 204);
    }
}
