<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_color_size_id' => 'required|exists:product_color_sizes,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_active' => 'nullable|boolean',
        ];
    }
}
