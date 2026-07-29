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

        @include('layouts.footer')

        @stack('scripts')
    </body>
</html>
