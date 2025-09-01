<?php

namespace App\Policies;

use App\Models\FinancialEntry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FinancialEntryPolicy
{
    public function view(User $user, FinancialEntry $financialEntry): bool
    {
        return $user->id === $financialEntry->user_id;
    }

    public function update(User $user, FinancialEntry $financialEntry): bool
    {
        return $user->id === $financialEntry->user_id;
    }

    public function delete(User $user, FinancialEntry $financialEntry): bool
    {
        return $user->id === $financialEntry->user_id;
    }
}
