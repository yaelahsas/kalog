<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_FacilityType extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all facility types
    public function get_all_facility_types() {
        return $this->db->get('facility_types')->result();
    }

    // Get facility type by id
    public function get_facility_type_by_id($id) {
        return $this->db->get_where('facility_types', ['id' => $id])->row();
    }

    // Insert new facility type
    public function insert_facility_type($data) {
        return $this->db->insert('facility_types', $data);
    }

    // Update facility type
    public function update_facility_type($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('facility_types', $data);
    }

    // Delete facility type
    public function delete_facility_type($id) {
        return $this->db->delete('facility_types', ['id' => $id]);
    }

    // Get facility types with facility count
    public function get_facility_types_with_count() {
        $this->db->select('facility_types.*, COUNT(facilities.id) as facility_count, SUM(facilities.total_harga_sewa) as total_value');
        $this->db->from('facility_types');
        $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
        $this->db->group_by('facility_types.id');
        return $this->db->get()->result();
    }

    // Get facility type statistics
    public function get_type_statistics() {
        $this->db->select('facility_types.nama_tipe, COUNT(facilities.id) as total_facilities, SUM(facilities.jumlah) as total_units, SUM(facilities.total_harga_sewa) as total_value');
        $this->db->from('facility_types');
        $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
        $this->db->group_by('facility_types.id');
        $this->db->order_by('total_value', 'DESC');
        return $this->db->get()->result();
    }
}