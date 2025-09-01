<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AccountController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $ownedAccounts = Account::where('user_id', $user->id)->get();
        $sharedAccounts = $user->accounts()->get();

        return $ownedAccounts->merge($sharedAccounts);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'current_balance' => 'required|numeric',
        ]);

        $account = Account::create($validatedData);

        return response()->json($account, 201);
    }

    public function show(Account $account)
    {
        $this->authorize('view', $account);
        return $account;
    }

    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $request->validate([
            'name' => 'string|max:255',
            'type' => 'string|max:255',
            'current_balance' => 'numeric',
        ]);

        $account->update($request->all());

        return response()->json($account, 200);
    }

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        $account->delete();

        return response()->json(null, 204);
    }
}
