<?php

namespace App\Http\Requests;

use App\Models\SaleItem;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreSaleReturnRequest extends FormRequest
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
            'sale_id'                => ['required', 'exists:sales,id'],
            'return_date'            => ['required', 'date'],
            'items'                  => ['required', 'array', 'min:1'],
            'items.*.sale_item_id'   => ['required', 'exists:sale_items,id'],
            'items.*.quantity'       => ['required', 'numeric', 'min:0.01'],
            'items.*.condition'      => ['required', 'in:good,bad'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Add at least one item to return.',
            'items.min'      => 'Add at least one item to return.',
        ];
    }

    /**
     * Ensure every returned sale item belongs to the given sale, and that the
     * total quantity requested per sale item (across all rows in this submission
     * plus any previously recorded returns) never exceeds what was originally sold.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $saleId = $this->input('sale_id');
            $items  = $this->input('items', []);

            $requestedBySaleItem = [];
            foreach ($items as $item) {
                $saleItemId = $item['sale_item_id'] ?? null;
                if (! $saleItemId) {
                    continue;
                }
                $requestedBySaleItem[$saleItemId] = ($requestedBySaleItem[$saleItemId] ?? 0) + (float) ($item['quantity'] ?? 0);
            }

            if ($requestedBySaleItem === []) {
                return;
            }

            $saleItems = SaleItem::withSum('returnItems as already_returned', 'quantity')
                ->whereIn('id', array_keys($requestedBySaleItem))
                ->get()
                ->keyBy('id');

            foreach ($requestedBySaleItem as $saleItemId => $requestedQty) {
                $saleItem = $saleItems->get($saleItemId);

                if (! $saleItem || $saleItem->sale_id != $saleId) {
                    $validator->errors()->add('items', 'One of the selected items does not belong to this invoice.');

                    continue;
                }

                $remaining = (float) $saleItem->quantity - (float) ($saleItem->already_returned ?? 0);

                if ($requestedQty > $remaining) {
                    $validator->errors()->add(
                        'items',
                        "Return quantity for \"{$saleItem->product->name}\" exceeds the remaining returnable quantity ({$remaining})."
                    );
                }
            }
        });
    }
}
