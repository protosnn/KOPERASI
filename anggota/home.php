<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include '../koneksi.php';

// Cek jika user sudah login sebagai anggota
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login_anggota") {
    header("location: login_anggota.php?pesan=belum_login");
    exit();
}

// Ambil data anggota dari database untuk memastikan data session valid
$anggota_id = $_SESSION['id_anggota'];
$query_anggota = "SELECT * FROM anggota WHERE id = '$anggota_id'";
$result_anggota = mysqli_query($koneksi, $query_anggota);
$anggota = mysqli_fetch_assoc($result_anggota);

// Update session dengan data terbaru dari database
$_SESSION['nama'] = $anggota['nama'];
$_SESSION['username'] = $anggota['username'];
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard Anggota - Koperasi</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../template2/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../template2/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../template2/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="home.php"><img src="../template2/images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="home.php"><img src="../template2/images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../template2/images/faces/face28.jpg" alt="profile"/>
              <span class="ml-2"><?php echo $_SESSION['nama']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="profile.php">
                <i class="ti-user text-primary"></i>
                Profile
              </a>
              <a class="dropdown-item" href="../logout.php">
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
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <!-- partial:setting_pannel -->

      <!-- sidebar -->
      <?php include '../layout/sidebar_anggota.php'; ?>
      <!-- sidebar -->

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h1 class="font-weight-bold">Selamat Datang, <?php echo $_SESSION['nama']; ?>!</h1>
                  <h6 class="font-weight-normal mb-0">Dashboard Anggota Koperasi</h6>
                </div>
              </div>
            </div>
          </div>

          <?php
          // Data Simpanan Anggota
          $query_simpanan = "SELECT COALESCE(SUM(nominal), 0) as total_simpanan FROM simpanan WHERE anggota_id = '$anggota_id'";
          $result_simpanan = mysqli_query($koneksi, $query_simpanan);
          $data_simpanan = mysqli_fetch_assoc($result_simpanan);
          
          // Data Pinjaman Anggota
          $query_pinjaman = "SELECT COALESCE(SUM(jumlah_pinjaman), 0) as total_pinjaman FROM pinjaman WHERE anggota_id = '$anggota_id' AND status = 'acc'";
          $result_pinjaman = mysqli_query($koneksi, $query_pinjaman);
          $data_pinjaman = mysqli_fetch_assoc($result_pinjaman);
          
          // Data Angsuran Anggota
          $query_angsuran = "SELECT COALESCE(SUM(nominal), 0) as total_angsuran FROM angsuran ag 
                           JOIN pinjaman p ON ag.pinjaman_id = p.id 
                           WHERE p.anggota_id = '$anggota_id' AND ag.status = 'lunas'";
          $result_angsuran = mysqli_query($koneksi, $query_angsuran);
          $data_angsuran = mysqli_fetch_assoc($result_angsuran);
          
          // Total Angsuran yang harus dibayar
          $query_total_angsuran = "SELECT COALESCE(SUM(nominal), 0) as total_harus_bayar FROM angsuran ag 
                                 JOIN pinjaman p ON ag.pinjaman_id = p.id 
                                 WHERE p.anggota_id = '$anggota_id' AND ag.status = 'belum lunas'";
          $result_total_angsuran = mysqli_query($koneksi, $query_total_angsuran);
          $data_total_angsuran = mysqli_fetch_assoc($result_total_angsuran);
          ?>

          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="../template2/images/dashboard/people.svg" alt="people">
                  <div class="weather-info">
                    <div class="d-flex">
                      <div>
                        <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
                      </div>
                      <div class="ml-2">
                        <h4 class="location font-weight-normal"><b>Kajen</b></h4>
                        <h6 class="font-weight-normal">Mrgoyoso</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="card-title text-white mb-4">Total Simpanan Saya</p>                      
                      <p class="fs-30 mb-2">
                        Rp <?php echo number_format($data_simpanan['total_simpanan'], 0, ',', '.'); ?>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4"><b>Total Pinjaman Saya</b></p>
                      <p class="fs-30 mb-2">Rp <?php echo number_format($data_pinjaman['total_pinjaman'], 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4"><b>Angsuran Dibayar</b></p>
                      <p class="fs-30 mb-2">Rp <?php echo number_format($data_angsuran['total_angsuran'], 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4"><b>Angsuran Harus Bayar</b></p>
                      <p class="fs-30 mb-2">Rp <?php echo number_format($data_total_angsuran['total_harus_bayar'], 0, ',', '.'); ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Activity - FIXED QUERY -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Aktivitas Terbaru Saya</h4>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Tanggal</th>
                          <th>Jenis</th>
                          <th>Keterangan</th>
                          <th>Nominal</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // Ambil data aktivitas terbaru anggota - FIXED QUERY
                        $query_aktivitas = "SELECT 
                                          'Simpanan' as jenis, 
                                          tanggal, 
                                          nominal, 
                                          'Berhasil' as status_aktivitas,
                                          'Setoran Simpanan' as keterangan
                                          FROM simpanan 
                                          WHERE anggota_id = '$anggota_id' 
                                          UNION ALL
                                          SELECT 
                                          'Pinjaman' as jenis, 
                                          tanggal_pengajuan as tanggal, 
                                          jumlah_pinjaman as nominal, 
                                          status as status_aktivitas,
                                          'Pengajuan Pinjaman' as keterangan
                                          FROM pinjaman 
                                          WHERE anggota_id = '$anggota_id'
                                          UNION ALL
                                          SELECT 
                                          'Angsuran' as jenis, 
                                          COALESCE(tgl_pelunasan, tgl_jatuhtempo) as tanggal, 
                                          ag.nominal, 
                                          ag.status as status_aktivitas,
                                          CONCAT('Angsuran ke-', ag.angsuran_ke) as keterangan
                                          FROM angsuran ag 
                                          JOIN pinjaman p ON ag.pinjaman_id = p.id 
                                          WHERE p.anggota_id = '$anggota_id'
                                          ORDER BY tanggal DESC 
                                          LIMIT 5";
                        
                        $result_aktivitas = mysqli_query($koneksi, $query_aktivitas);
                        
                        if($result_aktivitas && mysqli_num_rows($result_aktivitas) > 0) {
                          while ($aktivitas = mysqli_fetch_assoc($result_aktivitas)) {
                            $tanggal = ($aktivitas['tanggal'] && $aktivitas['tanggal'] != '0000-00-00') ? date('d-m-Y', strtotime($aktivitas['tanggal'])) : '-';
                            
                            // Tentukan badge status berdasarkan jenis dan status
                            if($aktivitas['jenis'] == 'Simpanan') {
                              $status_badge = 'badge badge-success';
                              $status_text = 'Berhasil';
                            } else {
                              $status_badge = ($aktivitas['status_aktivitas'] == 'acc' || $aktivitas['status_aktivitas'] == 'lunas') ? 'badge badge-success' : 
                                            (($aktivitas['status_aktivitas'] == 'pending' || $aktivitas['status_aktivitas'] == 'belum lunas') ? 'badge badge-warning' : 'badge badge-danger');
                              
                              $status_text = ($aktivitas['status_aktivitas'] == 'acc') ? 'Disetujui' : 
                                           (($aktivitas['status_aktivitas'] == 'pending') ? 'Menunggu' : 
                                           (($aktivitas['status_aktivitas'] == 'lunas') ? 'Lunas' : 
                                           (($aktivitas['status_aktivitas'] == 'belum lunas') ? 'Belum Lunas' : $aktivitas['status_aktivitas'])));
                            }
                        ?>
                        <tr>
                          <td><?php echo $tanggal; ?></td>
                          <td><?php echo $aktivitas['jenis']; ?></td>
                          <td><?php echo $aktivitas['keterangan']; ?></td>
                          <td>Rp <?php echo number_format($aktivitas['nominal'], 0, ',', '.'); ?></td>
                          <td><span class="<?php echo $status_badge; ?>"><?php echo $status_text; ?></span></td>
                        </tr>
                        <?php 
                          }
                        } else {
                        ?>
                        <tr>
                          <td colspan="5" class="text-center">Belum ada aktivitas</td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium Bootstrap admin template from BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../template2/vendors/chart.js/Chart.min.js"></script>
  <script src="../template2/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="../template2/js/dataTables.select.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../template2/js/off-canvas.js"></script>
  <script src="../template2/js/hoverable-collapse.js"></script>
  <script src="../template2/js/template.js"></script>
  <script src="../template2/js/settings.js"></script>
  <script src="../template2/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../template2/js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>
</html>