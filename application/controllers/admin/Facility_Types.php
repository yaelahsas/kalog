<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facility_Types extends CI_Controller {
    private $sess;

    public function __construct(){
        parent::__construct();
        $this->sess = $this->M_Auth->session(array('root','admin','user'));
        if ($this->sess === FALSE) {
            redirect(site_url('admin/auth/logout'),'refresh');
        }
        $this->load->model('M_FacilityType');
        $this->load->helper('permission');
    }

    // ==============================================
    //               LOAD VIEW
    // ==============================================

    public function index(){
        $data['datatables'] = true;
        $data['icheck']     = false;
        $data['switch']     = false;
        $data['select2']    = false;
        $data['daterange']  = false;
        $data['colorpicker']= false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        $data['session']    = $this->sess;
        $data['sidebar']    = 'facility-types';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        $data['title']      = 'Daftar Jenis Fasilitas';
        $data['card_title'] = 'Data Jenis Fasilitas';

        $data['swal'] = array(
            'type' => 'delete',
            'button'  => 'Yes, delete it!',
            'url' => NULL,
        );
        $data['breadcrumb'] = array(
            'Jenis Fasilitas' => site_url('dashboard/facility_types'),
            'Daftar'   => site_url('dashboard/facility_types'),
        );
        $data['btn_add']    = array(
            'url' => site_url('dashboard/facility_types/add'),
            'name' => 'Add Jenis Fasilitas',
        );

        $this->load->view('admin/facility_types/index.php', $data);
    }

    public function add(){
        // Check if user has permission to add
        if (!can_add($this->sess)) {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Anda tidak memiliki izin untuk menambah data!</div>');
            redirect(site_url('dashboard/facility_types'), 'refresh');
        }
        $data['datatables'] = false;
        $data['icheck']     = false;
        $data['switch']     = false;
        $data['select2']    = false;
        $data['daterange']  = false;
        $data['colorpicker']= false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        $data['session']    = $this->sess;
        $data['sidebar']    = 'facility-type-add';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        $data['title']      = 'Add Jenis Fasilitas';
        $data['card_title'] = 'Form Input';

        $data['swal'] = array(
            'type' => 'button',
            'button'  => 'Check Data',
            'url' => site_url('dashboard/facility_types'),
        );
        $data['breadcrumb'] = array(
            'Jenis Fasilitas' => site_url('dashboard/facility_types'),
            'Add'     => site_url('dashboard/facility_types/add'),
        );

        $this->form_validation->set_rules('nama_tipe', 'Nama Jenis Fasilitas', 'required|trim|is_unique[facility_types.nama_tipe]');
        
        if ($this->form_validation->run() === TRUE) {
            $data_insert = [
                'nama_tipe' => $this->input->post('nama_tipe')
            ];
            
            if ($this->M_FacilityType->insert_facility_type($data_insert)) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil disimpan!</div>');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal disimpan!</div>');
            }
            redirect(site_url('dashboard/facility_types/add'),'refresh');

        } else {
            $data['notif'] = $this->M_Auth->notification();
            $this->load->view('admin/facility_types/add.php', $data);
        }
    }

    public function edit($id=null){
        // Check if user has permission to edit
        if (!can_edit($this->sess)) {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Anda tidak memiliki izin untuk mengedit data!</div>');
            redirect(site_url('dashboard/facility_types'), 'refresh');
        }
        
        if ($id != null) {
            $data['datatables'] = false;
            $data['icheck']     = false;
            $data['switch']     = false;
            $data['select2']    = false;
            $data['daterange']  = false;
            $data['colorpicker']= false;
            $data['inputmask']  = false;
            $data['dropzonejs'] = false;
            $data['summernote'] = false;
            $data['session']    = $this->sess;
            $data['sidebar']    = 'facility-type-edit';
            $data['layout']     = 'layout-navbar-fixed pace-warning';
            $data['title']      = 'Edit Jenis Fasilitas';
            $data['card_title'] = 'Form Input';

            $data['swal'] = array(
                'type' => 'button',
                'button'  => 'Check Data',
                'url' => site_url('dashboard/facility_types'),
            );
            $data['breadcrumb'] = array(
                'Jenis Fasilitas' => site_url('dashboard/facility_types'),
                'Edit'     => site_url('dashboard/facility_types/edit/'.$id),
            );

            $data['id']   = $id;
            $data['data'] = $this->M_FacilityType->get_facility_type_by_id($id);

            $this->form_validation->set_rules('nama_tipe', 'Nama Jenis Fasilitas', 'required|trim|callback_check_nama_tipe['.$id.']');
            
            if ($this->form_validation->run() === TRUE) {
                $data_update = [
                    'nama_tipe' => $this->input->post('nama_tipe')
                ];
                
                if ($this->M_FacilityType->update_facility_type($id, $data_update)) {
                    $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil diupdate!</div>');
                } else {
                    $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal diupdate!</div>');
                }
                redirect(site_url('dashboard/facility_types/edit/'.$id),'refresh');
            } else {
                $data['notif'] = $this->M_Auth->notification();
                $this->load->view('admin/facility_types/edit.php', $data);
            }
        } else {
            redirect(site_url('dashboard/facility_types'),'refresh');
        }
    }

    // ==============================================
    //               RETURN JSON ENCODE
    // ==============================================

    public function data()
    {
        $draw   = intval($this->input->post("draw"));
        $start  = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $search = $this->input->post("search")['value'];

        // Total tanpa filter
        $total = $this->db->count_all("facility_types");

        // Query untuk filtered count
        $this->db->select('facility_types.*, COUNT(facilities.id) as facility_count, SUM(facilities.total_harga_sewa) as total_value');
        $this->db->from('facility_types');
        $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
        $this->db->group_by('facility_types.id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('facility_types.nama_tipe', $search);
            $this->db->group_end();
        }

        $filtered = $this->db->get()->num_rows();

        // Query untuk paginated data
        $this->db->select('facility_types.*, COUNT(facilities.id) as facility_count, SUM(facilities.total_harga_sewa) as total_value');
        $this->db->from('facility_types');
        $this->db->join('facilities', 'facilities.facility_type_id = facility_types.id', 'left');
        $this->db->group_by('facility_types.id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('facility_types.nama_tipe', $search);
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

    public function delete($id=null){
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
            $response = $this->M_FacilityType->delete_facility_type($id);
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

    public function delete_ajax($id = null)
    {
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
                'message' => 'Facility Type ID is required'
            ];
            echo json_encode($response);
            return;
        }
        
        try {
            if ($this->M_FacilityType->delete_facility_type($id)) {
                $response = [
                    'success' => true,
                    'message' => 'Facility Type deleted successfully'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to delete facility type'
                ];
            }
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error deleting facility type: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    public function check_nama_tipe($nama_tipe, $id){
        $facility_type = $this->M_FacilityType->get_facility_type_by_id($id);
        if ($facility_type && $facility_type->nama_tipe != $nama_tipe) {
            $this->db->where('nama_tipe', $nama_tipe);
            $check = $this->db->get('facility_types')->row();
            if ($check) {
                $this->form_validation->set_message('check_nama_tipe', 'Nama Jenis Fasilitas sudah digunakan!');
                return FALSE;
            }
        }
        return TRUE;
    }
}