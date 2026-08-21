{{-- Every sale line behind one product's row, so the average price and the
     profit above it can be traced back to the invoices that produced them. --}}
@php
    $trim = fn ($n) => rtrim(rtrim(number_format((float) $n, 2), '0'), '.');
    $cost = (float) $product->cost_price;
@endphp

<div class="px-3 pt-3">
    <div class="d-flex flex-wrap gap-4 mb-3">
        <div>
            <div class="text-muted" style="font-size:11px;">{{ __('Cost price / unit') }}</div>
            <div class="fw-semibold">
                {{ $product->cost_price === null ? __('Not set') : number_format($cost, 2) }}
            </div>
        </div>
        <div>
            <div class="text-muted" style="font-size:11px;">{{ __('Units sold (net)') }}</div>
            <div class="fw-semibold">{{ $trim($lines->sum('qty_net')) }}</div>
        </div>
        <div>
            <div class="text-muted" style="font-size:11px;">{{ __('Revenue') }}</div>
            <div class="fw-semibold">{{ number_format((float) $lines->sum('revenue'), 2) }}</div>
        </div>
        <div>
            <div class="text-muted" style="font-size:11px;">{{ __('Profit / Loss') }}</div>
            @php($total = (float) $lines->sum('profit'))
            <div class="fw-semibold" style="color:{{ $total < 0 ? '#d03b3b' : '#006300' }};">
                {{ number_format($total, 2) }}
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-sm align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="ps-3">{{ __('Invoice') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Customer') }}</th>
                <th class="text-end">{{ __('Qty') }}</th>
                <th class="text-end">{{ __('Sold At') }}</th>
                <th class="text-end">{{ __('Revenue') }}</th>
                <th class="text-end">{{ __('Cost') }}</th>
                <th class="text-end pe-3">{{ __('Profit / Loss') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lines as $line)
                <tr>
                    <td class="ps-3">
                        <a href="{{ route('sales.show', $line->sale_id) }}" class="text-decoration-none fw-medium">
                            {{ $line->invoice_no }}
                        </a>
                    </td>
                    <td class="text-secondary">{{ \Carbon\Carbon::parse($line->sale_date)->format('M d, Y') }}</td>
                    <td class="text-secondary">{{ $line->customer_name ?? '—' }}</td>
                    <td class="text-end">{{ $trim($line->qty_net) }}</td>
                    {{-- The price on the line, before the invoice discount is
                         shared out — which is why revenue can be lower. --}}
                    <td class="text-end">{{ number_format((float) $line->selling_price, 2) }}</td>
                    <td class="text-end">{{ number_format((float) $line->revenue, 2) }}</td>
                    <td class="text-end text-secondary">{{ number_format((float) $line->cogs, 2) }}</td>
                    <td class="text-end pe-3 fw-semibold"
                        style="color:{{ (float) $line->profit < 0 ? '#d03b3b' : '#006300' }};">
                        {{ number_format((float) $line->profit, 2) }}
                    </td>
                </tr>
            {{-- Also what a product shows once every sale of it was returned. --}}
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        {{ __('This product has no sales in the selected period.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
