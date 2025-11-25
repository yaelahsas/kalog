<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Permission Helper
 * Fungsi-fungsi untuk memeriksa hak akses user berdasarkan level
 */

if (!function_exists('can_access')) {
    /**
     * Memeriksa apakah user memiliki akses ke fitur tertentu
     * @param string $permission Jenis permission (view, add, edit, delete)
     * @param array $session Data session user
     * @return bool
     */
    function can_access($permission, $session = null) {
        $CI =& get_instance();
        
        if ($session === null) {
            $session = $CI->session->userdata();
        }
        
        // Jika session tidak ada, return false
        if (!isset($session['level'])) {
            return false;
        }
        
        $user_level = $session['level'];
        
        // Root memiliki semua akses
        if ($user_level === 'root') {
            return true;
        }
        
        // Admin memiliki semua akses kecuali management account
        if ($user_level === 'admin') {
            return true;
        }
        
        // User hanya bisa view (melihat data)
        if ($user_level === 'user') {
            return $permission === 'view';
        }
        
        return false;
    }
}

if (!function_exists('can_view_menu')) {
    /**
     * Memeriksa apakah user bisa melihat menu tertentu
     * @param string $menu Nama menu
     * @param array $session Data session user
     * @return bool
     */
    function can_view_menu($menu, $session = null) {
        $CI =& get_instance();
        
        if ($session === null) {
            $session = $CI->session->userdata();
        }
        
        // Jika session tidak ada, return false
        if (!isset($session['level'])) {
            return false;
        }
        
        $user_level = $session['level'];
        
        // Root bisa melihat semua menu
        if ($user_level === 'root') {
            return true;
        }
        
        // Admin tidak bisa melihat menu Account
        if ($user_level === 'admin' && $menu === 'account') {
            return false;
        }
        
        // User tidak bisa melihat menu Account, Category, Phone
        if ($user_level === 'user') {
            $restricted_menus = ['account', 'category', 'phone'];
            return !in_array($menu, $restricted_menus);
        }
        
        return true;
    }
}

if (!function_exists('can_add')) {
    /**
     * Memeriksa apakah user bisa menambah data
     * @param array $session Data session user
     * @return bool
     */
    function can_add($session = null) {
        return can_access('add', $session);
    }
}

if (!function_exists('can_edit')) {
    /**
     * Memeriksa apakah user bisa mengedit data
     * @param array $session Data session user
     * @return bool
     */
    function can_edit($session = null) {
        return can_access('edit', $session);
    }
}

if (!function_exists('can_delete')) {
    /**
     * Memeriksa apakah user bisa menghapus data
     * @param array $session Data session user
     * @return bool
     */
    function can_delete($session = null) {
        return can_access('delete', $session);
    }
}

if (!function_exists('get_user_level')) {
    /**
     * Mendapatkan level user dari session
     * @param array $session Data session user
     * @return string|null
     */
    function get_user_level($session = null) {
        $CI =& get_instance();
        
        if ($session === null) {
            $session = $CI->session->userdata();
        }
        
        return isset($session['level']) ? $session['level'] : null;
    }
}