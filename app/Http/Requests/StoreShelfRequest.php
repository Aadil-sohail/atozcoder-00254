<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShelfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * A shelf name only has to be unique inside its own rack: every rack can
     * have a "Shelf 1".
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('shelves', 'name')
                    ->where('rack_id', $this->input('rack_id'))
                    ->where('status', '1')
                    ->where('close', '1'),
            ],
            'rack_id' => ['required', 'integer', Rule::exists('racks', 'id')->where('status', '1')->where('close', '1')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => __('This shelf already exists in the selected rack.'),
        ];
    }
}
