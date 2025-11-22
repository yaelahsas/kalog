<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Facility extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all facilities with joins
    public function get_all_facilities() {
        $this->db->select('facilities.*, areas.nama_area, areas.kode_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        return $this->db->get()->result();
    }

    // Get facility by id
    public function get_facility_by_id($id) {
        $this->db->select('facilities.*, areas.nama_area, areas.kode_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->where('facilities.id', $id);
        return $this->db->get()->row();
    }

    // Insert new facility
    public function insert_facility($data) {
        return $this->db->insert('facilities', $data);
    }

    // Update facility
    public function update_facility($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('facilities', $data);
    }

    // Delete facility
    public function delete_facility($id) {
        return $this->db->delete('facilities', ['id' => $id]);
    }

    // Get facilities by area
    public function get_facilities_by_area($area_id) {
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->where('facilities.area_id', $area_id);
        return $this->db->get()->result();
    }

    // Get facilities by type
    public function get_facilities_by_type($facility_type_id) {
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->where('facilities.facility_type_id', $facility_type_id);
        return $this->db->get()->result();
    }

    // Get facilities by vendor
    public function get_facilities_by_vendor($vendor_id) {
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->where('facilities.vendor_id', $vendor_id);
        return $this->db->get()->result();
    }

    // Get dashboard statistics
    public function get_dashboard_stats() {
        $stats = [];
        
        // Total facilities
        $stats['total_facilities'] = $this->db->count_all('facilities');
        
        // Total value
        $this->db->select_sum('total_harga_sewa');
        $result = $this->db->get('facilities')->row();
        $stats['total_value'] = $result->total_harga_sewa;
        
        // Facilities by type
        $this->db->select('facility_types.nama_tipe, COUNT(facilities.id) as count, SUM(facilities.total_harga_sewa) as total_value');
        $this->db->from('facility_types');
        $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
        $this->db->group_by('facility_types.id');
        $stats['by_type'] = $this->db->get()->result();
        
        // Facilities by area
        $this->db->select('areas.nama_area, COUNT(facilities.id) as count, SUM(facilities.total_harga_sewa) as total_value');
        $this->db->from('areas');
        $this->db->join('facilities', 'facilities.area_id = areas.id', 'left');
        $this->db->group_by('areas.id');
        $stats['by_area'] = $this->db->get()->result();
        
        // Facilities by year
        $this->db->select('tahun_unit, COUNT(id) as count, SUM(total_harga_sewa) as total_value');
        $this->db->from('facilities');
        $this->db->where('tahun_unit IS NOT NULL');
        $this->db->group_by('tahun_unit');
        $this->db->order_by('tahun_unit', 'DESC');
        $stats['by_year'] = $this->db->get()->result();
        
        return $stats;
    }

    // Get facilities with expiring contracts (within 3 months)
    public function get_expiring_contracts() {
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->where('akhir_sewa <=', date('Y-m-d', strtotime('+3 months')));
        $this->db->where('akhir_sewa >=', date('Y-m-d'));
        $this->db->where('status', 'active');
        return $this->db->get()->result();
    }

    // Search facilities
    public function search_facilities($keyword) {
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->group_start();
        $this->db->like('facilities.tipe', $keyword);
        $this->db->or_like('areas.nama_area', $keyword);
        $this->db->or_like('facility_types.nama_tipe', $keyword);
        $this->db->or_like('vendors.nama_vendor', $keyword);
        $this->db->or_like('facilities.no_perjanjian', $keyword);
        $this->db->group_end();
        return $this->db->get()->result();
    }

    // Get newest facilities (latest added)
    public function get_newest_facilities($limit = 10) {
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->order_by('facilities.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    // Get facilities by type with detailed information
    public function get_facilities_by_type_detailed($facility_type_id) {
        $this->db->select([
            'facilities.*',
            'areas.nama_area',
            'areas.kode_area',
            'facility_types.nama_tipe',
            'vendors.nama_vendor'
        ]);
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->where('facilities.facility_type_id', $facility_type_id);
        $this->db->order_by('facilities.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get facilities by area with detailed information
    public function get_facilities_by_area_detailed($area_id) {
        $this->db->select([
            'facilities.*',
            'areas.nama_area',
            'areas.kode_area',
            'facility_types.nama_tipe',
            'vendors.nama_vendor'
        ]);
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->where('facilities.area_id', $area_id);
        $this->db->order_by('facilities.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get total area count
    public function get_total_area_count() {
        $this->db->select('COUNT(DISTINCT area_id) as total_area');
        $this->db->from('facilities');
        $result = $this->db->get()->row();
        return $result->total_area;
    }

    // Get total rental value
    public function get_total_rental_value() {
        $this->db->select_sum('total_harga_sewa');
        $result = $this->db->get('facilities')->row();
        return $result->total_harga_sewa ? $result->total_harga_sewa : 0;
    }

    // Get facilities by type with count
    public function get_facilities_by_type_count() {
        $this->db->select('facility_types.nama_tipe, COUNT(facilities.id) as count');
        $this->db->from('facility_types');
        $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
        $this->db->group_by('facility_types.id');
        $this->db->order_by('count', 'DESC');
        return $this->db->get()->result();
    }

    // Get facilities by area with count
    public function get_facilities_by_area_count() {
        $this->db->select('areas.nama_area, COUNT(facilities.id) as count');
        $this->db->from('areas');
        $this->db->join('facilities', 'facilities.area_id = areas.id', 'left');
        $this->db->group_by('areas.id');
        $this->db->order_by('count', 'DESC');
        return $this->db->get()->result();
    }

    // Get newest facilities for dashboard
    public function get_newest_facilities_for_dashboard($limit = 5) {
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');
        $this->db->order_by('facilities.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
}