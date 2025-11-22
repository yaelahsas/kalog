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
              <h1 class="m-0">Laporan Monitoring Fasilitas</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Home</a></li>
                <li class="breadcrumb-item active">Laporan</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <!-- Summary Cards -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo isset($stats['total_facilities']) ? number_format($stats['total_facilities']) : '0'; ?></h3>
                  <p>Total Fasilitas</p>
                </div>
                <div class="icon">
                  <i class="fas fa-truck-loading"></i>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>Rp <?php echo isset($stats['total_value']) ? number_format($stats['total_value'], 0, ',', '.') : '0'; ?></h3>
                  <p>Total Nilai Sewa</p>
                </div>
                <div class="icon">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo isset($areas) ? count($areas) : '0'; ?></h3>
                  <p>Total Area</p>
                </div>
                <div class="icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?php echo isset($facility_types) ? count($facility_types) : '0'; ?></h3>
                  <p>Jenis Fasilitas</p>
                </div>
                <div class="icon">
                  <i class="fas fa-cogs"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Fasilitas per Jenis</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="typeChart" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>

            <div class="col-md-6">
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
                  <canvas id="areaChart" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Vendor Statistics -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Statistik Vendor</h3>
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
                          <th>Vendor</th>
                          <th>Jumlah Fasilitas</th>
                          <th>Total Nilai Sewa</th>
                          <th>Persentase</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($vendor_stats) && count($vendor_stats) > 0): ?>
                          <?php
                          $no = 1;
                          $total_value = isset($stats['total_value']) ? $stats['total_value'] : 0;
                          foreach ($vendor_stats as $vendor):
                            $percentage = $total_value > 0 ? ($vendor->total_value / $total_value) * 100 : 0;
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td><?php echo $vendor->nama_vendor; ?></td>
                              <td><?php echo $vendor->total_facilities; ?></td>
                              <td>Rp <?php echo number_format($vendor->total_value, 0, ',', '.'); ?></td>
                              <td><?php echo number_format($percentage, 2); ?>%</td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center">Belum ada data vendor</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Facility Type Statistics -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Statistik Jenis Fasilitas</h3>
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
                          <th>Jenis Fasilitas</th>
                          <th>Jumlah Unit</th>
                          <th>Total Nilai Sewa</th>
                          <th>Persentase</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($facility_types) && count($facility_types) > 0): ?>
                          <?php
                          $no = 1;
                          $total_value = isset($stats['total_value']) ? $stats['total_value'] : 0;
                          foreach ($facility_types as $type):
                            $percentage = $total_value > 0 ? ($type->total_value / $total_value) * 100 : 0;
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <a href="javascript:void(0)" onclick="showFacilitiesByType(<?php echo $type->id; ?>, '<?php echo $type->nama_tipe; ?>')" class="text-decoration-none">
                                  <?php echo $type->nama_tipe; ?>
                                  <i class="fas fa-eye ml-1 text-primary"></i>
                                </a>
                              </td>
                              <td><?php echo $type->total_units; ?></td>
                              <td>Rp <?php echo number_format($type->total_value, 0, ',', '.'); ?></td>
                              <td><?php echo number_format($percentage, 2); ?>%</td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center">Belum ada data jenis fasilitas</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Export Buttons and Filters -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Export & Filter Laporan</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <h5>Filter Laporan</h5>
                      <div class="form-group">
                        <label for="filterArea">Filter Area</label>
                        <select class="form-control" id="filterArea">
                          <option value="">Semua Area</option>
                          <?php if (isset($areas) && count($areas) > 0): ?>
                            <?php foreach ($areas as $area): ?>
                              <option value="<?php echo $area->id; ?>"><?php echo $area->nama_area; ?></option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="filterType">Filter Jenis Fasilitas</label>
                        <select class="form-control" id="filterType">
                          <option value="">Semua Jenis</option>
                          <?php if (isset($facility_types) && count($facility_types) > 0): ?>
                            <?php foreach ($facility_types as $type): ?>
                              <option value="<?php echo $type->id; ?>"><?php echo $type->nama_tipe; ?></option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="filterVendor">Filter Vendor</label>
                        <select class="form-control" id="filterVendor">
                          <option value="">Semua Vendor</option>
                          <?php if (isset($vendor_stats) && count($vendor_stats) > 0): ?>
                            <?php foreach ($vendor_stats as $vendor): ?>
                              <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->nama_vendor; ?></option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </div>
                      <button type="button" class="btn btn-primary" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Terapkan Filter
                      </button>
                      <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Reset
                      </button>
                    </div>
                    <div class="col-md-6">
                      <h5>Export Laporan</h5>
                      <div class="btn-group-vertical d-grid gap-2">
                        <a href="<?php echo site_url('dashboard/reports/export_excel'); ?>" class="btn btn-success">
                          <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <button type="button" class="btn btn-danger" onclick="window.print()">
                          <i class="fas fa-print"></i> Cetak Laporan
                        </button>
                        <button type="button" class="btn btn-info" onclick="exportPDF()">
                          <i class="fas fa-file-pdf"></i> Export PDF
                        </button>
                        <button type="button" class="btn btn-warning" onclick="generateCustomReport()">
                          <i class="fas fa-cog"></i> Laporan Kustom
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Additional Charts -->
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Status Kontrak</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="contractChart" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Fasilitas per Tahun</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="yearChart" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Newest Facilities Table -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Daftar Fasilitas Baru</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" onclick="refreshNewestFacilities()">
                      <i class="fas fa-sync"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="newestFacilitiesTable">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Tipe Fasilitas</th>
                          <th>Nama/Area</th>
                          <th>Vendor</th>
                          <th>Tahun Unit</th>
                          <th>Status Kontrak</th>
                          <th>Tanggal Ditambahkan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($newest_facilities) && count($newest_facilities) > 0): ?>
                          <?php
                          $no = 1;
                          foreach ($newest_facilities as $facility):
                            // Calculate contract status
                            $today = date('Y-m-d');
                            $status_badge = 'success';
                            $status_text = 'Aktif';
                            
                            if ($facility->akhir_sewa < $today) {
                              $status_badge = 'danger';
                              $status_text = 'Kadaluarsa';
                            } elseif (strtotime($facility->akhir_sewa) <= strtotime('+30 days')) {
                              $status_badge = 'warning';
                              $status_text = 'Akan Kadaluarsa';
                            }
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <span class="badge badge-info"><?php echo $facility->nama_tipe; ?></span>
                              </td>
                              <td>
                                <strong><?php echo $facility->tipe ?: '-'; ?></strong><br>
                                <small class="text-muted"><?php echo $facility->nama_area ?: '-'; ?></small>
                              </td>
                              <td><?php echo $facility->nama_vendor ?: '-'; ?></td>
                              <td><?php echo $facility->tahun_unit ?: '-'; ?></td>
                              <td>
                                <span class="badge badge-<?php echo $status_badge; ?>"><?php echo $status_text; ?></span>
                              </td>
                              <td>
                                <small><?php echo date('d/m/Y H:i', strtotime($facility->created_at)); ?></small>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center">Belum ada data fasilitas baru</td>
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

  <!-- Chart.js sudah dimuat di footer-new -->
