<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountShareController extends Controller
{
    /**
     * Share an account with another user.
     */
    public function store(Request $request, Account $account)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Ensure the authenticated user owns the account
        if (Auth::id() !== $account->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $userToShareWith = User::where('email', $request->email)->first();

        // Prevent sharing with oneself
        if ($userToShareWith->id === Auth::id()) {
            return response()->json(['message' => 'You cannot share an account with yourself.'], 422);
        }

        // Share the account
        $account->sharedWith()->syncWithoutDetaching($userToShareWith->id);

        return response()->json(['message' => 'Account shared successfully.']);
    }

    /**
     * Revoke access to an account from a user.
     */
    public function destroy(Account $account, User $user)
    {
        // Ensure the authenticated user owns the account
        if (Auth::id() !== $account->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Revoke access
        $account->sharedWith()->detach($user->id);

        return response()->json(['message' => 'Account access revoked successfully.']);
    }
}
