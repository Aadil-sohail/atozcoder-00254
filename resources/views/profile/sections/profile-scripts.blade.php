@push('scripts')
    <style>
        .profile-picture-wrapper {
            width: 200px;
            margin: 0 auto;
        }

        .profile-picture-wrapper .dropify-wrapper {
            border-radius: 50% !important;
            width: 200px !important;
            height: 200px !important;
            padding: 0 !important;
        }

        .profile-picture-wrapper .dropify-wrapper .dropify-preview {
            border-radius: 50% !important;
        }

        .profile-picture-wrapper .dropify-wrapper .dropify-message {
            border-radius: 50% !important;
        }

        .profile-picture-wrapper .dropify-wrapper .dropify-message span {
            font-size: 12px !important;
            line-height: 1.4 !important;
        }

        .profile-picture-wrapper .dropify-wrapper .dropify-preview .dropify-render img {
            border-radius: 50% !important;
            object-fit: cover;
        }

        .profile-picture-wrapper .dropify-wrapper .dropify-clear {
            border-radius: 50% !important;
        }

        .company-logo-wrapper {
            width: 200px;
            margin: 0 auto;
        }

        .company-logo-wrapper .dropify-wrapper {
            border-radius: 50% !important;
            width: 200px !important;
            height: 200px !important;
            padding: 0 !important;
        }

        .company-logo-wrapper .dropify-wrapper .dropify-preview {
            border-radius: 50% !important;
        }

        .company-logo-wrapper .dropify-wrapper .dropify-message {
            border-radius: 50% !important;
        }

        .company-logo-wrapper .dropify-wrapper .dropify-message span {
            font-size: 12px !important;
            line-height: 1.4 !important;
        }

        .company-logo-wrapper .dropify-wrapper .dropify-preview .dropify-render img {
            border-radius: 50% !important;
            object-fit: cover;
        }

        .company-logo-wrapper .dropify-wrapper .dropify-clear {
            border-radius: 50% !important;
        }

        .fav-icon-wrapper {
            width: 200px;
            margin: 0 auto;
        }

        .fav-icon-wrapper .dropify-wrapper {
            border-radius: 50% !important;
            width: 200px !important;
            height: 200px !important;
            padding: 0 !important;
        }

        .fav-icon-wrapper .dropify-wrapper .dropify-preview {
            border-radius: 50% !important;
        }

        .fav-icon-wrapper .dropify-wrapper .dropify-message {
            border-radius: 50% !important;
        }

        .fav-icon-wrapper .dropify-wrapper .dropify-message span {
            font-size: 12px !important;
            line-height: 1.4 !important;
        }

        .fav-icon-wrapper .dropify-wrapper .dropify-preview .dropify-render img {
            border-radius: 50% !important;
            object-fit: cover;
        }

        .fav-icon-wrapper .dropify-wrapper .dropify-clear {
            border-radius: 50% !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Activate password tab after password update
            @if (session('status') === 'password-updated')
                setTimeout(function() {
                    const passwordTab = document.querySelector(
                        'button[data-bs-target="#navs-pills-top-password"]');
                    if (passwordTab) {
                        const tab = new bootstrap.Tab(passwordTab);
                        tab.show();
                    }
                }, 100);
            @endif

            // Activate company tab after company update
            @if (session('status') === 'company-updated')
                setTimeout(function() {
                    const companyTab = document.querySelector(
                        'button[data-bs-target="#navs-pills-top-company"]');
                    if (companyTab) {
                        const tab = new bootstrap.Tab(companyTab);
                        tab.show();
                    }
                }, 100);
            @endif

            // Activate email tab after email update
            @if (session('status') === 'email-updated')
                setTimeout(function() {
                    const emailTab = document.querySelector(
                        'button[data-bs-target="#navs-pills-top-email"]');
                    if (emailTab) {
                        const tab = new bootstrap.Tab(emailTab);
                        tab.show();
                    }
                }, 100);
            @endif

            // AJAX Password Check
            let passwordCheckTimeout;
            $('#currentPassword').on('input', function() {
                const password = $(this).val();
                const messageDiv = $('#password-check-message');

                // Clear previous timeout
                clearTimeout(passwordCheckTimeout);

                // Clear message if empty
                if (password.length === 0) {
                    messageDiv.html('').removeClass('text-success text-danger');
                    return;
                }

                // Debounce: Wait 500ms after user stops typing
                passwordCheckTimeout = setTimeout(function() {
                    if (password.length > 0) {
                        // Show loading state
                        messageDiv.html('<i class="ti ti-loader-2 ti-spin me-1"></i>Checking...')
                            .removeClass('text-success text-danger').addClass('text-info');

                        $.ajax({
                            url: '{{ route('profile.check-password') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                current_password: password
                            },
                            success: function(response) {
                                if (response.valid) {
                                    messageDiv.html('<i class="ti ti-check me-1"></i>' +
                                        response.message).removeClass(
                                        'text-danger text-info').addClass(
                                        'text-success');
                                    $('#currentPassword').removeClass('is-invalid')
                                        .addClass('is-valid');
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    const response = xhr.responseJSON;
                                    messageDiv.html('<i class="ti ti-x me-1"></i>' + (
                                        response.message ||
                                        'Password is incorrect')).removeClass(
                                        'text-success text-info').addClass(
                                        'text-danger');
                                    $('#currentPassword').removeClass('is-valid')
                                        .addClass('is-invalid');
                                } else {
                                    messageDiv.html(
                                            '<i class="ti ti-alert-circle me-1"></i>An error occurred'
                                        ).removeClass('text-success text-info')
                                        .addClass('text-danger');
                                }
                            }
                        });
                    }
                }, 500);
            });

            // Password Toggle Functionality
            function setupPasswordToggle(inputId, toggleId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const toggleButton = document.getElementById(toggleId);
                const toggleIcon = document.getElementById(iconId);

                if (passwordInput && toggleButton && toggleIcon) {
                    toggleButton.addEventListener('click', function() {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                            'password';
                        passwordInput.setAttribute('type', type);

                        if (type === 'password') {
                            toggleIcon.classList.remove('ti-eye');
                            toggleIcon.classList.add('ti-eye-off');
                        } else {
                            toggleIcon.classList.remove('ti-eye-off');
                            toggleIcon.classList.add('ti-eye');
                        }
                    });
                }
            }

            // Setup toggles for all three password fields
            setupPasswordToggle('currentPassword', 'toggleCurrentPassword', 'currentPasswordToggleIcon');
            setupPasswordToggle('newPassword', 'toggleNewPassword', 'newPasswordToggleIcon');
            setupPasswordToggle('confirmPassword', 'toggleConfirmPassword', 'confirmPasswordToggleIcon');
        });
    </script>
@endpush
