<?php

namespace App\Domains\Car\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|integer|exists:clients,id', // Vagy owner_id, ha az a kapcsolat neve
            'car_id' => 'required|string|max:20|unique:cars,car_id',
            'type' => 'required|string|max:50',
            'registered' => 'required|date',
            'ownbrand' => 'required|boolean',
            'accident' => 'required|integer|min:0',
        ];
    }
}

namespace App\Http\Requests;
