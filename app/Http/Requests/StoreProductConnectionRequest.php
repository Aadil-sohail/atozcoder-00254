<?php

namespace App\Http\Requests;

use App\Models\ProductConnectionItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Rules for making or changing a connection. Used by both, so the connection
 * being edited excuses itself from the "already connected" check.
 */
class StoreProductConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:150'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.ebay_account_ids' => ['nullable', 'array'],
            'items.*.ebay_account_ids.*' => ['integer', 'exists:ebay_accounts,id'],
            'master_product_id' => ['required', 'integer', 'exists:products,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Pick the products this connection joins together.',
            'items.min' => 'Pick the product this connection is for.',
            'master_product_id.required' => 'Choose which product the prices and stock come from.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $productIds = collect($this->input('items', []))->pluck('product_id')->filter();

            $storeIds = collect($this->input('items', []))
                ->pluck('ebay_account_ids')
                ->flatten()
                ->filter()
                ->unique();

            // Two shapes are worth connecting: several product rows that are
            // secretly the same part, or one product row that sells on more
            // than one store. One product on one store is just a product.
            if ($productIds->count() < 2 && $storeIds->count() < 2) {
                $validator->errors()->add('items', 'A connection needs either two products to join together, or one product listed on two stores.');
            }

            if ($productIds->count() !== $productIds->unique()->count()) {
                $validator->errors()->add('items', 'The same product is picked more than once.');
            }

            if (! $productIds->contains($this->input('master_product_id'))) {
                $validator->errors()->add('master_product_id', 'The product prices come from must be one of the products in the connection.');
            }

            // A product in two connections would leave its stock with two
            // owners, so the ones already spoken for are named outright.
            $taken = ProductConnectionItem::with('product')
                ->whereIn('product_id', $productIds)
                ->when($this->route('connection'), fn ($query, $connection) => $query
                    ->where('product_connection_id', '!=', $connection->id))
                ->get();

            foreach ($taken as $item) {
                $validator->errors()->add('items', sprintf(
                    '"%s" is already part of another connection.',
                    $item->product?->name ?? "Product #{$item->product_id}",
                ));
            }
        });
    }
}
