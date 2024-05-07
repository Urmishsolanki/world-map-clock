<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>World Map Clock</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ URL::asset('public//img/favicon.png')}}" rel="icon">
    <link href="{{ URL::asset('public/backend/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ URL::asset('public/backend/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('public/backend/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ URL::asset('public/backend/assets/css/style.css')}}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
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
</head>

<body>
    <div class="user-login">
        <div class="account-pages py-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern shadow-none">
                            <div class="card-body reset-password-link" >
                                <div class="p-3">
                                    <p class="text-muted text-center mb-4">Reset Password.</p>
                                    @if (Session::has('success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ Session::get('success') }}
                                    </div>
                                    @endif
                                    @if (Session::has('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ Session::get('error') }}
                                    </div>
                                    @endif
                                    <form class="form-horizontal" method="POST" action="{{ route('ResetPasswordPost') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="form-group">
                                            @if(old('email'))
                                                @php $email = old('email'); @endphp
                                            @else
                                                @php $email = ""; @endphp
                                            @endif
                                            <label for="email">{{ __('E-Mail Address') }}</label>
                                            <input id="email" type="text" class="form-control" name="email" placeholder="Enter e-mail address" value="{{$email}}" autofocus>
                                            @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="password">{{ __('Password') }}</label>
                                            <input id="password" type="password" class="form-control" name="password" placeholder="Enter password"><i class="fas fa-eye-slash field-icon toggle-password"></i>
                                            @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                            <p>
                                                <font>(*The password must include at least one uppercase, one lower case, one special character and one numeric value.)</font>
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">{{ __('Confirm Password') }}</label>
                                            <input type="password" id="password-confirm" class="form-control" name="password_confirmation" placeholder="Confirm Password" autofocus onkeyup="passwordConfirmCheck()">
                                            @if ($errors->has('password_confirmation'))
                                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
                                        </div>
                                        <div class="mt-3">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">{{ __('Reset Password') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Vendor JS Files -->
    <script src="{{ URL::asset('public/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
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
</script>
<script type="text/javascript">
    $(document).ready(function() {
      $("Button").addClass('btn btn-outline-primary disabled');
      $("#email,#password,#password-confirm").keyup(function(event) {
        var email = $("#email").val().trim();
        var password = $("#password").val().trim();
        var confirm_password = $("#password-confirm").val().trim();
        // console.log(password)
        if (validateEmail(email) && password && confirm_password) {
        $("Button").removeClass('btn btn-outline-primary disabled');
        $("Button").addClass('btn btn-primary');
        } else {
          $("Button").addClass('btn btn-outline-primary disabled');
        }
      });
      function validateEmail(email) {
        var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
      }
    });
</script>
</html>