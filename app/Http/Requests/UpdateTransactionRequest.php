<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('transaction'));
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'in:credit,debit'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
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
