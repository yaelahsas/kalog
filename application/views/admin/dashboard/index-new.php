<?php $this->load->view('admin/partials/head-new') ?>

<body class="admin-layout">
  <!-- Sidebar -->
  <?php $this->load->view('admin/partials/sidebar-new') ?>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Header -->
    <?php $this->load->view('admin/partials/header-new') ?>

    <!-- Content -->
    <div class="content">
      <!-- Content Header -->
      <div class="content-header">
        <h1 class="content-title">Dashboard Monitoring Fasilitas</h1>
        <p class="content-subtitle">Selamat datang di sistem monitoring fasilitas Kalog</p>
      </div>

      <!-- Breadcrumb -->
      <nav class="breadcrumb">
        <span class="breadcrumb-item">
          <a href="<?= site_url('dashboard') ?>">Home</a>
        </span>
        <span class="breadcrumb-item active">Dashboard</span>
      </nav>

      <!-- Page Actions -->
      <div class="page-actions">
        <div class="page-actions-left">
          <button class="btn btn-primary" onclick="exportData('pdf')">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
          </button>
          <button class="btn btn-success" onclick="exportData('excel')">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
          </button>
        </div>
        <div class="page-actions-right">
          <select class="form-control" id="dateRangeFilter" onchange="updateDashboard()">
            <option value="today">Hari Ini</option>
            <option value="week">Minggu Ini</option>
            <option value="month" selected>Bulan Ini</option>
            <option value="quarter">Kuartal Ini</option>
            <option value="year">Tahun Ini</option>
          </select>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <!-- Total Facilities -->
        <div class="stat-card">
          <div class="stat-header">
            <div class="stat-icon primary">
              <i class="fas fa-truck-loading"></i>
            </div>
            <div class="stat-change positive">
              <i class="fas fa-arrow-up"></i>
              <span>12%</span>
            </div>
          </div>
          <h3 class="stat-value"><?php echo isset($stats['total_facilities']) ? number_format($stats['total_facilities']) : '0'; ?></h3>
          <p class="stat-label">Total Fasilitas</p>
        </div>

        <!-- Total Value -->
        <div class="stat-card success">
          <div class="stat-header">
            <div class="stat-icon success">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-change positive">
              <i class="fas fa-arrow-up"></i>
              <span>8%</span>
            </div>
          </div>
          <h3 class="stat-value">Rp <?php echo isset($stats['total_value']) ? number_format($stats['total_value'], 0, ',', '.') : '0'; ?></h3>
          <p class="stat-label">Total Nilai Sewa</p>
        </div>

        <!-- Total Areas -->
        <div class="stat-card info">
          <div class="stat-header">
            <div class="stat-icon info">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-change positive">
              <i class="fas fa-arrow-up"></i>
              <span>5%</span>
            </div>
          </div>
          <h3 class="stat-value"><?php echo isset($areas) ? count($areas) : '0'; ?></h3>
          <p class="stat-label">Total Area</p>
        </div>

        <!-- Expiring Contracts -->
        <div class="stat-card warning">
          <div class="stat-header">
            <div class="stat-icon warning">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-change negative">
              <i class="fas fa-arrow-down"></i>
              <span>3%</span>
            </div>
          </div>
          <h3 class="stat-value"><?php echo isset($expiring_contracts) ? count($expiring_contracts) : '0'; ?></h3>
          <p class="stat-label">Kontrak Habis 3 Bulan</p>
        </div>
      </div>

      <!-- Alert for Expiring Contracts -->
      <?php if(isset($expiring_contracts) && count($expiring_contracts) > 0): ?>
      <div class="alert alert-warning alert-dismissible">
        <h5 class="alert-heading">
          <i class="fas fa-exclamation-triangle mr-2"></i> Peringatan Kontrak!
        </h5>
        <p class="mb-2">Terdapat <strong><?php echo count($expiring_contracts); ?></strong> kontrak yang akan berakhir dalam 3 bulan ke depan.</p>
        <button type="button" class="btn btn-warning btn-sm" onclick="viewExpiringContracts()">
          <i class="fas fa-list mr-1"></i> Lihat Detail
        </button>
        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
          <span>&times;</span>
        </button>
      </div>
      <?php endif; ?>

      <!-- Charts Grid -->
      <div class="grid grid-cols-2">
        <!-- Facility Type Chart -->
        <div class="content-section">
          <div class="content-section-header">
            <h3 class="content-section-title">Fasilitas per Tipe</h3>
            <div class="content-section-actions">
              <button class="btn btn-sm btn-outline-primary" onclick="refreshChart('facilityType')">
                <i class="fas fa-sync-alt"></i>
              </button>
              <button class="btn btn-sm btn-outline-primary" onclick="downloadChart('facilityType')">
                <i class="fas fa-download"></i>
              </button>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="facilityTypeChart"></canvas>
          </div>
        </div>

        <!-- Facility Area Chart -->
        <div class="content-section">
          <div class="content-section-header">
            <h3 class="content-section-title">Fasilitas per Area</h3>
            <div class="content-section-actions">
              <button class="btn btn-sm btn-outline-primary" onclick="refreshChart('facilityArea')">
                <i class="fas fa-sync-alt"></i>
              </button>
              <button class="btn btn-sm btn-outline-primary" onclick="downloadChart('facilityArea')">
                <i class="fas fa-download"></i>
              </button>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="facilityAreaChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Additional Charts -->
      <div class="grid grid-cols-3">
        <!-- Monthly Trend -->
        <div class="content-section">
          <div class="content-section-header">
            <h3 class="content-section-title">Tren Bulanan</h3>
          </div>
          <div class="chart-container">
            <canvas id="monthlyTrendChart"></canvas>
          </div>
        </div>

        <!-- Vendor Distribution -->
        <div class="content-section">
          <div class="content-section-header">
            <h3 class="content-section-title">Distribusi Vendor</h3>
          </div>
          <div class="chart-container">
            <canvas id="vendorChart"></canvas>
          </div>
        </div>

        <!-- Contract Status -->
        <div class="content-section">
          <div class="content-section-header">
            <h3 class="content-section-title">Status Kontrak</h3>
          </div>
          <div class="chart-container">
            <canvas id="contractStatusChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Facilities Table -->
      <div class="content-section">
        <div class="content-section-header">
          <h3 class="content-section-title">Data Fasilitas Terbaru</h3>
          <div class="content-section-actions">
            <button class="btn btn-sm btn-outline-primary" onclick="refreshTable()">
              <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
            <a href="<?= site_url('dashboard/facilities') ?>" class="btn btn-sm btn-primary">
              <i class="fas fa-list mr-1"></i> Lihat Semua
            </a>
          </div>
        </div>
        
        <div class="table-container">
          <table class="table table-hover" id="recentFacilitiesTable">
            <thead>
              <tr>
                <th>No</th>
                <th>Area</th>
                <th>Jenis Fasilitas</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Vendor</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if(isset($recent_facilities) && count($recent_facilities) > 0): ?>
                <?php $no = 1; foreach(array_slice($recent_facilities, 0, 10) as $facility): ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                      <?php echo $facility->nama_area; ?>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-cogs text-info mr-2"></i>
                      <?php echo $facility->nama_tipe; ?>
                    </div>
                  </td>
                  <td><?php echo $facility->tipe; ?></td>
                  <td>
                    <span class="badge badge-primary"><?php echo $facility->jumlah; ?></span>
                  </td>
                  <td>
                    <?php if($facility->nama_vendor): ?>
                      <div class="d-flex align-items-center">
                        <i class="fas fa-building text-secondary mr-2"></i>
                        <?php echo $facility->nama_vendor; ?>
                      </div>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="badge badge-<?php echo $facility->status == 'active' ? 'success' : 'danger'; ?>">
                      <?php echo ucfirst($facility->status); ?>
                    </span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button class="btn btn-sm btn-outline-primary" onclick="viewFacility(<?php echo $facility->id; ?>)" title="View">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-secondary" onclick="editFacility(<?php echo $facility->id; ?>)" title="Edit">
                        <i class="fas fa-edit"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada data fasilitas</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Quick Actions Section -->
      <div class="content-section">
        <div class="content-section-header">
          <h3 class="content-section-title">Aksi Cepat</h3>
        </div>
        <div class="grid grid-cols-4">
          <button class="btn btn-primary btn-lg" onclick="window.location.href='<?= site_url('dashboard/facilities/add') ?>'">
            <i class="fas fa-plus mr-2"></i> Tambah Fasilitas
          </button>
          <button class="btn btn-success btn-lg" onclick="window.location.href='<?= site_url('dashboard/vendors/add') ?>'">
            <i class="fas fa-plus mr-2"></i> Tambah Vendor
          </button>
          <button class="btn btn-info btn-lg" onclick="window.location.href='<?= site_url('dashboard/reports') ?>'">
            <i class="fas fa-chart-bar mr-2"></i> Lihat Laporan
          </button>
          <button class="btn btn-warning btn-lg" onclick="window.location.href='<?= site_url('dashboard/maintenance') ?>'">
            <i class="fas fa-wrench mr-2"></i> Maintenance
          </button>
        </div>
      </div>
    </div>
  </main>
