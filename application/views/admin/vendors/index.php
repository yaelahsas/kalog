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
              <li class="breadcrumb-item active">Vendor</li>
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

        <!-- Vendors Table -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $card_title; ?></h3>
            <?php if (can_add($session)) { ?>
            <div class="card-tools">
              <a href="<?php echo $btn_add['url']; ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <?php echo $btn_add['name']; ?>
              </a>
            </div>
            <?php } ?>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="vendorsTable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Vendor</th>
                    <th>Jumlah Fasilitas</th>
                    <th>Total Nilai Sewa</th>
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
    $('#vendorsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo site_url('admin/Vendors/data'); ?>",
            "type": "POST"
        },
        "columns": [
            { "data": null, "orderable": false, "searchable": false },
            { "data": "nama_vendor" },
            { "data": "facility_count" },
            { "data": "total_value" },
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
                "targets": 2,
                "render": function(data, type, row, meta) {
                    return data ? '<span class="badge badge-info">' + data + '</span>' : '<span class="badge badge-secondary">0</span>';
                }
            },
            {
                "targets": 3,
                "render": function(data, type, row, meta) {
                    return data ? 'Rp ' + parseFloat(data).toLocaleString('id-ID') : 'Rp 0';
                }
            },
            {
                "targets": 4,
                "render": function(data, type, row, meta) {
                    var buttons = '';
                    
                    // Edit button - only admin and root
                    <?php if (can_edit($session)) { ?>
                    buttons += `<a href="<?php echo site_url('dashboard/vendors/edit/'); ?>` + row.id + `" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>`;
                    <?php } ?>
                    
                    // Delete button - only admin and root
                    <?php if (can_delete($session)) { ?>
                    buttons += `<button type="button" class="btn btn-danger btn-sm" onclick="deleteVendor(` + row.id + `)">
                        <i class="fas fa-trash"></i>
                    </button>`;
                    <?php } ?>
                    
                    return `<div class="btn-group">` + buttons + `</div>`;
                }
            }
        ],
        "order": [[1, "asc"]]
    });
});

function deleteVendor(id) {
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
                url: "<?php echo site_url('admin/Vendors/delete/'); ?>" + id,
                type: "POST",
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        );
                        $('#vendorsTable').DataTable().ajax.reload();
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