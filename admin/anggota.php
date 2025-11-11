<!DOCTYPE html>
<html lang="en">
<?php 
// CEK LOGIN - Perbaiki path atau buat file sementara
// include '../cek_login.php';

// KONEKSI DATABASE
include '../koneksi.php';

// PROSES TAMBAH ANGGOTA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telpon = mysqli_real_escape_string($koneksi, $_POST['telpon']);

    $query = "INSERT INTO anggota (nama, username, password, almat, telpon) 
              VALUES ('$nama', '$username', '$password', '$alamat', '$telpon')";

    if (mysqli_query($koneksi, $query)) {
        $pesan_sukses = "Data anggota berhasil ditambahkan!";
    } else {
        $pesan_error = "Error: " . mysqli_error($koneksi);
    }
}

// PROSES EDIT ANGGOTA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['edit_id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['edit_nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['edit_username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['edit_password']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['edit_alamat']);
    $telpon = mysqli_real_escape_string($koneksi, $_POST['edit_telpon']);

    $query = "UPDATE anggota SET 
              nama = '$nama', 
              username = '$username', 
              password = '$password', 
              almat = '$alamat', 
              telpon = '$telpon' 
              WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $pesan_sukses = "Data anggota berhasil diupdate!";
    } else {
        $pesan_error = "Error: " . mysqli_error($koneksi);
    }
}

// PROSES HAPUS ANGGOTA - DIPERBAIKI UNTUK FOREIGN KEY CONSTRAINT
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    if (is_numeric($id)) {
        // CEK APAKAH ANGGOTA MEMILIKI DATA PINJAMAN
        $cek_pinjaman = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pinjaman WHERE anggota_id = '$id'");
        $data_pinjaman = mysqli_fetch_assoc($cek_pinjaman);
        
        if ($data_pinjaman['total'] > 0) {
            $pesan_error = "Tidak dapat menghapus anggota karena memiliki data pinjaman!";
        } else {
            // Hapus data anggota jika tidak ada pinjaman
            $query = "DELETE FROM anggota WHERE id = '$id'";
            
            if (mysqli_query($koneksi, $query)) {
                if (mysqli_affected_rows($koneksi) > 0) {
                    $pesan_sukses = "Data anggota berhasil dihapus!";
                } else {
                    $pesan_error = "Data tidak ditemukan!";
                }
            } else {
                $pesan_error = "Error: " . mysqli_error($koneksi);
            }
        }
    } else {
        $pesan_error = "ID tidak valid!";
    }
}

