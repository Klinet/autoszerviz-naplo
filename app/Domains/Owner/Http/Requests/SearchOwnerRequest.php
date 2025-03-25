<?php

namespace App\Domains\Owner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required_without:idcard',
                'prohibited_if:idcard',
                'nullable',
                'string',
                'max:255'
            ],
            'idcard' => [
                'required_without:name',
                'prohibited_if:name',
                'nullable',
                'string',
                'alpha_num',
                'max:255'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required_without' => 'Vagy a név, vagy az igazolványszám megadása kötelező.',
            'idcard.required_without' => 'Vagy a név, vagy az igazolványszám megadása kötelező.',
            'name.prohibited_if' => 'Csak az egyik mezőt (név vagy igazolványszám) adja meg!',
            'idcard.prohibited_if' => 'Csak az egyik mezőt (név vagy igazolványszám) adja meg!',
            'idcard.alpha_num' => 'Az igazolványszám csak betűket és számokat tartalmazhat.',
        ];
    }
}
