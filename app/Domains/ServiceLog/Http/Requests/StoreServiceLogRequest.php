<?php

namespace App\Domains\ServiceLog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'car_id' => 'required|integer|exists:cars,id',
            'lognumber' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('services', 'lognumber')
                    ->where('car_id', $this->car_id)
            ],
            'event_id' => [
                'required',
                'integer',
                Rule::in(array_column(config('event_types.types', []), 'id'))
            ],
            'eventtime' => 'nullable|date_format:Y-m-d H:i:s',
            'document_id' => 'nullable|integer',
        ];
    }
}