<?php $this->load->view('admin/partials/javascript') ?>

  <script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
      // Facility Type Chart
      const typeCtx = document.getElementById('typeChart');
      if (typeCtx) {
        const typeChart = new Chart(typeCtx.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: <?php echo isset($facility_types) ? json_encode(array_column($facility_types, 'nama_tipe')) : '[]'; ?>,
            datasets: [{
              data: <?php echo isset($facility_types) ? json_encode(array_column($facility_types, 'total_units')) : '[]'; ?>,
              backgroundColor: [
                '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                '#f97316', '#14b8a6', '#6b7280', '#ec4899', '#06b6d4'
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
            },
            onClick: function(event, elements) {
              if (elements.length > 0) {
                const typeLabels = <?php echo isset($facility_types) ? json_encode(array_column($facility_types, 'nama_tipe')) : '[]'; ?>;
                const typeIds = <?php echo isset($facility_types) ? json_encode(array_column($facility_types, 'id')) : '[]'; ?>;
                const typeIndex = elements[0].index;
                const typeName = typeLabels[typeIndex];
                const typeId = typeIds[typeIndex];
                showFacilitiesByType(typeId, typeName);
              }
            }
          }
        });
      }

      // Facility Area Chart
      const areaCtx = document.getElementById('areaChart');
      if (areaCtx) {
        const areaChart = new Chart(areaCtx.getContext('2d'), {
          type: 'bar',
          data: {
            labels: <?php echo isset($areas) ? json_encode(array_column($areas, 'nama_area')) : '[]'; ?>,
            datasets: [{
              label: 'Jumlah Fasilitas',
              data: <?php echo isset($areas) ? json_encode(array_column($areas, 'facility_count')) : '[]'; ?>,
              backgroundColor: '#3b82f6',
              borderColor: '#2563eb',
              borderWidth: 1,
              borderRadius: 4,
              hoverBackgroundColor: '#2563eb'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  stepSize: 1,
                  font: {
                    size: 11
                  }
                },
                grid: {
                  color: 'rgba(0, 0, 0, 0.05)'
                }
              },
              x: {
                ticks: {
                  font: {
                    size: 11
                  }
                },
                grid: {
                  display: false
                }
              }
            },
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                  size: 13
                },
                bodyFont: {
                  size: 12
                },
                callbacks: {
                  afterLabel: function(context) {
                    const areaLabels = <?php echo isset($areas) ? json_encode(array_column($areas, 'nama_area')) : '[]'; ?>;
                    const areaIds = <?php echo isset($areas) ? json_encode(array_column($areas, 'id')) : '[]'; ?>;
                    const areaIndex = context.dataIndex;
                    const areaName = areaLabels[areaIndex];
                    const areaId = areaIds[areaIndex];
                    return 'Klik untuk detail';
                  }
                }
              }
            },
            onClick: function(event, elements) {
              if (elements.length > 0) {
                const areaLabels = <?php echo isset($areas) ? json_encode(array_column($areas, 'nama_area')) : '[]'; ?>;
                const areaIds = <?php echo isset($areas) ? json_encode(array_column($areas, 'id')) : '[]'; ?>;
                const areaIndex = elements[0].index;
                const areaName = areaLabels[areaIndex];
                const areaId = areaIds[areaIndex];
                showFacilitiesByArea(areaId, areaName);
              }
            }
          }
        });
      }

      // Contract Status Chart
      const contractCtx = document.getElementById('contractChart');
      if (contractCtx) {
        // Load contract status data
        fetch('<?php echo site_url('dashboard/reports/get_chart_data?type=contract_status'); ?>')
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              new Chart(contractCtx.getContext('2d'), {
                type: 'pie',
                data: {
                  labels: data.data.map(item => item.status),
                  datasets: [{
                    data: data.data.map(item => item.count),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                  }]
                },
                options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  plugins: {
                    legend: {
                      position: 'bottom'
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
          })
          .catch(error => console.error('Error loading contract status:', error));
      }

      // Year Chart
      const yearCtx = document.getElementById('yearChart');
      if (yearCtx) {
        // Load year data
        fetch('<?php echo site_url('dashboard/reports/get_chart_data?type=by_year'); ?>')
          .then(response => response.json())
          .then(data => {
            console.log('Year data response:', data); // Debug log
            if (data.success && data.data && data.data.length > 0) {
              new Chart(yearCtx.getContext('2d'), {
                type: 'line',
                data: {
                  labels: data.data.map(item => item.year),
                  datasets: [{
                    label: 'Jumlah Fasilitas',
                    data: data.data.map(item => item.facility_count),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                  }]
                },
                options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                    y: {
                      beginAtZero: true,
                      ticks: {
                        stepSize: 1
                      }
                    }
                  },
                  plugins: {
                    legend: {
                      display: false
                    },
                    tooltip: {
                      callbacks: {
                        label: function(context) {
                          return `Jumlah: ${context.parsed.y} fasilitas`;
                        }
                      }
                    }
                  }
                }
              });
            } else {
              console.log('No year data available or empty data');
              // Show no data message
              yearCtx.parentElement.innerHTML = '<div class="text-center text-muted p-4">Tidak ada data fasilitas per tahun</div>';
            }
          })
          .catch(error => {
            console.error('Error loading year data:', error);
            yearCtx.parentElement.innerHTML = '<div class="text-center text-danger p-4">Gagal memuat data grafik</div>';
          });
      }
    });

    // Export functionality enhancement
    function exportReport(format) {
      if (format === 'excel') {
        window.location.href = '<?php echo site_url('dashboard/reports/export_excel'); ?>';
      } else if (format === 'print') {
        window.print();
      }
    }

    // Filter functions
    function applyFilters() {
      const area = document.getElementById('filterArea').value;
      const type = document.getElementById('filterType').value;
      const vendor = document.getElementById('filterVendor').value;

      // Build URL with filters
      let url = '<?php echo site_url('dashboard/reports'); ?>';
      const params = new URLSearchParams();

      if (area) params.append('area', area);
      if (type) params.append('type', type);
      if (vendor) params.append('vendor', vendor);

      if (params.toString()) {
        url += '?' + params.toString();
      }

      window.location.href = url;
    }

    function resetFilters() {
      document.getElementById('filterArea').value = '';
      document.getElementById('filterType').value = '';
      document.getElementById('filterVendor').value = '';
      window.location.href = '<?php echo site_url('dashboard/reports'); ?>';
    }

    function exportPDF() {
      // Show loading
      Swal.fire({
        title: 'Menghasilkan PDF...',
        html: 'Mohon tunggu, laporan sedang dibuat.',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // Simulate PDF generation (replace with actual implementation)
      setTimeout(() => {
        Swal.close();
        showToast('Success', 'PDF akan segera tersedia untuk diunduh.', 'success');
      }, 2000);
    }

    function generateCustomReport() {
      Swal.fire({
        title: 'Laporan Kustom',
        html: `
            <div class="form-group">
                <label for="customTitle">Judul Laporan</label>
                <input type="text" id="customTitle" class="swal2-input" placeholder="Masukkan judul laporan">
            </div>
            <div class="form-group">
                <label for="customDateRange">Rentang Tanggal</label>
                <select id="customDateRange" class="swal2-input">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                    <option value="90">3 Bulan Terakhir</option>
                    <option value="365">1 Tahun Terakhir</option>
                    <option value="custom">Kustom</option>
                </select>
            </div>
            <div class="form-group">
                <label for="customFormat">Format</label>
                <select id="customFormat" class="swal2-input">
                    <option value="excel">Excel</option>
                    <option value="pdf">PDF</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
        `,
        confirmButtonText: 'Buat Laporan',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        preConfirm: () => {
          const title = document.getElementById('customTitle').value;
          const dateRange = document.getElementById('customDateRange').value;
          const format = document.getElementById('customFormat').value;

          if (!title) {
            Swal.showValidationMessage('Judul laporan harus diisi');
            return false;
          }

          return {
            title,
            dateRange,
            format
          };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const {
            title,
            dateRange,
            format
          } = result.value;

          // Show loading
          Swal.fire({
            title: 'Membuat Laporan...',
            html: `Laporan "${title}" sedang dibuat.`,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // Simulate report generation (replace with actual implementation)
          setTimeout(() => {
            Swal.close();
            showToast('Success', `Laporan "${title}" telah dibuat dan siap diunduh.`, 'success');
          }, 2000);
        }
      });
    }

    // Add print styles
    window.addEventListener('beforeprint', function() {
      document.body.classList.add('printing');
    });

    window.addEventListener('afterprint', function() {
      document.body.classList.remove('printing');
    });

    // Refresh newest facilities
    function refreshNewestFacilities() {
      const tableBody = document.querySelector('#newestFacilitiesTable tbody');
      
      // Show loading
      tableBody.innerHTML = `
        <tr>
          <td colspan="7" class="text-center">
            <div class="spinner-border spinner-border-sm" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            Memuat data...
          </td>
        </tr>
      `;

      // Fetch newest facilities
      fetch('<?php echo site_url('dashboard/reports/get_newest_facilities'); ?>')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data.length > 0) {
            let html = '';
            let no = 1;
            
            data.data.forEach(facility => {
              const today = new Date().toISOString().split('T')[0];
              let statusBadge = 'success';
              let statusText = 'Aktif';
              
              if (facility.akhir_sewa < today) {
                statusBadge = 'danger';
                statusText = 'Kadaluarsa';
              } else if (new Date(facility.akhir_sewa) <= new Date(Date.now() + 30 * 24 * 60 * 60 * 1000)) {
                statusBadge = 'warning';
                statusText = 'Akan Kadaluarsa';
              }
              
              html += `
                <tr>
                  <td>${no++}</td>
                  <td><span class="badge badge-info">${facility.nama_tipe || '-'}</span></td>
                  <td>
                    <strong>${facility.tipe || '-'}</strong><br>
                    <small class="text-muted">${facility.nama_area || '-'}</small>
                  </td>
                  <td>${facility.nama_vendor || '-'}</td>
                  <td>${facility.tahun_unit || '-'}</td>
                  <td><span class="badge badge-${statusBadge}">${statusText}</span></td>
                  <td><small>${new Date(facility.created_at).toLocaleString('id-ID')}</small></td>
                </tr>
              `;
            });
            
            tableBody.innerHTML = html;
          } else {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada data fasilitas baru</td></tr>';
          }
        })
        .catch(error => {
          console.error('Error loading newest facilities:', error);
          tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>';
        });
    }

    // Show detailed facilities by type
    function showFacilitiesByType(typeId, typeName) {
      Swal.fire({
        title: `Fasilitas Tipe: ${typeName}`,
        html: `
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Memuat data fasilitas...</p>
          </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        width: '80%'
      });

      fetch(`<?php echo site_url('dashboard/reports/get_facilities_by_type_detailed'); ?>?type_id=${typeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            let html = `
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tipe</th>
                      <th>Area</th>
                      <th>Vendor</th>
                      <th>Tahun</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
            `;
            
            if (data.data.length > 0) {
              data.data.forEach((facility, index) => {
                const today = new Date().toISOString().split('T')[0];
                let statusBadge = 'success';
                let statusText = 'Aktif';
                
                if (facility.akhir_sewa < today) {
                  statusBadge = 'danger';
                  statusText = 'Kadaluarsa';
                } else if (new Date(facility.akhir_sewa) <= new Date(Date.now() + 30 * 24 * 60 * 60 * 1000)) {
                  statusBadge = 'warning';
                  statusText = 'Akan Kadaluarsa';
                }
                
                html += `
                  <tr>
                    <td>${index + 1}</td>
                    <td>${facility.tipe || '-'}</td>
                    <td>${facility.nama_area || '-'}</td>
                    <td>${facility.nama_vendor || '-'}</td>
                    <td>${facility.tahun_unit || '-'}</td>
                    <td><span class="badge badge-${statusBadge}">${statusText}</span></td>
                  </tr>
                `;
              });
            } else {
              html += '<tr><td colspan="6" class="text-center">Tidak ada fasilitas untuk tipe ini</td></tr>';
            }
            
            html += '</tbody></table></div>';
            
            Swal.fire({
              title: `Fasilitas Tipe: ${typeName}`,
              html: html,
              width: '80%',
              showConfirmButton: true,
              confirmButtonText: 'Tutup',
              showCloseButton: true
            });
          } else {
            Swal.fire('Error', 'Gagal memuat data fasilitas', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
        });
    }

    // Show detailed facilities by area
    function showFacilitiesByArea(areaId, areaName) {
      Swal.fire({
        title: `Fasilitas Area: ${areaName}`,
        html: `
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Memuat data fasilitas...</p>
          </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        width: '80%'
      });

      fetch(`<?php echo site_url('dashboard/reports/get_facilities_by_area_detailed'); ?>?area_id=${areaId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            let html = `
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tipe Fasilitas</th>
                      <th>Nama</th>
                      <th>Vendor</th>
                      <th>Tahun</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
            `;
            
            if (data.data.length > 0) {
              data.data.forEach((facility, index) => {
                const today = new Date().toISOString().split('T')[0];
                let statusBadge = 'success';
                let statusText = 'Aktif';
                
                if (facility.akhir_sewa < today) {
                  statusBadge = 'danger';
                  statusText = 'Kadaluarsa';
                } else if (new Date(facility.akhir_sewa) <= new Date(Date.now() + 30 * 24 * 60 * 60 * 1000)) {
                  statusBadge = 'warning';
                  statusText = 'Akan Kadaluarsa';
                }
                
                html += `
                  <tr>
                    <td>${index + 1}</td>
                    <td><span class="badge badge-info">${facility.nama_tipe || '-'}</span></td>
                    <td>${facility.tipe || '-'}</td>
                    <td>${facility.nama_vendor || '-'}</td>
                    <td>${facility.tahun_unit || '-'}</td>
                    <td><span class="badge badge-${statusBadge}">${statusText}</span></td>
                  </tr>
                `;
              });
            } else {
              html += '<tr><td colspan="6" class="text-center">Tidak ada fasilitas di area ini</td></tr>';
            }
            
            html += '</tbody></table></div>';
            
            Swal.fire({
              title: `Fasilitas Area: ${areaName}`,
              html: html,
              width: '80%',
              showConfirmButton: true,
              confirmButtonText: 'Tutup',
              showCloseButton: true
            });
          } else {
            Swal.fire('Error', 'Gagal memuat data fasilitas', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
        });
    }
  </script>

  <style>
    /* Print-specific styles */
    @media print {
      .no-print {
        display: none !important;
      }

      .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
      }

      .card {
        border: 1px solid #ddd !important;
        break-inside: avoid;
        margin-bottom: 20px;
      }

      .card-header {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
      }

      .table {
        font-size: 12px;
      }

      .chart-container {
        page-break-inside: avoid;
      }
    }

    /* Enhanced card styles */
    .card {
      border: none;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.2s ease;
    }

    .card:hover {
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #f8f9fa;
      border-bottom: 1px solid #e9ecef;
      padding: 1rem 1.25rem;
    }

    .card-title {
      font-size: 1rem;
      font-weight: 600;
      color: #495057;
      margin: 0;
    }

    /* Enhanced small box styles */
    .small-box {
      border-radius: 8px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      overflow: hidden;
    }

    .small-box:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .small-box .inner {
      padding: 1.25rem;
    }

    .small-box h3 {
      font-size: 2rem;
      font-weight: 700;
      margin: 0 0 0.5rem 0;
    }

    .small-box p {
      font-size: 0.875rem;
      margin: 0;
      opacity: 0.9;
    }

    .small-box .icon {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      font-size: 3rem;
      opacity: 0.8;
    }

    /* Enhanced table styles */
    .table {
      margin-bottom: 0;
    }

    .table th {
      border-top: none;
      font-weight: 600;
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      background-color: #f8f9fa;
    }

    .table td {
      vertical-align: middle;
      font-size: 0.875rem;
    }

    /* Enhanced button styles */
    .btn {
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .btn:hover {
      transform: translateY(-1px);
    }

    .btn-group .btn {
      margin-right: 0.5rem;
    }

    .btn-group .btn:last-child {
      margin-right: 0;
    }

    /* Chart container improvements */
    .chart-container {
      position: relative;
      height: 300px;
      width: 100%;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .small-box h3 {
        font-size: 1.5rem;
      }

      .small-box .icon {
        font-size: 2rem;
      }

      .card-header {
        padding: 0.75rem 1rem;
      }

      .table {
        font-size: 0.8rem;
      }
    }
  </style>

</body>

</html>