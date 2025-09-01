<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $account = Account::findOrFail($this->input('account_id'));
        return $this->user()->can('update', $account);
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'account_id' => [
                'description' => 'The ID of the account.',
                'example' => 1,
            ],
            'type' => [
                'description' => 'The type of the transaction.',
                'example' => 'credit',
            ],
            'amount' => [
                'description' => 'The amount of the transaction.',
                'example' => 100.00,
            ],
            'description' => [
                'description' => 'The description of the transaction.',
                'example' => 'Groceries',
            ],
        ];
    }
}
