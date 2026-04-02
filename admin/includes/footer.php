</div><!-- #page-content -->

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script>
$(function () {

  /* DataTables */
  if ($('.datatable').length) {
    $('.datatable').DataTable({
      pageLength: 25,
      order: [],
      language: {
        search: '',
        searchPlaceholder: 'Search…',
        lengthMenu: 'Show _MENU_ entries',
        info: 'Showing _START_–_END_ of _TOTAL_',
        emptyTable: 'No records found'
      }
    });
  }

  /* Sidebar toggle (desktop collapse) */
  $('#btn-toggle').on('click', function () {
    if ($(window).width() > 768) {
      $('body').toggleClass('sb-collapsed');
    } else {
      $('body').toggleClass('sb-open');
    }
  });

  /* Sub-menu accordion */
  $('.has-sub > a').on('click', function (e) {
    e.preventDefault();
    var $li = $(this).closest('li');
    $li.toggleClass('open');
    $li.children('.submenu').slideToggle(200);
  });

  /* Auto-dismiss flash alerts */
  setTimeout(function () { $('.alert').alert('close'); }, 5000);

});
</script>
</body>
</html>
