<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'View';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = 'admin/auth/login';

// Dashboard routes untuk menghandle URL dashboard/*
$route['dashboard'] = 'admin/dashboard';
$route['dashboard/facilities'] = 'admin/facilities';
$route['dashboard/facilities/add'] = 'admin/facilities/add';
$route['dashboard/facilities/edit/(:any)'] = 'admin/facilities/edit/$1';
$route['dashboard/facility_detail/(:any)'] = 'admin/facilities/detail/$1';
$route['dashboard/areas'] = 'admin/areas';
$route['dashboard/areas/add'] = 'admin/areas/add';
$route['dashboard/areas/edit/(:any)'] = 'admin/areas/edit/$1';
$route['dashboard/vendors'] = 'admin/vendors';
$route['dashboard/vendors/add'] = 'admin/vendors/add';
$route['dashboard/vendors/edit/(:any)'] = 'admin/vendors/edit/$1';
$route['dashboard/facility_types'] = 'admin/facility_types';
$route['dashboard/facility_types/add'] = 'admin/facility_types/add';
$route['dashboard/facility_types/edit/(:any)'] = 'admin/facility_types/edit/$1';
$route['dashboard/reports'] = 'admin/reports';
$route['dashboard/reports/get_chart_data'] = 'admin/reports/get_chart_data';
$route['dashboard/export_excel'] = 'admin/reports/export_excel';
$route['dashboard/reports/get_newest_facilities'] = 'admin/reports/get_newest_facilities';
$route['dashboard/reports/get_facilities_by_type_detailed'] = 'admin/reports/get_facilities_by_type_detailed';
$route['dashboard/reports/get_facilities_by_area_detailed'] = 'admin/reports/get_facilities_by_area_detailed';
$route['dashboard/facilities/filter'] = 'admin/facilities/filter';
$route['dashboard/facilities/search'] = 'admin/facilities/search';

// Routes untuk method data() yang digunakan oleh DataTables
$route['admin/vendors/data'] = 'admin/vendors/data';
$route['admin/areas/data'] = 'admin/areas/data';
$route['admin/facilities/data'] = 'admin/facilities/data';
$route['admin/facility_types/data'] = 'admin/facility_types/data';
$route['admin/account/data'] = 'admin/account/data';
$route['admin/category/data'] = 'admin/category/data';
$route['admin/phone/data'] = 'admin/phone/data';

// Routes untuk method delete() yang digunakan oleh DataTables
$route['admin/areas/delete/(:any)'] = 'admin/areas/delete/$1';
$route['admin/facilities/delete/(:any)'] = 'admin/facilities/delete/$1';
$route['admin/facility_types/delete/(:any)'] = 'admin/facility_types/delete/$1';
$route['admin/vendors/delete/(:any)'] = 'admin/vendors/delete/$1';

// Routes untuk method delete_ajax() yang digunakan oleh DataTables
$route['admin/areas/delete_ajax/(:any)'] = 'admin/areas/delete_ajax/$1';
$route['admin/facilities/delete_ajax/(:any)'] = 'admin/facilities/delete_ajax/$1';
$route['admin/facility_types/delete_ajax/(:any)'] = 'admin/facility_types/delete_ajax/$1';
$route['admin/vendors/delete_ajax/(:any)'] = 'admin/vendors/delete_ajax/$1';
