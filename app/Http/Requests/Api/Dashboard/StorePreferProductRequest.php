<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StorePreferProductRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id|unique:prefer_products,product_id', // Ensure product_id is unique in prefer_products
            'title.en' => 'required|string|max:255',
            'title.ar' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10000', // Handle file uploads
        ];
    }
}