</body>

</html>

<!-- Enhanced JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart configurations
const chartColors = {
  primary: '#2563eb',
  success: '#10b981',
  warning: '#f59e0b',
  danger: '#ef4444',
  info: '#06b6d4',
  secondary: '#64748b'
};

// Initialize charts
let facilityTypeChart, facilityAreaChart, monthlyTrendChart, vendorChart, contractStatusChart;

document.addEventListener('DOMContentLoaded', function() {
  initializeCharts();
  initializeDataTable();
});

function initializeCharts() {
  // Facility Type Chart (Doughnut)
  const facilityTypeCtx = document.getElementById('facilityTypeChart').getContext('2d');
  facilityTypeChart = new Chart(facilityTypeCtx, {
    type: 'doughnut',
    data: {
      labels: <?php echo isset($stats['by_type']) ? json_encode(array_column($stats['by_type'], 'nama_tipe')) : '[]'; ?>,
      datasets: [{
        data: <?php echo isset($stats['by_type']) ? json_encode(array_column($stats['by_type'], 'count')) : '[]'; ?>,
        backgroundColor: [
          chartColors.primary, chartColors.success, chartColors.warning, 
          chartColors.danger, chartColors.info, chartColors.secondary,
          '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'
        ],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 15,
            usePointStyle: true,
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.parsed || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((value / total) * 100).toFixed(1);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });

  // Facility Area Chart (Bar)
  const facilityAreaCtx = document.getElementById('facilityAreaChart').getContext('2d');
  facilityAreaChart = new Chart(facilityAreaCtx, {
    type: 'bar',
    data: {
      labels: <?php echo isset($stats['by_area']) ? json_encode(array_column($stats['by_area'], 'nama_area')) : '[]'; ?>,
      datasets: [{
        label: 'Jumlah Fasilitas',
        data: <?php echo isset($stats['by_area']) ? json_encode(array_column($stats['by_area'], 'count')) : '[]'; ?>,
        backgroundColor: chartColors.primary,
        borderColor: chartColors.primary,
        borderWidth: 1,
        borderRadius: 6,
        barThickness: 'flex'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `Jumlah: ${context.parsed.y}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            borderDash: [2, 2]
          }
        },
        x: {
          grid: {
            display: false
          }
        }
      }
    }
  });

  // Monthly Trend Chart (Line)
  const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
  monthlyTrendChart = new Chart(monthlyTrendCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Fasilitas Aktif',
        data: [65, 72, 78, 85, 89, 92],
        borderColor: chartColors.primary,
        backgroundColor: `${chartColors.primary}20`,
        tension: 0.4,
        fill: true
      }, {
        label: 'Fasilitas Maintenance',
        data: [5, 8, 6, 9, 7, 8],
        borderColor: chartColors.warning,
        backgroundColor: `${chartColors.warning}20`,
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            font: {
              size: 11
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            borderDash: [2, 2]
          }
        },
        x: {
          grid: {
            display: false
          }
        }
      }
    }
  });

  // Vendor Chart (Polar Area)
  const vendorCtx = document.getElementById('vendorChart').getContext('2d');
  vendorChart = new Chart(vendorCtx, {
    type: 'polarArea',
    data: {
      labels: ['PT EMITRACO', 'PT TAS', 'PT SONS', 'PT PKP', 'Lainnya'],
      datasets: [{
        data: [15, 12, 8, 6, 9],
        backgroundColor: [
          chartColors.primary,
          chartColors.success,
          chartColors.warning,
          chartColors.danger,
          chartColors.info
        ],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            font: {
              size: 11
            }
          }
        }
      }
    }
  });

  // Contract Status Chart (Pie)
  const contractStatusCtx = document.getElementById('contractStatusChart').getContext('2d');
  contractStatusChart = new Chart(contractStatusCtx, {
    type: 'pie',
    data: {
      labels: ['Aktif', 'Akan Habis', 'Habis', 'Perpanjangan'],
      datasets: [{
        data: [75, 15, 5, 5],
        backgroundColor: [
          chartColors.success,
          chartColors.warning,
          chartColors.danger,
          chartColors.info
        ],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            font: {
              size: 11
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.parsed || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((value / total) * 100).toFixed(1);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });
}

function initializeDataTable() {
  // Initialize DataTable for recent facilities
  $('#recentFacilitiesTable').DataTable({
    responsive: true,
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    order: [[0, 'desc']],
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Selanjutnya",
        previous: "Sebelumnya"
      }
    }
  });
}

// Action functions
function refreshChart(chartType) {
  // Show loading state
  const chart = window[chartType + 'Chart'];
  if (chart) {
    chart.data.datasets[0].data = chart.data.datasets[0].data.map(() => Math.floor(Math.random() * 100));
    chart.update('active');
  }
}

function downloadChart(chartType) {
  const chart = window[chartType + 'Chart'];
  if (chart) {
    const url = chart.toBase64Image();
    const link = document.createElement('a');
    link.download = `${chartType}-chart.png`;
    link.href = url;
    link.click();
  }
}

function refreshTable() {
  $('#recentFacilitiesTable').DataTable().ajax.reload();
}

function viewFacility(id) {
  window.location.href = `<?= site_url('dashboard/facilities/detail') ?>/${id}`;
}

function editFacility(id) {
  window.location.href = `<?= site_url('dashboard/facilities/edit') ?>/${id}`;
}

function viewExpiringContracts() {
  window.location.href = `<?= site_url('dashboard/facilities') ?>?filter=expiring`;
}

function exportData(format) {
  const dateRange = document.getElementById('dateRangeFilter').value;
  window.location.href = `<?= site_url('dashboard/export') ?>?format=${format}&range=${dateRange}`;
}

function updateDashboard() {
  const dateRange = document.getElementById('dateRangeFilter').value;
  
  // Show loading state
  document.querySelectorAll('.stat-value').forEach(el => {
    el.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
  });
  
  // Fetch updated data
  fetch(`<?= site_url('dashboard/update') ?>?range=${dateRange}`, {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.json())
  .then(data => {
    updateDashboardData(data);
  })
  .catch(error => {
    console.error('Error updating dashboard:', error);
    // Remove loading state on error
    location.reload();
  });
}

function updateDashboardData(data) {
  // Update stats
  if (data.stats) {
    document.querySelector('.stat-card:nth-child(1) .stat-value').textContent = data.stats.total_facilities || '0';
    document.querySelector('.stat-card:nth-child(2) .stat-value').textContent = `Rp ${data.stats.total_value || '0'}`;
    document.querySelector('.stat-card:nth-child(3) .stat-value').textContent = data.stats.total_areas || '0';
    document.querySelector('.stat-card:nth-child(4) .stat-value').textContent = data.stats.expiring_contracts || '0';
  }
  
  // Update charts
  if (data.charts) {
    // Update facility type chart
    if (data.charts.facility_types && facilityTypeChart) {
      facilityTypeChart.data.labels = data.charts.facility_types.labels;
      facilityTypeChart.data.datasets[0].data = data.charts.facility_types.data;
      facilityTypeChart.update();
    }
    
    // Update facility area chart
    if (data.charts.facility_areas && facilityAreaChart) {
      facilityAreaChart.data.labels = data.charts.facility_areas.labels;
      facilityAreaChart.data.datasets[0].data = data.charts.facility_areas.data;
      facilityAreaChart.update();
    }
  }
}

// Auto-refresh dashboard every 5 minutes
setInterval(() => {
  updateDashboard();
}, 300000);
</script>

<style>
.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
}

.table-container {
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
}

.btn-group {
  display: inline-flex;
  border-radius: var(--radius-md);
  overflow: hidden;
}

.btn-group .btn {
  border-radius: 0;
  margin: 0;
}

.btn-group .btn:first-child {
  border-top-left-radius: var(--radius-md);
  border-bottom-left-radius: var(--radius-md);
}

.btn-group .btn:last-child {
  border-top-right-radius: var(--radius-md);
  border-bottom-right-radius: var(--radius-md);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .grid.grid-cols-2 {
    grid-template-columns: 1fr;
  }
  
  .grid.grid-cols-3 {
    grid-template-columns: 1fr;
  }
  
  .grid.grid-cols-4 {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .page-actions {
    flex-direction: column;
    gap: var(--space-3);
  }
  
  .page-actions-left,
  .page-actions-right {
    width: 100%;
  }
  
  .page-actions-right .form-control {
    width: 100%;
  }
}

@media (max-width: 640px) {
  .grid.grid-cols-4 {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>