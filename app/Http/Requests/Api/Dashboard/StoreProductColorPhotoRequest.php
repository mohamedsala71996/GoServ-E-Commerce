<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductColorPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'product_id' => [
            //     'required',
            //     'exists:products,id',
            //     // Add additional rules if needed
            // ],
            // 'color_id' => [
            //     'required',
            //     'exists:colors,id',
            //     // Custom validation rule to ensure uniqueness
            //     Rule::unique('product_color_photos')
            //         ->where(function ($query) {
            //             return $query->where('product_id', $this->input('product_id'))
            //                          ->where('color_id', $this->input('color_id'));
            //         })
            //         ->ignore($this->route('id')), // Adjust if you're updating existing records
            // ],
            // 'product_color_id' => 'required|exists:product_colors,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:0',
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:10000', // Validate each photo
        ];
    }
}
