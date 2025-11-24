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
              <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard/facilities'); ?>">Fasilitas</a></li>
              <li class="breadcrumb-item active">Add</li>
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

        <!-- Form Facility -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $card_title; ?></h3>
            <div class="card-tools">
              <a href="<?php echo site_url('dashboard/facilities'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
            </div>
          </div>
          <div class="card-body">
            <?php echo form_open_multipart('dashboard/facilities/add', ['class' => 'form-horizontal']); ?>
              
              <div class="form-group row">
                <label for="area_id" class="col-sm-2 col-form-label">Area</label>
                <div class="col-sm-10">
                  <select class="form-control" id="area_id" name="area_id" required>
                    <option value="">Pilih Area</option>
                    <?php foreach($areas as $area): ?>
                      <option value="<?php echo $area->id; ?>"><?php echo $area->nama_area; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('area_id', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="facility_type_id" class="col-sm-2 col-form-label">Jenis Fasilitas</label>
                <div class="col-sm-10">
                  <select class="form-control" id="facility_type_id" name="facility_type_id" required>
                    <option value="">Pilih Jenis Fasilitas</option>
                    <?php foreach($facility_types as $type): ?>
                      <option value="<?php echo $type->id; ?>"><?php echo $type->nama_tipe; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('facility_type_id', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="vendor_id" class="col-sm-2 col-form-label">Vendor</label>
                <div class="col-sm-10">
                  <select class="form-control" id="vendor_id" name="vendor_id">
                    <option value="">Pilih Vendor</option>
                    <?php foreach($vendors as $vendor): ?>
                      <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->nama_vendor; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('vendor_id', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="tipe" class="col-sm-2 col-form-label">Tipe</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tipe" name="tipe" value="<?php echo set_value('tipe'); ?>" placeholder="Masukkan Tipe" required>
                  <?php echo form_error('tipe', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="kapasitas" class="col-sm-2 col-form-label">Kapasitas</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="kapasitas" name="kapasitas" value="<?php echo set_value('kapasitas'); ?>" placeholder="Masukkan Kapasitas">
                  <?php echo form_error('kapasitas', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="jumlah" class="col-sm-2 col-form-label">Jumlah</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo set_value('jumlah'); ?>" placeholder="Masukkan Jumlah" required>
                  <?php echo form_error('jumlah', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="tahun_unit" class="col-sm-2 col-form-label">Tahun Unit</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="tahun_unit" name="tahun_unit" value="<?php echo set_value('tahun_unit'); ?>" placeholder="Masukkan Tahun Unit">
                  <?php echo form_error('tahun_unit', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="awal_sewa" class="col-sm-2 col-form-label">Awal Sewa</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="awal_sewa" name="awal_sewa" value="<?php echo set_value('awal_sewa'); ?>">
                  <?php echo form_error('awal_sewa', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="akhir_sewa" class="col-sm-2 col-form-label">Akhir Sewa</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="akhir_sewa" name="akhir_sewa" value="<?php echo set_value('akhir_sewa'); ?>">
                  <?php echo form_error('akhir_sewa', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="total_harga_sewa" class="col-sm-2 col-form-label">Total Harga Sewa</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="total_harga_sewa" name="total_harga_sewa" value="<?php echo set_value('total_harga_sewa'); ?>" placeholder="Masukkan Total Harga Sewa">
                  <?php echo form_error('total_harga_sewa', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="no_perjanjian" class="col-sm-2 col-form-label">No. Perjanjian</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="no_perjanjian" name="no_perjanjian" value="<?php echo set_value('no_perjanjian'); ?>" placeholder="Masukkan No. Perjanjian">
                  <?php echo form_error('no_perjanjian', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="dokumen_perjanjian" class="col-sm-2 col-form-label">Dokumen Perjanjian</label>
                <div class="col-sm-10">
                  <input type="file" class="form-control" id="dokumen_perjanjian" name="dokumen_perjanjian" accept=".jpg,.jpeg,.png,.pdf">
                  <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, PDF (Maksimal 2MB)</small>
                  <?php echo form_error('dokumen_perjanjian', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                  <select class="form-control" id="status" name="status" required>
                    <option value="">Pilih Status</option>
                    <option value="active" <?php echo set_select('status', 'active'); ?>>Active</option>
                    <option value="inactive" <?php echo set_select('status', 'inactive'); ?>>Inactive</option>
                    <option value="maintenance" <?php echo set_select('status', 'maintenance'); ?>>Maintenance</option>
                  </select>
                  <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan Keterangan"><?php echo set_value('keterangan'); ?></textarea>
                  <?php echo form_error('keterangan', '<small class="text-danger">', '</small>'); ?>
                </div>
              </div>

              <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                  </button>
                  <a href="<?php echo site_url('dashboard/facilities'); ?>" class="btn btn-secondary">
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