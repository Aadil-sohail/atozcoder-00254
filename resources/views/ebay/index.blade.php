@extends('layouts.header')

@section('title', 'eBay Stores')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('eBay Stores') }}
    </h2>
@endsection

@section('content')

    @if ($credentialsMissing)
        <div class="alert alert-warning" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ __('eBay API credentials are not configured yet. Add EBAY_CLIENT_ID, EBAY_CLIENT_SECRET and EBAY_RU_NAME to your .env file (from') }}
            <a href="https://developer.ebay.com" target="_blank" rel="noopener">developer.ebay.com</a>{{ __(') before connecting a store.') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 p-2 mb-4">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Connect one or more eBay seller accounts, then sync products to them from the Products page.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $accounts->count() }} {{ Str::plural('store', $accounts->count()) }} connected</span>
            </div>
        </div>

        @can('create ebay stores')
        <div class="card-body border-bottom">
            <form method="POST" action="{{ route('ebay.connect') }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">{{ __('Store name (label)') }}</label>
                    <input type="text" name="store_name" value="{{ old('store_name') }}" class="form-control form-control-sm @error('store_name') is-invalid @enderror"
                        placeholder="{{ __('e.g. Main US Store') }}" required>
                    @error('store_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('Marketplace') }}</label>
                    <select name="marketplace_id" class="form-select form-select-sm @error('marketplace_id') is-invalid @enderror" required>
                        @foreach (config('ebay.marketplaces') as $id => $marketplace)
                            <option value="{{ $id }}" @selected(old('marketplace_id', config('ebay.default_marketplace')) === $id)>
                                {{ $marketplace['label'] }} ({{ $marketplace['currency'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('marketplace_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark btn-sm d-flex align-items-center gap-2" {{ $credentialsMissing ? 'disabled' : '' }}>
                        <i class="fa-brands fa-ebay"></i>
                        {{ __('Connect eBay Store') }}
                    </button>
                </div>
            </form>
            <p class="text-muted mt-2 mb-0" style="font-size:12px;">
                {{ __('You will be redirected to eBay to log in with the store\'s seller account and grant access.') }}
            </p>
        </div>
        @endcan

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Store') }}</th>
                        <th>{{ __('eBay User') }}</th>
                        <th>{{ __('Marketplace') }}</th>
                        <th>{{ __('Products Linked') }}</th>
                        <th>{{ __('Setup') }}</th>
                        <th>{{ __('Authorization') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr>
                            <td class="fw-medium">{{ $account->store_name }}</td>
                            <td class="text-secondary">{{ $account->ebay_username ?? '—' }}</td>
                            <td class="text-secondary">
                                {{ config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id) }}
                            </td>
                            <td class="text-secondary">{{ $account->listings_count }}</td>
                            <td>
                                @if ($account->isFullyConfigured())
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('Ready') }}</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">{{ __('Setup needed') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($account->needsReconnect())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ __('Expired — reconnect') }}</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        {{ __('Valid') }}
                                        @if ($account->refresh_token_expires_at)
                                            {{ __('until') }} {{ $account->refresh_token_expires_at->format('M Y') }}
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @can('edit ebay stores')
                                    <a href="{{ route('ebay.setup', $account) }}" class="btn btn-sm btn-outline-primary" title="{{ __('Setup policies & location') }}">
                                        <i class="fa-solid fa-sliders"></i>
                                    </a>
                                    @endcan
                                    @can('delete ebay stores')
                                    <form method="POST" action="{{ route('ebay.destroy', $account) }}" class="d-inline"
                                        onsubmit="return confirmDelete(event, '{{ __('Disconnect this eBay store? Synced listings will stay live on eBay but will no longer be linked here.') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Disconnect') }}">
                                            <i class="fa-solid fa-link-slash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-brands fa-ebay fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">{{ __('No eBay stores connected yet. Use the form above to connect your first store.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
