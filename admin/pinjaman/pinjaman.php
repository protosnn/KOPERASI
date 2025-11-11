<!DOCTYPE html>
<html lang="en">
<?php include '../cek_login.php'; ?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Koperasi - Peminjaman</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="../../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../template2/vendors/ti-icons/css/themify-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../template2/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../template2/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="../../template2/images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../../template2/images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../../template2/images/faces/face28.jpg" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Settings
              </a>
              <a href="../../logout.php" class="dropdown-item">
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
      include '../../layout/sidebar.php';
      ?>
      <!-- sidebar -->

    <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-2 mb-xl-0">
                  <h1 class="font-weight-bold">Halaman Data Peminjaman</h1>
                </div>
              </div>
            </div>
          </div>
            <div class="stretch-card grid-margin grid-margin-md-0 mb-5">
            <div class="card data-icon-card-primary">
              <div class="card-body">
                <?php
                require_once '../../koneksi.php';
                $query_total = "SELECT COUNT(*) as total, SUM(jumlah_pinjaman) as total_pinjaman FROM pinjaman WHERE status='aktif'";
                $result_total = mysqli_query($koneksi, $query_total);
                $data_total = mysqli_fetch_assoc($result_total);
                ?>
                <p class="card-title text-white">Total Peminjaman Aktif</p>                      
                <div class="row">
                  <div class="col-8 text-white">
                    <h3><?php echo $data_total['total']; ?> Pinjaman</h3>
                    <p class="text-white font-weight-500 mb-0">
                      Total Nilai Pinjaman: Rp <?php echo number_format($data_total['total_pinjaman'], 0, ',', '.'); ?>
                    </p>
                  </div>
                  <div class="col-4 background-icon">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Peminjaman</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPinjaman">
                      <i class="ti-plus menu-icon"></i> Tambah Pinjaman
                    </button>
                  </div>
                  <!-- Modal Tambah Pinjaman -->
                  <div class="modal fade" id="modalTambahPinjaman" tabindex="-1" role="dialog" aria-labelledby="modalTambahPinjamanLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalTambahPinjamanLabel">Tambah Data Pinjaman</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="formTambahPinjaman" action="../../proses/proses_tambah_pinjaman.php" method="POST">
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="anggota_id">Nama Peminjam</label>
                              <select class="form-control" name="anggota_id" required>
                                <option value="">Pilih Anggota</option>
                                <?php
                                $query_anggota = mysqli_query($koneksi, "SELECT id, nama FROM anggota ORDER BY nama ASC");
                                while($anggota = mysqli_fetch_assoc($query_anggota)) {
                                  echo "<option value='".$anggota['id']."'>".$anggota['nama']."</option>";
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="jumlah_pinjaman">Jumlah Pinjaman</label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" name="jumlah_pinjaman" placeholder="Masukkan jumlah pinjaman" required>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="tenor">Tenor (Bulan)</label>
                                  <select class="form-control" name="tenor" required>
                                    <option value="">Pilih Tenor</option>
                                    <option value="10">10 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                    <option value="18">18 Bulan</option>
                                    <option value="24">24 Bulan</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="tanggal_pengajuan">Tanggal Pengajuan</label>
                                  <input type="date" class="form-control" name="tanggal_pengajuan" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="keterangan">Keterangan</label>
                              <textarea class="form-control" name="keterangan" rows="3" placeholder="Masukkan keterangan (opsional)"></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="action" value="add" class="btn btn-primary">Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- End Modal -->
                  <div class="table-responsive">
                    <table id="tabelPinjaman" class="table table-striped table-borderless" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Peminjam</th>
                          <th>Jumlah Pinjaman</th>
                          <th>Tenor</th>
                          <th>Tanggal Pengajuan</th>
                          <th>Tanggal ACC</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>  
                      </thead>
                      <tbody>
                        <?php
                            $query = "SELECT
                                  p.id AS id_pinjaman,
                                  a.nama AS nama_anggota,
                                  p.jumlah_pinjaman,
                                  p.tenor,
                                  p.status,
                                  p.tanggal_pengajuan,
                                  p.tanggal_acc,
                                  DATE_ADD(p.tanggal_pengajuan, INTERVAL p.tenor MONTH) AS tanggal_jatuh_tempo,
                                  CASE
                                      WHEN p.status = 'acc' AND CURDATE() > DATE_ADD(p.tanggal_pengajuan, INTERVAL p.tenor MONTH) 
                                          THEN 'pending'
                                      ELSE p.status
                                  END AS status_aktual
                              FROM pinjaman p
                              JOIN anggota a ON p.anggota_id = a.id
                              ORDER BY p.tanggal_pengajuan DESC";

                        $result = mysqli_query($koneksi, $query);
                        $no = 1;

                        while($row = mysqli_fetch_assoc($result)) {
                          $status_badge = '';
                          switch($row['status_aktual']) {
                            case 'acc':
                              $status_badge = 'badge-success';
                              break;
                            case 'pending':
                              $status_badge = 'badge-warning';
                              break;
                            default:
                              $status_badge = 'badge-secondary';
                          }

                          // Hitung sisa hari untuk jatuh tempo
                          $jatuh_tempo = new DateTime($row['tanggal_jatuh_tempo']);
                          $hari_ini = new DateTime();
                          $sisa_hari = $hari_ini->diff($jatuh_tempo)->format("%r%a");
                        ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td>
                            <div style="font-weight: bold;"><?php echo $row['nama_anggota']; ?></div>
                            <small class="text-muted">ID: <?php echo $row['id_pinjaman']; ?></small>
                          </td>
                          <td data-sort="<?php echo $row['jumlah_pinjaman']; ?>">
                            Rp <?php echo number_format($row['jumlah_pinjaman'], 0, ',', '.'); ?>
                          </td>
                          <td><?php echo $row['tenor']; ?> bulan</td>
                          
                          <td data-sort="<?php echo strtotime($row['tanggal_pengajuan']); ?>">
                            <?php echo date('d/m/Y', strtotime($row['tanggal_pengajuan'])); ?>
                          </td>
                          <td data-sort="<?php echo strtotime($row['tanggal_acc']); ?>">
                            <?php if($row['status'] == 'pending'): ?>
                              <div class="small text-muted">
                                Menunggu ACC
                              </div>

                            <?php else: ?>
                            <?php echo date('d/m/Y', strtotime($row['tanggal_acc'])); ?>
                            <?php endif; ?>
                          </td>
                          <td>
                            <div class="badge <?php echo $status_badge; ?>">
                              <?php echo ucfirst($row['status_aktual']); ?>
                            </div>
                          </td>
                          <td>
                            <div class="btn-group" role="group">
                              <?php if($row['status'] == 'pending'): ?>
                                <a href="../../proses/pinjaman_acc.php?pinjaman_id=<?php echo $row['id_pinjaman']; ?>" 
                                   class="btn btn-warning btn-sm" title="ACC Pinjaman">
                                  <i class="ti-check"></i>
                                </a>
                                <a href="javascript:void(0);" 
                                   onclick="if(confirm('Apakah Anda yakin ingin menolak pinjaman ini?')) window.location.href='../../proses/pinjaman_acc.php?action=tolak&pinjaman_id=<?php echo $row['id_pinjaman']; ?>'"
                                   class="btn btn-danger btn-sm" title="Tolak Pinjaman">
                                  <i class="ti-close"></i>
                                </a>
                              <?php endif; ?>
                              
                              <?php if($row['status'] == 'acc'): ?>
                                <button type="button" 
                                        class="btn btn-success btn-sm" 
                                        data-toggle="modal"
                                        data-target="#modalAngsuran<?php echo $row['id_pinjaman']; ?>"
                                        title="Lihat Angsuran">
                                  <i class="ti-money"></i>
                                </button>

                                <!-- Modal Angsuran untuk ID <?php echo $row['id_pinjaman']; ?> -->
                                <div class="modal fade" id="modalAngsuran<?php echo $row['id_pinjaman']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title"><i class="ti-money mr-2"></i>Detail Angsuran Pinjaman</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <?php
                                        // Query untuk mendapatkan data angsuran
                                        $query_angsuran = "SELECT id, nominal, tgl_pelunasan, status 
                                                         FROM angsuran 
                                                         WHERE pinjaman_id = ? 
                                                         ORDER BY tgl_pelunasan ASC";
                                        $stmt = mysqli_prepare($koneksi, $query_angsuran);
                                        mysqli_stmt_bind_param($stmt, "i", $row['id_pinjaman']);
                                        mysqli_stmt_execute($stmt);
                                        $result_angsuran = mysqli_stmt_get_result($stmt);
                                        
                                        // Hitung total angsuran
                                        $total_angsuran = 0;
                                        $angsuran_list = [];
                                        while($ang = mysqli_fetch_assoc($result_angsuran)) {
                                            $total_angsuran += $ang['nominal'];
                                            $angsuran_list[] = $ang;
                                        }
                                        
                                        $sisa_angsuran = $row['jumlah_pinjaman'] - $total_angsuran;
                                        ?>
                                        
                                        <div class="row mb-4">
                                          <div class="col-md-6">
                                            <div class="card bg-light">
                                              <div class="card-body">
                                                <h6 class="card-title text-primary">Informasi Pinjaman</h6>
                                                <table class="table table-borderless table-sm mb-0">
                                                  <tr>
                                                    <td style="width: 45%">Total Pinjaman</td>
                                                    <td style="width: 5%">:</td>
                                                    <td style="width: 50%">Rp <?php echo number_format($row['jumlah_pinjaman'], 0, ',', '.'); ?></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Total Angsuran</td>
                                                    <td>:</td>
                                                    <td>Rp <?php echo number_format($total_angsuran, 0, ',', '.'); ?></td>
                                                  </tr>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="card bg-light">
                                              <div class="card-body">
                                                <h6 class="card-title text-primary">Status Pembayaran</h6>
                                                <table class="table table-borderless table-sm mb-0">
                                                  <tr>
                                                    <td style="width: 45%">Sisa Angsuran</td>
                                                    <td style="width: 5%">:</td>
                                                    <td style="width: 50%">Rp <?php echo number_format($sisa_angsuran, 0, ',', '.'); ?></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Status</td>
                                                    <td>:</td>
                                                    <td>
                                                      <?php if($sisa_angsuran <= 0): ?>
                                                        <span class="badge badge-success">Lunas</span>
                                                      <?php else: ?>
                                                        <span class="badge badge-info">Aktif</span>
                                                      <?php endif; ?>
                                                    </td>
                                                  </tr>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        
                                        <div class="card">
                                          <div class="card-body">
                                            <h6 class="card-title text-primary mb-4">Riwayat Angsuran</h6>
                                            <div class="table-responsive">
                                              <table class="table table-striped table-bordered">
                                                <thead>
                                                  <tr class="bg-light">
                                                    <th style="width: 10%">No</th>
                                                    <th style="width: 30%">Tanggal Bayar</th>
                                                    <th style="width: 30%">Jumlah Bayar</th>
                                                    <th style="width: 30%">Status</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php if(empty($angsuran_list)): ?>
                                                    <tr>
                                                      <td colspan="4" class="text-center">Belum ada data angsuran</td>
                                                    </tr>
                                                  <?php else: ?>
                                                    <?php foreach($angsuran_list as $index => $angsuran): ?>
                                                      <tr>
                                                        <td><?php echo $index + 1; ?></td>
                                                        <td><?php echo $angsuran['tgl_pelunasan'] ? date('d/m/Y', strtotime($angsuran['tgl_pelunasan'])) : '-'; ?></td>
                                                        <td>Rp <?php echo number_format($angsuran['nominal'], 0, ',', '.'); ?></td>
                                                        <td>
                                                          <span class="badge <?php echo $angsuran['status'] == 'Lunas' ? 'badge-success' : 'badge-warning'; ?>">
                                                            <?php echo $angsuran['status'] ?: 'Belum Lunas'; ?>
                                                          </span>
                                                        </td>
                                                      </tr>
                                                    <?php endforeach; ?>
                                                  <?php endif; ?>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                          <i class="ti-close mr-2"></i>Tutup
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php endif; ?>
                              
                              <button type="button" 
                                      class="btn btn-info btn-sm" 
                                      onclick="window.location.href='detail_pinjaman.php?id=<?php echo $row['id_pinjaman']; ?>'"
                                      title="Detail Pinjaman">
                                <i class="ti-eye"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <script>
                    function accPinjaman(id) {
                      if(confirm('Apakah Anda yakin ingin ACC pinjaman?')) {
                        window.location.href = '../../proses/pinjaman_acc.php?id=' + id;
                      }
                    }
                    function tolakPinjaman(id) {
                      if(confirm('Apakah Anda yakin ingin menolak pinjaman?')) {
                        window.location.href = 'proses_tolak_pinjam.php?id=' + id;
                      }
                    }
                  </script>
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
  <!-- Modal Data Angsuran -->
  <div class="modal fade" id="modalAngsuran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="ti-money mr-2"></i>Detail Angsuran Pinjaman</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="card bg-light">
                <div class="card-body">
                  <h6 class="card-title text-primary">Informasi Pinjaman</h6>
                  <table class="table table-borderless table-sm mb-0">
                    <tr>
                      <td style="width: 45%">Total Pinjaman</td>
                      <td style="width: 5%">:</td>
                      <td id="totalPinjaman" style="width: 50%"></td>
                    </tr>
                    <tr>
                      <td>Total Angsuran</td>
                      <td>:</td>
                      <td id="totalAngsuran"></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card bg-light">
                <div class="card-body">
                  <h6 class="card-title text-primary">Status Pembayaran</h6>
                  <table class="table table-borderless table-sm mb-0">
                    <tr>
                      <td style="width: 45%">Sisa Angsuran</td>
                      <td style="width: 5%">:</td>
                      <td id="sisaAngsuran" style="width: 50%"></td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td>:</td>
                      <td id="statusPinjaman"></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-primary mb-4">Riwayat Angsuran</h6>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr class="bg-light">
                      <th style="width: 10%">No</th>
                      <th style="width: 30%">Tanggal Bayar</th>
                      <th style="width: 30%">Jumlah Bayar</th>
                      <th style="width: 30%">Status</th>
                    </tr>
                  </thead>
                  <tbody id="tableAngsuran">
                    <tr>
                      <td colspan="4" class="text-center">Memuat data...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="ti-close mr-2"></i>Tutup
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
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
  <script src="../../template2/js/off-canvas.js"></script>
  <script src="../../template2/js/hoverable-collapse.js"></script>
  <script src="../../template2/js/template.js"></script>
  <script src="../../template2/js/settings.js"></script>
  <!-- endinject -->

  <script>
    // Format currency
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    $(document).ready(function() {
        // Currency input formatting
        $('.currency').on('keyup', function() {
            $(this).val(formatRupiah($(this).val()));
        });

        // Form submission handling
        $('#formTambahPinjaman').on('submit', function(e) {
            var jumlahPinjaman = $('.currency').val().replace(/\./g, '');
            $('.currency').val(jumlahPinjaman);
        });

        // Set default date
        var today = new Date().toISOString().split('T')[0];
        $('input[name="tanggal_pengajuan"]').val(today);

        // Initialize DataTable
        var table = $('#tabelPinjaman').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-clipboard"></i> Copy',
                    className: 'btn btn-info btn-sm'
                },
                {
                    extend: 'csv',
                    text: '<i class="ti-file"></i> CSV',
                    className: 'btn btn-info btn-sm'
                },
                {
                    extend: 'excel',
                    text: '<i class="ti-file"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="ti-file"></i> PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-primary btn-sm'
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
            },
            order: [[5, "desc"]], // Urutkan berdasarkan tanggal pinjam secara descending
            columnDefs: [
                {
                    targets: [2, 4], // kolom jumlah pinjaman dan angsuran
                    render: function(data, type, row) {
                        return type === 'display' ? 
                            'Rp ' + new Intl.NumberFormat('id-ID').format(parseInt(data.replace(/[^\d]/g, ''))) :
                            data;
                    }
                },
                {
                    targets: -1, // kolom aksi
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Menambahkan class untuk styling button
        $('.dt-buttons').addClass('mb-3');
    });

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    // Fungsi untuk format rupiah di bagian lain yang mungkin membutuhkan
    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }
  </script>
</body>

</html>

