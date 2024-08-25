<?php

namespace App\Http\Requests\Attendances;

use App\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class AttendanceUpdateRequest extends FormRequest
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
            'clock_in' => ['required','string', 'date_format:Y-m-d\TH:i'],
            'clock_out' => ['required','string', 'date_format:Y-m-d\TH:i', 'after:clock_in'],
            'is_late' => ['required', 'boolean'],
            'status' => ['required', 'integer', new Enum(AttendanceStatus::class)],
            'overtime' => ['required', 'string', 'date_format:H:i:s'],
            'note' => ['nullable', 'string'],
        ];
    }
}
