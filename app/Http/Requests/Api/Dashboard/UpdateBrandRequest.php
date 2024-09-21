<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => 'required|exists:brands,id', // Ensure the brand exists
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10000',
        ];
    }
}
