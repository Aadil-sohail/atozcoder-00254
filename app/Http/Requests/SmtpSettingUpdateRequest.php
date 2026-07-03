<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmtpSettingUpdateRequest extends FormRequest
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
            'mailer' => ['required', 'string', 'max:255'],
            'host' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'port' => ['required', 'string', 'max:255'],
            'encryption' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'from_address' => ['required', 'string', 'email', 'max:255'],
        ];
    }
}
