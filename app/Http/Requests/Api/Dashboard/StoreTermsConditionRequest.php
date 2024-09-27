<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreTermsConditionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description.en' => 'required|string',
            'description.ar' => 'required|string',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
