<!DOCTYPE html>
<html lang="en">
<?php include 'cek_login.php'; ?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Koperasi - Simpanan</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
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
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="../template2/images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../template2/images/logo-mini.svg" alt="logo"/></a>
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
      <?php include '../layout/sidebar.php'; ?>
      <!-- sidebar -->

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-2 mb-xl-0">
                  <h1 class="font-weight-bold">Halaman data rekap bulanan</h1>
                </div>
              </div>
            </div>
          </div>
          
          <div class="stretch-card grid-margin grid-margin-md-0 mb-5">
            <div class="card data-icon-card-primary">
              <div class="card-body">
                <?php
                require_once '../koneksi.php';
                
                // Initialize all variables with default values
                $jumlah_simpanan_pokok = ['total' => 0];
                $jumlah_simpanan_wajib = ['total' => 0];
                $jumlah_simpanan_sukarela = ['total' => 0];
                $jumlah_simpanan = ['total' => 0];
                $jumlah_pinjaman_acc = ['total' => 0];
                $jumlah_angsuran_lunas = ['total' => 0];
                $jumlah_bunga_lunas = ['total' => 0];
                $saldo_akhir = 0;

                try {
                    // First, let's check what tables and columns actually exist
                    $tables_result = mysqli_query($koneksi, "SHOW TABLES");
                    $tables = [];
                    while ($row = mysqli_fetch_array($tables_result)) {
                        $tables[] = $row[0];
                    }

                    // APPROACH 1: Check if separate tables exist for each simpanan type
                    if (in_array('simpanan_pokok', $tables)) {
                        // If separate tables exist for each type
                        $query_simpanan_pokok = "SELECT SUM(nominal) as total FROM simpanan_pokok";
                        $query_simpanan_wajib = "SELECT SUM(nominal) as total FROM simpanan_wajib";
                        $query_simpanan_sukarela = "SELECT SUM(nominal) as total FROM simpanan_sukarela";
                    } 
                    // APPROACH 2: Check if simpanan table exists and has type columns
                    else if (in_array('simpanan', $tables)) {
                        // Check columns in simpanan table
                        $columns_result = mysqli_query($koneksi, "SHOW COLUMNS FROM simpanan");
                        $columns = [];
                        while ($row = mysqli_fetch_assoc($columns_result)) {
                            $columns[] = $row['Field'];
                        }
                        
                        // If simpanan table has separate columns for each type
                        if (in_array('simpanan_pokok', $columns)) {
                            $query_simpanan_pokok = "SELECT SUM(simpanan_pokok) as total FROM simpanan";
                            $query_simpanan_wajib = "SELECT SUM(simpanan_wajib) as total FROM simpanan";
                            $query_simpanan_sukarela = "SELECT SUM(simpanan_sukarela) as total FROM simpanan";
                        }
                        // If simpanan table has a type column
                        else if (in_array('jenis', $columns)) {
                            $query_simpanan_pokok = "SELECT SUM(nominal) as total FROM simpanan WHERE jenis = 'Pokok' OR jenis = 'pokok'";
                            $query_simpanan_wajib = "SELECT SUM(nominal) as total FROM simpanan WHERE jenis = 'Wajib' OR jenis = 'wajib'";
                            $query_simpanan_sukarela = "SELECT SUM(nominal) as total FROM simpanan WHERE jenis = 'Sukarela' OR jenis = 'sukarela' OR jenis = 'Sukarela'";
                        }
                        else if (in_array('type', $columns)) {
                            $query_simpanan_pokok = "SELECT SUM(nominal) as total FROM simpanan WHERE type = 'Pokok' OR type = 'pokok'";
                            $query_simpanan_wajib = "SELECT SUM(nominal) as total FROM simpanan WHERE type = 'Wajib' OR type = 'wajib'";
                            $query_simpanan_sukarela = "SELECT SUM(nominal) as total FROM simpanan WHERE type = 'Sukarela' OR type = 'sukarela' OR type = 'Sukarela'";
                        }
                        // Fallback: just get total from simpanan table
                        else {
                            $query_simpanan_pokok = "SELECT SUM(nominal) as total FROM simpanan";
                            $query_simpanan_wajib = "SELECT 0 as total";
                            $query_simpanan_sukarela = "SELECT 0 as total";
                        }
                    }
                    // APPROACH 3: No simpanan tables found
                    else {
                        $query_simpanan_pokok = "SELECT 0 as total";
                        $query_simpanan_wajib = "SELECT 0 as total";
                        $query_simpanan_sukarela = "SELECT 0 as total";
                    }

                    // Execute simpanan queries
                    $result_simpanan_pokok = mysqli_query($koneksi, $query_simpanan_pokok);
                    if($result_simpanan_pokok && mysqli_num_rows($result_simpanan_pokok) > 0) {
                        $jumlah_simpanan_pokok = mysqli_fetch_assoc($result_simpanan_pokok);
                    }

                    $result_simpanan_wajib = mysqli_query($koneksi, $query_simpanan_wajib);
                    if($result_simpanan_wajib && mysqli_num_rows($result_simpanan_wajib) > 0) {
                        $jumlah_simpanan_wajib = mysqli_fetch_assoc($result_simpanan_wajib);
                    }

                    $result_simpanan_sukarela = mysqli_query($koneksi, $query_simpanan_sukarela);
                    if($result_simpanan_sukarela && mysqli_num_rows($result_simpanan_sukarela) > 0) {
                        $jumlah_simpanan_sukarela = mysqli_fetch_assoc($result_simpanan_sukarela);
                    }

                    // Query for Total Simpanan (sum of all types)
                    $total_simpanan = ($jumlah_simpanan_pokok['total'] ?? 0) + ($jumlah_simpanan_wajib['total'] ?? 0) + ($jumlah_simpanan_sukarela['total'] ?? 0);
                    $jumlah_simpanan = ['total' => $total_simpanan];

                    // Query for Pinjaman ACC
                    if (in_array('pinjaman', $tables)) {
                        $pinjaman_columns_result = mysqli_query($koneksi, "SHOW COLUMNS FROM pinjaman");
                        $pinjaman_columns = [];
                        while ($row = mysqli_fetch_assoc($pinjaman_columns_result)) {
                            $pinjaman_columns[] = $row['Field'];
                        }
                        
                        if (in_array('status', $pinjaman_columns)) {
                            $query_pinjaman_acc = "SELECT SUM(jumlah_pinjaman) as total FROM pinjaman WHERE status = 'ACC' OR status = 'acc' OR status = 'Acc'";
                        } else if (in_array('status_pinjaman', $pinjaman_columns)) {
                            $query_pinjaman_acc = "SELECT SUM(jumlah_pinjaman) as total FROM pinjaman WHERE status_pinjaman = 'ACC' OR status_pinjaman = 'acc' OR status_pinjaman = 'Acc'";
                        } else {
                            $query_pinjaman_acc = "SELECT SUM(jumlah_pinjaman) as total FROM pinjaman";
                        }
                        
                        $result_pinjaman_acc = mysqli_query($koneksi, $query_pinjaman_acc);
                        if($result_pinjaman_acc && mysqli_num_rows($result_pinjaman_acc) > 0) {
                            $jumlah_pinjaman_acc = mysqli_fetch_assoc($result_pinjaman_acc);
                        }
                    }

                    // Query for Angsuran Lunas
                    if (in_array('angsuran', $tables)) {
                        $angsuran_columns_result = mysqli_query($koneksi, "SHOW COLUMNS FROM angsuran");
                        $angsuran_columns = [];
                        while ($row = mysqli_fetch_assoc($angsuran_columns_result)) {
                            $angsuran_columns[] = $row['Field'];
                        }
                        
                        // Cek kolom yang tersedia untuk jumlah angsuran
                        $angsuran_amount_column = 'jumlah_angsuran'; // default
                        if (in_array('jumlah_bayar', $angsuran_columns)) {
                            $angsuran_amount_column = 'jumlah_bayar';
                        } else if (in_array('nominal', $angsuran_columns)) {
                            $angsuran_amount_column = 'nominal';
                        } else if (in_array('besar_angsuran', $angsuran_columns)) {
                            $angsuran_amount_column = 'besar_angsuran';
                        }
                        
                        if (in_array('status', $angsuran_columns)) {
                            $query_angsuran_lunas = "SELECT SUM($angsuran_amount_column) as total FROM angsuran WHERE status = 'lunas' OR status = 'Lunas'";
                        } else if (in_array('status_angsuran', $angsuran_columns)) {
                            $query_angsuran_lunas = "SELECT SUM($angsuran_amount_column) as total FROM angsuran WHERE status_angsuran = 'lunas' OR status_angsuran = 'Lunas'";
                        } else {
                            $query_angsuran_lunas = "SELECT SUM($angsuran_amount_column) as total FROM angsuran";
                        }
                        
                        $result_angsuran_lunas = mysqli_query($koneksi, $query_angsuran_lunas);
                        if($result_angsuran_lunas && mysqli_num_rows($result_angsuran_lunas) > 0) {
                            $jumlah_angsuran_lunas = mysqli_fetch_assoc($result_angsuran_lunas);
                        }

                        // Query for Bunga Lunas
                        if (in_array('bunga', $angsuran_columns)) {
                            if (in_array('status', $angsuran_columns)) {
                                $query_bunga_lunas = "SELECT SUM(bunga) as total FROM angsuran WHERE status = 'lunas' OR status = 'Lunas'";
                            } else if (in_array('status_angsuran', $angsuran_columns)) {
                                $query_bunga_lunas = "SELECT SUM(bunga) as total FROM angsuran WHERE status_angsuran = 'lunas' OR status_angsuran = 'Lunas'";
                            } else {
                                $query_bunga_lunas = "SELECT SUM(bunga) as total FROM angsuran";
                            }
                            
                            $result_bunga_lunas = mysqli_query($koneksi, $query_bunga_lunas);
                            if($result_bunga_lunas && mysqli_num_rows($result_bunga_lunas) > 0) {
                                $jumlah_bunga_lunas = mysqli_fetch_assoc($result_bunga_lunas);
                            }
                        }
                    }

                    // Calculate Saldo Akhir
                    $saldo_akhir = ($jumlah_simpanan['total'] ?? 0) + ($jumlah_angsuran_lunas['total'] ?? 0) + ($jumlah_bunga_lunas['total'] ?? 0) - ($jumlah_pinjaman_acc['total'] ?? 0);

                } catch (Exception $e) {
                    // If any error occurs, set all values to 0
                    error_log("Database error: " . $e->getMessage());
                }
                ?>
              </div>
            </div>
          </div>
          
          <!-- Tabel Saldo ksp -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Rekap Bulanan</h4>
                        <table id="rekapBulanan" class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Total (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Simpanan Pokok</td>
                                    <td>Rp <?= number_format($jumlah_simpanan_pokok['total'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td>Total Simpanan Wajib</td>
                                    <td>Rp <?= number_format($jumlah_simpanan_wajib['total'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td>Total Simpanan Sukarela</td>
                                    <td>Rp <?= number_format($jumlah_simpanan_sukarela['total'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td><b>Total Simpanan</b></td>
                                    <td><b>Rp <?= number_format($jumlah_simpanan['total'] ?? 0, 0, ',', '.') ?></b></td>
                                </tr>
                                <tr>
                                    <td>Total Pinjaman ACC</td>
                                    <td>Rp <?= number_format($jumlah_pinjaman_acc['total'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td>Total Angsuran Lunas</td>
                                    <td>Rp <?= number_format($jumlah_angsuran_lunas['total'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td>Total Bunga Lunas</td>
                                    <td>Rp <?= number_format($jumlah_bunga_lunas['total'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                                <tr class="table-primary">
                                    <td><b>Total Saldo Akhir</b></td>
                                    <td><b>Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          </div>

          <!-- TABEL REKAP PENARIKAN / PEMASUKAN -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Data Pemasukan / Penarikan</h3>

                        <div class="table-responsive">
                            <button onclick="printTable()" class="btn btn-primary btn-sm mb-3"><i class="ti-printer btn-icon-append"></i> Print</button>
                            <button onclick="exportTableToExcel('rekapTable')" class="btn btn-success btn-sm mb-3"><i class="ti-file"></i> Excel</button>

                            <table id="rekapTable" class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Anggota</th>
                                        <th>Simpan Pokok</th>
                                        <th>Simpanan Wajib</th>
                                        <th>Simpanan Sukarela</th>
                                        <th>Total Simpanan</th>
                                        <th>Penarikan</th>
                                        <th>Total Hutang</th>
                                        <th>Angsuran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Debug: Cek struktur tabel angsuran
                                    $debug_angsuran = false;
                                    if (in_array('angsuran', $tables)) {
                                        $debug_columns = mysqli_query($koneksi, "SHOW COLUMNS FROM angsuran");
                                        $debug_angsuran_columns = [];
                                        while ($row = mysqli_fetch_assoc($debug_columns)) {
                                            $debug_angsuran_columns[] = $row['Field'];
                                        }
                                        $debug_angsuran = true;
                                    }

                                    // Query sederhana untuk menghindari error JOIN yang kompleks
                                    $query_simpanan = "SELECT * FROM simpanan ORDER BY tanggal DESC LIMIT 50";
                                    $ambil_simpanan = mysqli_query($koneksi, $query_simpanan);
                                    $no = 1;
                                    
                                    if ($ambil_simpanan && mysqli_num_rows($ambil_simpanan) > 0) {
                                        while ($data_simpanan = mysqli_fetch_assoc($ambil_simpanan)) {
                                            $id_anggota = $data_simpanan['id_anggota'] ?? null;
                                            
                                            // Get nama anggota
                                            $nama_anggota = 'Tidak ada nama';
                                            if ($id_anggota) {
                                                $query_anggota = "SELECT nama FROM anggota WHERE id_anggota = '$id_anggota' LIMIT 1";
                                                $result_anggota = mysqli_query($koneksi, $query_anggota);
                                                if ($result_anggota && mysqli_num_rows($result_anggota) > 0) {
                                                    $data_anggota = mysqli_fetch_assoc($result_anggota);
                                                    $nama_anggota = $data_anggota['nama'] ?? 'Tidak ada nama';
                                                }
                                            }
                                            
                                            // Get total hutang
                                            $total_hutang = 0;
                                            if ($id_anggota && in_array('pinjaman', $tables)) {
                                                $query_hutang = "SELECT SUM(jumlah_pinjaman) as total FROM pinjaman WHERE id_anggota = '$id_anggota'";
                                                $result_hutang = mysqli_query($koneksi, $query_hutang);
                                                if ($result_hutang && mysqli_num_rows($result_hutang) > 0) {
                                                    $data_hutang = mysqli_fetch_assoc($result_hutang);
                                                    $total_hutang = $data_hutang['total'] ?? 0;
                                                }
                                            }
                                            
                                            // Get total angsuran
                                            $total_angsuran = 0;
                                            if ($id_anggota && $debug_angsuran) {
                                                // Gunakan kolom yang tersedia
                                                $angsuran_column = 'jumlah_angsuran';
                                                if (in_array('jumlah_bayar', $debug_angsuran_columns)) {
                                                    $angsuran_column = 'jumlah_bayar';
                                                } else if (in_array('nominal', $debug_angsuran_columns)) {
                                                    $angsuran_column = 'nominal';
                                                } else if (in_array('besar_angsuran', $debug_angsuran_columns)) {
                                                    $angsuran_column = 'besar_angsuran';
                                                }
                                                
                                                $query_angsuran = "SELECT SUM($angsuran_column) as total FROM angsuran WHERE id_anggota = '$id_anggota'";
                                                $result_angsuran = mysqli_query($koneksi, $query_angsuran);
                                                if ($result_angsuran && mysqli_num_rows($result_angsuran) > 0) {
                                                    $data_angsuran = mysqli_fetch_assoc($result_angsuran);
                                                    $total_angsuran = $data_angsuran['total'] ?? 0;
                                                }
                                            }
                                            
                                            // Hitung total simpanan
                                            $total_simpanan = ($data_simpanan['simpanan_pokok'] ?? 0) + 
                                                             ($data_simpanan['simpanan_wajib'] ?? 0) + 
                                                             ($data_simpanan['simpanan_sukarela'] ?? 0);
                                            
                                            // Format tanggal
                                            $tanggal = $data_simpanan['tanggal'] ?? '';
                                            if ($tanggal && $tanggal != '0000-00-00') {
                                                $tanggal_formatted = date('d-m-Y', strtotime($tanggal));
                                            } else {
                                                $tanggal_formatted = '-';
                                            }
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $tanggal_formatted ?></td>
                                                <td><?= htmlspecialchars($nama_anggota) ?></td>
                                                <td>Rp <?= number_format($data_simpanan['simpanan_pokok'] ?? 0, 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($data_simpanan['simpanan_wajib'] ?? 0, 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($data_simpanan['simpanan_sukarela'] ?? 0, 0, ',', '.') ?></td>
                                                <td><b>Rp <?= number_format($total_simpanan, 0, ',', '.') ?></b></td>
                                                <td>Rp <?= number_format($data_simpanan['penarikan'] ?? 0, 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($total_hutang, 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($total_angsuran, 0, ',', '.') ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="10" class="text-center">Tidak ada data simpanan</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        
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
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../template2/js/off-canvas.js"></script>
  <script src="../template2/js/hoverable-collapse.js"></script>
  <script src="../template2/js/template.js"></script>
  <script src="../template2/js/settings.js"></script>
  <!-- endinject -->

  <script>
    // Print function
    function printTable() {
        window.print();
    }

    // Export to Excel function
    function exportTableToExcel(tableId) {
        var table = document.getElementById(tableId);
        var html = table.outerHTML;
        var url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(html);
        var downloadLink = document.createElement("a");
        downloadLink.href = url;
        downloadLink.download = "rekap_data.xls";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    $(document).ready(function() {
        // Initialize DataTable for rekapTable
        $('#rekapTable').DataTable({
            responsive: true,
            dom: 'Blfrtip',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-clipboard"></i> Copy',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="ti-file"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-dark btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data yang ditemukan",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // Initialize DataTable for rekapBulanan
        $('#rekapBulanan').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            info: false,
            ordering: false
        });
    });
  </script>
</body>
</html>