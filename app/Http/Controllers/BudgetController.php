<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        return Budget::where('user_id', Auth::id())->with('category')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $budget = Budget::create($validatedData);

        return response()->json($budget, 201);
    }

    public function show(Budget $budget)
    {
        $this->authorize('view', $budget);
        return $budget->load('category');
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $request->validate([
            'category_id' => 'exists:categories,id',
            'amount' => 'numeric',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
        ]);

        $budget->update($request->all());

        return response()->json($budget, 200);
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return response()->json(null, 204);
    }
}
