<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardMonitoring extends CI_Controller {
    private $sess;

    public function __construct(){
        parent::__construct();
        $this->sess = $this->M_Auth->session(array('root','admin'));
        if ($this->sess === FALSE) {
            redirect(site_url('admin/auth/logout'),'refresh');
        }
        $this->load->model(['M_Facility', 'M_Area', 'M_Vendor', 'M_FacilityType']);
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
    }

    // Main dashboard page
    public function index() {
        $data['title'] = 'Dashboard Monitoring Fasilitas';
        $data['sidebar'] = 'dashboard';
        $data['layout'] = 'layout-navbar-fixed pace-warning';
        
        // Get dashboard statistics
        $data['stats'] = $this->M_Facility->get_dashboard_stats();
        
        // Get expiring contracts
        $data['expiring_contracts'] = $this->M_Facility->get_expiring_contracts();
        
        // Get recent facilities
        $data['recent_facilities'] = $this->M_Facility->get_all_facilities();
        
        // Get areas with facility count
        $data['areas'] = $this->M_Area->get_areas_with_facility_count();
        
        // Get facility types with count
        $data['facility_types'] = $this->M_FacilityType->get_facility_types_with_count();
        
        // Get vendor statistics
        $data['vendor_stats'] = $this->M_Vendor->get_vendor_stats();
        
        $this->load->view('admin/dashboard/index', $data);
    }
}