<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubcategoryRequest extends FormRequest
{
    /**
     * The error bag used to keep validation errors scoped to the edit modal.
     */
    protected $errorBag = 'updateSubcategory';

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('subcategories', 'name')
                    ->where('category_id', $this->input('category_id'))
                    ->where('status', '1')
                    ->where('close', '1')
                    ->ignore($this->route('subcategory')),
            ],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('status', '1')->where('close', '1')],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => __('This sub category already exists under the selected category.'),
        ];
    }
}
