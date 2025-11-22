<?php $this->load->view('admin/partials/head') ?>

<style>
/* Responsive adjustments for dashboard statistics */
@media (max-width: 768px) {
  .small-box .inner h3 {
    font-size: 1.1rem !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2 !important;
    word-break: break-all;
  }
  
  .small-box p {
    font-size: 0.9rem !important;
  }
  
  .small-box .icon {
    font-size: 1.5rem !important;
  }
  
  .small-box-footer {
    font-size: 0.8rem !important;
    padding: 5px 10px;
  }
  
  /* Make tables responsive on mobile */
  .table-responsive {
    font-size: 0.8rem;
  }
  
  .table th, .table td {
    padding: 0.5rem;
    vertical-align: middle;
  }
  
  /* Adjust card padding on mobile */
  .card-body {
    padding: 0.75rem;
  }
  
  /* Adjust chart containers */
  canvas {
    max-height: 250px !important;
  }
  
  /* Ensure small boxes don't overflow */
  .small-box {
    min-height: 120px;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
  }
  
  .small-box .inner {
    padding: 10px;
    flex: 1;
  }
  
  /* Add spacing between cards on mobile */
  .card {
    margin-bottom: 1rem;
  }
  
  /* Improve text wrapping for long numbers */
  .format-currency {
    max-width: 100%;
    display: inline-block;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }
  
  /* Fix small box footer positioning */
  .small-box-footer {
    margin-top: auto;
  }
}

