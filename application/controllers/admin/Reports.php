<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
    private $sess;

    public function __construct(){
        parent::__construct();
        
        // Load database first
        $this->load->database();
        
        // Load models
        $this->load->model(['M_Facility', 'M_Area', 'M_Vendor', 'M_FacilityType', 'M_Auth']);
        
        // Load helpers and libraries
        $this->load->helper(['url', 'form', 'date']);
        $this->load->library(['session', 'form_validation']);
        
        // Check session
        $this->sess = $this->M_Auth->session(array('root','admin'));
        if ($this->sess === FALSE) {
            redirect(site_url('admin/auth/logout'),'refresh');
        }
    }

    // Reports page
    public function index() {
        $data['title'] = 'Laporan Monitoring Fasilitas';
        $data['sidebar'] = 'reports';
        $data['layout'] = 'layout-navbar-fixed pace-warning';
        $data['body_class'] = 'reports-page';
        
        // Get statistics for reports
        $data['stats'] = $this->get_dashboard_stats();
        $data['areas'] = $this->get_areas_with_facility_count();
        $data['facility_types'] = $this->get_type_statistics();
        $data['vendor_stats'] = $this->get_vendor_stats();
        $data['newest_facilities'] = $this->M_Facility->get_newest_facilities(10);
        
        // Pass session data to the view
        $data['session'] = $this->sess;
        
        $this->load->view('admin/reports/index', $data);
    }

    // Export to Excel
    public function export_excel() {
        // Check if user has permission
        if (!$this->sess) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized access']));
            return;
        }
        
        try {
            // Get all facilities with related data
            $this->db->select([
                'facilities.*',
                'areas.nama_area',
                'facility_types.nama_tipe',
                'vendors.nama_vendor'
            ]);
            $this->db->from('facilities');
            $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
            $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
            $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
            $this->db->order_by('facilities.id', 'ASC');
            $facilities = $this->db->get()->result();
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment;filename="laporan_fasilitas_' . date('Y-m-d_H-i-s') . '.csv"');
            header('Cache-Control: max-age=0');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            $output = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel display
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add header
            fputcsv($output, [
                'No', 'ID Fasilitas', 'Area', 'Jenis Fasilitas', 'Tipe', 'Kapasitas',
                'Jumlah Unit', 'Tahun Unit', 'Vendor', 'Awal Sewa', 'Akhir Sewa',
                'Total Harga Sewa', 'No. Perjanjian', 'Status'
            ]);
            
            // Add data
            $no = 1;
            foreach ($facilities as $facility) {
                // Calculate contract status
                $today = date('Y-m-d');
                $status = 'Aktif';
                if ($facility->akhir_sewa < $today) {
                    $status = 'Kadaluarsa';
                } elseif (strtotime($facility->akhir_sewa) <= strtotime('+30 days')) {
                    $status = 'Akan Kadaluarsa';
                }
                
                fputcsv($output, [
                    $no,
                    $facility->id,
                    $facility->nama_area ?? '-',
                    $facility->nama_tipe ?? '-',
                    $facility->tipe ?? '-',
                    $facility->kapasitas ?? '-',
                    $facility->jumlah ?? 0,
                    $facility->tahun_unit ?? '-',
                    $facility->nama_vendor ?? '-',
                    $facility->awal_sewa ? date('d/m/Y', strtotime($facility->awal_sewa)) : '-',
                    $facility->akhir_sewa ? date('d/m/Y', strtotime($facility->akhir_sewa)) : '-',
                    $facility->total_harga_sewa ? number_format($facility->total_harga_sewa, 0, ',', '.') : '0',
                    $facility->no_perjanjian ?? '-',
                    $status
                ]);
                $no++;
            }
            
            // Add summary row
            fputcsv($output, []);
            fputcsv($output, ['SUMMARY']);
            fputcsv($output, ['Total Fasilitas', count($facilities)]);
            
            $total_value = array_sum(array_column($facilities, 'total_harga_sewa'));
            fputcsv($output, ['Total Nilai Sewa', 'Rp ' . number_format($total_value, 0, ',', '.')]);
            
            fputcsv($output, ['Tanggal Export', date('d/m/Y H:i:s')]);
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            // Log error and show user-friendly message
            log_message('error', 'Export Excel Error: ' . $e->getMessage());
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi.'
                ]));
        }
    }

    // API endpoint for charts
    public function get_chart_data() {
        // Check if user has permission
        if (!$this->sess) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized access']));
            return;
        }
        
        $type = $this->input->get('type');
        $data = [];
        
        try {
            switch ($type) {
                case 'by_type':
                    $this->db->select([
                        'facility_types.nama_tipe',
                        'COUNT(facilities.id) as facility_count',
                        'COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value'
                    ]);
                    $this->db->from('facility_types');
                    $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
                    $this->db->group_by('facility_types.id');
                    $this->db->order_by('facility_count', 'DESC');
                    $data = $this->db->get()->result();
                    break;
                    
                case 'by_area':
                    $this->db->select([
                        'areas.nama_area',
                        'COUNT(facilities.id) as facility_count',
                        'COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value'
                    ]);
                    $this->db->from('areas');
                    $this->db->join('facilities', 'facilities.area_id = areas.id', 'left');
                    $this->db->group_by('areas.id');
                    $this->db->order_by('facility_count', 'DESC');
                    $data = $this->db->get()->result();
                    break;
                    
                case 'by_year':
                    $this->db->select([
                        'tahun_unit as year',
                        'COUNT(*) as facility_count',
                        'COALESCE(SUM(total_harga_sewa), 0) as total_value'
                    ]);
                    $this->db->from('facilities');
                    $this->db->where('tahun_unit IS NOT NULL');
                    $this->db->where('tahun_unit !=', '');
                    $this->db->group_by('tahun_unit');
                    $this->db->order_by('tahun_unit', 'ASC');
                    $data = $this->db->get()->result();
                    break;
                    
                case 'by_vendor':
                    $this->db->select([
                        'vendors.nama_vendor',
                        'COUNT(facilities.id) as facility_count',
                        'COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value'
                    ]);
                    $this->db->from('vendors');
                    $this->db->join('facilities', 'facilities.vendor_id = vendors.id', 'left');
                    $this->db->group_by('vendors.id');
                    $this->db->order_by('facility_count', 'DESC');
                    $data = $this->db->get()->result();
                    break;
                    
                case 'contract_status':
                    $today = date('Y-m-d');
                    $thirty_days = date('Y-m-d', strtotime('+30 days'));
                    
                    // Active contracts
                    $active = $this->db->where('akhir_sewa >=', $today)
                                     ->where('akhir_sewa >', $thirty_days)
                                     ->count_all_results('facilities');
                    
                    // Expiring soon
                    $expiring = $this->db->where('akhir_sewa >=', $today)
                                        ->where('akhir_sewa <=', $thirty_days)
                                        ->count_all_results('facilities');
                    
                    // Expired
                    $expired = $this->db->where('akhir_sewa <', $today)
                                      ->count_all_results('facilities');
                    
                    $data = [
                        ['status' => 'Aktif', 'count' => $active],
                        ['status' => 'Akan Kadaluarsa', 'count' => $expiring],
                        ['status' => 'Kadaluarsa', 'count' => $expired]
                    ];
                    break;
                    
                default:
                    $data = [];
            }
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'data' => $data
                ]));
                
        } catch (Exception $e) {
            log_message('error', 'Chart Data Error: ' . $e->getMessage());
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memuat data grafik.'
                ]));
        }
    }
    
    // Helper functions for getting statistics
    private function get_dashboard_stats() {
        $stats = [];
        
        // Total facilities
        $stats['total_facilities'] = $this->db->count_all('facilities');
        
        // Total value
        $this->db->select_sum('total_harga_sewa');
        $result = $this->db->get('facilities')->row();
        $stats['total_value'] = $result->total_harga_sewa ? $result->total_harga_sewa : 0;
        
        // Facilities by year - fix the YEAR function issue
        $this->db->select('tahun_unit as year, COUNT(*) as count, SUM(total_harga_sewa) as value');
        $this->db->from('facilities');
        $this->db->where('tahun_unit IS NOT NULL');
        $this->db->group_by('tahun_unit');
        $this->db->order_by('tahun_unit', 'DESC');
        $stats['by_year'] = $this->db->get()->result();
        
        return $stats;
    }
    
    private function get_areas_with_facility_count() {
        $this->db->select([
            'areas.id',
            'areas.nama_area',
            'COUNT(facilities.id) as facility_count',
            'COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value'
        ]);
        $this->db->from('areas');
        $this->db->join('facilities', 'facilities.area_id = areas.id', 'left');
        $this->db->group_by('areas.id');
        $this->db->order_by('facility_count', 'DESC');
        $result = $this->db->get()->result();
        
        // Filter out areas with zero facilities to avoid empty chart data
        return array_filter($result, function($area) {
            return $area->facility_count > 0;
        });
    }
    
    private function get_type_statistics() {
        $this->db->select([
            'facility_types.id',
            'facility_types.nama_tipe',
            'COUNT(facilities.id) as total_units',
            'COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value'
        ]);
        $this->db->from('facility_types');
        $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
        $this->db->group_by('facility_types.id');
        $this->db->order_by('total_units', 'DESC');
        $result = $this->db->get()->result();
        
        // Filter out types with zero facilities to avoid empty chart data
        return array_filter($result, function($type) {
            return $type->total_units > 0;
        });
    }
    
    private function get_vendor_stats() {
        $this->db->select([
            'vendors.id',
            'vendors.nama_vendor',
            'COUNT(facilities.id) as total_facilities',
            'COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value'
        ]);
        $this->db->from('vendors');
        $this->db->join('facilities', 'facilities.vendor_id = vendors.id', 'left');
        $this->db->group_by('vendors.id');
        $this->db->having('total_facilities >', 0);
        $this->db->order_by('total_value', 'DESC');
        return $this->db->get()->result();
    }

    // Get newest facilities for dashboard
    public function get_newest_facilities() {
        // Check if user has permission
        if (!$this->sess) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized access']));
            return;
        }
        
        $limit = $this->input->get('limit') ?: 10;
        $newest_facilities = $this->M_Facility->get_newest_facilities($limit);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data' => $newest_facilities
            ]));
    }

    // Get detailed facilities by type
    public function get_facilities_by_type_detailed() {
        // Check if user has permission
        if (!$this->sess) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized access']));
            return;
        }
        
        $type_id = $this->input->get('type_id');
        if (!$type_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Type ID is required']));
            return;
        }
        
        $facilities = $this->M_Facility->get_facilities_by_type_detailed($type_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data' => $facilities
            ]));
    }

    // Get detailed facilities by area
    public function get_facilities_by_area_detailed() {
        // Check if user has permission
        if (!$this->sess) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized access']));
            return;
        }
        
        $area_id = $this->input->get('area_id');
        if (!$area_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Area ID is required']));
            return;
        }
        
        $facilities = $this->M_Facility->get_facilities_by_area_detailed($area_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data' => $facilities
            ]));
    }
}