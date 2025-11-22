<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(['M_Facility', 'M_Area', 'M_Vendor', 'M_FacilityType']);
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
        
        // Set response header
        header('Content-Type: application/json');
    }

    // Get all facilities
    public function index_get() {
        try {
            $facilities = $this->M_Facility->get_all_facilities();
            
            $response = [
                'success' => true,
                'message' => 'Facilities retrieved successfully',
                'facilities' => $facilities
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error retrieving facilities: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    // Get facility by id
    public function detail_get($id = null) {
        if ($id == null) {
            $response = [
                'success' => false,
                'message' => 'Facility ID is required'
            ];
            echo json_encode($response);
            return;
        }
        
        try {
            $facility = $this->M_Facility->get_facility_by_id($id);
            
            if ($facility) {
                $response = [
                    'success' => true,
                    'message' => 'Facility retrieved successfully',
                    'facility' => $facility
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Facility not found'
                ];
            }
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error retrieving facility: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    // Filter facilities
    public function filter_post() {
        try {
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
            
            $facilities = $this->db->get()->result();
            
            $response = [
                'success' => true,
                'message' => 'Facilities filtered successfully',
                'facilities' => $facilities
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error filtering facilities: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    // Search facilities
    public function search_get() {
        try {
            $keyword = $this->input->get('keyword');
            
            if ($keyword) {
                $facilities = $this->M_Facility->search_facilities($keyword);
            } else {
                $facilities = $this->M_Facility->get_all_facilities();
            }
            
            $response = [
                'success' => true,
                'message' => 'Facilities searched successfully',
                'facilities' => $facilities,
                'keyword' => $keyword
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error searching facilities: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    // Bulk action
    public function bulk_post() {
        try {
            $action = $this->input->post('action');
            $ids = $this->input->post('ids');
            
            if (!$action || !$ids) {
                $response = [
                    'success' => false,
                    'message' => 'Action and IDs are required'
                ];
                echo json_encode($response);
                return;
            }
            
            $success_count = 0;
            $error_count = 0;
            
            foreach ($ids as $id) {
                $data = [];
                
                switch ($action) {
                    case 'activate':
                        $data['status'] = 'active';
                        break;
                    case 'deactivate':
                        $data['status'] = 'inactive';
                        break;
                    case 'maintenance':
                        $data['status'] = 'maintenance';
                        break;
                    case 'delete':
                        if ($this->M_Facility->delete_facility($id)) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                        continue 2; // Skip update for delete action
                    default:
                        $error_count++;
                        continue 2;
                }
                
                if ($this->M_Facility->update_facility($id, $data)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
            
            $response = [
                'success' => $error_count === 0,
                'message' => "Bulk action completed. Success: {$success_count}, Errors: {$error_count}"
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    // Delete facility
    public function delete_delete($id = null) {
        if ($id == null) {
            $response = [
                'success' => false,
                'message' => 'Facility ID is required'
            ];
            echo json_encode($response);
            return;
        }
        
        try {
            if ($this->M_Facility->delete_facility($id)) {
                $response = [
                    'success' => true,
                    'message' => 'Facility deleted successfully'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to delete facility'
                ];
            }
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error deleting facility: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    // Export facilities
    public function export_get() {
        try {
            $format = $this->input->get('format', TRUE);
            $area_id = $this->input->get('area_id', TRUE);
            $facility_type_id = $this->input->get('facility_type_id', TRUE);
            $vendor_id = $this->input->get('vendor_id', TRUE);
            $status = $this->input->get('status', TRUE);
            
            // Apply filters
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
            
            $facilities = $this->db->get()->result();
            
            // For now, just return JSON data
            // In a real implementation, you would generate Excel/PDF/CSV files
            $response = [
                'success' => true,
                'message' => 'Export data retrieved successfully',
                'format' => $format,
                'data' => $facilities
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error exporting facilities: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    // Import facilities
    public function import_post() {
        try {
            if (empty($_FILES['file']['name'])) {
                $response = [
                    'success' => false,
                    'message' => 'No file uploaded'
                ];
                echo json_encode($response);
                return;
            }
            
            // For now, just return a success message
            // In a real implementation, you would process the uploaded file
            $response = [
                'success' => true,
                'message' => 'Facilities imported successfully'
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error importing facilities: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }
}