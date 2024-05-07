<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>World Map Clock</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="<?php echo e(URL::asset('public//img/favicon.png')); ?>" rel="icon">
    <link href="<?php echo e(URL::asset('public/backend/assets/img/apple-touch-icon.png')); ?>" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?php echo e(URL::asset('public/backend/assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('public/backend/assets/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?php echo e(URL::asset('public/backend/assets/css/style.css')); ?>" rel="stylesheet">

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
              <div class="pb-4">
                <div class="invoice-logo text-center"><img src="<?php echo e(url('public/img/logo.png')); ?>" alt=""  height="50" style="height: 70px;"></div>
                <a href="<?php echo e(url('/login')); ?>" class="logo d-flex align-items-center w-auto">
                  <span class="d-none d-lg-block">Forgot password</span>
                </a>
              </div>
              <div class="card mb-3">
                <div class="card-body">
                    
                        <?php if(Session::has('message')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(Session::get('message')); ?>

                        </div>
                        <?php endif; ?>
                        <?php if(Session::has('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo e(Session::get('error')); ?>

                        </div>
                        <?php endif; ?>
                        <form class="row g-3 form-horizontal" action="<?php echo e(url('forget-password')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="col-12">
                                <label for="email" class="form-label"><?php echo e(__('E-Mail Address')); ?></label>
                                <input type="text" placeholder="Enter e-mail address" id="email_address" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" autofocus>
                                <?php if($errors->has('email')): ?>
                                <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-primary w-100 btn"> Send Reset Password Link </button>
                            </div>
                            <div class="text-center forgotpass mt-2">
                                <a class="btn btn-link" href="<?php echo e(url('login')); ?>"><?php echo e(__('Login')); ?></a>
                            </div>
                        </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Vendor JS Files -->
    <script src="<?php echo e(URL::asset('public/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("Button").addClass('btn btn-outline-primary disabled');
            $("#email_address").keyup(function(event) {
                var email = $("#email_address").val();
                if (validateEmail(email)) {
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
</body>
</html><?php /**PATH /home3/cyblasjd/wixwebsitesbuilder.com/world-map-clock/resources/views/auth/forget-password.blade.php ENDPATH**/ ?>