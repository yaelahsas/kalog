<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Permission Library
 * Library untuk mengelola hak akses user berdasarkan level
 */
class Permission_lib {
    
    protected $CI;
    protected $user_level;
    protected $session_data;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('permission');
        $this->session_data = $this->CI->session->userdata();
        $this->user_level = isset($this->session_data['level']) ? $this->session_data['level'] : null;
    }
    
    /**
     * Memeriksa apakah user memiliki akses ke fitur tertentu
     * @param string $permission Jenis permission (view, add, edit, delete)
     * @return bool
     */
    public function can_access($permission) {
        return can_access($permission, $this->session_data);
    }
    
    /**
     * Memeriksa apakah user bisa melihat menu tertentu
     * @param string $menu Nama menu
     * @return bool
     */
    public function can_view_menu($menu) {
        return can_view_menu($menu, $this->session_data);
    }
    
    /**
     * Memeriksa apakah user bisa menambah data
     * @return bool
     */
    public function can_add() {
        return can_add($this->session_data);
    }
    
    /**
     * Memeriksa apakah user bisa mengedit data
     * @return bool
     */
    public function can_edit() {
        return can_edit($this->session_data);
    }
    
    /**
     * Memeriksa apakah user bisa menghapus data
     * @return bool
     */
    public function can_delete() {
        return can_delete($this->session_data);
    }
    
    /**
     * Mendapatkan level user
     * @return string|null
     */
    public function get_user_level() {
        return $this->user_level;
    }
    
    /**
     * Memeriksa apakah user adalah root
     * @return bool
     */
    public function is_root() {
        return $this->user_level === 'root';
    }
    
    /**
     * Memeriksa apakah user adalah admin
     * @return bool
     */
    public function is_admin() {
        return $this->user_level === 'admin';
    }
    
    /**
     * Memeriksa apakah user adalah user biasa
     * @return bool
     */
    public function is_user() {
        return $this->user_level === 'user';
    }
    
    /**
     * Redirect user jika tidak memiliki akses
     * @param string $permission Jenis permission yang dibutuhkan
     * @param string $redirect_url URL untuk redirect
     * @return bool|void
     */
    public function check_access($permission, $redirect_url = null) {
        if (!$this->can_access($permission)) {
            if ($redirect_url) {
                redirect($redirect_url);
            } else {
                show_error('Anda tidak memiliki akses ke halaman ini', 403, 'Access Denied');
            }
            return false;
        }
        return true;
    }
    
    /**
     * Redirect user jika tidak bisa melihat menu
     * @param string $menu Nama menu
     * @param string $redirect_url URL untuk redirect
     * @return bool|void
     */
    public function check_menu_access($menu, $redirect_url = null) {
        if (!$this->can_view_menu($menu)) {
            if ($redirect_url) {
                redirect($redirect_url);
            } else {
                show_error('Anda tidak memiliki akses ke menu ini', 403, 'Access Denied');
            }
            return false;
        }
        return true;
    }
    
    /**
     * Mendapatkan daftar menu yang bisa diakses user
     * @return array
     */
    public function get_accessible_menus() {
        $all_menus = [
            'dashboard' => 'Dashboard Monitoring',
            'facilities' => 'Fasilitas',
            'areas' => 'Area',
            'vendors' => 'Vendor',
            'facility_types' => 'Jenis Fasilitas',
            'reports' => 'Laporan',
            'account' => 'Account',
            'category' => 'Category',
            'phone' => 'Phone'
        ];
        
        $accessible_menus = [];
        
        foreach ($all_menus as $menu_key => $menu_name) {
            if ($this->can_view_menu($menu_key)) {
                $accessible_menus[$menu_key] = $menu_name;
            }
        }
        
        return $accessible_menus;
    }
    
    /**
     * Mendapatkan permissions untuk user saat ini
     * @return array
     */
    public function get_permissions() {
        return [
            'view' => $this->can_access('view'),
            'add' => $this->can_access('add'),
            'edit' => $this->can_access('edit'),
            'delete' => $this->can_access('delete'),
            'level' => $this->user_level
        ];
    }
}