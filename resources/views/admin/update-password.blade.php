@extends('admin.layouts.default')

@section('content')
<main id="main" class="main">
    <section class="section password-page">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">Update Your Password</h5>
                  <a href="{{ route('home') }}" ><span class="btn btn-primary">Back to List</span></a>
                </div>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                       {{ session('status') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                @endif
                <!-- Multi Columns Form -->
                <form class="row g-3" action="{{url('password/store')}}" method="post" id="searchFrom" name="searchFrom">
                    @csrf
                    <div class="col-md-12">
                        <label for="inputName5" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Old Password">
                    </div>
                    <div class="col-md-6">
                        <label for="inputName5" class="form-label">New Password</label>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter New Password" autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label for="inputName5" class="form-label">Confirm New Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Enter Confirm Password" autocomplete="new-password">
                    </div>
                    <div class="text-">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form><!-- End Multi Columns Form -->
            </div>
            <div class="col-12" style="padding-left: 20px;">
                <p class="small">You want to Update Profile? <a href="{{url('/user')}}">Click here</a></p>
            </div>
        </div>
    </section>

</main><!-- End #main -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function () {
    setTimeout(function(){
      jQuery('.alert-success').fadeOut(1000);
  }, 1500);
});
</script>
@endsection