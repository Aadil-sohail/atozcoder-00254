{{-- One price for the connection, taken from the master member. --}}
<div class="small">{{ __('Sell') }}: <strong>{{ number_format((float) $connection->master_selling_price, 2) }}</strong></div>
<div class="text-muted" style="font-size:11px;">{{ __('Cost') }}: {{ number_format((float) $connection->master_cost_price, 2) }}</div>
