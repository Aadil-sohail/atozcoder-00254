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
            // One row per store: the store, and the product that store
            // sells the part under.
            'items' => ['required', 'array', 'min:2'],
            'items.*.ebay_account_id' => ['required', 'integer', 'exists:ebay_accounts,id'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            // The row that was marked, named by its store: the product cannot
            // stand for it when the same product serves two stores.
            'master_ebay_account_id' => ['required', 'integer', 'exists:ebay_accounts,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Pick the stores this part sells on and the product on each.',
            'items.min' => 'A connection joins at least two stores — add the second one.',
            'items.*.ebay_account_id.required' => 'Choose the store for every row.',
            'items.*.product_id.required' => 'Choose the product for every store.',
            'master_ebay_account_id.required' => 'Mark the store whose price and stock to use.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $rows = collect($this->input('items', []));
            $productIds = $rows->pluck('product_id')->filter()->unique();
            $storeIds = $rows->pluck('ebay_account_id')->filter();

            // One row per store. The same product across two stores is the
            // whole point of a connection; the same store twice is just the
            // same listing entered again.
            if ($storeIds->count() !== $storeIds->unique()->count()) {
                $validator->errors()->add('items', 'Each store can only be picked once in a connection.');
            }

            if (! $storeIds->contains($this->input('master_ebay_account_id'))) {
                $validator->errors()->add('master_ebay_account_id', 'The store the price comes from must be one of the stores in the connection.');
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
