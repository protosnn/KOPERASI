<!DOCTYPE html>
<html lang="en">
<?php include '../cek_login.php'; ?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Koperasi - Peminjaman</title>
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
                  <h1 class="font-weight-bold">Halaman Data Peminjaman</h1>
                </div>
              </div>
            </div>
          </div>
            <div class="stretch-card grid-margin grid-margin-md-0 mb-5">
            <div class="card data-icon-card-primary">
              <div class="card-body">
                <?php
                require_once '../koneksi.php';
                $query_total = "SELECT COUNT(*) as total, SUM(jumlah_pinjaman) as total_pinjaman FROM pinjaman WHERE status='aktif'";
                $result_total = mysqli_query($koneksi, $query_total);
                $data_total = mysqli_fetch_assoc($result_total);
                ?>
                <p class="card-title text-white">Total Anggota Aktif</p>                      
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
                    <h4 class="card-title mb-0">Daftar Anggota</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPinjaman">
                      <i class="ti-plus menu-icon"></i> Tambah Anggota
                    </button>
                  </div>
                  <!-- Modal Tambah Pinjaman -->
                  <div class="modal fade" id="modalTambahPinjaman" tabindex="-1" role="dialog" aria-labelledby="modalTambahPinjamanLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalTambahPinjamanLabel">Tambah Data Anggota</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="formTambahAnggota" action="../proses/proses_tambah_anggota.php" method="POST">
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="anggota_id">Nama</label>
                              <input type="text" class="form-control" name="nama_anggota" required>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="anggota_id">Password</label>
                              <input type="text" class="form-control" name="password" required>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="anggota_id">Alamat</label>
                              <input type="text" class="form-control" name="alamat" required>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="anggota_id">Telepon</label>
                              <input type="text" class="form-control" name="telpon" required>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="anggota_id">Username</label>
                              <input type="text" class="form-control" name="username" required>
                              </select>
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
                    <table id="tabelAnggota" class="table table-striped table-borderless" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Anggota</th>
                          <th>Password</th>
                          <th>Alamat</th>
                          <th>Telepon</th>
                          <th>Username</th>
                        </tr>  
                      </thead>
                      <tbody>
                       
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
 

  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
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
  <script src="../template2/js/off-canvas.js"></script>
  <script src="../template2/js/hoverable-collapse.js"></script>
  <script src="../template2/js/template.js"></script>
  <script src="../template2/js/settings.js"></script>
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

    function lihatAngsuran(pinjamanId) {
        // Ambil data pinjaman dan angsuran
        $.ajax({
            url: '../proses/get_angsuran.php',
            type: 'GET',
            data: {
                pinjaman_id: pinjamanId
            },
            dataType: 'json',
            success: function(response) {
                var data = response;
                
                // Update informasi pinjaman
                $('#totalPinjaman').text(formatRupiah(data.pinjaman.jumlah_pinjaman));
                $('#totalAngsuran').text(formatRupiah(data.total_angsuran));
                $('#sisaAngsuran').text(formatRupiah(data.pinjaman.jumlah_pinjaman - data.total_angsuran));
                
                var statusBadge = '';
                if (data.pinjaman.status === 'acc') {
                    statusBadge = '<span class="badge badge-success">Aktif</span>';
                } else if (data.pinjaman.status === 'lunas') {
                    statusBadge = '<span class="badge badge-info">Lunas</span>';
                }
                $('#statusPinjaman').html(statusBadge);

                // Isi tabel angsuran
                var html = '';
                data.angsuran.forEach(function(item, index) {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.tgl_pelunasan}</td>
                            <td>${formatRupiah(item.nominal)}</td>
                            <td>${item.status || '-'}</td>
                        </tr>
                    `;
                });
                
                $('#tableAngsuran').html(html);
                
                // Tampilkan modal
                $('#modalAngsuran').modal('show');
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan saat mengambil data angsuran');
            }
        });
    }
  </script>
</body>

</html>

