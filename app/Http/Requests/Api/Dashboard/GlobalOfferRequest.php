<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class GlobalOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_active' => 'nullable|boolean',
        ];
    }
}