@media (max-width: 576px) {
  .small-box .inner h3 {
    font-size: 0.9rem !important;
  }
  
  .small-box p {
    font-size: 0.8rem !important;
  }
  
  /* Further reduce table font size on very small screens */
  .table-responsive {
    font-size: 0.7rem;
  }
  
  .table th, .table td {
    padding: 0.3rem;
  }
  
  /* Hide some less important columns on very small screens */
  .table-hide-mobile {
    display: none;
  }
}
</style>

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
            <h1 class="m-0">Dashboard Monitoring Fasilitas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6 mb-3">
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="text-truncate" style="font-size: 1.2rem;"><?php echo isset($stats['total_facilities']) ? number_format($stats['total_facilities']) : '0'; ?></h3>
                <p>Total Fasilitas</p>
              </div>
              <div class="icon">
                <i class="fas fa-truck-loading"></i>
              </div>
              <a href="<?php echo site_url('dashboard/facilities'); ?>" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6 mb-3">
            <div class="small-box bg-success">
              <div class="inner">
                <h3 class="text-truncate format-currency" style="font-size: 1.2rem;" data-value="<?php echo isset($stats['total_value']) ? $stats['total_value'] : '0'; ?>">Rp <?php echo isset($stats['total_value']) ? number_format($stats['total_value'], 0, ',', '.') : '0'; ?></h3>
                <p>Total Nilai Sewa</p>
              </div>
              <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
              <a href="<?php echo site_url('dashboard/reports'); ?>" class="small-box-footer">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6 mb-3">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 class="text-truncate" style="font-size: 1.2rem;"><?php echo isset($stats['by_area']) ? count($stats['by_area']) : '0'; ?></h3>
                <p>Total Area</p>
              </div>
              <div class="icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <a href="<?php echo site_url('dashboard/areas'); ?>" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6 mb-3">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="text-truncate" style="font-size: 1.2rem;"><?php echo isset($expiring_contracts) ? count($expiring_contracts) : '0'; ?></h3>
                <p>Kontrak Habis 3 Bulan</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <a href="<?php echo site_url('dashboard/facilities'); ?>" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Info boxes -->
        <?php if(isset($expiring_contracts) && count($expiring_contracts) > 0): ?>
        <div class="row">
          <div class="col-12">
            <div class="alert alert-warning alert-dismissible">
              <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
              Terdapat <?php echo count($expiring_contracts); ?> kontrak yang akan berakhir dalam 3 bulan ke depan.
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Chart Row -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Fasilitas per Tipe</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="facilityTypeChart" style="height: 300px;"></canvas>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Fasilitas per Area</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="facilityAreaChart" style="height: 300px;"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Statistics Row -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Fasilitas per Tipe (Detail)</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Tipe Fasilitas</th>
                        <th>Jumlah</th>
                        <th class="table-hide-mobile">Total Nilai</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(isset($stats['by_type']) && count($stats['by_type']) > 0): ?>
                        <?php foreach($stats['by_type'] as $type): ?>
                        <tr>
                          <td><?php echo $type->nama_tipe; ?></td>
                          <td><?php echo $type->count; ?></td>
                          <td class="table-hide-mobile format-currency" data-value="<?php echo $type->total_value; ?>">Rp <?php echo number_format($type->total_value, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="2" class="text-center">Belum ada data</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Fasilitas per Area (Detail)</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Area</th>
                        <th>Jumlah</th>
                        <th class="table-hide-mobile">Total Nilai</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(isset($stats['by_area']) && count($stats['by_area']) > 0): ?>
                        <?php foreach($stats['by_area'] as $area): ?>
                        <tr>
                          <td><?php echo $area->nama_area; ?></td>
                          <td><?php echo $area->count; ?></td>
                          <td class="table-hide-mobile format-currency" data-value="<?php echo $area->total_value; ?>">Rp <?php echo number_format($area->total_value, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="2" class="text-center">Belum ada data</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Expiring Contracts Details -->
        <?php if(isset($expiring_contracts) && count($expiring_contracts) > 0): ?>
        <div class="row">
          <div class="col-12 mb-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Kontrak yang Akan Habis dalam 3 Bulan</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Area</th>
                        <th>Jenis Fasilitas</th>
                        <th class="table-hide-mobile">Tipe</th>
                        <th class="table-hide-mobile">Vendor</th>
                        <th>Tanggal Akhir Sewa</th>
                        <th>Sisa Hari</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; foreach($expiring_contracts as $contract): ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $contract->nama_area; ?></td>
                        <td><?php echo $contract->nama_tipe; ?></td>
                        <td class="table-hide-mobile"><?php echo $contract->tipe; ?></td>
                        <td class="table-hide-mobile"><?php echo $contract->nama_vendor ? $contract->nama_vendor : '-'; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($contract->akhir_sewa)); ?></td>
                        <td>
                          <span class="badge badge-<?php echo (strtotime($contract->akhir_sewa) - strtotime(date('Y-m-d'))) / (60 * 60 * 24) <= 30 ? 'danger' : 'warning'; ?>">
                            <?php echo round((strtotime($contract->akhir_sewa) - strtotime(date('Y-m-d'))) / (60 * 60 * 24)); ?> hari
                          </span>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Recent Facilities Table -->
        <div class="row">
          <div class="col-12 mb-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Fasilitas Terbaru</h3>
                <div class="card-tools">
                  <a href="<?php echo site_url('dashboard/facilities'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-list"></i> Lihat Semua
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Area</th>
                        <th>Jenis Fasilitas</th>
                        <th class="table-hide-mobile">Tipe</th>
                        <th>Jumlah</th>
                        <th>Nilai Sewa</th>
                        <th class="table-hide-mobile">Vendor</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(isset($recent_facilities) && count($recent_facilities) > 0): ?>
                        <?php $no = 1; foreach(array_slice($recent_facilities, 0, 10) as $facility): ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td><?php echo $facility->nama_area; ?></td>
                          <td><?php echo $facility->nama_tipe; ?></td>
                          <td class="table-hide-mobile"><?php echo $facility->tipe; ?></td>
                          <td><?php echo $facility->jumlah; ?></td>
                          <td class="format-currency" data-value="<?php echo $facility->total_harga_sewa; ?>">Rp <?php echo number_format($facility->total_harga_sewa, 0, ',', '.'); ?></td>
                          <td class="table-hide-mobile"><?php echo $facility->nama_vendor ? $facility->nama_vendor : '-'; ?></td>
                          <td>
                            <span class="badge badge-<?php echo $facility->status == 'active' ? 'success' : 'danger'; ?>">
                              <?php echo ucfirst($facility->status); ?>
                            </span>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" class="text-center">Belum ada data fasilitas</td>
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
    </section>
  </div>

  <?php $this->load->view('admin/partials/footer') ?>
</div>

<?php $this->load->view('admin/partials/javascript') ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Facility Type Chart
const facilityTypeCtx = document.getElementById('facilityTypeChart').getContext('2d');
const facilityTypeChart = new Chart(facilityTypeCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo isset($stats['by_type']) ? json_encode(array_column($stats['by_type'], 'nama_tipe')) : '[]'; ?>,
        datasets: [{
            data: <?php echo isset($stats['by_type']) ? json_encode(array_column($stats['by_type'], 'count')) : '[]'; ?>,
            backgroundColor: [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                '#fd7e14', '#20c997', '#6c757d', '#e83e8c', '#17a2b8'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Facility Area Chart
const facilityAreaCtx = document.getElementById('facilityAreaChart').getContext('2d');
const facilityAreaChart = new Chart(facilityAreaCtx, {
    type: 'bar',
    data: {
        labels: <?php echo isset($stats['by_area']) ? json_encode(array_column($stats['by_area'], 'nama_area')) : '[]'; ?>,
        datasets: [{
            label: 'Jumlah Fasilitas',
            data: <?php echo isset($stats['by_area']) ? json_encode(array_column($stats['by_area'], 'count')) : '[]'; ?>,
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<script>
// Format large numbers for mobile display
function formatLargeNumber(num) {
  // Convert to number
  const cleanNum = parseFloat(num);
  
  if (cleanNum >= 1000000000) {
    return 'Rp ' + (cleanNum / 1000000000).toFixed(1) + 'M';
  } else if (cleanNum >= 1000000) {
    return 'Rp ' + (cleanNum / 1000000).toFixed(1) + 'JT';
  } else if (cleanNum >= 1000) {
    return 'Rp ' + (cleanNum / 1000).toFixed(1) + 'RB';
  }
  return 'Rp ' + cleanNum.toLocaleString('id-ID');
}

// Apply formatting to currency elements based on screen size
document.addEventListener('DOMContentLoaded', function() {
  function formatCurrencyElements() {
    const isMobile = window.innerWidth <= 768;
    const currencyElements = document.querySelectorAll('.format-currency');
    
    currencyElements.forEach(element => {
      const value = element.getAttribute('data-value');
      if (value) {
        if (isMobile) {
          element.textContent = formatLargeNumber(value);
        } else {
          element.textContent = 'Rp ' + parseFloat(value).toLocaleString('id-ID');
        }
      }
    });
  }
  
  // Initial formatting
  formatCurrencyElements();
  
  // Reformat on window resize with debounce
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(formatCurrencyElements, 250);
  });
});
</script>

</body>
</html>
