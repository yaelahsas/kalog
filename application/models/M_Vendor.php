<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Vendor extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all vendors
    public function get_all_vendors() {
        return $this->db->get('vendors')->result();
    }

    // Get vendor by id
    public function get_vendor_by_id($id) {
        return $this->db->get_where('vendors', ['id' => $id])->row();
    }

    // Insert new vendor
    public function insert_vendor($data) {
        return $this->db->insert('vendors', $data);
    }

    // Update vendor
    public function update_vendor($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('vendors', $data);
    }

    // Delete vendor
    public function delete_vendor($id) {
        return $this->db->delete('vendors', ['id' => $id]);
    }

    // Get vendors with facility count
    public function get_vendors_with_facility_count() {
        $this->db->select('vendors.*, COUNT(facilities.id) as facility_count, COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value');
        $this->db->from('vendors');
        $this->db->join('facilities', 'facilities.vendor_id = vendors.id', 'left');
        $this->db->group_by('vendors.id');
        
        $result = $this->db->get()->result();
        
        return $result;
    }

    // Get vendor statistics
    public function get_vendor_stats() {
        $this->db->select('vendors.nama_vendor, COUNT(facilities.id) as total_facilities, SUM(facilities.total_harga_sewa) as total_value');
        $this->db->from('vendors');
        $this->db->join('facilities', 'facilities.vendor_id = vendors.id', 'left');
        $this->db->group_by('vendors.id');
        $this->db->order_by('total_value', 'DESC');
        return $this->db->get()->result();
    }
}