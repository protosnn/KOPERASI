<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include '../koneksi.php';

// Cek jika user sudah login sebagai anggota
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login_anggota") {
    header("location: login.php?pesan=belum_login");
    exit();
}

// Ambil data anggota dari session
$anggota_id = $_SESSION['id_anggota'];
$nama_anggota = $_SESSION['nama'];

$data_pinjaman = [];
$data_notifikasi = [];

try {
    // GET NOTIFIKASI UNTUK ANGGOTA
    $notifikasi_query = "SELECT 
                        n.id,
                        n.pinjaman_id,
                        n.pesan,
                        n.status,
                        n.tanggal_dibuat,
                        p.jumlah_pinjaman
                        FROM notifikasi n
                        JOIN pinjaman p ON n.pinjaman_id = p.id
                        WHERE n.anggota_id = " . intval($anggota_id) . " AND n.tipe = 'ditolak'
                        ORDER BY n.tanggal_dibuat DESC";
    
    $result_notifikasi = mysqli_query($koneksi, $notifikasi_query);
    if ($result_notifikasi) {
        while ($row = mysqli_fetch_assoc($result_notifikasi)) {
            $data_notifikasi[] = $row;
        }
    }

    // GET DETAIL PINJAMAN ANGGOTA
    $detail_pinjaman_query = "SELECT 
                            p.id,
                            p.tanggal_pengajuan,
                            p.tanggal_acc,
                            p.jumlah_pinjaman,
                            p.tenor,
                            p.status
                            FROM pinjaman p
                            WHERE p.anggota_id = " . intval($anggota_id) . "
                            ORDER BY p.tanggal_pengajuan DESC, p.id DESC";
    
    $result_detail_pinjaman = mysqli_query($koneksi, $detail_pinjaman_query);
    if ($result_detail_pinjaman) {
        while ($row = mysqli_fetch_assoc($result_detail_pinjaman)) {
            $data_pinjaman[] = $row;
        }
    }
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
}
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Rekap Saya - Koperasi</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../template2/js/select.dataTables.min.css">
  <style>
    .badge {
      padding: 6px 10px;
      font-size: 12px;
    }
    .badge-success {
      background-color: #00d25b;
      color: white;
    }
    .badge-warning {
      background-color: #ffab00;
      color: white;
    }
    .badge-danger {
      background-color: #fc424a;
      color: white;
    }
    .badge-info {
      background-color: #3699ff;
      color: white;
    }
    .total-row {
      background-color: #e8f5e8 !important;
      font-weight: bold;
    }
    .table th {
      background-color: #f8f9fa;
    }
    .text-right {
      text-align: right;
    }
    .card {
      margin-bottom: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .info-item {
      display: flex;
      align-items: flex-start;
    }
    .form-control, .form-control:focus {
      border-color: #ddd;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
      background-color: #667eea;
      border-color: #667eea;
    }
    .btn-primary:hover {
      background-color: #764ba2;
      border-color: #764ba2;
    }
    @media print {
      .no-print {
        display: none !important;
      }
      .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
      }
    }
  </style>
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../template2/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../template2/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php 
      $root_path = '../';
      include '../layout/header_anggota.php'; 
    ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper no-print">
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

      <!-- sidebar -->
      <?php include '../layout/sidebar_anggota.php'; ?>

      <!-- Main Panel -->
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Header -->
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-12 mb-2 mb-xl-0">
                  <h1 class="font-weight-bold">Pengajuan Pinjaman</h1>
                  <h6 class="font-weight-normal mb-0">Ajukan pinjaman kepada admin koperasi</h6>
                </div>
              </div>
            </div>
          </div>

          <!-- Alert Messages -->
          <?php
          if (isset($_GET['pesan'])) {
              if ($_GET['pesan'] == 'pengajuan_berhasil') {
                  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti-check"></i> <strong>Sukses!</strong> Pengajuan pinjaman Anda telah berhasil dikirim. Silahkan tunggu persetujuan dari admin.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
              } elseif ($_GET['pesan'] == 'pengajuan_gagal') {
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti-close"></i> <strong>Gagal!</strong> Pengajuan pinjaman gagal. Silahkan coba lagi.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
              } elseif ($_GET['pesan'] == 'data_tidak_lengkap') {
                  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="ti-alert"></i> <strong>Peringatan!</strong> Data tidak lengkap. Silahkan isi semua field.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
              } elseif ($_GET['pesan'] == 'jumlah_minimal') {
                  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="ti-alert"></i> <strong>Peringatan!</strong> Jumlah pinjaman minimal adalah Rp 100.000.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
              }
          }
          ?>

          <!-- Notifikasi Pinjaman Ditolak -->
          <?php if (count($data_notifikasi) > 0): ?>
            <div class="row mb-4">
              <div class="col-md-12">
                <?php foreach ($data_notifikasi as $notif): ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                      <div>
                        <i class="ti-alert"></i> <strong>Pengajuan Pinjaman Ditolak!</strong><br>
                        <small>Pengajuan pinjaman Rp <?= number_format($notif['jumlah_pinjaman'], 0, ',', '.') ?> telah ditolak oleh admin.</small><br>
                        <small class="text-muted">Tanggal: <?= date('d-m-Y H:i', strtotime($notif['tanggal_dibuat'])) ?></small>
                      </div>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-left: 20px;">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Form Pengajuan Pinjaman -->
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4">Form Pengajuan Pinjaman</h4>
                  <form id="formPengajuan" method="POST" action="../proses/proses_tambah_pinjaman_anggota.php">
                    <div class="form-group">
                      <label for="nama">Nama Anggota</label>
                      <input type="text" class="form-control" id="nama" value="<?php echo $nama_anggota; ?>" disabled>
                      <input type="hidden" name="anggota_id" value="<?php echo $anggota_id; ?>">
                    </div>

                    <div class="form-group">
                      <label for="jumlah_pinjaman">Jumlah Pinjaman (Rp)</label>
                      <input type="number" class="form-control" id="jumlah_pinjaman" name="jumlah_pinjaman" placeholder="Contoh: 5000000" required min="100000" step="100000">
                      <small class="form-text text-muted">Minimal Rp 100.000</small>
                    </div>

                    <div class="form-group">
                      <label for="lama_angsuran">Lama Angsuran (Bulan)</label>
                      <select class="form-control" id="lama_angsuran" name="lama_angsuran" required>
                        <option value="">-- Pilih Lama Angsuran --</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">12 Bulan</option>
                        <option value="24">24 Bulan</option>
                        <option value="36">36 Bulan</option>
                      </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                      <i class="ti-pencil-alt"></i> Ajukan Pinjaman
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <!-- Info Box -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                  <h4 class="card-title mb-4" style="color: white;">Informasi Penting</h4>
                  <div class="info-item mb-3">
                    <i class="ti-info-alt" style="font-size: 1.2rem;"></i>
                    <span class="ml-2"><strong>Proses Persetujuan:</strong><br>Pengajuan Anda akan diproses oleh admin dalam waktu 1-2 hari kerja.</span>
                  </div>
                  <div class="info-item mb-3">
                    <i class="ti-money" style="font-size: 1.2rem;"></i>
                    <span class="ml-2"><strong>Perhitungan Bunga:</strong><br>Bunga pinjaman dihitung berdasarkan tenor dan kebijakan koperasi.</span>
                  </div>
                  <div class="info-item mb-3">
                    <i class="ti-alert" style="font-size: 1.2rem;"></i>
                    <span class="ml-2"><strong>Catatan:</strong><br>Pastikan data yang Anda masukkan benar sebelum mengajukan.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Riwayat Pengajuan Pinjaman -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4">Riwayat Pengajuan Pinjaman</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-light">
                        <tr>
                          <th>No</th>
                          <th>Tanggal Pengajuan</th>
                          <th>Tanggal ACC</th>
                          <th class="text-right">Jumlah Pinjaman</th>
                          <th>Tenor</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($data_pinjaman) > 0): ?>
                          <?php $no = 1; $total_pinjaman = 0; ?>
                          <?php foreach ($data_pinjaman as $pinjaman): ?>
                            <?php
                            $tgl_pengajuan = ($pinjaman['tanggal_pengajuan'] && $pinjaman['tanggal_pengajuan'] != '0000-00-00') ? date('d-m-Y H:i', strtotime($pinjaman['tanggal_pengajuan'])) : '-';
                            $tgl_acc = ($pinjaman['tanggal_acc'] && $pinjaman['tanggal_acc'] != '0000-00-00') ? date('d-m-Y H:i', strtotime($pinjaman['tanggal_acc'])) : '-';
                            $total_pinjaman += $pinjaman['jumlah_pinjaman'];
                            
                            $status_badge = '';
                            $status_text = '';
                            if($pinjaman['status'] == 'acc') {
                                $status_badge = 'badge-success';
                                $status_text = '<i class="ti-check"></i> DISETUJUI';
                            } elseif($pinjaman['status'] == 'pending') {
                                $status_badge = 'badge-warning';
                                $status_text = '<i class="ti-time"></i> MENUNGGU';
                            } else {
                                $status_badge = 'badge-danger';
                                $status_text = '<i class="ti-close"></i> DITOLAK';
                            }
                            ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $tgl_pengajuan ?></td>
                              <td><?= $tgl_acc ?></td>
                              <td class="text-right">Rp <?= number_format($pinjaman['jumlah_pinjaman'] ?? 0, 0, ',', '.') ?></td>
                              <td><?= $pinjaman['tenor'] ?? 0 ?> Bulan</td>
                              <td>
                                <span class="badge <?= $status_badge ?>">
                                  <?= $status_text ?>
                                </span>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr class="table-primary" style="background-color: #f0f0f0; font-weight: bold;">
                            <td colspan="3" class="text-right">Total Pinjaman:</td>
                            <td class="text-right">Rp <?= number_format($total_pinjaman, 0, ',', '.') ?></td>
                            <td colspan="2"></td>
                          </tr>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                              <i class="ti-package" style="font-size: 2rem;"></i><br>
                              Tidak ada data pengajuan pinjaman
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <footer class="footer no-print">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025. Koperasi Management System.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Rekap Data Anggota</span>
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
</body>
</html>
<?php
// Close database connection
mysqli_close($koneksi);
?>