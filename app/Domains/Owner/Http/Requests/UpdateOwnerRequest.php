<?php

namespace App\Domains\Owner\Http\Requests;

use App\Domains\Owner\Models\Owner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ownerModel = $this->route('owner');
        $ownerId = $ownerModel instanceof Owner ? $ownerModel->id : null; // Vagy Client // mert abból származtatható
// és lehet Operator is
        return [
            'name' => 'sometimes|required|string|max:255',
            'idcard' => [
                'sometimes',
                'required',
                'string',
                'alpha_num',
                'max:255',
                Rule::unique('clients', 'idcard')->ignore($ownerId),
            ],
        ];
    }
}
