<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeveralBrandsRequest  extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brands' => 'required|array',
            'brands.*.id' => 'nullable|integer|exists:brands,id',  // Add the ID validation
            'brands.*.name.en' => 'required|string|max:255',
            'brands.*.name.ar' => 'required|string|max:255',
            'brands.*.description.en' => 'nullable|string',
            'brands.*.description.ar' => 'nullable|string',
            'brands.*.logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10000',
            'remove_brands' => 'nullable|array',
            'remove_brands.*' => 'integer|exists:brands,id'

        ];
    }
}
