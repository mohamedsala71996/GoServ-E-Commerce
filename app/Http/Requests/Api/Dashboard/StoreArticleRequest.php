<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'title.en' => 'required|string|max:255',
            'title.ar' => 'required|string|max:255',
            'content.en' => 'required|string',
            'content.ar' => 'required|string',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:10000', // Handle file uploads

        ];
    }
}
