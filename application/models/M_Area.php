<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Area extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all areas
    public function get_all_areas() {
        return $this->db->get('areas')->result();
    }

    // Get area by id
    public function get_area_by_id($id) {
        return $this->db->get_where('areas', ['id' => $id])->row();
    }

    // Get area by kode_area
    public function get_area_by_kode($kode_area) {
        return $this->db->get_where('areas', ['kode_area' => $kode_area])->row();
    }

    // Insert new area
    public function insert_area($data) {
        return $this->db->insert('areas', $data);
    }

    // Update area
    public function update_area($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('areas', $data);
    }

    // Delete area
    public function delete_area($id) {
        // Check if area is being used by facilities
        $this->db->where('area_id', $id);
        $facilities_count = $this->db->count_all_results('facilities');
        
        if ($facilities_count > 0) {
            return false; // Cannot delete, area is in use
        }
        
        return $this->db->delete('areas', ['id' => $id]);
    }

    // Get areas with facility count
    public function get_areas_with_facility_count() {
        $this->db->select('areas.*, COUNT(facilities.id) as facility_count');
        $this->db->from('areas');
        $this->db->join('facilities', 'facilities.area_id = areas.id', 'left');
        $this->db->group_by('areas.id');
        return $this->db->get()->result();
    }
}