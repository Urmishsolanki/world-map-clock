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
</head>

<body>

  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="text-center py-4">
                <div class="invoice-logo"><img src="{{url('public/img/logo.png')}}" alt=""  height="50" style="height: 75px;"></div>
                <a href="{{url('/login')}}" class="logo d-flex align-items-center w-auto">
                  <span class="d-none d-lg-block">Login</span>
                </a>
              </div>
              <div class="card mb-3">
                <div class="card-body">
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
                  <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Enter Email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password" name="password" autocomplete="current-password">
                      @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                  </form>
                  <div class="text-center forgotpass mt-2">
                    @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ url('forget-password') }}">
                      {{ __('Forgot Your Password?') }}
                    </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      setTimeout(function() {
        $('.alert-success').fadeOut(1000);
      }, 1500);
    });
    $(document).ready(function() {
      $("Button").addClass('btn btn-outline-primary disabled');
      $("#email,#password").keyup(function(event) {
        var email = $("#email").val().trim();
        var password = $("#password").val().trim();
        if (validateEmail(email) && password) {
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
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="{{ URL::asset('public/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>