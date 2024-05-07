@extends('admin.layouts.default')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style type="text/css">
    .field-icon {
        float: right;
        padding-right: 10px;
        margin-left: -25px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }
    .container {
        padding-top: 50px;
        margin: auto;
    }
</style>
@section('content')
<main id="main" class="main">
    <section class="section profile-page">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Edit Profile</h5>
                </div>
                @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
                @endif

                @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
                @endif

                <form class="row g-3" action="{{url('user/store')}}" method="post" id="searchFrom" name="searchFrom">
                    @csrf
                    <div class="col-md-6">
                        @php
                        if((old('name')) != ""){
                            $name = old('name');
                        }elseif($user[0]->name != ""){
                            $name = $user[0]->name ;
                        }else{
                            $name = "";
                        }
                        @endphp
                        <label for="inputName5" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$name}}" placeholder="Enter Name">
                    </div>
                    <div class="col-md-6">
                        @php
                        if((old('email')) != ""){
                            $email = old('email');
                        }elseif($user[0]->email != ""){
                            $email = $user[0]->email ;
                        }else{
                            $email = "";
                        }
                        @endphp
                        <label for="inputName5" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{$email}}" placeholder="Enter Email">
                    </div>
                    <div class="col-md-12">
                        <label for="inputName5" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Old Password"><i class="fas fa-eye-slash field-icon toggle-password"></i>
                    </div>
                    <div class="col-md-6">
                        <label for="inputName5" class="form-label">New Password</label>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter New Password" autocomplete="new-password"><i class="fas fa-eye-slash field-icon toggle-password"></i>
                    </div>
                    <div class="col-md-6">
                        <label for="inputName5" class="form-label">Confirm New Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Enter Confirm Password" autocomplete="new-password">
                    </div>
                    <div class="text-">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main><!-- End #main -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        setTimeout(function() {
            jQuery('.alert-success').fadeOut(1000);
        }, 1500);

        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash"); // Toggle the classes fa-eye and fa-eye-slash
            var input = $($(this).prev());
            var inputType = input.attr("type") === "password" ? "text" : "password";
            var newInput = $("<input>").attr({
                "type": inputType,
                "class": input.attr("class"),
                "id": input.attr("id"),
                "name": input.attr("name"),
                "placeholder": input.attr("placeholder"),
                "value": input.val()
            });
            input.replaceWith(newInput);
        });
    });
    $(document).ready(function () {
        $("Button").addClass('btn btn-outline-primary disabled');
        var OldValue;
        $("form :input").click(function () {
            var field= ($(this).attr('name'));
            OldValue= ($(this).attr('value'));
            console.log(OldValue)
        })
        $("form :input").keyup(function () {
            var field = ($(this).attr('name'));
            var value = ($(this).attr('value'));
            if(OldValue.trim() === value.trim()){
                $("Button").addClass('btn btn-outline-primary disabled');
            }
            else{
                $("Button").removeClass('btn btn-outline-primary disabled');
                $("Button").addClass('btn btn-primary');
            }
        })
    });
</script>
@endsection