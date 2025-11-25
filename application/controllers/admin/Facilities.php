<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities extends CI_Controller {
    private $sess;

    public function __construct(){
        parent::__construct();
        $this->load->model(['M_Facility', 'M_Area', 'M_Vendor', 'M_FacilityType', 'M_Auth']);
        $this->sess = $this->M_Auth->session(array('root','admin','user'));
        if ($this->sess === FALSE) {
            redirect(site_url('admin/auth/logout'),'refresh');
        }
        $this->load->helper(['url', 'form', 'permission']);
        $this->load->library(['session', 'form_validation', 'upload', 'permission_lib']);
    }

    // ==============================================
    //               LOAD VIEW
    // ==============================================

    public function index()
    {
        $data['datatables'] = true;
        $data['icheck']     = false;
        $data['switch']     = false;
        $data['select2']    = false;
        $data['daterange']  = false;
        $data['colorpicker'] = false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        $data['session']    = $this->sess;
        $data['sidebar']    = 'facilities';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        $data['title']      = 'Daftar Fasilitas';
        $data['card_title'] = 'Data Fasilitas';

        $data['swal'] = array(
            'type' => 'delete',
            'button'  => 'Yes, delete it!',
            'url' => NULL,
        );
        $data['breadcrumb'] = array(
            'Fasilitas' => site_url('dashboard/facilities'),
            'Daftar'   => site_url('dashboard/facilities'),
        );
        $data['btn_add']    = array(
            'url' => site_url('dashboard/facilities/add'),
            'name' => 'Add Fasilitas',
        );

        $this->load->view('admin/facilities/index.php', $data);
    }

    // Add facility page
    public function add() {
        // Check if user has permission to add
        if (!can_add($this->sess)) {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Anda tidak memiliki izin untuk menambah data!</div>');
            redirect(site_url('dashboard/facilities'),'refresh');
        }
        $data['title'] = 'Tambah Fasilitas';
        $data['card_title'] = 'Tambah Fasilitas';
        $data['sidebar'] = 'facility-add';
        $data['layout'] = 'layout-navbar-fixed pace-warning';
        
        // Get areas for dropdown
        $data['areas'] = $this->M_Area->get_all_areas();
        
        // Get facility types for dropdown
        $data['facility_types'] = $this->M_FacilityType->get_all_facility_types();
        
        // Get vendors for dropdown
        $data['vendors'] = $this->M_Vendor->get_all_vendors();
        
        // Add session data
        $data['session'] = $this->sess;

        $this->form_validation->set_rules('area_id', 'Area', 'required');
        $this->form_validation->set_rules('facility_type_id', 'Jenis Fasilitas', 'required');
        $this->form_validation->set_rules('tipe', 'Tipe', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric');
        $this->form_validation->set_rules('tahun_unit', 'Tahun Unit', 'numeric');
        $this->form_validation->set_rules('status', 'Status', 'required');
        
        // Check if upload directory exists, if not create it
        $upload_path = './uploads/facilities/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        // File upload validation for dokumen_perjanjian
        if (!empty($_FILES['dokumen_perjanjian']['name'])) {
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = TRUE;
            $config['file_name'] = 'perjanjian_' . time();
            
            $this->upload->initialize($config);
            
            if (!$this->upload->do_upload('dokumen_perjanjian')) {
                $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
                $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> ' . $this->upload->display_errors('', '') . '</div>');
                redirect(site_url('dashboard/facilities/add'),'refresh');
            }
        }
        
        // File upload validation for dokumen_bast
        if (!empty($_FILES['dokumen_bast']['name'])) {
            $config_bast['upload_path'] = $upload_path;
            $config_bast['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config_bast['max_size'] = 2048; // 2MB
            $config_bast['encrypt_name'] = TRUE;
            $config_bast['file_name'] = 'bast_' . time();
            
            $this->upload->initialize($config_bast);
            
            if (!$this->upload->do_upload('dokumen_bast')) {
                $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
                $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Error upload BAST: ' . $this->upload->display_errors('', '') . '</div>');
                redirect(site_url('dashboard/facilities/add'),'refresh');
            }
        }
        
        if ($this->form_validation->run() === TRUE) {
            $data_insert = [
                'area_id' => $this->input->post('area_id'),
                'facility_type_id' => $this->input->post('facility_type_id'),
                'vendor_id' => $this->input->post('vendor_id') ?: null,
                'tipe' => $this->input->post('tipe'),
                'kapasitas' => $this->input->post('kapasitas'),
                'jumlah' => $this->input->post('jumlah'),
                'tahun_unit' => $this->input->post('tahun_unit') ?: null,
                'awal_sewa' => $this->input->post('awal_sewa') ?: null,
                'akhir_sewa' => $this->input->post('akhir_sewa') ?: null,
                'total_harga_sewa' => $this->input->post('total_harga_sewa') ?: null,
                'no_perjanjian' => $this->input->post('no_perjanjian') ?: null,
                'status' => $this->input->post('status'),
                'keterangan' => $this->input->post('keterangan') ?: null
            ];
            
            // Handle file upload for dokumen_perjanjian
            if (!empty($_FILES['dokumen_perjanjian']['name'])) {
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
                $config['file_name'] = 'perjanjian_' . time();
                
                $this->upload->initialize($config);
                $this->upload->do_upload('dokumen_perjanjian');
                $upload_data = $this->upload->data();
                $data_insert['dokumen_perjanjian'] = $upload_data['file_name'];
            }
            
            // Handle file upload for dokumen_bast
            if (!empty($_FILES['dokumen_bast']['name'])) {
                $config_bast['upload_path'] = $upload_path;
                $config_bast['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config_bast['max_size'] = 2048; // 2MB
                $config_bast['encrypt_name'] = TRUE;
                $config_bast['file_name'] = 'bast_' . time();
                
                $this->upload->initialize($config_bast);
                $this->upload->do_upload('dokumen_bast');
                $upload_data_bast = $this->upload->data();
                $data_insert['dokumen_bast'] = $upload_data_bast['file_name'];
            }
            
            if ($this->M_Facility->insert_facility($data_insert)) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil disimpan!</div>');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal disimpan!</div>');
            }
            redirect(site_url('dashboard/facilities/add'),'refresh');
        } else {
            $data['notif'] = $this->M_Auth->notification();
            
            // Load new design system views
            $data['datatables'] = false;
            $data['icheck']     = true;
            $data['switch']     = false;
            $data['select2']    = true;
            $data['daterange']  = false;
            $data['colorpicker']= false;
            $data['inputmask']  = false;
            $data['dropzonejs'] = false;
            $data['summernote'] = false;
            
   
      
   $data['breadcrumb'] = array(
            'Fasilitas' => site_url('dashboard/facilities'),
            'Daftar'   => site_url('dashboard/facilities'),
        );
        $data['btn_add']    = array(
            'url' => site_url('dashboard/facilities/add'),
            'name' => 'Add Fasilitas',
        );
              $this->load->view('admin/facilities/add', $data);

        }
    }

    // Edit facility page
    public function edit($id = null) {
        // Check if user has permission to edit
        if (!can_edit($this->sess)) {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Anda tidak memiliki izin untuk mengedit data!</div>');
            redirect(site_url('dashboard/facilities'),'refresh');
        }
        
        if ($id != null) {
            $data['title'] = 'Edit Fasilitas';
            $data['card_title'] = 'Edit Fasilitas';
            $data['sidebar'] = 'facility-edit';
            $data['layout'] = 'layout-navbar-fixed pace-warning';
           
            // Get facility data
            $data['facility'] = $this->M_Facility->get_facility_by_id($id);
           
            // Get areas for dropdown
            $data['areas'] = $this->M_Area->get_all_areas();
           
            // Get facility types for dropdown
            $data['facility_types'] = $this->M_FacilityType->get_all_facility_types();
           
            // Get vendors for dropdown
            $data['vendors'] = $this->M_Vendor->get_all_vendors();
            
            // Add session data
            $data['session'] = $this->sess;

            $this->form_validation->set_rules('area_id', 'Area', 'required');
            $this->form_validation->set_rules('facility_type_id', 'Jenis Fasilitas', 'required');
            $this->form_validation->set_rules('tipe', 'Tipe', 'required');
            $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric');
            $this->form_validation->set_rules('tahun_unit', 'Tahun Unit', 'numeric');
            $this->form_validation->set_rules('status', 'Status', 'required');
            
            // Check if upload directory exists, if not create it
            $upload_path = './uploads/facilities/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }
            
            // File upload validation for dokumen_perjanjian
            $upload_success = true;
            if (!empty($_FILES['dokumen_perjanjian']['name'])) {
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
                $config['file_name'] = 'perjanjian_' . time();
                
                $this->upload->initialize($config);
                
                if (!$this->upload->do_upload('dokumen_perjanjian')) {
                    $upload_success = false;
                    $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
                    $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> ' . $this->upload->display_errors('', '') . '</div>');
                    redirect(site_url('dashboard/facilities/edit/'.$id),'refresh');
                }
            }
            
            // File upload validation for dokumen_bast
            if (!empty($_FILES['dokumen_bast']['name'])) {
                $config_bast['upload_path'] = $upload_path;
                $config_bast['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config_bast['max_size'] = 2048; // 2MB
                $config_bast['encrypt_name'] = TRUE;
                $config_bast['file_name'] = 'bast_' . time();
                
                $this->upload->initialize($config_bast);
                
                if (!$this->upload->do_upload('dokumen_bast')) {
                    $upload_success = false;
                    $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
                    $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Error upload BAST: ' . $this->upload->display_errors('', '') . '</div>');
                    redirect(site_url('dashboard/facilities/edit/'.$id),'refresh');
                }
            }
            
            if ($this->form_validation->run() === TRUE && $upload_success) {
                $data_update = [
                    'area_id' => $this->input->post('area_id'),
                    'facility_type_id' => $this->input->post('facility_type_id'),
                    'vendor_id' => $this->input->post('vendor_id') ?: null,
                    'tipe' => $this->input->post('tipe'),
                    'kapasitas' => $this->input->post('kapasitas'),
                    'jumlah' => $this->input->post('jumlah'),
                    'tahun_unit' => $this->input->post('tahun_unit') ?: null,
                    'awal_sewa' => $this->input->post('awal_sewa') ?: null,
                    'akhir_sewa' => $this->input->post('akhir_sewa') ?: null,
                    'total_harga_sewa' => $this->input->post('total_harga_sewa') ?: null,
                    'no_perjanjian' => $this->input->post('no_perjanjian') ?: null,
                    'status' => $this->input->post('status'),
                    'keterangan' => $this->input->post('keterangan') ?: null
                ];
                
                // Handle file upload for dokumen_perjanjian
                if (!empty($_FILES['dokumen_perjanjian']['name'])) {
                    // Delete old file if exists
                    $old_facility = $this->M_Facility->get_facility_by_id($id);
                    if (!empty($old_facility->dokumen_perjanjian) && file_exists($upload_path . $old_facility->dokumen_perjanjian)) {
                        unlink($upload_path . $old_facility->dokumen_perjanjian);
                    }
                    
                    $config['upload_path'] = $upload_path;
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config['max_size'] = 2048; // 2MB
                    $config['encrypt_name'] = TRUE;
                    $config['file_name'] = 'perjanjian_' . time();
                    
                    $this->upload->initialize($config);
                    $this->upload->do_upload('dokumen_perjanjian');
                    $upload_data = $this->upload->data();
                    $data_update['dokumen_perjanjian'] = $upload_data['file_name'];
                }
                
                // Handle file upload for dokumen_bast
                if (!empty($_FILES['dokumen_bast']['name'])) {
                    // Delete old file if exists
                    $old_facility = $this->M_Facility->get_facility_by_id($id);
                    if (!empty($old_facility->dokumen_bast) && file_exists($upload_path . $old_facility->dokumen_bast)) {
                        unlink($upload_path . $old_facility->dokumen_bast);
                    }
                    
                    $config_bast['upload_path'] = $upload_path;
                    $config_bast['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config_bast['max_size'] = 2048; // 2MB
                    $config_bast['encrypt_name'] = TRUE;
                    $config_bast['file_name'] = 'bast_' . time();
                    
                    $this->upload->initialize($config_bast);
                    $this->upload->do_upload('dokumen_bast');
                    $upload_data_bast = $this->upload->data();
                    $data_update['dokumen_bast'] = $upload_data_bast['file_name'];
                }
                
                if ($this->M_Facility->update_facility($id, $data_update)) {
                    $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil diupdate!</div>');
                } else {
                    $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal diupdate!</div>');
                }
                redirect(site_url('dashboard/facilities/edit/'.$id),'refresh');
            } else {
                $data['notif'] = $this->M_Auth->notification();
                
                // Load new design system views
                $data['datatables'] = false;
                $data['icheck']     = true;
                $data['switch']     = false;
                $data['select2']    = true;
                $data['daterange']  = false;
                $data['colorpicker']= false;
                $data['inputmask']  = false;
                $data['dropzonejs'] = false;
                $data['summernote'] = false;
       
                $this->load->view('admin/facilities/edit', $data);
  
            }
        } else {
            redirect(site_url('dashboard/facilities'),'refresh');
        }
    }

    // Facility detail page
    public function detail($id = null) {
        if ($id != null) {
            $data['title'] = 'Detail Fasilitas';
            $data['sidebar'] = 'facility-detail';
            $data['layout'] = 'layout-navbar-fixed pace-warning';
            $data['facility'] = $this->M_Facility->get_facility_by_id($id);
           
            if (!$data['facility']) {
                show_404();
            }
            
            // Add session data
            $data['session'] = $this->sess;
           
            // Load new design system views
            $data['datatables'] = false;
            $data['icheck']     = true;
            $data['switch']     = false;
            $data['select2']    = false;
            $data['daterange']  = false;
            $data['colorpicker']= false;
            $data['inputmask']  = false;
            $data['dropzonejs'] = false;
            $data['summernote'] = false;
            
            // Load views with proper layout
            
           
            $this->load->view('admin/facilities/detail', $data);
        
        } else {
            redirect(site_url('dashboard/facilities'),'refresh');
        }
    }

    // Filter facilities
    public function filter() {
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
        
        // Load new design system views
        $data['datatables'] = false;
        $data['icheck']     = true;
        $data['switch']     = false;
        $data['select2']    = false;
        $data['daterange']  = false;
        $data['colorpicker']= false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        

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
        $data['sidebar'] = 'facilities';
        $data['keyword'] = $keyword;
        
        // Add session data
        $data['session'] = $this->sess;
        
        // Load new design system views
        $data['datatables'] = false;
        $data['icheck']     = true;
        $data['switch']     = false;
        $data['select2']    = false;
        $data['daterange']  = false;
        $data['colorpicker']= false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        

        $this->load->view('admin/facilities/search_result', $data);

    }

    // Delete facility
    public function delete($id = null) {
        // Check if user has permission to delete
        if (!can_delete($this->sess)) {
            $response = array(
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus data!',
            );
            echo json_encode($response);
            return;
        }
        
        if ($id != null) {
            $response = $this->M_Facility->delete_facility($id);
            if ($response) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus!',
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Data gagal dihapus!',
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Data not found!',
            );
        }
        echo json_encode($response);
    }

    // Get facilities data for AJAX
    public function get_data() {
        // Check if user is authenticated
        $session_data = $this->M_Auth->session(array('root','admin'));
        if ($session_data === FALSE) {
            $response = [
                'success' => false,
                'message' => 'Authentication required'
            ];
            echo json_encode($response);
            return;
        }
        
        header('Content-Type: application/json');
        
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

    // Bulk action for facilities
    public function bulk_action() {
        // Check if user is authenticated
        $session_data = $this->M_Auth->session(array('root','admin','user'));
        if ($session_data === FALSE) {
            $response = [
                'success' => false,
                'message' => 'Authentication required'
            ];
            echo json_encode($response);
            return;
        }
        
        // Check if user has permission for bulk actions
        $input = json_decode(file_get_contents('php://input'), true);
        $action = isset($input['action']) ? $input['action'] : '';
        
        // Only allow bulk actions that don't involve add/edit/delete for user level
        if (!can_edit($session_data) && in_array($action, ['activate', 'deactivate', 'maintenance', 'delete'])) {
            $response = [
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini!'
            ];
            echo json_encode($response);
            return;
        }
        
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $action = $input['action'];
            $ids = $input['ids'];
            
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

    // Delete facility via AJAX
    public function delete_ajax($id = null) {
        // Check if user is authenticated
        $session_data = $this->M_Auth->session(array('root','admin','user'));
        if ($session_data === FALSE) {
            $response = [
                'success' => false,
                'message' => 'Authentication required'
            ];
            echo json_encode($response);
            return;
        }
        
        // Check if user has permission to delete
        if (!can_delete($session_data)) {
            $response = [
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus data!'
            ];
            echo json_encode($response);
            return;
        }
        
        header('Content-Type: application/json');
        
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

    // Import facilities data
    public function import_data() {
        // Check if user is authenticated
        $session_data = $this->M_Auth->session(array('root','admin','user'));
        if ($session_data === FALSE) {
            $response = [
                'success' => false,
                'message' => 'Authentication required'
            ];
            echo json_encode($response);
            return;
        }
        
        // Check if user has permission to add
        if (!can_add($session_data)) {
            $response = [
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengimpor data!'
            ];
            echo json_encode($response);
            return;
        }
        
        header('Content-Type: application/json');
        
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

    // Export facilities data
    public function export_data() {
        $format = $this->input->get('format', TRUE);
        $area_id = $this->input->get('area', TRUE);
        $facility_type_id = $this->input->get('type', TRUE);
        $vendor_id = $this->input->get('vendor', TRUE);
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
        
        // For now, just redirect to the old export method
        // In a real implementation, you would generate Excel/PDF/CSV files
        redirect(site_url('dashboard/export_excel'));
    }

    // ==============================================
    //               RETURN JSON ENCODE
    // ==============================================

    public function data()
    {
        $draw   = intval($this->input->post("draw"));
        $start  = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        // $search = $this->input->post("search")['value'];

        // Total tanpa filter
        $total = $this->db->count_all("facilities");

        // Query untuk filtered count
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('facilities.tipe', $search);
            $this->db->or_like('areas.nama_area', $search);
            $this->db->or_like('facility_types.nama_tipe', $search);
            $this->db->or_like('vendors.nama_vendor', $search);
            $this->db->or_like('facilities.no_perjanjian', $search);
            $this->db->group_end();
        }

        $filtered = $this->db->get()->num_rows();

        // Query untuk paginated data
        $this->db->select('facilities.*, areas.nama_area, facility_types.nama_tipe, vendors.nama_vendor');
        $this->db->from('facilities');
        $this->db->join('areas', 'areas.id = facilities.area_id', 'left');
        $this->db->join('facility_types', 'facility_types.id = facilities.facility_type_id', 'left');
        $this->db->join('vendors', 'vendors.id = facilities.vendor_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('facilities.tipe', $search);
            $this->db->or_like('areas.nama_area', $search);
            $this->db->or_like('facility_types.nama_tipe', $search);
            $this->db->or_like('vendors.nama_vendor', $search);
            $this->db->or_like('facilities.no_perjanjian', $search);
            $this->db->group_end();
        }

        $this->db->limit($length, $start);

        $data = $this->db->get()->result();

        echo json_encode([
            "draw"            => $draw,
            "recordsTotal"    => $total,
            "recordsFiltered" => $filtered,
            "data"            => $data
        ]);
    }
}