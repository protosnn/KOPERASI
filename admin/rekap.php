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
                $query_total = "SELECT COUNT(*) as total, SUM(nominal) as total_saldo FROM simpanan";
                $result_total = mysqli_query($koneksi, $query_total);
                $data_total = mysqli_fetch_assoc($result_total);
                ?>
                <p class="card-title text-white">Total Saldo</p>                      
                <div class="row">
                  <div class="col-8 text-white">
                    <h3><?php echo $data_total['total']; ?> Pemasukan</h3>
                    <p class="text-white font-weight-500 mb-0">
                      Total Nilai Saldo: Rp <?php echo number_format($data_total['total_saldo'], 0, ',', '.'); ?>
                    </p>
                  </div>
                  <div class="col-4 background-icon">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Tabel Saldo ksp -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-3">Daftar Nominal Pemasukan</h4>
                  </div>
                  <table class="table table-bordered" border = "1">
                    <thead>
                      <tr>
                        <th><b>Jumlah Simpanan Pokok</b></th>
                        <th>Jumlah Simpanan Wajib</th>
                        <th>Jumlah Simpanan Sukarela</th>
                      </tr>
                      <?php
                        $query_jumlah_simpanan_pokok= mysqli_query($koneksi, "select sum(nominal) as total from simpanan where jenissimpanan_id=1");
                        $jumlah_simpanan_pokok = mysqli_fetch_assoc($query_jumlah_simpanan_pokok);

                        $query_jumlah_simpanan_wajib= mysqli_query($koneksi, "select sum(nominal) as total from simpanan where jenissimpanan_id=2");
                        $jumlah_simpanan_wajib = mysqli_fetch_assoc($query_jumlah_simpanan_wajib);

                        $query_jumlah_simpanan_sukarela= mysqli_query($koneksi, "select sum(nominal) as total from simpanan where jenissimpanan_id=3");
                        $jumlah_simpanan_sukarela = mysqli_fetch_assoc($query_jumlah_simpanan_sukarela);

                        $query_jumlah_simpanan= mysqli_query($koneksi, "select sum(nominal) as total from simpanan");
                        $jumlah_simpanan = mysqli_fetch_assoc($query_jumlah_simpanan);

                        $query_jumlah_pinjaman_acc= mysqli_query($koneksi, "select sum(jumlah_pinjaman) as total from pinjaman where status='acc'");
                        $jumlah_pinjaman_acc = mysqli_fetch_assoc($query_jumlah_pinjaman_acc);

                        $query_jumlah_angsuran_lunas= mysqli_query($koneksi, "select sum(nominal) as total from angsuran where status='l'");
                        $jumlah_angsuran_lunas= mysqli_fetch_assoc($query_jumlah_angsuran_lunas);

                        $query_jumlah_bunga_lunas= mysqli_query($koneksi, "select sum(bunga) as total from angsuran where status='l'");
                        $jumlah_bunga_lunas= mysqli_fetch_assoc($query_jumlah_bunga_lunas);
                      ?>
                    </thead>
                    <tbody>
                      <tr>
                        <th>Rp <?=$jumlah_simpanan_pokok['total']?></th>
                        <th>Rp <?=$jumlah_simpanan_wajib['total']?></th>
                        <th>Rp <?=$jumlah_simpanan_sukarela['total']?></th>
                      </tr>
                      <tr>
                        <th colspan=2>Total Simpanan</th>
                        <th><?="Rp ".number_format($jumlah_simpanan['total'],2,',','.')?></th>
                      </tr>
                      <tr>
                        <th colspan=2>Total Pinjaman</th>
                        <th><?="Rp ".number_format($jumlah_pinjaman_acc['total'],2,',','.')?></th>
                      </tr>
                      <tr>
                        <th colspan=2>Total Angsuran Lunas</th>
                        <th><?="Rp ".number_format($jumlah_angsuran_lunas['total'],2,',','.')?></th>
                      </tr>
                      <tr>
                        <th colspan=2>Total Bunga Lunas</th>
                        <th><?="Rp ".number_format($jumlah_angsuran_lunas['total'],2,',','.')?></th>
                      </tr>
                      <tr>
                        <th colspan=2>Total Saldo</th>
                        <th><?="Rp ".number_format($jumlah_simpanan['total']-$jumlah_pinjaman_acc['total']+$jumlah_angsuran_lunas['total']+$jumlah_bunga_lunas['total'],2,',','.')?></th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- Akhir Tabel Saldo ksp -->

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Data Pemasukan</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahSimpanan">
                      <i class="ti-plus menu-icon"></i> Tambah Pemasukan
                    </button>
                  </div>
                  <!-- Modal Tambah Pinjaman -->
                  <div class="modal fade" id="modalTambahSimpanan" tabindex="-1" role="dialog" aria-labelledby="modalTambahSimpananLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalTambahSimpananLabel">Tambah Data Pemasukan</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="formTambahSimpanan" action="../proses/proses_tambah_simpanan.php" method="POST">
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="anggota_id">Nama Anggota</label>
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
                              <label for="nominal">Nominal</label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control currency" name="nominal" placeholder="Masukkan Nominal Untuk Simpanan Sukarela">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="jenissimpanan_id">Jenis simpanan</label>
                                  <select class="form-control" name="jenissimpanan_id" required>
                                    <option value="">Pilih Jenis Simpanan</option>
                                    <?php
                                    $query_jenis = mysqli_query($koneksi, "select id, nama from jenissimpanan order by nama asc");
                                    while($jenis = mysqli_fetch_assoc($query_jenis)) {
                                      echo "<option value='".$jenis['id']."'>".$jenis['nama']."</option>";
                                    }
                                    ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="tanggal">Tanggal</label>
                                  <input type="date" class="form-control" name="tanggal" required>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" name="action" value="add" class="btn btn-primary">Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- End Modal -->
                  <div class="table-responsive">
                    <table id="tabelSimpanan" class="table table-striped table-borderless" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Anggota</th>
                          <th>Simpanan</th>
                          <th>Nominal</th>
                          <th>Tanggal</th>

                        </tr>  
                      </thead>
                      <tbody>
                        <?php
                            $query =mysqli_query($koneksi, "SELECT
                                simpanan.*,
                                anggota.nama AS nama_anggota,
                                jenissimpanan.nama AS nama_simpanan
                            FROM simpanan
                            LEFT JOIN anggota
                                ON simpanan.anggota_id = anggota.id
                            LEFT JOIN jenissimpanan
                                ON simpanan.jenissimpanan_id = jenissimpanan.id
                                ") ;
                            
                        while($result = mysqli_fetch_array($query)){

                        
                        $no = 1;
                        
                          
                        ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td>
                            <div style="font-weight: bold;"><?php echo $result['nama_anggota']; ?></div>
                            <small class="text-muted">ID: <?php echo $result['id']; ?></small>
                          </td>
                          <td>
                            <div style="font-weight: bold;"><?php echo $result['nama_simpanan']; ?></div>
                          </td>
                          <td data-sort="<?php echo $result['nominal']; ?>">
                            Rp <?php echo number_format($result['nominal'], 0, ',', '.'); ?>
                          </td>                          
                          <td data-sort="<?php echo strtotime($result['tanggal']); ?>">
                            <?php echo date('d/m/Y', strtotime($result['tanggal'])); ?>
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
        $('#formTambahSimpanan').on('submit', function(e) {
            var nominal = $('.currency').val().replace(/\./g, '');
            $('.currency').val(nominal);
        });

        // Set default date
        var today = new Date().toISOString().split('T')[0];
        $('input[name="tanggal"]').val(today);

        // Initialize DataTable
        var table = $('#tabelSimpanan').DataTable({
            responsive: true,
            dom: 'Blfrtip',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="ti-clipboard"></i> Copy',
                            className: 'btn btn-info btn-sm mb-5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="ti-file"></i> Excel',
                            className: 'btn btn-success btn-sm mb-5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="ti-file"></i> PDF',
                            className: 'btn btn-danger btn-sm mb-5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="ti-printer"></i> Print',
                            className: 'btn btn-dark btn-sm mb-5',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
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
            },
            order: [[4, "desc"]], // Urutkan berdasarkan tanggal secara descending
            columnDefs: [
                {
                    targets: 0, // kolom nomor
                    orderable: false
                },
                {
                    targets: 3, // kolom nominal
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            return data;
                        }
                        return data.replace(/[^\d]/g, '');
                    }
                },
                {
                    targets: 4, // kolom tanggal
                    render: function(data, type, row) {
                        if (type === 'sort') {
                            return row[4].split('data-sort="')[1].split('"')[0];
                        }
                        return data;
                    }
                }
            ]
        });

        // Menambahkan class untuk styling button
        $('.dt-buttons').addClass('mb-3');
    });
  </script>
</body>

</html>

