<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProductFeatureRequest extends FormRequest
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
            // 'product_id' => 'required|exists:products,id',
        ];
    }
}
