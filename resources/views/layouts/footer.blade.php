{{-- ============================================================
     Global Footer: CDN libraries + global JS utilities
     Included by layouts/header.blade.php before @stack('scripts')
     ============================================================ --}}

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/v/bs5/dt-3.0.0/datatables.min.js"></script>

<!-- Dropify -->
<script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // ── Toast preset (top-right, auto-dismiss) ──────────────────
    window.Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
    });

    // ── Session flash messages ──────────────────────────────────
    @if (session('status'))
        Toast.fire({ icon: 'success', title: @json(session('status')) });
    @endif

    @if (session('error'))
        Toast.fire({ icon: 'error', title: @json(session('error')) });
    @endif

    @if (session('warning'))
        Toast.fire({ icon: 'warning', title: @json(session('warning')) });
    @endif

    @if (session('info'))
        Toast.fire({ icon: 'info', title: @json(session('info')) });
    @endif

    // ── Global confirm-delete helper ────────────────────────────
    // Usage: <form onsubmit="return confirmDelete(event)"> ...
    window.confirmDelete = function(e, message) {
        e.preventDefault();
        const form = e.target;
        Swal.fire({
            title: 'Are you sure?',
            text: message || 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
        }).then(function(result) {
            if (result.isConfirmed) form.submit();
        });
    };

    // ── Global alert helper ─────────────────────────────────────
    // Usage: showAlert('success'|'error'|'warning'|'info', 'message')
    window.showAlert = function(icon, title, text) {
        if (text) {
            return Swal.fire({ icon, title, text });
        }
        return Toast.fire({ icon, title });
    };

    // ── Auto-init Dropify on any <input class="dropify"> ────────
    // Configure per input via data attributes, e.g.:
    //   data-default-file, data-max-file-size, data-allowed-file-extensions
    $(function () {
        if ($.fn.dropify) {
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop a file here or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something went wrong.'
                }
            });
        }
    });
</script>
