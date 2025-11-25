  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="<?= site_url('dashboard') ?>" class="brand-link">
      <img src="<?= base_url('assets/adminlte') ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Kalog System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?= base_url('uploads/account/'.$session['image']) ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="<?= site_url('dashboard') ?>" class="d-block"><?= $session['name'] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="<?= site_url('dashboard') ?>" class="nav-link <?php if($sidebar=='dashboard'){echo'active';}?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard Monitoring</p>
            </a>
          </li>

          <!-- Facilities Menu -->
          <?php if (can_view_menu('facilities', $session)) { ?>
          <?php $side_facilities = array('facilities','facility-detail','facility-add','facility-edit') ?>
          <li class="nav-item <?php if(in_array($sidebar, $side_facilities)){echo 'menu-open';}?>">
            <a href="#" class="nav-link <?php if(in_array($sidebar, $side_facilities)){echo 'active';}?>">
              <i class="nav-icon fas fa-truck-loading"></i>
              <p>
                Fasilitas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= site_url('dashboard/facilities') ?>" class="nav-link <?php if($sidebar=='facilities'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Fasilitas</p>
                </a>
              </li>
              <?php if (can_add($session)) { ?>
              <li class="nav-item">
                <a href="<?= site_url('dashboard/facilities/add') ?>" class="nav-link <?php if($sidebar=='facility-add'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tambah Fasilitas</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>

          <!-- Areas Menu -->
          <?php if (can_view_menu('areas', $session)) { ?>
          <?php $side_areas = array('areas','area-add','area-edit') ?>
          <li class="nav-item <?php if(in_array($sidebar, $side_areas)){echo 'menu-open';}?>">
            <a href="#" class="nav-link <?php if(in_array($sidebar, $side_areas)){echo 'active';}?>">
              <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                Area
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= site_url('dashboard/areas') ?>" class="nav-link <?php if($sidebar=='areas'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Area</p>
                </a>
              </li>
              <?php if (can_add($session)) { ?>
              <li class="nav-item">
                <a href="<?= site_url('dashboard/areas/add') ?>" class="nav-link <?php if($sidebar=='area-add'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tambah Area</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>

          <!-- Vendors Menu -->
          <?php if (can_view_menu('vendors', $session)) { ?>
          <?php $side_vendors = array('vendors','vendor-add','vendor-edit') ?>
          <li class="nav-item <?php if(in_array($sidebar, $side_vendors)){echo 'menu-open';}?>">
            <a href="#" class="nav-link <?php if(in_array($sidebar, $side_vendors)){echo 'active';}?>">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Vendor
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= site_url('dashboard/vendors') ?>" class="nav-link <?php if($sidebar=='vendors'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Vendor</p>
                </a>
              </li>
              <?php if (can_add($session)) { ?>
              <li class="nav-item">
                <a href="<?= site_url('dashboard/vendors/add') ?>" class="nav-link <?php if($sidebar=='vendor-add'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tambah Vendor</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>

          <!-- Facility Types Menu -->
          <?php if (can_view_menu('facility_types', $session)) { ?>
          <?php $side_types = array('facility-types','facility-type-add','facility-type-edit') ?>
          <li class="nav-item <?php if(in_array($sidebar, $side_types)){echo 'menu-open';}?>">
            <a href="#" class="nav-link <?php if(in_array($sidebar, $side_types)){echo 'active';}?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Jenis Fasilitas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= site_url('dashboard/facility_types') ?>" class="nav-link <?php if($sidebar=='facility-types'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Jenis</p>
                </a>
              </li>
              <?php if (can_add($session)) { ?>
              <li class="nav-item">
                <a href="<?= site_url('dashboard/facility_types/add') ?>" class="nav-link <?php if($sidebar=='facility-type-add'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tambah Jenis</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>

          <!-- Reports Menu -->
          <?php if (can_view_menu('reports', $session)) { ?>
          <li class="nav-item">
            <a href="<?= site_url('dashboard/reports') ?>" class="nav-link <?php if($sidebar=='reports'){echo'active';}?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Laporan</p>
            </a>
          </li>
          <?php } ?>

          <?php if (can_view_menu('account', $session) || can_view_menu('category', $session) || can_view_menu('phone', $session)) { ?>
          <li class="nav-header">MANAGEMENT</li>

          <?php if (can_view_menu('account', $session)) { ?>
          <?php $side_account = array('account-index','account-add'); ?>
          <li class="nav-item <?php if(in_array($sidebar, $side_account)){echo 'menu-open';}?>">
            <a href="#" class="nav-link <?php if(in_array($sidebar, $side_account)){echo 'active';}?>">
              <i class="nav-icon fas fa fa-user-circle"></i>
              <p>
                Account
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= site_url('admin/account/index') ?>" class="nav-link <?php if($sidebar==$side_account[0]){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Table</p>
                </a>
              </li>
              <?php if (can_add($session)) { ?>
              <li class="nav-item">
                <a href="<?= site_url('admin/account/add') ?>" class="nav-link <?php if($sidebar==$side_account[1]){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>

          <?php if (can_view_menu('category', $session)) { ?>
          <?php $side_category = array('category-index','category-add') ?>
          <li class="nav-item <?php if(in_array($sidebar, $side_category)){echo 'menu-open';}?>">
            <a href="#" class="nav-link <?php if(in_array($sidebar, $side_category)){echo 'active';}?>">
              <i class="nav-icon fas fa-tags"></i>
              <p>
                Category
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= site_url('admin/category/index') ?>" class="nav-link <?php if($sidebar==$side_category[0]){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Table</p>
                </a>
              </li>
              <?php if (can_add($session)) { ?>
              <li class="nav-item">
                <a href="<?= site_url('admin/category/add') ?>" class="nav-link <?php if($sidebar==$side_category[1]){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>

          <?php if (can_view_menu('phone', $session)) { ?>
          <?php $side_phone = array('phone-index','phone-add') ?>
          <li class="nav-item <?php if(in_array($sidebar, $side_phone)){echo 'menu-open';}?>">
            <a href="#" class="nav-link <?php if(in_array($sidebar, $side_phone)){echo 'active';}?>">
              <i class="nav-icon fas fa-phone"></i>
              <p>
                Phone
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= site_url('admin/phone/index') ?>" class="nav-link <?php if($sidebar==$side_phone[0]){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Table</p>
                </a>
              </li>
              <?php if (can_add($session)) { ?>
              <li class="nav-item">
                <a href="<?= site_url('admin/phone/add') ?>" class="nav-link <?php if($sidebar==$side_phone[1]){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>
          <?php } ?>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>