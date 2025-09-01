<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function spendingByCategory(Request $request)
    {
        $user = Auth::user();
        $data = Cache::remember("spending-by-category-{$user->id}", 60, function () use ($user) {
            return Transaction::where('user_id', $user->id)
                ->with('category')
                ->get()
                ->groupBy('category.name')
                ->map(function ($transactions, $category) {
                    return [
                        'name' => $category,
                        'value' => $transactions->sum('amount')
                    ];
                })
                ->values();
        });

        return response()->json(['data' => $data]);
    }

    public function cashFlow(Request $request)
    {
        $user = Auth::user();
        $data = Cache::remember("cash-flow-{$user->id}", 60, function () use ($user) {
            $transactions = Transaction::where('user_id', $user->id)
                ->orderBy('date')
                ->get();

            $income = $transactions->where('type', 'income')->groupBy(function($d) {
                return substr($d->date, 0, 7); // group by year and month
            })->map(function ($transactions) {
                return $transactions->sum('amount');
            });

            $expense = $transactions->where('type', 'expense')->groupBy(function($d) {
                return substr($d->date, 0, 7); // group by year and month
            })->map(function ($transactions) {
                return $transactions->sum('amount');
            });

            $dates = $income->keys()->merge($expense->keys())->unique()->sort();

            return $dates->map(function($date) use ($income, $expense) {
                return [
                    'date' => $date,
                    'income' => $income->get($date, 0),
                    'expense' => $expense->get($date, 0),
                ];
            })->values();
        });
        
        return response()->json(['data' => $data]);
    }
    /**
     * Generate a PDF report for a given month's transactions.
     */
    public function generatePdfReport(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $user = Auth::user();
        $month = $request->input('month');
        $transactions = Transaction::where('user_id', $user->id)
            ->whereYear('date', '=', substr($month, 0, 4))
            ->whereMonth('date', '=', substr($month, 5, 2))
            ->get();

        $pdf = Pdf::loadView('reports.transactions', compact('transactions', 'month'));

        return $pdf->download('report.pdf');
    }
}
