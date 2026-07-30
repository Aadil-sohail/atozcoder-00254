<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'variant' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'size' => ['nullable', 'string', 'max:50'],
            'warranty_months' => ['nullable', 'integer', 'in:1,2,3,4,5,6,12'],
            'category_id' => ['nullable', 'exists:categories,id'],
            // Must belong to the chosen category, so a hand-crafted POST cannot
            // pair a subcategory with an unrelated category.
            'subcategory_id' => [
                'nullable',
                Rule::exists('subcategories', 'id')
                    ->where('category_id', $this->input('category_id')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'subcategory_id.exists' => __('The selected subcategory does not belong to the selected category.'),
        ];
    }
}
