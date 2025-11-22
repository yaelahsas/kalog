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
              <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard/vendors'); ?>">Vendor</a></li>
              <li class="breadcrumb-item active">Edit</li>
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

        <!-- Form Vendor -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $card_title; ?></h3>
            <div class="card-tools">
              <a href="<?php echo site_url('dashboard/vendors'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
            </div>
          </div>
          <div class="card-body">
            <?php echo form_open_multipart('dashboard/vendors/edit/'.$id, ['class' => 'form-horizontal']); ?>
              
              <div class="form-group row">
                <label for="nama_vendor" class="col-sm-2 col-form-label">Nama Vendor</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="nama_vendor" name="nama_vendor" value="<?php echo set_value('nama_vendor', $data->nama_vendor); ?>" placeholder="Masukkan Nama Vendor" required>
                  <?php echo form_error('nama_vendor', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                  </button>
                  <a href="<?php echo site_url('dashboard/vendors'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                  </a>
                </div>
              </div>

            <?php echo form_close(); ?>
          </div>
        </div>

      </div>
    </section>
  </div>

  <?php $this->load->view('admin/partials/footer') ?>
</div>

<?php $this->load->view('admin/partials/javascript') ?>

</body>
</html>