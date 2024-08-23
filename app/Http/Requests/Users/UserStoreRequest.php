<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Details
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',

            // Profile
            'department_id' => 'required|integer|exists:departments,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'joined_at' => 'required|date:Y-m-d',
            'gender' => 'nullable|string|max:255',

            // Bank
            'bank_id' => 'nullable|integer|exists:banks,id',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',

            // Roles
            'roles' => 'required|array',
        ];
    }
}
