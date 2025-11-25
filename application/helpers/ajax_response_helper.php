<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Standardized AJAX Response Helper
 * 
 * This helper provides standardized response format for all AJAX operations
 * to ensure consistency across the application.
 */

if (!function_exists('ajax_success_response')) {
    /**
     * Create a standardized success response
     * 
     * @param string $message Success message
     * @param array $data Additional data to include
     * @return string JSON encoded response
     */
    function ajax_success_response($message = 'Operation completed successfully', $data = []) {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

if (!function_exists('ajax_error_response')) {
    /**
     * Create a standardized error response
     * 
     * @param string $message Error message
     * @param array $data Additional data to include
     * @return string JSON encoded response
     */
    function ajax_error_response($message = 'Operation failed', $data = []) {
        $response = [
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

if (!function_exists('ajax_permission_denied_response')) {
    /**
     * Create a standardized permission denied response
     * 
     * @param string $message Permission denied message
     * @return string JSON encoded response
     */
    function ajax_permission_denied_response($message = 'Anda tidak memiliki izin untuk melakukan aksi ini!') {
        $response = [
            'status' => 'error',
            'message' => $message,
            'error_type' => 'permission_denied'
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

if (!function_exists('ajax_auth_required_response')) {
    /**
     * Create a standardized authentication required response
     * 
     * @param string $message Authentication required message
     * @return string JSON encoded response
     */
    function ajax_auth_required_response($message = 'Authentication required') {
        $response = [
            'status' => 'error',
            'message' => $message,
            'error_type' => 'auth_required'
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

if (!function_exists('ajax_validation_error_response')) {
    /**
     * Create a standardized validation error response
     * 
     * @param array $errors Validation errors
     * @param string $message General error message
     * @return string JSON encoded response
     */
    function ajax_validation_error_response($errors = [], $message = 'Validation failed') {
        $response = [
            'status' => 'error',
            'message' => $message,
            'error_type' => 'validation_error',
            'errors' => $errors
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

if (!function_exists('ajax_not_found_response')) {
    /**
     * Create a standardized not found response
     * 
     * @param string $message Not found message
     * @return string JSON encoded response
     */
    function ajax_not_found_response($message = 'Data not found') {
        $response = [
            'status' => 'error',
            'message' => $message,
            'error_type' => 'not_found'
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}