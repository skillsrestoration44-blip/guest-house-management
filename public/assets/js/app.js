/**
 * Guest House Management System - global front-end glue.
 *
 * Wires together:
 *   - jQuery AJAX with CSRF
 *   - SweetAlert2 confirm-delete helper
 *   - PHPFlasher receives notifications via blade @flasher_render
 *   - Flatpickr on .js-flatpickr / .js-flatpickr-datetime
 *   - Tom Select on .js-tom-select / select[multiple]
 *   - Yajra DataTables server-side initialisation on .js-datatable
 *   - Bootstrap 5-styled DataTables paging
 *   - AJAX language switching (no page refresh)
 *   - AJAX branch switching
 */

(function ($) {
    'use strict';

    /* ----------------------------------------------------- */
    /* CSRF                                                   */
    /* ----------------------------------------------------- */
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (csrfToken) {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
    }

    /* ----------------------------------------------------- */
    /* Translation helper                                     */
    /* (defined BEFORE first use so DataTables init can call  */
    /* window.__ without throwing TypeError)                  */
    /* ----------------------------------------------------- */
    window.__ = function (key) {
        return (window.appTranslations && window.appTranslations[key]) || key;
    };

    /* ----------------------------------------------------- */
    /* DataTables Bootstrap 5 paging                          */
    /* ----------------------------------------------------- */
    if ($.fn.dataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            pagingType: 'simple_numbers',
            dom:
                "<'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            language: {
                processing: window.__('loading') || 'Loading...',
                search: (window.__('search') || 'Search') + ':',
                lengthMenu: '_MENU_',
                info: (window.__('showing') || 'Showing') + ' _START_ - _END_ / _TOTAL_',
                infoEmpty: (window.__('no_record') || 'No data available'),
                emptyTable: (window.__('no_record') || 'No data available'),
                zeroRecords: (window.__('no_results') || 'No matching records'),
                paginate: { first: '«', last: '»', next: '›', previous: '‹' },
            },
        });
    }

    /* ----------------------------------------------------- */
    /* SweetAlert2 delete confirmation                        */
    /* ----------------------------------------------------- */
    $(document).on('click', '.js-delete', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const url = $btn.data('url') || $btn.attr('href');
        const tableId = $btn.data('table');

        Swal.fire({
            title: window.__('are_you_sure') || 'Are you sure?',
            text: window.__('confirm_delete') || 'You will not be able to recover this data!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: window.__('yes_delete') || 'Yes, delete it!',
            cancelButtonText: window.__('cancel') || 'Cancel',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url,
                type: 'POST',
                data: { _method: 'DELETE', _token: csrfToken },
            })
                .done(function (resp) {
                    if (tableId && $.fn.dataTable.isDataTable('#' + tableId)) {
                        $('#' + tableId).DataTable().ajax.reload(null, false);
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: (resp && resp.message) || window.__('deleted_successfully') || 'Deleted successfully.',
                        showConfirmButton: false,
                        timer: 2500,
                    });
                })
                .fail(function (xhr) {
                    const msg = xhr.responseJSON?.message || window.__('something_went_wrong') || 'Something went wrong';
                    Swal.fire({ icon: 'error', title: window.__('error') || 'Error', text: msg });
                });
        });
    });

    /* ----------------------------------------------------- */
    /* Flatpickr                                              */
    /* ----------------------------------------------------- */
    function initFlatpickr(scope) {
        if (typeof flatpickr === 'undefined') return;
        $(scope).find('.js-flatpickr').each(function () {
            if (!this._flatpickr) flatpickr(this, { dateFormat: 'Y-m-d', allowInput: true });
        });
        $(scope).find('.js-flatpickr-datetime').each(function () {
            if (!this._flatpickr) flatpickr(this, { enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true, allowInput: true });
        });
        $(scope).find('.js-flatpickr-time').each(function () {
            if (!this._flatpickr) flatpickr(this, { enableTime: true, noCalendar: true, dateFormat: 'H:i', time_24hr: true, allowInput: true });
        });
        $(scope).find('.js-flatpickr-month').each(function () {
            if (!this._flatpickr) flatpickr(this, { dateFormat: 'Y-m', allowInput: true });
        });
    }

    /* ----------------------------------------------------- */
    /* Tom Select                                             */
    /* ----------------------------------------------------- */
    function initTomSelect(scope) {
        if (typeof TomSelect === 'undefined') return;
        $(scope).find('.js-tom-select').each(function () {
            if (this.tomselect) return;
            new TomSelect(this, { plugins: this.multiple ? ['remove_button'] : [], create: false, allowEmptyOption: true });
        });
    }

    /* ----------------------------------------------------- */
    /* Yajra DataTable initialiser (server-side)              */
    /* ----------------------------------------------------- */
    function initDataTables(scope) {
        if (!$.fn.dataTable) return;
        $(scope).find('.js-datatable').each(function () {
            const $tbl = $(this);
            if ($.fn.dataTable.isDataTable(this)) return;

            const url = $tbl.data('url');
            const columns = $tbl.data('columns');
            if (!url || !columns) return;

            $tbl.DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url,
                    data: function (d) {
                        d._token = csrfToken;
                    },
                },
                columns,
                order: $tbl.data('order') || [[0, 'desc']],
            });
        });
    }

    /* ----------------------------------------------------- */
    /* Language switcher (AJAX, no refresh)                   */
    /* ----------------------------------------------------- */
    $(document).on('click', '.js-lang-switch', function () {
        const $a = $(this);
        const locale = $a.data('locale');
        if (!locale) return;

        $.post('/locale/switch', { locale })
            .done(function (resp) {
                if (resp.status !== 'success') return;
                window.appTranslations = resp.translations || {};
                $('html').attr('lang', resp.locale).attr('data-locale', resp.locale);
                $('#currentLocaleLabel').text(resp.locale.toUpperCase());

                $('[data-i18n]').each(function () {
                    const key = $(this).data('i18n');
                    if (window.appTranslations[key] !== undefined) {
                        $(this).text(window.appTranslations[key]);
                    }
                });
                $('[data-i18n-placeholder]').each(function () {
                    const key = $(this).data('i18n-placeholder');
                    if (window.appTranslations[key] !== undefined) {
                        $(this).attr('placeholder', window.appTranslations[key]);
                    }
                });

                $('.js-lang-switch').removeClass('active');
                $a.addClass('active');

                $('.js-datatable').each(function () {
                    if ($.fn.dataTable.isDataTable(this)) {
                        const dt = $(this).DataTable();
                        dt.ajax.reload(null, false);
                    }
                });

                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: window.__('updated_successfully') || 'Updated.',
                    showConfirmButton: false, timer: 1800,
                });
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: window.__('error') || 'Error', text: window.__('something_went_wrong') });
            });
    });

    /* ----------------------------------------------------- */
    /* Branch switcher (AJAX)                                 */
    /* ----------------------------------------------------- */
    $(document).on('click', '.js-branch-switch', function () {
        const $a = $(this);
        const branchId = $a.data('branch-id') || 0;

        $.post('/branch/switch', { branch_id: branchId })
            .done(function (resp) {
                if (resp.status !== 'success') return;

                $('#currentBranchLabel').text(
                    resp.branch ? resp.branch.name : (window.__('all_branches') || 'All Branches')
                );
                $('.js-branch-switch').removeClass('active');
                $a.addClass('active');

                $('.js-datatable').each(function () {
                    if ($.fn.dataTable.isDataTable(this)) {
                        $(this).DataTable().ajax.reload(null, false);
                    }
                });

                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: window.__('updated_successfully') || 'Updated.',
                    showConfirmButton: false, timer: 1800,
                });
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: window.__('error') || 'Error' });
            });
    });

    /* ----------------------------------------------------- */
    /* DOM ready                                              */
    /* ----------------------------------------------------- */
    $(function () {
        initFlatpickr(document);
        initTomSelect(document);
        initDataTables(document);

        $(document).on('shown.bs.modal', function (e) {
            initFlatpickr(e.target);
            initTomSelect(e.target);
        });
    });
})(jQuery);
