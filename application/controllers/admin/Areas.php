<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Areas extends CI_Controller
{
    private $sess;

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->M_Auth->session(array('root', 'admin'));
        if ($this->sess === FALSE) {
            redirect(site_url('admin/auth/logout'), 'refresh');
        }
        $this->load->model('M_Area');
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
        $data['sidebar']    = 'areas';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        $data['title']      = 'Daftar Area';
        $data['card_title'] = 'Data Area';

        $data['swal'] = array(
            'type' => 'delete',
            'button'  => 'Yes, delete it!',
            'url' => NULL,
        );
        $data['breadcrumb'] = array(
            'Area' => site_url('dashboard/areas'),
            'Daftar'   => site_url('dashboard/areas'),
        );
        $data['btn_add']    = array(
            'url' => site_url('dashboard/areas/add'),
            'name' => 'Add Area',
        );

        $this->load->view('admin/areas/index.php', $data);
    }

    public function add()
    {
        $data['datatables'] = false;
        $data['icheck']     = false;
        $data['switch']     = false;
        $data['select2']    = false;
        $data['daterange']  = false;
        $data['colorpicker'] = false;
        $data['inputmask']  = false;
        $data['dropzonejs'] = false;
        $data['summernote'] = false;
        $data['session']    = $this->sess;
        $data['sidebar']    = 'area-add';
        $data['layout']     = 'layout-navbar-fixed pace-warning';
        $data['title']      = 'Add Area';
        $data['card_title'] = 'Form Input';

        $data['swal'] = array(
            'type' => 'button',
            'button'  => 'Check Data',
            'url' => site_url('dashboard/areas'),
        );
        $data['breadcrumb'] = array(
            'Area' => site_url('dashboard/areas'),
            'Add'     => site_url('dashboard/areas/add'),
        );

        $this->form_validation->set_rules('kode_area', 'Kode Area', 'required|trim|is_unique[areas.kode_area]');
        $this->form_validation->set_rules('nama_area', 'Nama Area', 'required|trim');

        if ($this->form_validation->run() === TRUE) {
            $data_insert = [
                'kode_area' => $this->input->post('kode_area'),
                'nama_area' => $this->input->post('nama_area')
            ];

            if ($this->M_Area->insert_area($data_insert)) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil disimpan!</div>');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal disimpan!</div>');
            }
            redirect(site_url('dashboard/areas/add'), 'refresh');
        } else {
            $data['notif'] = $this->M_Auth->notification();
            $this->load->view('admin/areas/add.php', $data);
        }
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $data['datatables'] = false;
            $data['icheck']     = false;
            $data['switch']     = false;
            $data['select2']    = false;
            $data['daterange']  = false;
            $data['colorpicker'] = false;
            $data['inputmask']  = false;
            $data['dropzonejs'] = false;
            $data['summernote'] = false;
            $data['session']    = $this->sess;
            $data['sidebar']    = 'area-edit';
            $data['layout']     = 'layout-navbar-fixed pace-warning';
            $data['title']      = 'Edit Area';
            $data['card_title'] = 'Form Input';

            $data['swal'] = array(
                'type' => 'button',
                'button'  => 'Check Data',
                'url' => site_url('dashboard/areas'),
            );
            $data['breadcrumb'] = array(
                'Area' => site_url('dashboard/areas'),
                'Edit'     => site_url('dashboard/areas/edit/' . $id),
            );

            $data['id']   = $id;
            $data['data'] = $this->M_Area->get_area_by_id($id);

            $this->form_validation->set_rules('kode_area', 'Kode Area', 'required|trim|callback_check_kode_area[' . $id . ']');
            $this->form_validation->set_rules('nama_area', 'Nama Area', 'required|trim');

            if ($this->form_validation->run() === TRUE) {
                $data_update = [
                    'kode_area' => $this->input->post('kode_area'),
                    'nama_area' => $this->input->post('nama_area')
                ];

                if ($this->M_Area->update_area($id, $data_update)) {
                    $this->session->set_flashdata('notif', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-check"></i> Data berhasil diupdate!</div>');
                } else {
                    $this->session->set_flashdata('notif', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fas fa-ban"></i> Data gagal diupdate!</div>');
                }
                redirect(site_url('dashboard/areas/edit/' . $id), 'refresh');
            } else {
                $data['notif'] = $this->M_Auth->notification();
                $this->load->view('admin/areas/edit.php', $data);
            }
        } else {
            redirect(site_url('dashboard/areas'), 'refresh');
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
        // $search = $this->input->post("search")['value'];

        // Total tanpa filter
        $total = $this->db->count_all("areas");

        // Query untuk filtered count
        $this->db->select('areas.*, COUNT(facilities.id) as facility_count');
        $this->db->from('areas');
        $this->db->join('facilities', 'facilities.area_id = areas.id', 'left');
        $this->db->group_by('areas.id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_area', $search);
            $this->db->or_like('nama_area', $search);
            $this->db->group_end();
        }

        $filtered = $this->db->get()->num_rows();

        // Query untuk paginated data
        $this->db->select('areas.*, COUNT(facilities.id) as facility_count');
        $this->db->from('areas');
        $this->db->join('facilities', 'facilities.area_id = areas.id', 'left');
        $this->db->group_by('areas.id');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_area', $search);
            $this->db->or_like('nama_area', $search);
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


    public function delete($id = null)
    {
        if ($id != null) {
            $response = $this->M_Area->delete_area($id);
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
        
        if ($id == null) {
            $response = [
                'success' => false,
                'message' => 'Area ID is required'
            ];
            echo json_encode($response);
            return;
        }
        
        try {
            if ($this->M_Area->delete_area($id)) {
                $response = [
                    'success' => true,
                    'message' => 'Area deleted successfully'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to delete area'
                ];
            }
            
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error deleting area: ' . $e->getMessage()
            ];
            
            echo json_encode($response);
        }
    }

    public function check_kode_area($kode_area, $id)
    {
        $area = $this->M_Area->get_area_by_kode($kode_area);
        if ($area && $area->id != $id) {
            $this->form_validation->set_message('check_kode_area', 'Kode Area sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
}
