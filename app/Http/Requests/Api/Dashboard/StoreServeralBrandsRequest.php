<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreServeralBrandsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brands' => 'required|array',
            'brands.*.name.en' => 'required|string|max:255',
            'brands.*.name.ar' => 'required|string|max:255',
            'brands.*.description.en' => 'nullable|string',
            'brands.*.description.ar' => 'nullable|string',
            'brands.*.logo' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:10000',
        ];
    }
}
