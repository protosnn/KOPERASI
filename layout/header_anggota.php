<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row no-print">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" href="<?php echo isset($root_path) ? $root_path : '../'; ?>anggota/home.php">
      <img src="<?php echo isset($root_path) ? $root_path : '../'; ?>logo.png" class="mr-2" alt="logo" style="height: 40px; width: auto; object-fit: contain;"/>
    </a>
    <a class="navbar-brand brand-logo-mini" href="<?php echo isset($root_path) ? $root_path : '../'; ?>anggota/home.php">
      <img src="<?php echo isset($root_path) ? $root_path : '../'; ?>logo.png" alt="logo" style="height: 30px; width: auto; object-fit: contain;"/>
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
          <img src="<?php echo isset($root_path) ? $root_path : '../'; ?>template2/images/faces/face28.jpg" alt="profile"/>
          <span class="ml-2"><?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Anggota'; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="<?php echo isset($root_path) ? $root_path : '../'; ?>anggota/profile.php">
            <i class="ti-user text-primary"></i>
            Profile
          </a>
          <a class="dropdown-item" href="<?php echo isset($root_path) ? $root_path : '../'; ?>logout.php">
            <i class="ti-power-off text-primary"></i>
            Logout
          </a>
        </div>
      </li>    
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>
<!-- End navbar -->
