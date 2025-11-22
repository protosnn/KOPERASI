<!DOCTYPE html>
<html lang="en">
<?php include 'cek_login.php'; ?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Koperasi - Dashboard</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="../template2/text/css" href="../template2/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../template2/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../template2/images/favicon.png" />
  <style>
    .card-light-green {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .card-light-orange {
      background: linear-gradient(135deg, #fd7e14 0%, #ff9e2e 100%);
    }
    .card-light-warning {
      background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    }
    .card-light-red {
      background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="../index.php"><img src="../logo.png" class="mr-2" alt="logo" style="height: 40px; width: auto; object-fit: contain;"/></a>
        <a class="navbar-brand brand-logo-mini" href="../index.php"><img src="../logo.png" alt="logo" style="height: 30px; width: auto; object-fit: contain;"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../template2/images/faces/face28.jpg" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Settings
              </a>
              <a href="../logout.php" class="dropdown-item">
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
       <?php
      include '../layout/sidebar.php';
      ?>
      <!-- sidebar -->

    <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h1 class="font-weight-bold">Dashboard Koperasi</h1>
                  <p class="text-muted">Ringkasan Keuangan & Aktivitas Harian</p>
                </div>
              </div>
            </div>
          </div>

          <!-- KPI Cards Row 1 -->
          <div class="row">
            <?php
              require_once '../koneksi.php';
              
              // Total Simpanan
              $query_simpanan = "SELECT COUNT(*) as jumlah_transaksi, SUM(nominal) as total_saldo FROM simpanan";
              $result_simpanan = mysqli_query($koneksi, $query_simpanan);
              $data_simpanan = mysqli_fetch_assoc($result_simpanan);

              // Total Pinjaman Aktif (ACC)
              $query_pinjaman_acc = "SELECT COUNT(*) as total_count, SUM(jumlah_pinjaman) as total_nominal FROM pinjaman WHERE status='acc'";
              $result_pinjaman_acc = mysqli_query($koneksi, $query_pinjaman_acc);
              $data_pinjaman_acc = mysqli_fetch_assoc($result_pinjaman_acc);

              // Pinjaman Pending
              $query_pinjaman_pending = "SELECT COUNT(*) as total_count, SUM(jumlah_pinjaman) as total_nominal FROM pinjaman WHERE status='pending'";
              $result_pinjaman_pending = mysqli_query($koneksi, $query_pinjaman_pending);
              $data_pinjaman_pending = mysqli_fetch_assoc($result_pinjaman_pending);

              // Total Angsuran Lunas
              $query_angsuran_lunas = "SELECT COUNT(*) as total_count, SUM(nominal) as total_nominal FROM angsuran WHERE LOWER(status) = 'lunas'";
              $result_angsuran_lunas = mysqli_query($koneksi, $query_angsuran_lunas);
              $data_angsuran_lunas = mysqli_fetch_assoc($result_angsuran_lunas);

              // Total Anggota
              $query_anggota = "SELECT COUNT(*) as total_count FROM anggota";
              $result_anggota = mysqli_query($koneksi, $query_anggota);
              $data_anggota = mysqli_fetch_assoc($result_anggota);

              // Angsuran Belum Lunas
              $query_angsuran_belum = "SELECT COUNT(*) as total_count, SUM(nominal) as total_nominal FROM angsuran WHERE LOWER(status) != 'lunas'";
              $result_angsuran_belum = mysqli_query($koneksi, $query_angsuran_belum);
              $data_angsuran_belum = mysqli_fetch_assoc($result_angsuran_belum);

              // Angsuran Jatuh Tempo Hari Ini
              $query_jatuh_tempo_hari_ini = "SELECT COUNT(*) as total_count FROM angsuran WHERE LOWER(status) != 'lunas' AND DATE(tgl_pelunasan) = CURDATE()";
              $result_jatuh_tempo_hari_ini = mysqli_query($koneksi, $query_jatuh_tempo_hari_ini);
              $data_jatuh_tempo_hari_ini = mysqli_fetch_assoc($result_jatuh_tempo_hari_ini);

              // Angsuran Overdue (terlambat)
              $query_overdue = "SELECT COUNT(*) as total_count, SUM(nominal) as total_nominal FROM angsuran WHERE LOWER(status) != 'lunas' AND DATE(tgl_pelunasan) < CURDATE()";
              $result_overdue = mysqli_query($koneksi, $query_overdue);
              $data_overdue = mysqli_fetch_assoc($result_overdue);
            ?>

            <!-- Card 1: Total Saldo Simpanan -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="card-title text-white mb-4">Total Saldo Simpanan</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    Rp <?php echo number_format($data_simpanan['total_saldo'] ?: 0, 0, ',', '.'); ?>
                  </p>
                  <p class="text-white text-sm"><?php echo $data_simpanan['jumlah_transaksi']; ?> Transaksi</p>
                </div>
              </div>
            </div>

            <!-- Card 2: Total Pinjaman Aktif -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="card-title text-white mb-4">Pinjaman Aktif</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    Rp <?php echo number_format($data_pinjaman_acc['total_nominal'] ?: 0, 0, ',', '.'); ?>
                  </p>
                  <p class="text-white text-sm"><?php echo $data_pinjaman_acc['total_count']; ?> Pinjaman</p>
                </div>
              </div>
            </div>

            <!-- Card 3: Pinjaman Pending -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="card-title text-white mb-4">Pinjaman Pending</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    <?php echo $data_pinjaman_pending['total_count']; ?>
                  </p>
                  <p class="text-white text-sm">Menunggu Persetujuan</p>
                </div>
              </div>
            </div>

            <!-- Card 4: Total Anggota -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="card-title text-white mb-4">Total Anggota</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    <?php echo $data_anggota['total_count']; ?>
                  </p>
                  <p class="text-white text-sm">Anggota Aktif</p>
                </div>
              </div>
            </div>
          </div>

          <!-- KPI Cards Row 2 -->
          <div class="row">
            <!-- Card 5: Angsuran Lunas -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-light-green">
                <div class="card-body">
                  <p class="card-title text-white mb-4">Angsuran Lunas</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    Rp <?php echo number_format($data_angsuran_lunas['total_nominal'] ?: 0, 0, ',', '.'); ?>
                  </p>
                  <p class="text-white text-sm"><?php echo $data_angsuran_lunas['total_count']; ?> Pembayaran</p>
                </div>
              </div>
            </div>

            <!-- Card 6: Angsuran Belum Lunas -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-light-orange">
                <div class="card-body">
                  <p class="card-title text-white mb-4">Angsuran Belum Lunas</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    Rp <?php echo number_format($data_angsuran_belum['total_nominal'] ?: 0, 0, ',', '.'); ?>
                  </p>
                  <p class="text-white text-sm"><?php echo $data_angsuran_belum['total_count']; ?> Angsuran</p>
                </div>
              </div>
            </div>

            <!-- Card 7: Total SHU (Bunga) -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-light-green">
                <div class="card-body">
                  <p class="card-title text-white mb-4">Total SHU (Bunga)</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    Rp <?php 
                      $query_shu_card = "SELECT COALESCE(SUM(bunga), 0) as total_bunga FROM angsuran WHERE status = 'lunas'";
                      $result_shu_card = mysqli_query($koneksi, $query_shu_card);
                      $data_shu_card = mysqli_fetch_assoc($result_shu_card);
                      echo number_format($data_shu_card['total_bunga'] ?: 0, 0, ',', '.');
                    ?>
                  </p>
                  <p class="text-white text-sm">Keuntungan Operasional</p>
                </div>
              </div>
            </div>

            <!-- Card 8: SHU per Anggota (Merata) -->
            <div class="col-md-6 col-lg-3 grid-margin stretch-card">
              <div class="card card-light-orange">
                <div class="card-body">
                  <p class="card-title text-white mb-4">SHU per Anggota</p>
                  <p class="fs-30 mb-2 text-white font-weight-bold">
                    Rp <?php 
                      $shu_per_anggota_card = $data_anggota['total_count'] > 0 ? ($data_shu_card['total_bunga'] / $data_anggota['total_count']) : 0;
                      echo number_format($shu_per_anggota_card, 0, ',', '.');
                    ?>
                  </p>
                  <p class="text-white text-sm">Dibagi Merata</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Ringkasan Aktivitas -->
          <div class="row mt-5">
            <!-- Pinjaman Pending -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-alert text-warning mr-2"></i>Pinjaman Menunggu Persetujuan</h4>
                  <div class="table-responsive">
                    <table class="table table-sm table-striped">
                      <thead>
                        <tr>
                          <th>Anggota</th>
                          <th>Nominal</th>
                          <th>Tanggal</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $query_pending_list = "SELECT p.id, p.anggota_id, a.nama, p.jumlah_pinjaman, p.tanggal_pengajuan 
                                                 FROM pinjaman p 
                                                 JOIN anggota a ON p.anggota_id = a.id 
                                                 WHERE p.status = 'pending' 
                                                 ORDER BY p.tanggal_pengajuan DESC 
                                                 LIMIT 5";
                          $result_pending_list = mysqli_query($koneksi, $query_pending_list);
                          
                          if(mysqli_num_rows($result_pending_list) > 0) {
                            while($pending = mysqli_fetch_assoc($result_pending_list)) {
                              echo "<tr>
                                      <td>" . htmlspecialchars($pending['nama']) . "</td>
                                      <td>Rp " . number_format($pending['jumlah_pinjaman'], 0, ',', '.') . "</td>
                                      <td>" . date('d/m/Y', strtotime($pending['tanggal_pengajuan'])) . "</td>
                                      <td>
                                        <a href='pinjaman/pinjaman.php' class='btn btn-sm btn-primary' title='Lihat'>
                                          <i class='ti-eye'></i>
                                        </a>
                                      </td>
                                    </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada pinjaman pending</td></tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <a href='pinjaman/pinjaman.php' class='btn btn-link btn-sm'>Lihat semua →</a>
                </div>
              </div>
            </div>

            <!-- Angsuran Overdue -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-close text-danger mr-2"></i>Angsuran Overdue (Terlambat)</h4>
                  <div class="table-responsive">
                    <table class="table table-sm table-striped">
                      <thead>
                        <tr>
                          <th>Anggota</th>
                          <th>Nominal</th>
                          <th>Jatuh Tempo</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $query_overdue_list = "SELECT a.nama, ang.nominal, ang.tgl_pelunasan, ang.status,
                                                        DATEDIFF(CURDATE(), DATE(ang.tgl_pelunasan)) as hari_terlambat
                                                 FROM angsuran ang
                                                 JOIN pinjaman p ON ang.pinjaman_id = p.id
                                                 JOIN anggota a ON p.anggota_id = a.id
                                                 WHERE LOWER(ang.status) != 'lunas' AND DATE(ang.tgl_pelunasan) < CURDATE()
                                                 ORDER BY ang.tgl_pelunasan ASC
                                                 LIMIT 5";
                          $result_overdue_list = mysqli_query($koneksi, $query_overdue_list);
                          
                          if(mysqli_num_rows($result_overdue_list) > 0) {
                            while($overdue = mysqli_fetch_assoc($result_overdue_list)) {
                              echo "<tr>
                                      <td>" . htmlspecialchars($overdue['nama']) . "</td>
                                      <td>Rp " . number_format($overdue['nominal'], 0, ',', '.') . "</td>
                                      <td>" . date('d/m/Y', strtotime($overdue['tgl_pelunasan'])) . "</td>
                                      <td><span class='badge badge-danger'>" . $overdue['hari_terlambat'] . " hari</span></td>
                                    </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada angsuran overdue</td></tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <a href='pinjaman/pinjaman.php' class='btn btn-link btn-sm'>Lihat semua →</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Ringkasan Aktivitas Row 2 -->
          <div class="row">
            <!-- SHU (Sisa Hasil Usaha) -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-money text-success mr-2"></i>Sisa Hasil Usaha (SHU)</h4>
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Deskripsi</th>
                          <th>Nominal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          // Total Bunga Lunas (SHU Total)
                          $query_shu = "SELECT COALESCE(SUM(bunga), 0) as total_bunga FROM angsuran WHERE status = 'lunas'";
                          $result_shu = mysqli_query($koneksi, $query_shu);
                          $data_shu = mysqli_fetch_assoc($result_shu);
                          
                          // SHU Per Anggota (dibagi merata)
                          $shu_total = $data_shu['total_bunga'];
                          $shu_per_anggota = $data_anggota['total_count'] > 0 ? $shu_total / $data_anggota['total_count'] : 0;
                          
                          echo "<tr>
                                  <td><strong>Total SHU (Bunga)</strong></td>
                                  <td><strong>Rp " . number_format($shu_total, 0, ',', '.') . "</strong></td>
                                </tr>";
                          echo "<tr>
                                  <td>SHU per Anggota</td>
                                  <td>Rp " . number_format($shu_per_anggota, 0, ',', '.') . "</td>
                                </tr>";
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="mt-3 p-3" style="background-color: #f8f9fa; border-radius: 5px;">
                    <p class="text-muted small mb-0">
                      <i class="ti-info-alt text-info mr-1"></i>
                      <strong>Penjelasan:</strong> SHU dibagi merata kepada <strong><?php echo $data_anggota['total_count']; ?> anggota</strong> dari keuntungan bunga angsuran.
                    </p>
                  </div>
                  <a href='rekap.php' class='btn btn-link btn-sm mt-3'>Lihat detail lengkap →</a>
                </div>
              </div>
            </div>

            <!-- Ringkasan Statistik Simpanan -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-wallet text-success mr-2"></i>Ringkasan Simpanan</h4>
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Jenis Simpanan</th>
                          <th>Nominal</th>
                          <th>Transaksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $query_jenis_simpanan = "SELECT j.nama, COUNT(s.id) as jumlah, SUM(s.nominal) as total
                                                   FROM simpanan s
                                                   JOIN jenissimpanan j ON s.jenissimpanan_id = j.id
                                                   GROUP BY j.id, j.nama
                                                   ORDER BY j.id ASC";
                          $result_jenis_simpanan = mysqli_query($koneksi, $query_jenis_simpanan);
                          
                          while($jenis = mysqli_fetch_assoc($result_jenis_simpanan)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($jenis['nama']) . "</td>
                                    <td>Rp " . number_format($jenis['total'], 0, ',', '.') . "</td>
                                    <td>" . $jenis['jumlah'] . " Transaksi</td>
                                  </tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <a href='pemasukan/simpanan.php' class='btn btn-link btn-sm'>Lihat detail →</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Chart Row -->
          <div class="row mt-5">
            <!-- Pie Chart: Status Angsuran -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-pie-chart text-info mr-2"></i>Status Angsuran</h4>
                  <div style="position: relative; height: 300px;">
                    <canvas id="angsuranChart"></canvas>
                  </div>
                  <div class="mt-3">
                    <p class="text-muted mb-1">
                      <span class="badge badge-success">Lunas</span>
                      <span class="float-right font-weight-bold">
                        <?php echo $data_angsuran_lunas['total_count']; ?> 
                        (<?php 
                          $total_angsuran_all = $data_angsuran_lunas['total_count'] + $data_angsuran_belum['total_count'];
                          echo $total_angsuran_all > 0 ? round(($data_angsuran_lunas['total_count'] / $total_angsuran_all) * 100) : 0;
                        ?>%)
                      </span>
                    </p>
                    <p class="text-muted">
                      <span class="badge badge-warning">Belum Lunas</span>
                      <span class="float-right font-weight-bold">
                        <?php echo $data_angsuran_belum['total_count']; ?> 
                        (<?php 
                          echo $total_angsuran_all > 0 ? round(($data_angsuran_belum['total_count'] / $total_angsuran_all) * 100) : 0;
                        ?>%)
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Chart: Pinjaman Status -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-bar-chart text-success mr-2"></i>Status Pinjaman</h4>
                  <div style="position: relative; height: 300px;">
                    <canvas id="pinjamanChart"></canvas>
                  </div>
                  <div class="mt-3">
                    <p class="text-muted mb-1">
                      <span class="badge badge-info">Pending</span>
                      <span class="float-right font-weight-bold">
                        <?php echo $data_pinjaman_pending['total_count']; ?> Pinjaman
                      </span>
                    </p>
                    <p class="text-muted">
                      <span class="badge badge-success">Disetujui</span>
                      <span class="float-right font-weight-bold">
                        <?php echo $data_pinjaman_acc['total_count']; ?> Pinjaman
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.  Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
          </div>
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span> 
          </div>
        </footer> 
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

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
  <script src="../template2/js/Chart.roundedBarCharts.js"></script>
  <!-- End custom js for this page-->

  <script>
    // Chart: Status Angsuran (Pie Chart)
    const ctxAngsuran = document.getElementById('angsuranChart').getContext('2d');
    const angsuranChart = new Chart(ctxAngsuran, {
      type: 'doughnut',
      data: {
        labels: ['Lunas', 'Belum Lunas'],
        datasets: [{
          data: [
            <?php echo $data_angsuran_lunas['total_count'] ?: 0; ?>,
            <?php echo $data_angsuran_belum['total_count'] ?: 0; ?>
          ],
          backgroundColor: ['#28a745', '#ffc107'],
          borderColor: ['#ffffff', '#ffffff'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 15,
              font: {
                size: 13
              }
            }
          }
        }
      }
    });

    // Chart: Status Pinjaman (Pie Chart)
    const ctxPinjaman = document.getElementById('pinjamanChart').getContext('2d');
    const pinjamanChart = new Chart(ctxPinjaman, {
      type: 'doughnut',
      data: {
        labels: ['Pending', 'Disetujui'],
        datasets: [{
          data: [
            <?php echo $data_pinjaman_pending['total_count'] ?: 0; ?>,
            <?php echo $data_pinjaman_acc['total_count'] ?: 0; ?>
          ],
          backgroundColor: ['#17a2b8', '#20c997'],
          borderColor: ['#ffffff', '#ffffff'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 15,
              font: {
                size: 13
              }
            }
          }
        }
      }
    });
  </script>
</body>

</html>

