@extends('admin.layouts.default')
<link rel='stylesheet' href='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.css'>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css" />
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
    z-index: 999;
  }

  ul#searchResult li {
    padding: 4px 15px;
    font-size: 15px;
    cursor: pointer;
  }

  ul#searchResult li:hover {
    background: #ededed;
  }
  .modelHeading{
    margin-bottom: 10px;
  }
</style>
<script src='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js'></script>
@section('content')
<main id="main" class="main">
  <section class="section datatable-page">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-12">
            <div class="card top-selling overflow-auto">
              <div class="card-body pb-0">
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">City List</h5>
                  <a class="btn btn-primary" href="javascript:void(0)" id="createNewProduct"> Add New City</a>
                </div>
                <div class="modelHeading modal-header"  style="display: none;">
                  <p class="modal-title" id="message" style="padding: 10px 20px;"></p>
                </div>
                <table class="table table-borderless table-striped" id="worldMapClock">
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
        </div>
      </div>
    </div>
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div style="float: right;"><img src="{{ URL::asset('public/img/pop-up-close.png')}}" class="close_id"></div>
            <form class="form-horizontal row g-3" method="post" id="searchFrom" name="worldMapClockAddForm">
              @csrf
              <div class="col-md-12 placeSearch">
                <input type="hidden" name="city_id" id="city_id">
                <input type="hidden" id="worldcity_id" name="worldcity_id" value="">
                <input type="hidden" id="worldcity_name" name="worldcity_name" value="">
                <div class="form-group" style="position: relative;margin-top: 20px;">
                  <label for="input-datalist">City Name</label>
                  <input type="text" class="form-control" id="city_name" placeholder="Enter City" name="name" list="list-timezone" autocomplete="off">
                  <ul id="searchResult" style="display: none;"></ul>
                </div>
                <span id="name-error" class="text-danger">{{$errors->first('name')}}</span>
              </div> <br>
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<script src="https://code.jquery.com/jquery-2.1.3.js"></script>
<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript">
  $(function() {
    var table = $('#worldMapClock').DataTable({
      processing: true,
      language: {
        processing: '<img src="{{ URL::asset("public/img/Snake.gif")}}">',
      },
      serverSide: true,
      //responsive: true,
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
    var worldcity_id = $('#worldcity_id').val();
    if (worldcity_id) {
      $.ajax({
        data: $('#searchFrom').serialize(),
        url: "{{ route('city.store') }}",
        type: "POST",
        dataType: 'json',
        success: function(data) {
          if (data.success == '1' || data.success == 1) {
            $('#searchFrom').trigger("reset");
            $('#ajaxModel').modal('hide');
            $('#message').text("City Updated Successfully.");
            $('#worldMapClock').DataTable().ajax.reload();
          } else {
            console.log(data.success);
            $('#searchFrom').trigger("reset");
            $('#ajaxModel').modal('hide');
            $('#message').text("New City Added Successfully.");
            $('#worldMapClock').DataTable().ajax.reload();
          }
          jQuery('.modal-header').stop().removeClass('fadeOut');
          jQuery('.modal-header').fadeIn();
          setTimeout(function() {
            jQuery('.modal-header').fadeOut(1000);
          }, 1500);
        },
        error: function(data) {
          const obj = JSON.parse(data.responseText);
          console.log(obj);
          $('#name-error').text(obj.message);
          $('#name-error').text(obj.errors.name);
        },
      });
      $("#name-error").text("");
    } else {
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
      $('#worldcity_id').val(data.worldcity_id);
      var name = data.name;
      $('#worldcity_name').val(name);
      $("#city_name").keyup(function () {
        var value = $(this).val().trim();
        if(value == ""){
          $("#searchResult").hide();
        }
        else if(name == value){
          $("#saveBtn").addClass('btn btn-outline-primary disabled');
        }else{
          $("#saveBtn").removeClass('btn btn-outline-primary disabled');
          $("#saveBtn").addClass('btn btn-primary');
        }
      });
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
      confirmButtonColor: '#1a83bf',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: false,
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

  $(document).ready(function() {
    var url = "{{ url('get-city') }}";
    $("#city_name").keyup(function() {
      var search = ($(this).val()).trim();
      $("#name-error").text("");
      if (search != "") {
        $("#searchResult").empty();
        $.ajax({
          url: url,
          type: 'post',
          data: {
            query: search,
            type: 1
          },
          dataType: 'json',
          beforeSend: function() {
            $("#city_name").css("background", "url('public/img/spinner.gif') no-repeat 100%");
          },
          success: function(response) {
            $("#searchResult").empty();
            if (response.data) {
              $("#searchResult").show();
              $("#searchResult").append(response.data);
              $("#worldcity_id").val('');
              $("#city_name").css("background", "url('') no-repeat 100%");
              $("#searchResult li").bind("click", function() {
                setText(this);
              });
            } else {
              $("#searchResult").show();
              $("#city_name").css("background", "url('') no-repeat 100%");
              $("#searchResult").text("No Record Found.");
              $("#searchResult").css("padding-left", "10px");
              $("#saveBtn").addClass('btn btn-outline-primary disabled');
            }
          }
        });
      }
    });
  });
  $(document).ready(function(){
    $("#city_name").keyup(function () {
    var value = $(this).val();
    if(value == ""){
      $("#searchResult").hide();
    }
    });
  });
  function setText(element) {
    var value = $(element).text();
    var worldcity_id = $(element).val();
    $("#city_name").val(value);
    $("#worldcity_id").val(worldcity_id);
    $("#searchResult").empty();
    $("#searchResult").hide();
    var a = $('#worldcity_name').val();

    if(a==value){
      $("#saveBtn").addClass('btn btn-outline-primary disabled');
    }else{
      $("#saveBtn").removeClass('btn btn-outline-primary disabled');
      $("#saveBtn").addClass('btn btn-primary');
    }
  }

  $('#ajaxModel').on('hidden.bs.modal', function() {
    $('#name-error').html('');
  })

  $(document).keyup(function(e) {
    if (e.key === "Escape" || e.keyCode === 27) {
      $('#ajaxModel').modal('hide');
    }
  });
  $(document).ready(function() {
    $("#saveBtn").addClass('btn btn-outline-primary disabled');
    $('#city_name').keyup('input', function() {
      var cityName = $(this).val().trim();
      if (cityName === '') {
        $("#saveBtn").addClass('btn btn-outline-primary disabled');
      } else {
        $("#saveBtn").removeClass('btn btn-outline-primary disabled');
        $("#saveBtn").addClass('btn btn-primary');
      }
    });
  });
</script>
@endsection