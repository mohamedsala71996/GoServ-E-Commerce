<?php

namespace App\Http\Requests\Api\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->route('admin');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $adminId,
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
