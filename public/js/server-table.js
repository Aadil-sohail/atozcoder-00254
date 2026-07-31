/**
 * Server-side DataTables initializer.
 *
 * Pairs with App\Support\ServerTable on the PHP side: searching, sorting and
 * paging all happen in SQL, so the browser only ever holds one page of rows.
 * Use it anywhere a listing might grow past a few hundred records.
 *
 *     serverTable('#products-table', {
 *         url: '{{ route('products.data') }}',
 *         columns: [
 *             { data: 'name' },
 *             { data: 'actions', orderable: false, searchable: false },
 *         ],
 *         order: [[0, 'asc']],
 *     });
 *
 * Every option DataTables understands may be passed through; only `url` is
 * required, and the defaults below are what the client-side tables in this
 * app already use.
 */
window.serverTable = function (selector, options) {
    const settings = Object.assign({
        processing: true,
        serverSide: true,
        order: [],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        // Waits for a pause in typing instead of querying on every keystroke.
        searchDelay: 350,
        // Keeps the page from jumping while a draw is in flight.
        autoWidth: false,
    }, options || {});

    // Our own indicator markup — the stock one is a row of bare dots. Styled by
    // public/css/server-table.css; caller-supplied language strings still win.
    settings.language = Object.assign({
        processing: '<span class="st-spinner"></span><span>Loading…</span>',
        // Blank on purpose: this row would otherwise say "Loading..." directly
        // underneath the pill above it, on the very first draw only. The empty
        // row still gives the table height for the pill to sit over.
        loadingRecords: '',
    }, options.language || {});

    settings.ajax = Object.assign({
        url: options.url,
        type: 'GET',
        // A failed draw otherwise leaves the table stuck on "Processing...".
        error: function (xhr) {
            const message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Could not load this table. Please refresh the page.';

            if (typeof showAlert === 'function') {
                showAlert('error', message);
            }
        },
    }, options.ajax || {});

    delete settings.url;

    const table = jQuery(selector).DataTable(settings);

    // Drives the progress bar and dims the rows being replaced, so a slow draw
    // reads as "working" rather than as a frozen page. The wrapper is found
    // from the table element itself rather than through a settings property,
    // which is what survives DataTables version changes.
    table.on('processing.dt', function (e, dtSettings, isProcessing) {
        jQuery(e.target).closest('.dt-container').toggleClass('st-busy', isProcessing);
    });

    return table;
};
