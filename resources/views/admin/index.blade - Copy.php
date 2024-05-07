@extends('admin.layouts.default')
<link rel='stylesheet' href='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.css'>
<link rel="stylesheet" href=
"https://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css"/>
<style type="text/css">
  ul#searchResult {
      position: absolute;
      top: 100%;
      background: #fff;
      width: 100%;
      border: 1px solid #cccd;
      max-height: 300px;
      overflow: auto;
      list-style: none;
      padding: 5px 0px;
  }
  ul#searchResult li {
      padding: 4px 15px;
      font-size: 15px;
      cursor: pointer;
  }
  ul#searchResult li:hover {
      background: #ededed;
  }
</style>
<script src='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js'></script>
@section('content')
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
                  <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">City List</h5>
                  <a class="btn btn-primary" href="javascript:void(0)" id="createNewProduct"> Add New City</a>
                </div>
                <div class="alert alert-success messsge-class" style="display: none;">
                  <span id="message">City Deleted Successfully</span>
                </div>
                <table class="table table-borderless" id="worldMapClock">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Name</th>
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
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <form class="form-horizontal row g-3" method="post" id="searchFrom" name="worldMapClockAddForm">
              @csrf
              <div class="col-md-12 placeSearch">
                <input type="hidden" name="city_id" id="city_id">
                <input type="hidden" id="selected_id" name="selected_id" value="">
                <div class="form-group" style="position: relative;">
                  <label for="input-datalist">Name</label>
                  <input type="text" class="form-control" id="city_name" placeholder="Enter City" name="name" list="list-timezone" autocomplete="off">
                  <ul id="searchResult" style="display: none;"></ul>
                </div>
                <span id="name-error" class="text-danger">{{$errors->first('name')}}</span>
              </div> <br>
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                </button>
                <button type="button" class="btn btn-danger close_id"  data-dismiss="modal" aria-label="Close">Close
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="https://code.jquery.com/jquery-2.1.3.js">
    </script>

    <script src=
        "https://code.jquery.com/ui/1.11.2/jquery-ui.js">
    </script>
<script type="text/javascript">
  //jQuery(document).ready(function () { jQuery('#worldMapClock').DataTable(); });
  $(function() {
    var table = $('#worldMapClock').DataTable({
      processing: true,
      language: {
        processing: '<img src="{{ URL::asset("public/img/Snake.gif")}}">',
      },
      serverSide: true,
      responsive: true,
      ajax: "{{ url('home') }}",
      columns: [{
          data: 'id',
          name: 'id',
          orderable: false,
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'created_at',
          name: 'created_at',
          orderable: false
        },
        {
          data: 'updated_at',
          name: 'updated_at',
          orderable: false
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
      ]
    });

  });

  $('#createNewProduct').click(function() {
    $("#searchResult").hide();
    $('#saveBtn').val("create-product");
    $('#product_id').val('');
    $('#searchFrom').trigger("reset");
    $('#ajaxModel').modal('show');
    $("#city_name").css("background", "#FFF url('') no-repeat 100%");
  });
  $('#saveBtn').click(function(e) {
    e.preventDefault();
    var selected_id =$('#selected_id').val();
    if(selected_id){

      $.ajax({
        data: $('#searchFrom').serialize(),
        url: "{{ route('city.store') }}",
        type: "POST",
        dataType: 'json',
        success: function(data) {

          if (data.success == '1') {
            $('#searchFrom').trigger("reset");
            $('#ajaxModel').modal('hide');
            $('#modelHeading').html("City Updated Successfully.");
            $('#worldMapClock').DataTable().ajax.reload();
            setTimeout(function() {
              jQuery('.modal-header').fadeOut(1000);
            }, 1500);
          } else {
            $('#searchFrom').trigger("reset");
            $('#ajaxModel').modal('hide');
            $('#worldMapClock').DataTable().ajax.reload();
            $('#modelHeading').html("New City Added Successfully.");
            setTimeout(function() {
              jQuery('.modal-header').fadeOut(1000);
            }, 1500);
          }
        },
        error: function(data) {
          const obj = JSON.parse(data.responseText);
          // console.log(obj);
          console.log(obj);
          $('#name-error').text(obj.message);
          $('#name-error').text(obj.errors.name);
        },
      });
      $("#name-error").text("");
    }else{
      $("#name-error").text("Please select valid Input.");
      return false;
    }
  });

  jQuery(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  });
  // For Model Pop Close
  $('body').on('click', '.close_id', function() {
    $('#ajaxModel').modal('hide');
  });
  // Edit City
  $('body').on('click', '.editProduct', function() {
    $("#searchResult").hide();
    $("#city_name").css("background", "#FFF url('') no-repeat 100%");
    var city_id = $(this).data('id');
    $('#modelHeading').html("");
    $.get("{{ route('city.index') }}" + '/' + city_id + '/edit', function(data) {
      $('#saveBtn').val("edit-user");
      $('#ajaxModel').modal('show');
      $('#city_id').val(data.id);
      $('#city_name').val(data.name);
      $('#selected_id').val(data.worldcity_id);
    })
  });
  //Delete City
  jQuery('body').on('click', '#delete-city', function() {
    var city = $(this).data("id");
    var url = "{{ route('city.destroy',':id') }}";
    url = url.replace(':id', city);
    $('#modelHeading').html("");
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
          var oTable = $('#worldMapClock').dataTable();
          oTable.fnDraw(false);
          swal("Done!", "It is succesfully deleted!", "success");
        },
        error: function(xhr, ajaxOptions, thrownError) {
          swal("Error deleting!", "Please try again", "error");
        }
      });
    });
  });

  $('#new').click(function(e) {
    $.ajax({
      url: "{{ route('city.create') }}",
      type: "GET",
      dataType: 'json',
    });
  });

$(document).ready(function(){
  var url = "{{ url('get-city') }}";
  $("#city_name").keyup(function(){
      var search = ($(this).val()).trim();
      $("#name-error").text("");
      if(search != ""){
        $("#searchResult").empty();
          $.ajax({
              url: url,
              type: 'post',
              data: {query:search, type:1},
              dataType: 'json',
              beforeSend: function(){
                // Show image container
                $("#city_name").css("background","url('public/img/Fountain.gif') no-repeat 100%");
              },
              success:function(response){
                $("#searchResult").empty();
                if(response.data){
                  $("#searchResult").show();
                  $("#searchResult").append(response.data);
                  $("#selected_id").val('');
                  $("#city_name").css("background","url('') no-repeat 100%");
                  $("#searchResult li").bind("click",function(){
                      setText(this);
                  });
                }else{
                  $("#searchResult").show();
                  $("#city_name").css("background","url('') no-repeat 100%");
                  $("#searchResult").text("No Record Found.");
                  $("#searchResult").css("padding-left","10px");
                }

              }
          });
      }
  });
});
function setText(element){
  //console.log(element);
  var value = $(element).text();
  var selected_id = $(element).val();
  $("#city_name").val(value);
  $("#selected_id").val(selected_id);
  $("#searchResult").empty();
  $("#searchResult").hide();
}
$('#ajaxModel').on('hidden.bs.modal', function () {
  $('#name-error').html('');
})
</script>
@endsection