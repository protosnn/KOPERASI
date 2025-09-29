<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../template2/js/select.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
  <style>
    .badge {
      padding: 6px 10px;
      font-size: 12px;
    }
    .badge-success {
      background-color: #00d25b;
    }
    .badge-warning {
      background-color: #ffab00;
    }
    .btn i {
      font-size: 12px;
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
                <div class="col-12 col-xl-8 mb-2 mb-xl-0">
                  <h1 class="font-weight-bold">Halaman Data Angsuran</h1>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Data Angsuran</h4>
                  <div class="table-responsive">
                    <table id="tableAngsuran" class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>ID Pinjaman</th>
                          <th>Nominal</th>
                          <th>Tanggal Jatuh Tempo</th>
                          <th>Tanggal Pelunasan</th>
                          <th>Status</th>
                          <th>Bukti</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        include '../koneksi.php';
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT a.*, p.anggota_id 
                                                        FROM angsuran a
                                                        LEFT JOIN pinjaman p ON a.pinjaman_id = p.id 
                                                        ORDER BY a.tgl_jatuhtempo DESC");
                        while($row = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td><?php echo $row['pinjaman_id']; ?></td>
                          <td>Rp <?php echo number_format($row['nominal'],0,',','.'); ?></td>
                          <td><?php echo date('d/m/Y', strtotime($row['tgl_jatuhtempo'])); ?></td>
                          <td><?php echo $row['tgl_pelunasan'] ? date('d/m/Y', strtotime($row['tgl_pelunasan'])) : '-'; ?></td>
                          <td>
                            <?php if($row['status'] == 'lunas'){ ?>
                              <span class="badge badge-success">Lunas</span>
                            <?php } else { ?>
                              <span class="badge badge-warning">Belum Lunas</span>
                            <?php } ?>
                          </td>
                          <td>
                            <?php if($row['bukti']){ ?>
                              <button type="button" class="btn btn-sm btn-info" onclick="window.open('../uploads/bukti_pembayaran/<?php echo $row['bukti']; ?>', '_blank')">
                                <i class="ti-file"></i>
                              </button>
                            <?php } else { ?>
                              -
                            <?php } ?>
                          </td>
                          <td>
                            <button type="button" class="btn btn-sm btn-info" onclick="window.location.href='detail_angsuran.php?id=<?php echo $row['id']; ?>'">
                              <i class="ti-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" onclick="window.location.href='edit_angsuran.php?id=<?php echo $row['id']; ?>'">
                              <i class="ti-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini?')){window.location.href='../proses/proses_angsuran.php?action=delete&id=<?php echo $row['id']; ?>'}">
                              <i class="ti-trash"></i>
                            </button>
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

            <h2 class="font-weight-bold mb-5">Verifikasi Angsuran</h2>
            <div class=" grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Form Vrifikasi Angsuran</h4>
                  <p class="card-description">
                    Form Verifikasi Angsuran
                  </p>
                  <form class="forms-sample">
                    <div class="form-group">
                      <div class="form-group">
                        <label for="anggota_id">Nama Peminjam</label>
                        <select class="form-control" name="anggota_id">
                          <option>Pemi</option>
                          <option>Aziz</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="Tanggal">Tanggal Peminjaman</label>
                      <input type="date" class="form-control" name="tanggal_pinjam">
                    </div>
                    <div class="form-group">
                      <div class="form-group">
                        <label for="status">Status Peminjam</label>
                        <select class="form-control" name="status">
                          <option>Aktif</option>
                          <option>Lunas</option>
                          <option>Terlambat</option>
                          <option>Macet</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="jumlah_pinjaman">Nominal Peminjaman</label>
                      <input type="number" class="form-control" name="jumlah_pinjaman" placeholder="Masukkan Nominal Peminjaman">
                    </div>
                    <div class="form-group">
                      <div class="form-group">
                        <label for="tenor">Tenor</label>
                        <select class="form-control" name="tenor">
                          <option>10 Bulan</option>
                          <option>12 Bulan</option>
                          <option>20 Bulan</option>
                          <option>24 Bulan</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
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
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../template2/vendors/chart.js/Chart.min.js"></script>
  <script src="../template2/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="../template2/js/dataTables.select.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#tableAngsuran').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
          {
            extend: 'copy',
            className: 'btn btn-sm btn-primary'
          },
          {
            extend: 'csv',
            className: 'btn btn-sm btn-primary'
          },
          {
            extend: 'excel',
            className: 'btn btn-sm btn-primary'
          },
          {
            extend: 'pdf',
            className: 'btn btn-sm btn-primary'
          },
          {
            extend: 'print',
            className: 'btn btn-sm btn-primary'
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
    });
  </script>
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
</body>

</html>

