<?php

namespace App\Http\Requests\Api\Website;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
            // 'product_id' => 'nullable|exists:products,id',
            'product_color_size_id' => 'required|exists:product_color_sizes,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}