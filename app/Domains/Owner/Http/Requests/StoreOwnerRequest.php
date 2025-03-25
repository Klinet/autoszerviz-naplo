<?php

namespace App\Domains\Owner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'idcard' => [
                'required',
                'string',
                'alpha_num',
                'max:255',
                Rule::unique('clients', 'idcard')
            ],
        ];
    }
}
