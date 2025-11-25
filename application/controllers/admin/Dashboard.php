<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
  private $sess;

  public function __construct(){
    parent::__construct();
    $this->load->model(['M_Auth', 'M_Facility', 'M_Area', 'M_Vendor', 'M_FacilityType']);
    $this->load->helper('permission'); // Load permission helper
    
    // FIX: Allow 'user' role access to dashboard
    $this->sess = $this->M_Auth->session(array('root','admin','user'));
    if ($this->sess === FALSE) {
      redirect(site_url('admin/auth/logout'),'refresh');
    }
 }

  
  // ==============================================
  //               LOAD VIEW
  // ==============================================

  public function index(){
    $data['datatables'] = false;
    $data['icheck']     = true;
    $data['switch']     = false;
    $data['select2']    = false;
    $data['daterange']  = false;
    $data['colorpicker']= false;
    $data['inputmask']  = false;
    $data['dropzonejs'] = false;
    $data['summernote'] = false;
    $data['session']    = $this->sess;
    $data['sidebar']    = 'dashboard';
    $data['layout']     = 'layout-navbar-fixed pace-warning';
    $data['title']      = 'Dashboard Monitoring Fasilitas';
    $data['swal'] = array(
      'type' => 'default',
      'button'  => NULL,
      'url' => NULL,
    );
    $data['breadcrumb'] = array(
      'Home'  => site_url('admin/dashboard/index'),
      'Dashboard' => site_url('admin/dashboard/index'),
    );

    // Get dashboard statistics
    $data['stats'] = $this->M_Facility->get_dashboard_stats();
    
    // Get expiring contracts
    $data['expiring_contracts'] = $this->M_Facility->get_expiring_contracts();
    
    // Get recent facilities
    $data['recent_facilities'] = $this->M_Facility->get_newest_facilities(10);
    
    // Get areas with facility count
    $data['areas'] = $this->M_Area->get_areas_with_facility_count();
    
    // Get facility types with count
    $data['facility_types'] = $this->M_FacilityType->get_facility_types_with_count();
    
    // Get vendor statistics
    $data['vendor_stats'] = $this->M_Vendor->get_vendor_stats();

    $this->load->view('admin/dashboard/index.php', $data);
  }

}

?>