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
            <h1 class="m-0"><?php echo $title; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Home</a></li>
              <li class="breadcrumb-item active">Fasilitas</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Notification -->
        <?php if($this->session->flashdata('notif')): ?>
          <?php echo $this->session->flashdata('notif'); ?>
        <?php endif; ?>

        <!-- Facilities Table -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $card_title; ?></h3>
            <div class="card-tools">
              <a href="<?php echo $btn_add['url']; ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <?php echo $btn_add['name']; ?>
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="facilitiesTable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Area</th>
                    <th>Jenis Fasilitas</th>
                    <th>Tipe</th>
                    <th>Kapasitas</th>
                    <th>Jumlah</th>
                    <th>Tahun Unit</th>
                    <th>Vendor</th>
                    <th>Awal Sewa</th>
                    <th>Akhir Sewa</th>
                    <th>Total Harga Sewa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data akan diisi melalui AJAX -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <?php $this->load->view('admin/partials/footer') ?>
</div>

<?php $this->load->view('admin/partials/javascript') ?>

<script>
$(document).ready(function() {
    $('#facilitiesTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo site_url('admin/facilities/data'); ?>",
            "type": "POST"
        },
        "columns": [
            { "data": null, "orderable": false, "searchable": false },
            { "data": "nama_area" },
            { "data": "nama_tipe" },
            { "data": "tipe" },
            { "data": "kapasitas" },
            { "data": "jumlah" },
            { "data": "tahun_unit" },
            { "data": "nama_vendor" },
            { "data": "awal_sewa" },
            { "data": "akhir_sewa" },
            { "data": "total_harga_sewa" },
            { "data": "status" },
            { "data": null, "orderable": false, "searchable": false }
        ],
        "columnDefs": [
            {
                "targets": 0,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "targets": 7,
                "render": function(data, type, row, meta) {
                    return data ? data : '-';
                }
            },
            {
                "targets": 8,
                "render": function(data, type, row, meta) {
                    return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                }
            },
            {
                "targets": 9,
                "render": function(data, type, row, meta) {
                    return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                }
            },
            {
                "targets": 10,
                "render": function(data, type, row, meta) {
                    return data ? 'Rp ' + parseFloat(data).toLocaleString('id-ID') : '-';
                }
            },
            {
                "targets": 11,
                "render": function(data, type, row, meta) {
                    var badgeClass = data == 'active' ? 'success' : (data == 'maintenance' ? 'warning' : 'danger');
                    return '<span class="badge badge-' + badgeClass + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                }
            },
            {
                "targets": 12,
                "render": function(data, type, row, meta) {
                    return `
                        <div class="btn-group">
                            <a href="<?php echo site_url('dashboard/facilities/detail/'); ?>` + row.id + `" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo site_url('dashboard/facilities/edit/'); ?>` + row.id + `" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteFacility(` + row.id + `)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        "order": [[1, "asc"]]
    });
});

function deleteFacility(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo site_url('admin/facilities/delete_ajax/'); ?>" + id,
                type: "POST",
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        );
                        $('#facilitiesTable').DataTable().ajax.reload();
                    } else {
                        Swal.fire(
                            'Gagal!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data',
                        'error'
                    );
                }
            });
        }
    });
}
</script>

</body>
</html>