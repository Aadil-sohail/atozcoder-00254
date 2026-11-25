<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRackRequest extends FormRequest
{
    /**
     * The error bag used to keep validation errors scoped to the edit modal.
     */
    protected $errorBag = 'updateRack';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('racks', 'name')
                    ->where('status', '1')
                    ->where('close', '1')
                    ->ignore($this->route('rack')),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => __('A rack with this name already exists.'),
        ];
    }
}
