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

<!-- Google Places Autocomplete for Admin -->
<script>
function _initAdminPlaces() {
    var service = new google.maps.places.AutocompleteService();
    var placesService = new google.maps.places.PlacesService(document.createElement('div'));

    var input = document.getElementById('adminAddress');
    if (!input) return;

    var dropdown = document.createElement('div');
    dropdown.className = 'gp-autocomplete-dropdown';
    input.parentNode.style.position = 'relative';
    input.parentNode.appendChild(dropdown);

    var debounceTimer = null;
    var sessionToken = new google.maps.places.AutocompleteSessionToken();

    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        var val = input.value.trim();
        if (val.length < 2) { dropdown.innerHTML = ''; dropdown.style.display = 'none'; return; }

        debounceTimer = setTimeout(function() {
            service.getPlacePredictions({
                input: val,
                types: ['geocode'],
                componentRestrictions: { country: 'in' },
                sessionToken: sessionToken
            }, function(predictions, status) {
                dropdown.innerHTML = '';
                if (status !== 'OK' || !predictions) { dropdown.style.display = 'none'; return; }

                predictions.forEach(function(p) {
                    var item = document.createElement('div');
                    item.className = 'gp-autocomplete-item';
                    item.innerHTML = '<i class="fas fa-map-marker-alt text-primary mr-2" style="font-size:12px;"></i>' + p.description;
                    item.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        input.value = p.description;
                        dropdown.innerHTML = '';
                        dropdown.style.display = 'none';
                        sessionToken = new google.maps.places.AutocompleteSessionToken();
                    });
                    dropdown.appendChild(item);
                });
                dropdown.style.display = 'block';
            });
        }, 300);
    });

    input.addEventListener('blur', function() {
        setTimeout(function() { dropdown.style.display = 'none'; }, 200);
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && dropdown.style.display === 'block') e.preventDefault();
    });
}
</script>
<style>
.gp-autocomplete-dropdown {
    position: absolute; left: 0; right: 0; top: 100%;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0,0,0,.12); z-index: 1070;
    max-height: 240px; overflow-y: auto; display: none;
}
.gp-autocomplete-item {
    padding: 10px 14px; cursor: pointer; font-size: 13px; color: #1a2332;
    border-bottom: 1px solid #f3f4f6;
}
.gp-autocomplete-item:last-child { border-bottom: none; }
.gp-autocomplete-item:hover { background: #f0f9ff; }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbb3sXzee-BxcZ4ci1sM0FWHiedO8Cc4c&libraries=places&callback=_initAdminPlaces" async defer></script>
</body>
</html>
