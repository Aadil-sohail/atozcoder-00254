<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255'],
            'company_phone' => ['required', 'string', 'max:255'],
            'company_mobile' => ['required', 'string', 'max:255'],
            'company_address' => ['required', 'string'],
            'company_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'],
            'fav_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,ico', 'max:8192'],
        ];
    }
}
