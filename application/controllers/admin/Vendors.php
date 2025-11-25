<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends CI_Controller {
    private $sess;

    public function __construct(){
        parent::__construct();
        $this->sess = $this->M_Auth->session(array('root','admin','user'));
        if ($this->sess === FALSE) {
            redirect(site_url('admin/auth/logout'),'refresh');
        }
        $this->load->model('M_Vendor');
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
        $data['sidebar']    = 'vendors';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        $data['title']      = 'Daftar Vendor';
        $data['card_title'] = 'Data Vendor';

        $data['swal'] = array(
            'type' => 'delete',
            'button'  => 'Yes, delete it!',
            'url' => NULL,
        );
        $data['breadcrumb'] = array(
            'Vendor' => site_url('dashboard/vendors'),
            'Daftar'   => site_url('dashboard/vendors'),
        );
        $data['btn_add']    = array(
            'url' => site_url('dashboard/vendors/add'),
            'name' => 'Add Vendor',
        );

        $this->load->view('admin/vendors/index.php', $data);
    }

    public function add(){
        // Check if user has permission to add
        if (!can_add($this->sess)) {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Anda tidak memiliki izin untuk menambah data!</div>');
            redirect(site_url('dashboard/vendors'), 'refresh');
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
        $data['sidebar']    = 'vendor-add';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        $data['title']      = 'Add Vendor';
        $data['card_title'] = 'Form Input';

        $data['swal'] = array(
            'type' => 'button',
            'button'  => 'Check Data',
            'url' => site_url('dashboard/vendors'),
        );
        $data['breadcrumb'] = array(
            'Vendor' => site_url('dashboard/vendors'),
            'Add'     => site_url('dashboard/vendors/add'),
        );

        $this->form_validation->set_rules('nama_vendor', 'Nama Vendor', 'required|trim|is_unique[vendors.nama_vendor]');
        
        if ($this->form_validation->run() === TRUE) {
            $data_insert = [
                'nama_vendor' => $this->input->post('nama_vendor')
            ];
            
            if ($this->M_Vendor->insert_vendor($data_insert)) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil disimpan!</div>');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal disimpan!</div>');
            }
            redirect(site_url('dashboard/vendors/add'),'refresh');

        } else {
            $data['notif'] = $this->M_Auth->notification();
            $this->load->view('admin/vendors/add.php', $data);
        }
    }

    public function edit($id=null){
        // Check if user has permission to edit
        if (!can_edit($this->sess)) {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Anda tidak memiliki izin untuk mengedit data!</div>');
            redirect(site_url('dashboard/vendors'), 'refresh');
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
            $data['sidebar']    = 'vendor-edit';
            $data['layout']     = 'layout-navbar-fixed pace-warning';
            $data['title']      = 'Edit Vendor';
            $data['card_title'] = 'Form Input';

            $data['swal'] = array(
                'type' => 'button',
                'button'  => 'Check Data',
                'url' => site_url('dashboard/vendors'),
            );
            $data['breadcrumb'] = array(
                'Vendor' => site_url('dashboard/vendors'),
                'Edit'     => site_url('dashboard/vendors/edit/'.$id),
            );

            $data['id']   = $id;
            $data['data'] = $this->M_Vendor->get_vendor_by_id($id);

            $this->form_validation->set_rules('nama_vendor', 'Nama Vendor', 'required|trim|callback_check_nama_vendor['.$id.']');
            
            if ($this->form_validation->run() === TRUE) {
                $data_update = [
                    'nama_vendor' => $this->input->post('nama_vendor')
                ];
                
                if ($this->M_Vendor->update_vendor($id, $data_update)) {
                    $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil diupdate!</div>');
                } else {
                    $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal diupdate!</div>');
                }
                redirect(site_url('dashboard/vendors/edit/'.$id),'refresh');
            } else {
                $data['notif'] = $this->M_Auth->notification();
                $this->load->view('admin/vendors/edit.php', $data);
            }
        } else {
            redirect(site_url('dashboard/vendors'),'refresh');
        }
    }

    // ==============================================
    //               RETURN JSON ENCODE
    // ==============================================

    public function data(){
        $draw   = intval($this->input->post("draw"));
        $start  = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $search = $this->input->post("search")['value'];

        // Total tanpa filter
        $total = $this->db->count_all("vendors");

        // Query untuk filtered count
        $this->db->select('vendors.*, COUNT(facilities.id) as facility_count, COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value');
        $this->db->from('vendors');
        $this->db->join('facilities', 'facilities.vendor_id = vendors.id', 'left');
        $this->db->group_by('vendors.id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('vendors.nama_vendor', $search);
            $this->db->group_end();
        }

        $filtered = $this->db->get()->num_rows();

        // Query untuk paginated data
        $this->db->select('vendors.*, COUNT(facilities.id) as facility_count, COALESCE(SUM(facilities.total_harga_sewa), 0) as total_value');
        $this->db->from('vendors');
        $this->db->join('facilities', 'facilities.vendor_id = vendors.id', 'left');
        $this->db->group_by('vendors.id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('vendors.nama_vendor', $search);
            $this->db->group_end();
        }

        $this->db->limit($length, $start);

        $data = $this->db->get()->result();

        // Format JSON yang sesuai untuk DataTables server-side processing
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
            $response = $this->M_Vendor->delete_vendor($id);
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
        // Load ajax response helper
        $this->load->helper('ajax_response');
        
        // Check if user is authenticated
        $session_data = $this->M_Auth->session(array('root','admin','user'));
        if ($session_data === FALSE) {
            ajax_auth_required_response();
            return;
        }
        
        // Check if user has permission to delete
        if (!can_delete($session_data)) {
            ajax_permission_denied_response();
            return;
        }
        
        if ($id == null) {
            ajax_not_found_response('Vendor ID is required');
            return;
        }
        
        try {
            if ($this->M_Vendor->delete_vendor($id)) {
                ajax_success_response('Vendor berhasil dihapus!');
            } else {
                ajax_error_response('Gagal menghapus vendor. Vendor mungkin sedang digunakan oleh fasilitas.');
            }
        } catch (Exception $e) {
            ajax_error_response('Error deleting vendor: ' . $e->getMessage());
        }
    }

    public function check_nama_vendor($nama_vendor, $id){
        $vendor = $this->M_Vendor->get_vendor_by_id($id);
        if ($vendor && $vendor->nama_vendor != $nama_vendor) {
            $this->db->where('nama_vendor', $nama_vendor);
            $check = $this->db->get('vendors')->row();
            if ($check) {
                $this->form_validation->set_message('check_nama_vendor', 'Nama Vendor sudah digunakan!');
                return FALSE;
            }
        }
        return TRUE;
    }
}