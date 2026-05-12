<!-- jQuery (required by DataTables / app) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables (Bootstrap 5) -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- Tom Select -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<!-- Application -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
  /* Sidebar collapse / mobile toggle */
  jQuery(function($) {
    $(document).on('click', '.metismenu .has-arrow', function(e) {
      e.preventDefault();
      $(this).parent('li').toggleClass('open');
    });
    $(document).on('click', '.mobile-toggle-icon, .toggle-icon, .nav-toggle-icon', function() {
      $('.sidebar-wrapper').toggleClass('show');
    });
    $('.metismenu li').each(function() {
      const $a = $(this).find('a').first();
      const href = $a.attr('href');
      if (href && href !== '#' && href !== 'javascript:;' && window.location.href.indexOf(href) === 0) {
        $(this).addClass('active open');
        $a.addClass('active');
        $(this).parents('li').addClass('open active');
        $(this).parents('li').children('a').addClass('active');
      }
    });
  });
</script>
