<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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

            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'required|string|max:1000',
            'description.ar' => 'required|string|max:1000',
            'details.en' => 'nullable|string',
            'details.ar' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'weight' => 'required|numeric|min:0',
            'colors' => 'nullable|array',
            'colors.*.id' => 'nullable|exists:product_colors,id', // Validate existing color id if provided
            'colors.*.color_id' => 'nullable|exists:colors,id', // Validate each color
            'colors.*.photos' => 'nullable|array',
            'colors.*.photos.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:10000', // Color-specific photos
            'colors.*.sizes' => 'nullable|array', // Sizes within each color
            'colors.*.sizes.*.id' => 'nullable|exists:product_color_sizes,id', // Validate existing size id if provided
            'colors.*.sizes.*.size_id' => 'nullable|exists:sizes,id', // Validate each size
            'colors.*.sizes.*.quantity' => 'nullable|integer|min:0', // Size-specific quantity
            'colors.*.sizes.*.price' => 'nullable|numeric|min:0', // Size-specific price
            'colors.*.sizes.*.cost' => 'nullable|numeric|min:0', // Size-specific cost
            'remove_colors' => 'nullable|array', // Items to remove
            'remove_colors.*' => 'integer|exists:product_colors,id', // Validating removed items


        ];

    }
}
            // 'product_id' => 'required|exists:products,id',
            // 'name.en' => 'required|string|max:255',
            // 'name.ar' => 'required|string|max:255',
            // 'description.en' => 'required|string|max:1000',
            // 'description.ar' => 'required|string|max:1000',
            // 'details.en' => 'required|string',
            // 'details.ar' => 'required|string',
            // 'price' => 'required|numeric|min:0',
            // 'category_id' => 'required|exists:categories,id',
            // 'main_photos' => 'nullable|array',
            // 'main_photos.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:10000', // Validate each photo upload
            // 'is_sold' => 'boolean',
            // 'weight' => 'nullable|numeric|min:0',
            // 'brand_id' => 'nullable|exists:brands,id',
            // 'main_color_quantity' => 'required|integer|min:0', // Validate quantity as a non-negative integer
            // 'color_id' => 'nullable|exists:colors,id', // Validate color_id

            // 'colors' => 'required|array',
            // 'colors.*.color_id' => 'required|exists:colors,id',
            // 'colors.*.photos' => 'required|array',
            // 'colors.*.photos.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:10000',
            // 'colors.*.quantity' => 'required|integer|min:1',


            // 'name.en' => 'required|string|max:255',
            // 'name.ar' => 'required|string|max:255',
            // 'description.en' => 'required|string|max:1000',
            // 'description.ar' => 'required|string|max:1000',
            // 'details.en' => 'required|string',
            // 'details.ar' => 'required|string',
            // 'category_id' => 'required|exists:categories,id',
            // 'brand_id' => 'nullable|exists:brands,id',
            // 'weight' => 'required|numeric',
            // 'price' => 'required|numeric',
            // 'size_id' => 'nullable|exists:sizes,id',
