<?php $this->load->view('admin/partials/head') ?>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php $this->load->view('admin/partials/navbar') ?>
  <?php $this->load->view('admin/partials/sidebar') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Fasilitas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard/facilities'); ?>">Fasilitas</a></li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Facility Detail Card -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Informasi Fasilitas</h3>
            <div class="card-tools">
              <a href="<?php echo site_url('dashboard/facilities'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
            </div>
          </div>
          <div class="card-body">
            <?php if(isset($facility)): ?>
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td width="150"><strong>Area</strong></td>
                    <td><?php echo $facility->nama_area; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Jenis Fasilitas</strong></td>
                    <td><?php echo $facility->nama_tipe; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Tipe</strong></td>
                    <td><?php echo $facility->tipe; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Kapasitas</strong></td>
                    <td><?php echo $facility->kapasitas; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Jumlah</strong></td>
                    <td><?php echo $facility->jumlah; ?> Unit</td>
                  </tr>
                  <tr>
                    <td><strong>Tahun Unit</strong></td>
                    <td><?php echo $facility->tahun_unit; ?></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td width="150"><strong>Vendor</strong></td>
                    <td><?php echo $facility->nama_vendor ? $facility->nama_vendor : '-'; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Awal Sewa</strong></td>
                    <td><?php echo $facility->awal_sewa ? date('d/m/Y', strtotime($facility->awal_sewa)) : '-'; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Akhir Sewa</strong></td>
                    <td><?php echo $facility->akhir_sewa ? date('d/m/Y', strtotime($facility->akhir_sewa)) : '-'; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Total Harga Sewa</strong></td>
                    <td><?php echo $facility->total_harga_sewa ? 'Rp ' . number_format($facility->total_harga_sewa, 0, ',', '.') : '-'; ?></td>
                  </tr>
                  <tr>
                    <td><strong>No. Perjanjian</strong></td>
                    <td><?php echo $facility->no_perjanjian ? $facility->no_perjanjian : '-'; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Dokumen Perjanjian</strong></td>
                    <td>
                      <?php if(!empty($facility->dokumen_perjanjian)): ?>
                        <?php
                        $file_path = 'uploads/facilities/'.$facility->dokumen_perjanjian;
                        $file_extension = strtolower(pathinfo($facility->dokumen_perjanjian, PATHINFO_EXTENSION));
                        $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif']);
                        ?>
                        
                        <?php if($is_image): ?>
                          <div class="mb-2">
                            <img src="<?php echo base_url($file_path); ?>"
                                 alt="Dokumen Perjanjian"
                                 class="img-thumbnail"
                                 style="max-width: 200px; max-height: 150px; cursor: pointer;"
                                 onclick="window.open('<?php echo base_url($file_path); ?>', '_blank')">
                          </div>
                          <a href="<?php echo base_url($file_path); ?>" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-search-plus"></i> Lihat Gambar
                          </a>
                        <?php else: ?>
                          <a href="<?php echo base_url($file_path); ?>" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-file-pdf"></i> Lihat Dokumen PDF
                          </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo base_url($file_path); ?>" download="<?php echo $facility->dokumen_perjanjian; ?>" class="btn btn-sm btn-success ml-1">
                          <i class="fas fa-download"></i> Download
                        </a>
                      <?php else: ?>
                        <span class="text-muted">Tidak ada dokumen</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Status</strong></td>
                    <td>
                      <span class="badge badge-<?php echo $facility->status == 'active' ? 'success' : ($facility->status == 'maintenance' ? 'warning' : 'danger'); ?>">
                        <?php echo ucfirst($facility->status); ?>
                      </span>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            
            <?php if($facility->keterangan): ?>
            <div class="row">
              <div class="col-12">
                <h5>Keterangan</h5>
                <p><?php echo $facility->keterangan; ?></p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="alert alert-danger">
              Data fasilitas tidak ditemukan.
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Contract Status Alert -->
        <?php if(isset($facility) && $facility->akhir_sewa): ?>
          <?php 
          $today = new DateTime();
          $end_date = new DateTime($facility->akhir_sewa);
          $interval = $today->diff($end_date);
          $days_left = $interval->days;
          
          if($days_left <= 90 && $days_left > 0):
          ?>
          <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan Kontrak!</h5>
            Kontrak fasilitas ini akan berakhir dalam <strong><?php echo $days_left; ?> hari</strong> 
            pada tanggal <?php echo date('d/m/Y', strtotime($facility->akhir_sewa)); ?>.
          </div>
          <?php elseif($days_left <= 0): ?>
          <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-times-circle"></i> Kontrak Berakhir!</h5>
            Kontrak fasilitas ini telah berakhir pada tanggal <?php echo date('d/m/Y', strtotime($facility->akhir_sewa)); ?>.
          </div>
          <?php endif; ?>
        <?php endif; ?>

      </div>
    </section>
  </div>

  <?php $this->load->view('admin/partials/footer') ?>
</div>

<?php $this->load->view('admin/partials/javascript') ?>

</body>
</html>