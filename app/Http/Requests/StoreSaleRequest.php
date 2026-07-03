<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
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
            'customer_id'            => ['required', 'exists:customers,id'],
            'invoice_no'             => ['required', 'string', 'max:50', 'unique:sales,invoice_no'],
            'sale_date'              => ['required', 'date'],
            'discount'               => ['nullable', 'numeric', 'min:0'],
            'items'                  => ['required', 'array', 'min:1'],
            'items.*.product_id'     => ['required', 'exists:products,id'],
            'items.*.quantity'       => ['required', 'numeric', 'min:0.01'],
            'items.*.selling_price'  => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Add at least one product to the sale.',
            'items.min'      => 'Add at least one product to the sale.',
        ];
    }
}
