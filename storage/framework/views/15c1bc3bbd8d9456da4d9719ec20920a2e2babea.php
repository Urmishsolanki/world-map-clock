<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="<?php echo e(url('/home')); ?>" class="logo d-flex align-items-center">
      <img src="<?php echo e(URL::asset('public/img/logo.png')); ?>" alt="">
      <span class="d-none d-lg-block">World Map Clock</span>
    </a>
  </div>
  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li>
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo e(Auth::user()->name); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo e(__('You are logged in!')); ?></h6>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="<?php echo e(url('user')); ?>">
              <i class="bi bi-person"></i>
              <span>Edit Profile</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="<?php echo e(url('home')); ?>">
              <i class="bi bi-pin"></i>
              <span>Map City List</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="<?php echo e(url('')); ?>" target="_Blank">
              <i class="bi bi-map"></i>
              <span>Go to Map</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right"></i>
              <?php echo e(__('Sign Out')); ?>

            </a>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
              <?php echo csrf_field(); ?>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</header>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script><?php /**PATH /home3/cyblasjd/wixwebsitesbuilder.com/world-map-clock/resources/views/admin/layouts/header.blade.php ENDPATH**/ ?>