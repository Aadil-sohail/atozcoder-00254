<aside id="sidebar">
    @php($sidebarCompany = $globalCompany ?? \App\Models\Company::first())
    <div id="sidebar-brand" class="d-flex align-items-center gap-2 px-3 border-bottom border-secondary" style="height:64px;">
        @if ($sidebarCompany?->company_logo)
            <img src="{{ asset($sidebarCompany->company_logo) }}" alt="{{ $sidebarCompany->company_name ?? config('app.name') }}"
                class="bg-white flex-shrink-0" style="width: 200px; height:60px; object-fit:cover;">
        @else
            <i class="fa-solid fa-layer-group text-white fs-5"></i>
        @endif
    </div>

    <nav class="mt-2 px-2 d-flex flex-column gap-1">
        <a href="{{ route('dashboard') }}"
            class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-white-50' }}"
            style="{{ request()->routeIs('dashboard') ? '' : 'transition: background 0.15s;' }}"
            onmouseover="{{ request()->routeIs('dashboard') ? '' : "this.style.background='#1f2937'" }}"
            onmouseout="{{ request()->routeIs('dashboard') ? '' : "this.style.background=''" }}">
            <i class="fa-solid fa-gauge" style="width:18px; text-align:center;"></i>
            {{ __('Dashboard') }}
        </a>

        @can('view categories')
            <a href="{{ route('categories.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('categories.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('categories.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('categories.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-tags" style="width:18px; text-align:center;"></i>
                {{ __('Categories') }}
            </a>
        @endcan

        @can('view subcategories')
            <a href="{{ route('subcategories.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('subcategories.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('subcategories.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('subcategories.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-tag" style="width:18px; text-align:center;"></i>
                {{ __('Sub Categories') }}
            </a>
        @endcan

        @can('view products')
            <a href="{{ route('products.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('products.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('products.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('products.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-box" style="width:18px; text-align:center;"></i>
                {{ __('Products') }}
            </a>
        @endcan

        @can('view inventories')
            <a href="{{ route('inventories.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('inventories.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('inventories.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('inventories.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-warehouse" style="width:18px; text-align:center;"></i>
                {{ __('Inventory') }}
            </a>
        @endcan

        @can('view products')
            <a href="{{ route('out-of-stock.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('out-of-stock.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('out-of-stock.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('out-of-stock.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-box-open" style="width:18px; text-align:center;"></i>
                {{ __('Out of Stock') }}
            </a>
        @endcan

        @can('view customers')
            <a href="{{ route('customers.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('customers.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('customers.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('customers.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-user-tie" style="width:18px; text-align:center;"></i>
                {{ __('Customers') }}
            </a>
        @endcan

        @can('view sales')
            <a href="{{ route('sales.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('sales.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('sales.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('sales.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-receipt" style="width:18px; text-align:center;"></i>
                {{ __('Sales') }}
            </a>
        @endcan

        @can('view sales')
            <a href="{{ route('warranties.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('warranties.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('warranties.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('warranties.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-shield-halved" style="width:18px; text-align:center;"></i>
                {{ __('Warranties') }}
            </a>
        @endcan

        @can('view sales')
            <a href="{{ route('profit-loss.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('profit-loss.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('profit-loss.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('profit-loss.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-chart-line" style="width:18px; text-align:center;"></i>
                {{ __('Profit & Loss') }}
            </a>
        @endcan

        @can('view returns')
            <a href="{{ route('returns.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('returns.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('returns.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('returns.*') ? '' : "this.style.background=''" }}">
                <i class="fa-solid fa-rotate-left" style="width:18px; text-align:center;"></i>
                {{ __('Returns') }}
            </a>
        @endcan

        @can('view ebay stores')
            <a href="{{ route('ebay.index') }}"
                class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ request()->routeIs('ebay.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                onmouseover="{{ request()->routeIs('ebay.*') ? '' : "this.style.background='#1f2937'" }}"
                onmouseout="{{ request()->routeIs('ebay.*') ? '' : "this.style.background=''" }}">
                <i class="fa-brands fa-ebay" style="width:18px; text-align:center;"></i>
                {{ __('eBay Stores') }}
            </a>
        @endcan

        @php($settingsActive = request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('profile.*'))

        <a href="#settings-submenu" data-bs-toggle="collapse" role="button"
            aria-expanded="{{ ($settingsActive ?? false) ? 'true' : 'false' }}" aria-controls="settings-submenu"
            class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none fw-medium small
                {{ ($settingsActive ?? false) ? 'text-white' : 'text-white-50 collapsed' }}"
            onmouseover="this.style.background='#1f2937'" onmouseout="this.style.background=''">
            <i class="fa-solid fa-gear" style="width:18px; text-align:center;"></i>
            {{ __('Settings') }}
            <i class="fa-solid fa-chevron-down ms-auto settings-caret"
                style="font-size:11px; transition: transform 0.2s;"></i>
        </a>

        <div class="collapse {{ ($settingsActive ?? false) ? 'show' : '' }}" id="settings-submenu">
            <div class="d-flex flex-column gap-1">
                @can('view users')
                    <a href="{{ route('users.index') }}"
                        class="d-flex align-items-center gap-3 py-2 rounded text-decoration-none fw-medium small
                        {{ request()->routeIs('users.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                        style="padding-left:44px; padding-right:12px;"
                        onmouseover="{{ request()->routeIs('users.*') ? '' : "this.style.background='#1f2937'" }}"
                        onmouseout="{{ request()->routeIs('users.*') ? '' : "this.style.background=''" }}">
                        <i class="fa-solid fa-users" style="width:18px; text-align:center;"></i>
                        {{ __('Users') }}
                    </a>
                @endcan

                @can('view roles')
                    <a href="{{ route('roles.index') }}"
                        class="d-flex align-items-center gap-3 py-2 rounded text-decoration-none fw-medium small
                        {{ request()->routeIs('roles.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                        style="padding-left:44px; padding-right:12px;"
                        onmouseover="{{ request()->routeIs('roles.*') ? '' : "this.style.background='#1f2937'" }}"
                        onmouseout="{{ request()->routeIs('roles.*') ? '' : "this.style.background=''" }}">
                        <i class="fa-solid fa-user-shield" style="width:18px; text-align:center;"></i>
                        {{ __('Roles & Permissions') }}
                    </a>
                @endcan

                <a href="{{ route('profile.edit') }}"
                    class="d-flex align-items-center gap-3 py-2 rounded text-decoration-none fw-medium small
                        {{ request()->routeIs('profile.*') ? 'bg-primary text-white' : 'text-white-50' }}"
                    style="padding-left:44px; padding-right:12px;"
                    onmouseover="{{ request()->routeIs('profile.*') ? '' : "this.style.background='#1f2937'" }}"
                    onmouseout="{{ request()->routeIs('profile.*') ? '' : "this.style.background=''" }}">
                    <i class="fa-solid fa-id-card" style="width:18px; text-align:center;"></i>
                    {{ __('Profile Setting') }}
                </a>
            </div>
        </div>
    </nav>

    <style>
        #sidebar a[data-bs-toggle="collapse"]:not(.collapsed) .settings-caret {
            transform: rotate(180deg);
        }
    </style>
</aside>