// AMBIL DATA ANGGOTA UNTUK DITAMPILKAN
$query_anggota = mysqli_query($koneksi, "SELECT * FROM anggota");
$total_anggota = mysqli_num_rows($query_anggota);
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Koperasi - Data Anggota</title>
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
              <a class="dropdown-item">
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
      <!-- partial:setting_panel -->

      <!-- sidebar -->
      <?php include '../layout/sidebar.php'; ?>
      <!-- sidebar -->

      <div class="main-panel">
        <div class="content-wrapper">
          <!-- TAMPILKAN PESAN SUKSES/ERROR -->
          <?php if (isset($pesan_sukses)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?php echo $pesan_sukses; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <?php if (isset($pesan_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?php echo $pesan_error; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-2 mb-xl-0">
                  <h1 class="font-weight-bold">Halaman Data Anggota</h1>
                </div>
              </div>
            </div>
          </div>

          <div class="stretch-card grid-margin grid-margin-md-0 mb-5">
            <div class="card data-icon-card-primary">
              <div class="card-body">
                <p class="card-title text-white">Total Anggota</p>                      
                <div class="row">
                  <div class="col-8 text-white">
                    <h3><?php echo $total_anggota; ?> Anggota</h3>
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
                    <h4 class="card-title mb-0">Daftar Anggota</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahAnggota">
                      <i class="ti-plus menu-icon"></i> Tambah Anggota
                    </button>
                  </div>

                  <!-- Modal Tambah Anggota -->
                  <div class="modal fade" id="modalTambahAnggota" tabindex="-1" role="dialog" aria-labelledby="modalTambahAnggotaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalTambahAnggotaLabel">Tambah Data Anggota</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form method="POST">
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="nama">Nama</label>
                              <input type="text" class="form-control" name="nama" required>
                            </div>
                            <div class="form-group">
                              <label for="username">Username</label>
                              <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group">
                              <label for="password">Password</label>
                              <input type="text" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                              <label for="alamat">Alamat</label>
                              <input type="text" class="form-control" name="alamat" required>
                            </div>
                            <div class="form-group">
                              <label for="telpon">Telepon</label>
                              <input type="text" class="form-control" name="telpon" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- End Modal Tambah -->

                  <!-- Modal Edit Anggota -->
                  <div class="modal fade" id="modalEditAnggota" tabindex="-1" role="dialog" aria-labelledby="modalEditAnggotaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditAnggotaLabel">Edit Data Anggota</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form method="POST">
                          <div class="modal-body">
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="form-group">
                              <label for="edit_nama">Nama</label>
                              <input type="text" class="form-control" id="edit_nama" name="edit_nama" required>
                            </div>
                            <div class="form-group">
                              <label for="edit_username">Username</label>
                              <input type="text" class="form-control" id="edit_username" name="edit_username" required>
                            </div>
                            <div class="form-group">
                              <label for="edit_password">Password</label>
                              <input type="text" class="form-control" id="edit_password" name="edit_password" required>
                            </div>
                            <div class="form-group">
                              <label for="edit_alamat">Alamat</label>
                              <input type="text" class="form-control" id="edit_alamat" name="edit_alamat" required>
                            </div>
                            <div class="form-group">
                              <label for="edit_telpon">Telepon</label>
                              <input type="text" class="form-control" id="edit_telpon" name="edit_telpon" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- End Modal Edit -->

                  <div class="table-responsive">
                    <table id="tabelAnggota" class="table table-striped table-borderless" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama</th>
                          <th>Username</th>
                          <th>Password</th>
                          <th>Alamat</th>
                          <th>Telepon</th>
                          <th>Aksi</th>
                        </tr>  
                      </thead>
                      <tbody>
                        <?php
                        $no = 1;
                        // Reset pointer query untuk membaca ulang data
                        mysqli_data_seek($query_anggota, 0);
                        while($result = mysqli_fetch_array($query_anggota)){
                            // Cek apakah anggota memiliki pinjaman
                            $cek_pinjaman = mysqli_query($koneksi, "SELECT COUNT(*) as total_pinjaman FROM pinjaman WHERE anggota_id = '{$result['id']}'");
                            $data_pinjaman = mysqli_fetch_assoc($cek_pinjaman);
                            $punya_pinjaman = $data_pinjaman['total_pinjaman'] > 0;
                        ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td><?php echo htmlspecialchars($result['nama']); ?></td>
                          <td><?php echo htmlspecialchars($result['username']); ?></td>
                          <td><?php echo htmlspecialchars($result['password']); ?></td>
                          <td><?php echo htmlspecialchars($result['almat']); ?></td>
                          <td><?php echo htmlspecialchars($result['telpon']); ?></td>
                          <td>
                            <!-- Tombol Edit -->
                            <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="bukaModalEdit(
                                      '<?php echo $result['id']; ?>',
                                      '<?php echo addslashes($result['nama']); ?>',
                                      '<?php echo addslashes($result['username']); ?>',
                                      '<?php echo addslashes($result['password']); ?>',
                                      '<?php echo addslashes($result['almat']); ?>',
                                      '<?php echo addslashes($result['telpon']); ?>'
                                    )">
                              <i class="ti-pencil"></i> Edit
                            </button>
                            
                            <!-- Tombol Hapus -->
                            <?php if ($punya_pinjaman): ?>
                              <button type="button" class="btn btn-danger btn-sm" disabled title="Tidak dapat dihapus karena memiliki data pinjaman">
                                <i class="ti-trash"></i> Hapus
                              </button>
                            <?php else: ?>
                              <a href="?hapus=<?php echo $result['id']; ?>" 
                                 class="btn btn-danger btn-sm" 
                                 onclick="return confirm('Yakin ingin menghapus data anggota <?php echo addslashes($result['nama']); ?>?')">
                                <i class="ti-trash"></i> Hapus
                              </a>
                            <?php endif; ?>
                          </td>
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
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
          </div>
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span> 
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  
  <!-- jQuery pertama (sangat penting) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

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
    function bukaModalEdit(id, nama, username, password, alamat, telpon) {
        // Isi data ke form
        $('#edit_id').val(id);
        $('#edit_nama').val(nama);
        $('#edit_username').val(username);
        $('#edit_password').val(password);
        $('#edit_alamat').val(alamat);
        $('#edit_telpon').val(telpon);
        
        // Tampilkan modal
        $('#modalEditAnggota').modal('show');
    }

    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#tabelAnggota').DataTable({
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
            columnDefs: [
                {
                    targets: -1, // kolom aksi
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Menambahkan class untuk styling button
        $('.dt-buttons').addClass('mb-3');

        // Auto close alert setelah 5 detik
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
  </script>
</body>
</html>