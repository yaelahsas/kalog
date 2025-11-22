<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['M_Facility', 'M_Area', 'M_Vendor', 'M_FacilityType', 'M_Auth']);
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
        $this->load->database();
        
        // Get session data
        $session_data = $this->M_Auth->session(array('root','admin'));
        if ($session_data === FALSE) {
            redirect(site_url('admin/auth/logout'),'refresh');
        }
        
        // Format session data to match expected format
        $this->sess = $session_data;
        $this->sess['account_id'] = $session_data['id']; // Add account_id for compatibility
    }

    // Main dashboard page
    public function index() {
        $data['title'] = 'Dashboard Monitoring Fasilitas';
        
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
        
        // Add session data
        $data['session'] = $this->sess;
        
        // Add layout configuration
        $data['datatables'] = true;
        $data['icheck']     = true;
        $data['switch']     = false;
        $data['select2']    = false;
        $data['daterange']  = false;
        $data['colorpicker']= false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        $data['sidebar']    = 'dashboard';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        
        // Load new design system views
        $this->load->view('admin/partials/head-new', $data);
        $this->load->view('admin/partials/sidebar-new', $data);
        $this->load->view('admin/partials/header-new', $data);
        $this->load->view('admin/dashboard/index-new', $data);
        $this->load->view('admin/partials/footer-new', $data);
    }

    // Facilities list page
    public function facilities() {
        $data['title'] = 'Daftar Fasilitas';
        
        // Get all facilities
        $data['facilities'] = $this->M_Facility->get_all_facilities();
        
        // Get areas for filter
        $data['areas'] = $this->M_Area->get_all_areas();
        
        // Get facility types for filter
        $data['facility_types'] = $this->M_FacilityType->get_all_facility_types();
        
        // Get vendors for filter
        $data['vendors'] = $this->M_Vendor->get_all_vendors();
        
        // Add session data
        $data['session'] = $this->sess;
        
        // Add layout configuration
        $data['datatables'] = false;
        $data['icheck']     = true;
        $data['switch']     = false;
        $data['select2']    = true;  // Enable select2 for filters
        $data['daterange']  = false;
        $data['colorpicker']= false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        $data['sidebar']    = 'facilities';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        
        // Load new design system views
        $this->load->view('admin/partials/head-new', $data);
        $this->load->view('admin/partials/sidebar-new', $data);
        $this->load->view('admin/partials/header-new', $data);
        $this->load->view('admin/facilities/index-new', $data);
        $this->load->view('admin/partials/footer-new', $data);
    }

    // Filter facilities
    public function filter_facilities() {
        $area_id = $this->input->post('area_id');
        $facility_type_id = $this->input->post('facility_type_id');
        $vendor_id = $this->input->post('vendor_id');
        $status = $this->input->post('status');
        
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        
        if ($area_id) {
            $this->db->where('facilities.area_id', $area_id);
        }
        
        if ($facility_type_id) {
            $this->db->where('facilities.facility_type_id', $facility_type_id);
        }
        
        if ($vendor_id) {
            $this->db->where('facilities.vendor_id', $vendor_id);
        }
        
        if ($status) {
            $this->db->where('facilities.status', $status);
        }
        
        $data['facilities'] = $this->db->get()->result();
        
        $this->load->view('admin/facilities/filter_result', $data);
    }

    // Search facilities
    public function search() {
        $keyword = $this->input->get('keyword');
        
        if ($keyword) {
            $data['facilities'] = $this->M_Facility->search_facilities($keyword);
        } else {
            $data['facilities'] = $this->M_Facility->get_all_facilities();
        }
        
        $data['title'] = 'Hasil Pencarian Fasilitas';
        $data['keyword'] = $keyword;
        
        $this->load->view('admin/partials/head', $data);
        $this->load->view('admin/partials/navbar');
        $this->load->view('admin/partials/sidebar');
        $this->load->view('admin/facilities/search_result', $data);
        $this->load->view('admin/partials/footer');
        $this->load->view('admin/partials/javascript');
    }

    // Facility detail page
    public function facility_detail($id) {
        $data['title'] = 'Detail Fasilitas';
        $data['facility'] = $this->M_Facility->get_facility_by_id($id);
        
        if (!$data['facility']) {
            show_404();
        }
        
        $this->load->view('admin/partials/head', $data);
        $this->load->view('admin/partials/navbar');
        $this->load->view('admin/partials/sidebar');
        $this->load->view('admin/facilities/detail', $data);
        $this->load->view('admin/partials/footer');
        $this->load->view('admin/partials/javascript');
    }

    // Reports page
    public function reports() {
        $data['title'] = 'Laporan Monitoring Fasilitas';
        
        // Get statistics for reports
        $data['stats'] = $this->M_Facility->get_dashboard_stats();
        $data['areas'] = $this->M_Area->get_areas_with_facility_count();
        $data['facility_types'] = $this->M_FacilityType->get_type_statistics();
        $data['vendor_stats'] = $this->M_Vendor->get_vendor_stats();
        
        $this->load->view('admin/partials/head', $data);
        $this->load->view('admin/partials/navbar');
        $this->load->view('admin/partials/sidebar');
        $this->load->view('admin/reports/index', $data);
        $this->load->view('admin/partials/footer');
        $this->load->view('admin/partials/javascript');
    }

    // API endpoint for charts
    public function get_chart_data() {
        $type = $this->input->get('type');
        
        switch ($type) {
            case 'by_type':
                $data = $this->M_FacilityType->get_facility_types_with_count();
                break;
            case 'by_area':
                $data = $this->M_Area->get_areas_with_facility_count();
                break;
            case 'by_year':
                $stats = $this->M_Facility->get_dashboard_stats();
                $data = $stats['by_year'];
                break;
            case 'by_vendor':
                $data = $this->M_Vendor->get_vendor_stats();
                break;
            default:
                $data = [];
        }
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Export to Excel
    public function export_excel() {
        // Simple CSV export as alternative to PHPExcel
        $facilities = $this->M_Facility->get_all_facilities();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="data_fasilitas_' . date('Y-m-d') . '.csv"');
        header('Cache-Control: max-age=0');
        
        $output = fopen('php://output', 'w');
        
        // Add header
        fputcsv($output, [
            'No', 'Area', 'Jenis Fasilitas', 'Tipe', 'Kapasitas',
            'Jumlah', 'Tahun Unit', 'Vendor', 'Awal Sewa', 'Akhir Sewa',
            'Total Harga Sewa', 'No. Perjanjian'
        ]);
        
        // Add data
        $no = 1;
        foreach ($facilities as $facility) {
            fputcsv($output, [
                $no,
                $facility->nama_area,
                $facility->nama_tipe,
                $facility->tipe,
                $facility->kapasitas,
                $facility->jumlah,
                $facility->tahun_unit,
                $facility->nama_vendor,
                $facility->awal_sewa,
                $facility->akhir_sewa,
                $facility->total_harga_sewa,
                $facility->no_perjanjian
            ]);
            $no++;
        }
        
        fclose($output);
        exit;
    }
}