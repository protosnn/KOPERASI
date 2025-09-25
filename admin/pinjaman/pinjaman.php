<!DOCTYPE html>
<html lang="en">

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
                  <h1 class="font-weight-bold">Halaman Peminjaman</h1>
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
                  <p class="card-title mb-2">Daftar Peminjaman</p>
                  <div class="table-responsive">
                    <table id="tabelPinjaman" class="table table-striped table-borderless" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Peminjam</th>
                          <th>Jumlah Pinjaman</th>
                          <th>Tenor</th>
                          <th>Angsuran/bulan</th>
                          <th>Tanggal Pinjam</th>
                          <th>Jatuh Tempo</th>
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
                                  p.angsuran_per_bulan,
                                  p.tanggal_pinjam,
                                  DATE_ADD(p.tanggal_pinjam, INTERVAL p.tenor MONTH) AS tanggal_jatuh_tempo,
                                  CASE
                                      WHEN p.status = 'aktif' AND CURDATE() > DATE_ADD(p.tanggal_pinjam, INTERVAL p.tenor MONTH) 
                                          THEN 'terlambat'
                                      ELSE p.status
                                  END AS status_aktual
                              FROM pinjaman p
                              JOIN anggota a ON p.anggota_id = a.id
                              ORDER BY p.tanggal_pinjam DESC";

                        $result = mysqli_query($koneksi, $query);
                        $no = 1;

                        while($row = mysqli_fetch_assoc($result)) {
                          $status_badge = '';
                          switch($row['status_aktual']) {
                            case 'aktif':
                              $status_badge = 'badge-success';
                              break;
                            case 'lunas':
                              $status_badge = 'badge-info';
                              break;
                            case 'macet':
                              $status_badge = 'badge-danger';
                              break;
                            case 'terlambat':
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
                          <td data-sort="<?php echo $row['angsuran_per_bulan']; ?>">
                            Rp <?php echo number_format($row['angsuran_per_bulan'], 0, ',', '.'); ?>
                          </td>
                          <td data-sort="<?php echo strtotime($row['tanggal_pinjam']); ?>">
                            <?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?>
                          </td>
                          <td data-sort="<?php echo strtotime($row['tanggal_jatuh_tempo']); ?>">
                            <?php echo date('d/m/Y', strtotime($row['tanggal_jatuh_tempo'])); ?>
                            <?php if($row['status'] == 'aktif'): ?>
                              <div class="small <?php echo $sisa_hari < 0 ? 'text-danger' : 'text-muted'; ?>">
                                <?php echo $sisa_hari < 0 ? abs($sisa_hari).' hari terlambat' : $sisa_hari.' hari lagi'; ?>
                              </div>
                            <?php endif; ?>
                          </td>
                          <td>
                            <div class="badge <?php echo $status_badge; ?>">
                              <?php echo ucfirst($row['status_aktual']); ?>
                            </div>
                          </td>
                          <td>
                            <div class="btn-group" role="group">
                              <a href="detail_pinjaman.php?id=<?php echo $row['id_pinjaman']; ?>" 
                                 class="btn btn-info btn-sm" title="Detail">
                                <i class="ti-eye"></i>
                              </a>
                              <?php if($row['status'] == 'aktif'): ?>
                              <a href="../angsuran/bayar_angsuran.php?id=<?php echo $row['id_pinjaman']; ?>" 
                                 class="btn btn-success btn-sm" title="Bayar Angsuran">
                                <i class="ti-money"></i>
                              </a>
                              <a href="javascript:void(0);" 
                                 onclick="confirmMacet(<?php echo $row['id_pinjaman']; ?>)"
                                 class="btn btn-danger btn-sm" title="Tandai Macet">
                                <i class="ti-close"></i>
                              </a>
                              <?php endif; ?>
                            </div>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <script>
                    function confirmMacet(id) {
                      if(confirm('Apakah Anda yakin ingin menandai pinjaman ini sebagai macet?')) {
                        window.location.href = 'proses_macet.php?id=' + id;
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
    $(document).ready(function() {
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
  </script>
</body>

</html>

