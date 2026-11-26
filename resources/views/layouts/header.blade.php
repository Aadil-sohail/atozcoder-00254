<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        @php($globalCompany = \App\Models\Company::first())
        @if ($globalCompany?->fav_icon)
            <link rel="icon" href="{{ asset($globalCompany->fav_icon) }}">
        @endif

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Dropify -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/css/dropify.min.css">

        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-3.0.0/datatables.min.css">

        <!-- Select2 (searchable dropdowns), themed to match Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

        <!-- Loading state for server-side grids, paired with public/js/server-table.js -->
        <link rel="stylesheet" href="{{ asset('css/server-table.css') }}?v={{ filemtime(public_path('css/server-table.css')) }}">

        @stack('styles')
        <style>
            body { background-color: #f3f4f6; }
            #sidebar {
                width: 256px;
                background-color: #111827;
                flex-shrink: 0;
                transition: transform 0.2s ease-in-out;
                display: flex;
                flex-direction: column;
            }
            @media (min-width: 992px) {
                /* Pin the sidebar to the viewport so the nav stays reachable
                   however far the page content scrolls. */
                #sidebar {
                    position: sticky;
                    top: 0;
                    align-self: flex-start;
                    height: 100vh;
                }
            }
            /* The brand stays put; only the nav scrolls, and only when it has to. */
            #sidebar-brand { flex-shrink: 0; }
            #sidebar nav {
                flex: 1;
                min-height: 0;                  /* lets the nav shrink so overflow can kick in */
                overflow-y: auto;
                overscroll-behavior: contain;   /* don't chain the scroll to the page */
                padding-bottom: 12px;
                scrollbar-width: thin;
                scrollbar-color: #374151 transparent;
            }
            #sidebar nav::-webkit-scrollbar { width: 6px; }
            #sidebar nav::-webkit-scrollbar-track { background: transparent; }
            #sidebar nav::-webkit-scrollbar-thumb {
                background: #374151;
                border-radius: 3px;
            }
            #sidebar nav:hover::-webkit-scrollbar-thumb { background: #4b5563; }
            #sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1039;
            }
            @media (max-width: 991.98px) {
                #sidebar {
                    position: fixed;
                    top: 0; left: 0; bottom: 0;
                    z-index: 1040;
                    transform: translateX(-100%);
                }
                #sidebar.open {
                    transform: translateX(0);
                }
                #sidebar-overlay.open {
                    display: block;
                }
            }
            #topbar { height: 64px; }
            /* ── Header product search ─────────────────────────── */
            .header-search { position: relative; max-width: 460px; }
            .header-search .form-control { padding-left: 32px; border-radius: 8px; background: #f9fafb; }
            .header-search .form-control:focus { background: #fff; }

            /* Phones: the search gets a full-width row under the title and user menu. */
            @media (max-width: 767.98px) {
                #topbar {
                    height: auto; min-height: 64px;
                    flex-wrap: wrap; row-gap: 8px;
                padding-top: 10px; padding-bottom: 10px;
                }
                .header-search { order: 3; flex: 0 0 100%; max-width: none; margin: 0 !important; }
            }
            .header-search-icon {
                position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
                color: #9ca3af; font-size: 13px; pointer-events: none;
            }
            .header-search-results {
                display: none; position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 1050;
                background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0,0,0,.08); max-height: 420px; overflow-y: auto;
            }
            .header-search-results.open { display: block; }
            .header-search-results a.hs-item {
                display: flex; align-items: center; gap: 10px; padding: 8px 12px;
                color: #111827; text-decoration: none; border-bottom: 1px solid #f3f4f6;
            }
            .header-search-results a.hs-item:hover,
            .header-search-results a.hs-item.active { background: #f3f4f6; }
            .header-search-results .hs-thumb {
                width: 36px; height: 36px; flex-shrink: 0; border-radius: 6px; object-fit: cover;
                background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;
            }
            .header-search-results .hs-name { font-size: 14px; font-weight: 500; }
            .header-search-results .hs-meta { font-size: 12px; color: #6b7280; }
            .header-search-results .hs-stock { margin-left: auto; font-size: 12px; white-space: nowrap; }
            .header-search-results .hs-badge {
                display: inline-block; font-size: 10.5px; font-weight: 600; line-height: 1;
                padding: 3px 6px; border-radius: 4px; vertical-align: middle;
                background: #e5e7eb; color: #374151;
            }
            .header-search-results .hs-badge-preferred { background: #dbeafe; color: #1e40af; }
            .header-search-results .hs-via { font-size: 12px; color: #92400e; }
            .header-search-results .hs-note { padding: 10px 12px; font-size: 13px; color: #6b7280; }
            .header-search-results a.hs-all { display: block; padding: 9px 12px; font-size: 13px; text-align: center; text-decoration: none; }
            .header-search-results a.hs-all.active { background: #f3f4f6; }

            .user-dropdown { position: relative; }
            .user-dropdown-menu {
                display: none;
                position: absolute;
                right: 0;
                top: calc(100% + 8px);
                min-width: 180px;
                background: #fff;
                border-radius: 6px;
                box-shadow: 0 4px 16px rgba(0,0,0,0.12);
                z-index: 1050;
            }
            .user-dropdown-menu.open { display: block; }
            .user-dropdown-menu a,
            .user-dropdown-menu button {
                display: block;
                width: 100%;
                padding: 8px 16px;
                font-size: 14px;
                color: #374151;
                text-decoration: none;
                background: none;
                border: none;
                text-align: left;
                cursor: pointer;
            }
            .user-dropdown-menu a:hover,
            .user-dropdown-menu button:hover { background-color: #f3f4f6; }

            /* ── DataTables column alignment ──────────────────────────────
               DataTables 3 auto-detects numeric/date columns, right-aligns
               them and flips the sort arrow to the opposite edge. In a wide
               table that strands each heading and its arrow at opposite
               sides and makes the alignment look arbitrary. Keep every
               column left-aligned with its arrow beside the title.
               Selectors mirror DataTables' own so they win on source order
               rather than !important. */
            table.dataTable th.dt-type-numeric,
            table.dataTable th.dt-type-date,
            table.dataTable td.dt-type-numeric,
            table.dataTable td.dt-type-date { text-align: left; }

            table.dataTable th.dt-type-numeric div.dt-column-header,
            table.dataTable th.dt-type-date div.dt-column-header,
            table.dataTable td.dt-type-numeric div.dt-column-header,
            table.dataTable td.dt-type-date div.dt-column-header { flex-direction: row; }

            table.dataTable thead > tr > th div.dt-column-header,
            table.dataTable thead > tr > td div.dt-column-header { justify-content: flex-start; }

            table.dataTable thead > tr > th div.dt-column-header .dt-column-title,
            table.dataTable thead > tr > td div.dt-column-header .dt-column-title { flex-grow: 0; }
        </style>
    </head>
    <body>
        <div class="d-flex">
            @include('layouts.sidebar')

            <div class="flex-grow-1 d-flex flex-column" style="min-width:0;">
                <!-- Topbar -->
                <header id="topbar" class="d-flex align-items-center justify-content-between border-bottom bg-white px-4">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-sm btn-light d-lg-none" onclick="toggleSidebar()">
                            <i class="fa-solid fa-bars"></i>
                        </button>

                        @hasSection('header')
                            @yield('header')
                        @endif
                    </div>

                    {{-- Enter opens the Products list, so the search also needs view products. --}}
                    @if (auth()->user()->can('use global search') && auth()->user()->can('view products'))
                        <!-- Product search: suggestions as you type, Enter for the full list -->
                        <form id="header-search" class="header-search mx-3 flex-grow-1" action="{{ route('products.index') }}" method="GET" role="search" autocomplete="off">
                            <i class="fa-solid fa-magnifying-glass header-search-icon"></i>
                            <input type="search" name="search" id="header-search-input" class="form-control form-control-sm"
                                placeholder="{{ __('Search products by name, SKU, listing ID or shelf…') }}"
                                value="{{ request()->routeIs('products.index') ? request('search') : '' }}"
                                aria-label="{{ __('Search products') }}" aria-autocomplete="list" aria-controls="header-search-results">
                            <div id="header-search-results" class="header-search-results" role="listbox"></div>
                        </form>
                    @endif

                    <!-- User Dropdown -->
                    <div class="user-dropdown">
                        <button class="btn btn-light d-flex align-items-center gap-2" onclick="toggleUserDropdown(event)">
                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-semibold"
                                style="width:32px; height:32px; font-size:13px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-sm-inline small fw-medium">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i>
                        </button>

                        <div class="user-dropdown-menu" id="user-dropdown-menu">
                            <a href="{{ route('profile.edit') }}">
                                <i class="fa-solid fa-user me-2 text-secondary"></i>{{ __('Profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fa-solid fa-right-from-bracket me-2 text-secondary"></i>{{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-4">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Sidebar overlay for mobile -->
        <div id="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('open');
                document.getElementById('sidebar-overlay').classList.toggle('open');
            }

            function toggleUserDropdown(e) {
                e.stopPropagation();
                document.getElementById('user-dropdown-menu').classList.toggle('open');
            }

            document.addEventListener('click', function () {
                document.getElementById('user-dropdown-menu').classList.remove('open');
            });
        </script>

        @can('view products')
            <script>
                /**
                 * Header product search. Suggestions come from products.quick-search
                 * after a short pause in typing; ↑/↓ move through them, Enter opens the
                 * highlighted one, or the full Products list filtered by the term.
                 */
                (function () {
                    const form = document.getElementById('header-search');
                    const input = document.getElementById('header-search-input');
                    const box = document.getElementById('header-search-results');
                    if (!form) return;   // search hidden: no permission
                    const url = @json(route('products.quick-search'));

                    let timer = null;
                    let request = 0;   // only the newest answer is drawn
                    let active = -1;

                    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
                    const items = () => Array.from(box.querySelectorAll('a.hs-item, a.hs-all'));

                    function close() {
                        box.classList.remove('open');
                        active = -1;
                    }

                    function highlight(index) {
                        const list = items();
                        if (!list.length) return;
                        active = (index + list.length) % list.length;
                        list.forEach((el, i) => el.classList.toggle('active', i === active));
                        list[active].scrollIntoView({ block: 'nearest' });
                    }

                    function render(data, term) {
                        active = -1;

                        if (!data.results.length) {
                            box.innerHTML = '<div class="hs-note">No products match “' + esc(term) + '”.</div>';
                        } else {
                            box.innerHTML = data.results.map((p) =>
                                '<a class="hs-item" role="option" href="' + esc(p.url) + '">' +
                                    (p.image
                                        ? '<img class="hs-thumb" src="' + esc(p.image) + '" alt="">'
                                        : '<span class="hs-thumb"><i class="fa-solid fa-image"></i></span>') +
                                    '<span class="d-flex flex-column" style="min-width:0;">' +
                                        '<span class="hs-name text-truncate">' + esc(p.name) +
                                            (p.connected
                                                ? ' <span class="hs-badge" title="' + esc('Connected product, listed on: ' + p.stores.join(', ')) + '"><i class="fa-solid fa-link"></i> Connected</span>' +
                                                  ' <span class="hs-badge hs-badge-preferred" title="Its price and stock are used for every store in the connection">Preferred</span>'
                                                : '') +
                                        '</span>' +
                                        '<span class="hs-meta text-truncate">' +
                                            (p.sku ? 'SKU ' + esc(p.sku) : '') +
                                            (p.sku && p.shelf ? ' · ' : '') +
                                            (p.shelf ? '<i class="fa-solid fa-layer-group me-1"></i>' + esc(p.shelf) : '') +
                                        '</span>' +
                                        // Why this row came up when a connected product matched.
                                        p.via.map((v) =>
                                            '<span class="hs-via text-truncate"><i class="fa-solid fa-link me-1"></i>Matched <strong>' + esc(v.name) + '</strong>' +
                                            (v.store ? ' (' + esc(v.store) + ')' : (v.sku ? ' (SKU ' + esc(v.sku) + ')' : '')) +
                                            ', connected to this product</span>'
                                        ).join('') +
                                    '</span>' +
                                    '<span class="hs-stock ' + (Number(p.stock) > 0 ? 'text-success' : 'text-danger') + '">' + esc(p.stock) + ' in stock</span>' +
                                '</a>'
                            ).join('') +
                            '<a class="hs-all" href="' + esc(data.all_url) + '">' +
                                (data.more ? 'See all results for “' + esc(term) + '”' : 'Open in the Products list') +
                                ' <i class="fa-solid fa-arrow-right ms-1"></i></a>';
                        }

                        box.classList.add('open');
                    }

                    input.addEventListener('input', function () {
                        const term = input.value.trim();
                        clearTimeout(timer);

                        if (term.length < 2) {
                            close();
                            return;
                        }

                        timer = setTimeout(function () {
                            const mine = ++request;

                            fetch(url + '?q=' + encodeURIComponent(term), { headers: { 'Accept': 'application/json' } })
                                .then((r) => r.ok ? r.json() : Promise.reject(r))
                                .then((data) => { if (mine === request) render(data, term); })
                                .catch(() => { if (mine === request) close(); });
                        }, 250);
                    });

                    input.addEventListener('keydown', function (e) {
                        if (!box.classList.contains('open')) return;

                        if (e.key === 'ArrowDown') { e.preventDefault(); highlight(active + 1); }
                        else if (e.key === 'ArrowUp') { e.preventDefault(); highlight(active - 1); }
                        else if (e.key === 'Escape') { close(); }
                        else if (e.key === 'Enter' && active >= 0) {
                            e.preventDefault();
                            window.location = items()[active].href;
                        }
                        // Enter with nothing highlighted submits the form: the full list.
                    });

                    input.addEventListener('focus', function () {
                        if (box.innerHTML && input.value.trim().length >= 2) box.classList.add('open');
                    });

                    // An empty search is not worth a page load.
                    form.addEventListener('submit', function (e) {
                        if (!input.value.trim()) e.preventDefault();
                    });

                    document.addEventListener('click', function (e) {
                        if (!form.contains(e.target)) close();
                    });

                    // Ctrl+K / ⌘K jumps to the search box from anywhere.
                    document.addEventListener('keydown', function (e) {
                        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                            e.preventDefault();
                            input.focus();
                            input.select();
                        }
                    });
                })();
            </script>
        @endcan

        @include('layouts.footer')

        @stack('scripts')
    </body>
</html>
