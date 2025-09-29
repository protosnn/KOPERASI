
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap4.min.css">
  <link rel="stylesheet" href="../../template2/vendors/ti-icons/css/themify-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../template2/css/vertical-layout-light/style.css">
  <style>
    .dataTables_wrapper .dataTables_filter input {
      margin-left: 0.5em;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      padding: 0.375rem 0.75rem;
    }
    .dataTables_wrapper .dataTables_length select {
      border: 1px solid #dee2e6;
      border-radius: 4px;
      padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    }
    .dataTables_wrapper .dt-buttons .btn {
      margin-right: 5px;
    }
    .btn i {
      font-size: 12px;
    }
    .btn-info i, .btn-warning i, .btn-danger i {
      color: white;
    }
    .table td {
      vertical-align: middle;
      white-space: nowrap;
    }
    .modal-header {
      background-color: #4B49AC;
      color: white;
    }
    .table th select {
      width: 100%;
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-top: 5px;
      font-size: 12px;
    }
    .dt-button-collection {
      width: auto !important;
    }
    .dt-button-collection .dt-button {
      display: block;
      width: 100%;
      margin: 2px 0;
    }
    .table th {
      white-space: normal;
    }
  </style>
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
                  <h1 class="font-weight-bold">Halaman Pemasukan</h1>
                </div>
              </div>
            </div>
          </div>
          <div class="stretch-card grid-margin grid-margin-md-0 mb-5">
            <div class="card data-icon-card-primary">
              <div class="card-body">
                <?php
                require_once '../../koneksi.php';
                $query_total = "SELECT COUNT(*) as total, SUM(nominal) as total_nominal FROM simpanan";
                $result_total = mysqli_query($koneksi, $query_total);
                $data_total = mysqli_fetch_assoc($result_total);
                ?>
                <p class="card-title text-white">Total Pemasukan</p>                      
                <div class="row">
                  <div class="col-8 text-white">
                    <h3><?php echo $data_total['total']; ?> Pemasukan</h3>
                    <p class="text-white font-weight-500 mb-0">
                      Total Pemasukan: Rp <?php echo number_format($data_total['total_nominal'], 0, ',', '.'); ?>
                    </p>
                  </div>
                  <div class="col-4 background-icon">
                    <i class="ti-money text-white"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-4">
                  <h4 class="card-title mb-0">Data Pemasukan</h4>
                  <button type="button" class="btn btn-primary" onclick="window.location.href='tambah_pemasukan.php'">
                    <i class="ti-plus menu-icon"></i> Tambah Pemasukan
                  </button>
                </div>
                <div class="table-responsive">
                  <table id="tablePemasukan" class="table table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Jenis Simpanan</th>
                        <th>Nama Anggota</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = "SELECT s.*, j.nama, a.nama as nama_anggota 
                               FROM simpanan s 
                               JOIN jenissimpanan j ON s.jenissimpanan_id = j.id
                               JOIN anggota a ON s.anggota_id = a.id 
                               ORDER BY s.tanggal DESC";
                      $result = mysqli_query($koneksi, $query);
                      $no = 1;
                      while($row = mysqli_fetch_assoc($result)) {
                      ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['nama_anggota']; ?></td>
                        <td>Rp <?php echo number_format($row['nominal'], 0, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                        <td>
                          <button type="button" class="btn btn-sm btn-warning" onclick="window.location.href='edit_pemasukan.php?id=<?php echo $row['id']; ?>'">
                            <i class="ti-pencil"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Apakah anda yakin ingin menghapus data ini?')){window.location.href='../../proses/proses_pemasukan.php?action=delete&id=<?php echo $row['id']; ?>'}">
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
  <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../../template2/js/off-canvas.js"></script>
  <script src="../../template2/js/hoverable-collapse.js"></script>
  <script src="../../template2/js/template.js"></script>
  <script src="../../template2/js/settings.js"></script>
  <!-- endinject -->

  <script>
    $(document).ready(function() {
        var table = $('#tablePemasukan').DataTable({
            lengthChange: true,
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="ti-printer"></i> Export',
                    className: 'btn btn-primary btn-sm',
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="ti-clipboard"></i> Copy',
                            className: 'btn btn-info btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="ti-file"></i> Excel',
                            className: 'btn btn-success btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="ti-file"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="ti-printer"></i> Print',
                            className: 'btn btn-warning btn-sm',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        }
                    ]
                }
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Detail untuk ' + data[1];
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            },
            order: [[4, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                buttons: {
                    collection: 'Pilih Export'
                }
            },
            columnDefs: [
                {
                    targets: 3,
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(
                                parseInt(data.toString().replace(/[\D]/g, ''))
                            );
                        }
                        return data;
                    }
                },
                {
                    targets: -1,
                    orderable: false,
                    className: 'text-center'
                }
            ],
            initComplete: function() {
                table.buttons().container().appendTo('#tablePemasukan_wrapper .col-md-6:eq(0)');
                
                // Menambahkan filter dropdown untuk Jenis Simpanan
                this.api().columns(2).every(function() {
                    var column = this;
                    var select = $('<select class="form-control form-control-sm"><option value="">Semua Anggota</option></select>')
                        .appendTo($(column.header()))
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^'+val+'$' : '', true, false).draw();
                        });
                    
                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="'+d+'">'+d+'</option>');
                    });
                });
            }
        });

        // Menambahkan class untuk styling
        $('.dataTables_wrapper .dt-buttons').addClass('mb-3');
        
        // Fixed header
        new $.fn.dataTable.FixedHeader(table);
    });
  </script>
  <!-- End custom js for this page-->
</body>

</html>

