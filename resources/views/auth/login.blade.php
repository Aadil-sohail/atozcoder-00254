@php
    $company = \App\Models\Company::first();
    $companyName = $company?->company_name ?: config('app.name', 'Laravel');
    $supportEmail = $company?->company_email;
    $supportPhone = $company?->company_phone ?: $company?->company_mobile;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Sign In') }} &middot; {{ $companyName }}</title>

    @if ($company?->fav_icon)
        <link rel="icon" href="{{ asset($company->fav_icon) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e5e7eb;
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--ink);
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
        }

        .auth-wrapper {
            min-height: 100vh;
        }

        /* ------------------------------------------------ brand panel */
        .auth-brand {
            position: relative;
            overflow: hidden;
            color: #fff;
            background: linear-gradient(155deg, #0b1220 0%, #111c33 45%, #14306b 100%);
        }

        .auth-brand-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .05) 1px, transparent 1px);
            background-size: 56px 56px;
            -webkit-mask-image: radial-gradient(ellipse at 30% 20%, #000 0%, transparent 75%);
            mask-image: radial-gradient(ellipse at 30% 20%, #000 0%, transparent 75%);
            pointer-events: none;
        }

        .auth-brand-glow {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-brand-glow.one {
            width: 520px;
            height: 520px;
            top: -200px;
            right: -180px;
            background: radial-gradient(circle, rgba(37, 99, 235, .38) 0%, rgba(37, 99, 235, 0) 70%);
        }

        .auth-brand-glow.two {
            width: 420px;
            height: 420px;
            bottom: -180px;
            left: -140px;
            background: radial-gradient(circle, rgba(56, 189, 248, .18) 0%, rgba(56, 189, 248, 0) 70%);
        }

        .auth-brand-inner {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        .logo-plate {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 12px;
            padding: 10px 16px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .28);
        }

        .logo-plate img {
            max-height: 100px;
            max-width: 190px;
            object-fit: contain;
            display: block;
        }

        .logo-fallback {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--brand) 0%, #38bdf8 100%);
            box-shadow: 0 10px 24px rgba(37, 99, 235, .35);
            font-size: 20px;
            color: #fff;
        }

        .brand-eyebrow {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #7dd3fc;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -.02em;
        }

        .brand-lead {
            color: #cbd5e1;
            font-size: 1rem;
            line-height: 1.65;
            max-width: 27rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .feature-icon {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #93c5fd;
            font-size: 14px;
        }

        .feature-title {
            font-size: .9375rem;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .feature-text {
            font-size: .8125rem;
            line-height: 1.55;
            color: #94a3b8;
        }

        .brand-footer {
            border-top: 1px solid rgba(255, 255, 255, .09);
            padding-top: 18px;
            font-size: .8125rem;
            color: #64748b;
        }

        .brand-footer a {
            color: #94a3b8;
            text-decoration: none;
        }

        .brand-footer a:hover {
            color: #cbd5e1;
        }

        /* ------------------------------------------------ form panel */
        .auth-panel {
            background: #f8fafc;
        }

        .panel-meta {
            font-size: .8125rem;
            color: var(--muted);
        }

        .panel-meta a {
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
        }

        .panel-meta a:hover {
            text-decoration: underline;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 12px 40px -14px rgba(15, 23, 42, .14), 0 2px 6px rgba(15, 23, 42, .04);
        }

        .card-eyebrow {
            font-size: .6875rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--brand);
            margin-bottom: .5rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -.02em;
            margin-bottom: .25rem;
        }

        .card-subtitle {
            font-size: .9375rem;
            color: var(--muted);
            margin-bottom: 1.75rem;
        }

        .mobile-brand img {
            max-height: 44px;
            max-width: 200px;
            object-fit: contain;
        }

        /* fields */
        .form-label {
            font-size: .8125rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: .4rem;
        }

        .field {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
            transition: color .15s ease;
        }

        .field .form-control {
            height: 48px;
            padding: .5rem .875rem .5rem 2.75rem;
            font-size: .9375rem;
            color: var(--ink);
            background-color: #fff;
            border: 1px solid #d8dee7;
            border-radius: 10px;
            box-shadow: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .field .form-control::placeholder {
            color: #a8b2c1;
        }

        .field .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .field:focus-within .field-icon {
            color: var(--brand);
        }

        .field.has-password .form-control {
            padding-right: 2.9rem;
        }

        .field .form-control.is-invalid {
            border-color: #e11d48;
            background-image: none;
        }

        .field .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(225, 29, 72, .12);
        }

        .btn-eye {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #94a3b8;
            font-size: 14px;
        }

        .btn-eye:hover {
            background: #f1f5f9;
            color: var(--brand);
        }

        .form-check-input {
            width: 1.05em;
            height: 1.05em;
            margin-top: .16em;
            border-color: #cbd5e1;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--brand);
            border-color: var(--brand);
        }

        .form-check-input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }

        .form-check-label {
            font-size: .875rem;
            color: #475569;
            cursor: pointer;
        }

        .link-brand {
            font-size: .875rem;
            font-weight: 600;
            color: var(--brand);
            text-decoration: none;
        }

        .link-brand:hover {
            color: var(--brand-dark);
            text-decoration: underline;
        }

        .btn-login {
            height: 48px;
            font-size: .9375rem;
            font-weight: 600;
            border: 0;
            border-radius: 10px;
            color: #fff;
            background: linear-gradient(180deg, var(--brand) 0%, var(--brand-dark) 100%);
            box-shadow: 0 6px 16px -5px rgba(37, 99, 235, .55);
            transition: filter .15s ease, box-shadow .15s ease, transform .15s ease;
        }

        .btn-login:hover {
            color: #fff;
            filter: brightness(1.06);
            box-shadow: 0 10px 22px -6px rgba(37, 99, 235, .6);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .btn-login:disabled {
            opacity: .7;
            box-shadow: none;
        }

        .card-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.75rem 0 1.25rem;
            color: #94a3b8;
            font-size: .75rem;
        }

        .card-divider::before,
        .card-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        .secure-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: .75rem;
            color: #94a3b8;
        }

        .auth-alert {
            border: 1px solid transparent;
            border-radius: 10px;
            padding: .75rem .875rem;
            font-size: .875rem;
            line-height: 1.5;
        }

        .auth-alert.is-error {
            border-color: #fecdd3;
            background: #fff1f2;
            color: #9f1239;
        }

        .auth-alert.is-success {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #15803d;
        }

        /* ------------------------------------------------ responsive */
        @media (max-width: 991.98px) {
            .auth-card {
                padding: 2rem 1.5rem;
                box-shadow: 0 10px 30px -16px rgba(15, 23, 42, .18);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .btn-login,
            .field .form-control,
            .field-icon {
                transition: none;
            }

            .btn-login:active {
                transform: none;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrapper row g-0">
        <!-- ============================= brand side ============================= -->
        <div class="auth-brand col-lg-6 d-none d-lg-flex flex-column justify-content-between px-5 py-5">
            <div class="auth-brand-grid"></div>
            <div class="auth-brand-glow one"></div>
            <div class="auth-brand-glow two"></div>

            <div class="auth-brand-inner mx-auto">
                @if ($company?->company_logo)
                    <span class="logo-plate">
                        <img src="{{ asset($company->company_logo) }}" alt="{{ $companyName }}">
                    </span>
                @else
                    <span class="logo-fallback"><i class="fa-solid fa-layer-group"></i></span>
                @endif
            </div>

            <div class="auth-brand-inner mx-auto py-5">
                <div class="brand-eyebrow mb-2">{{ __('Management Portal') }}</div>
                <h1 class="brand-title mb-3">{{ $companyName }}</h1>
                <p class="brand-lead mb-5">
                    {{ __('Manage your inventory, sales and online stores from a single, secure dashboard.') }}
                </p>

                <div class="d-flex flex-column gap-4">
                    <div class="feature-item">
                        <span class="feature-icon"><i class="fa-solid fa-warehouse"></i></span>
                        <div>
                            <div class="feature-title">{{ __('Inventory & Stock Control') }}</div>
                            <div class="feature-text">
                                {{ __('Track products, stock levels and low-stock alerts in real time.') }}
                            </div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <span class="feature-icon"><i class="fa-solid fa-receipt"></i></span>
                        <div>
                            <div class="feature-title">{{ __('Sales, Returns & Warranties') }}</div>
                            <div class="feature-text">
                                {{ __('Record every sale and follow it through returns and warranty claims.') }}
                            </div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <span class="feature-icon"><i class="fa-brands fa-ebay"></i></span>
                        <div>
                            <div class="feature-title">{{ __('eBay Store Integration') }}</div>
                            <div class="feature-text">
                                {{ __('Sync listings and orders with your connected eBay stores.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-brand-inner mx-auto">
                <div class="brand-footer d-flex flex-wrap align-items-center gap-2">
                    <span>&copy; {{ date('Y') }} {{ $companyName }}</span>
                    @if ($supportEmail)
                        <span class="opacity-50">&middot;</span>
                        <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                    @endif
                    @if ($supportPhone)
                        <span class="opacity-50">&middot;</span>
                        <span>{{ $supportPhone }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- ============================= form side ============================= -->
        <div class="auth-panel col-lg-6 d-flex flex-column">
            {{-- <div class="d-none d-lg-flex justify-content-end px-5 pt-4">
                <span class="panel-meta">
                    {{ __('Need help?') }}
                    @if ($supportEmail)
                        <a href="mailto:{{ $supportEmail }}">{{ __('Contact support') }}</a>
                    @else
                        <span class="fw-semibold text-secondary">{{ __('Contact your administrator') }}</span>
                    @endif
                </span>
            </div> --}}

            <div class="flex-grow-1 d-flex align-items-center justify-content-center px-3 px-sm-4 py-5">
                <div class="auth-card">
                    <!-- Logo on small screens -->
                    <div class="mobile-brand d-lg-none text-center mb-4">
                        @if ($company?->company_logo)
                            <img src="{{ asset($company->company_logo) }}" alt="{{ $companyName }}">
                        @else
                            <span class="logo-fallback"><i class="fa-solid fa-layer-group"></i></span>
                        @endif
                    </div>

                    <div class="card-eyebrow">{{ __('Account Login') }}</div>
                    <h2 class="card-title">{{ __('Welcome back') }}</h2>
                    <p class="card-subtitle">{{ __('Sign in to continue to your dashboard.') }}</p>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="auth-alert is-success d-flex align-items-start gap-2 mb-4">
                            <i class="fa-solid fa-circle-check mt-1"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Failed login / lockout message -->
                    @if ($errors->has('email'))
                        <div class="auth-alert is-error d-flex align-items-start gap-2 mb-4">
                            <i class="fa-solid fa-circle-exclamation mt-1"></i>
                            <span>{{ $errors->first('email') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf

                        <!-- Email or Username -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email or Username') }}</label>
                            <div class="field">
                                <i class="fa-solid fa-user field-icon"></i>
                                <input id="email" type="text" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="{{ __('Enter your email or username') }}" required autofocus
                                    autocomplete="username">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="link-brand mb-1"
                                        style="font-size: .8125rem;">{{ __('Forgot password?') }}</a>
                                @endif
                            </div>
                            <div class="field has-password">
                                <i class="fa-solid fa-lock field-icon"></i>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="{{ __('Enter your password') }}" required autocomplete="current-password">
                                <button type="button" class="btn-eye" id="toggle-password"
                                    aria-label="{{ __('Show password') }}">
                                    <i class="fa-solid fa-eye" id="toggle-password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1" style="font-size: .8125rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember_me">
                                {{ __('Keep me signed in on this device') }}
                            </label>
                        </div>

                        <button type="submit"
                            class="btn btn-login w-100 d-flex align-items-center justify-content-center gap-2"
                            id="login-button">
                            <span class="spinner-border spinner-border-sm d-none" id="login-spinner" role="status"
                                aria-hidden="true"></span>
                            <span id="login-button-text">{{ __('Sign In') }}</span>
                            <i class="fa-solid fa-arrow-right-to-bracket" id="login-button-icon"
                                style="font-size: 13px;"></i>
                        </button>
                    </form>

                    <div class="card-divider">{{ __('AUTHORISED ACCESS ONLY') }}</div>

                    <div class="secure-note">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>{{ __('Your connection to this portal is private and encrypted.') }}</span>
                    </div>
                </div>
            </div>

            <div class="text-center panel-meta px-4 pb-4">
                &copy; {{ date('Y') }} {{ $companyName }}. {{ __('All rights reserved.') }}
            </div>
        </div>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('toggle-password');
            const password = document.getElementById('password');
            const icon = document.getElementById('toggle-password-icon');

            toggle.addEventListener('click', function () {
                const hidden = password.type === 'password';
                password.type = hidden ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !hidden);
                icon.classList.toggle('fa-eye-slash', hidden);
                toggle.setAttribute('aria-label', hidden ? '{{ __('Hide password') }}' : '{{ __('Show password') }}');
                password.focus();
            });

            document.getElementById('login-form').addEventListener('submit', function () {
                document.getElementById('login-button').disabled = true;
                document.getElementById('login-spinner').classList.remove('d-none');
                document.getElementById('login-button-icon').classList.add('d-none');
                document.getElementById('login-button-text').textContent = '{{ __('Signing in...') }}';
            });
        })();
    </script>
</body>

</html>
