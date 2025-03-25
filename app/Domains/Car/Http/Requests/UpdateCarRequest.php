<?php

namespace App\Domains\Car\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $carId = $this->route('car') ? $this->route('car')->id : null;

        return [
            'client_id' => 'sometimes|required|integer|exists:clients,id',
            'car_id' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('cars', 'car_id')->ignore($carId),
            ],
            'type' => 'sometimes|required|string|max:50',
            'registered' => 'sometimes|required|date',
            'ownbrand' => 'sometimes|required|boolean',
            'accident' => 'sometimes|required|integer|min:0',
        ];
    }
}
