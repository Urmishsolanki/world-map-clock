<link rel='stylesheet' href='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.css'>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css" />

<script src='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js'></script>
<?php $__env->startSection('content'); ?>
<main id="main" class="main">
  <section class="section datatable-page">
    <div class="row">
      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">
          <!-- Top Selling -->
          <div class="col-12">
            <div class="card top-selling overflow-auto">
              <div class="card-body pb-0">
                <div class="modelHeading modal-header">
                  <h4 class="modal-title" id="message" style="padding-left: 10px;"></h4>
                </div>
                <?php if(session('success')): ?>
                <div class="alert alert-success">
                  <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">World City List</h5>
                  <a class="btn btn-primary" href="<?php echo e(url('world-city/create')); ?>">Add City</a>
                </div>
                <table class="table table-borderless" id="worldCityId">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Name</th>
                      <th scope="col">Country Code</th>
                      <th scope="col">Created At</th>
                      <th scope="col">Updated At</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- End Top Selling -->
        </div>
      </div>
      <!-- End Left side columns -->
    </div>
  </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
  //jQuery(document).ready(function () { jQuery('#worldMapClock').DataTable(); });
  $(function() {
    var table = $('#worldCityId').DataTable({
      processing: true,
      language: {
        processing: '<img src="<?php echo e(URL::asset("public/img/Snake.gif")); ?>">',
      },
      serverSide: true,
      responsive: true,
      ajax: "<?php echo e(url('world-city')); ?>",
      columns: [{
          data: 'id',
          name: 'id',
          orderable: false,
          searchable: false,
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'country_code',
          name: 'country_code',
          orderable: false
        },
        {
          data: 'created_at',
          name: 'created_at',
          orderable: false,
        },
        {
          data: 'updated_at',
          name: 'updated_at',
          orderable: false,
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          orderable: false
        },
      ]
    });

  });
  setTimeout(function() {
    jQuery('.alert-success').fadeOut(1000);
  }, 1500);
  jQuery(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  });
  //Delete City
  jQuery('body').on('click', '#delete-world-city', function() {
    var city = $(this).data("id");
    var url = "<?php echo e(route('world-city.destroy',':id')); ?>";
    url = url.replace(':id', city);
    swal({
      title: "Are you sure want to delete?",
      text: "",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: false,
      //closeOnCancel: false
    }, function(isConfirm) {
      if (!isConfirm) return;
      jQuery.ajax({
        type: "DELETE",
        url: url,
        success: function() {
          var oTable = $('#worldCityId').dataTable();
          oTable.fnDraw(false);
          swal("Done!", "It is succesfully deleted!", "success");
        },
        error: function(xhr, ajaxOptions, thrownError) {
          swal("Error deleting!", "Please try again", "error");
        }
      });
    });
  });

  jQuery(document).ready(function() {
    jQuery('body').on('click', '#EditAcess', function() {
      Swal.fire("Sorry, you don't have access to edit this.")
    });
    jQuery('body').on('click', '#DeleteAcess', function() {
      Swal.fire("Sorry, you don't have access to delete this.")
    });
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/cyblasjd/wixwebsitesbuilder.com/world-map-clock/resources/views/admin/worldcity/index-world-city.blade.php ENDPATH**/ ?>