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
$route['default_controller'] = 'welcome';
$route['about-us'] = 'welcome/about_us';

$route['grindhouse'] = 'grindhouse';
$route['upcoming'] = 'grindhouse/upcoming';
$route['past'] = 'grindhouse/past';
$route['calendar'] = 'grindhouse/calendar';
$route['admin/grindhouses'] = 'grindhouse/manage';
$route['admin/grindhouse/edit/(:num)'] = 'grindhouse/edit/$1';

$route['admin'] = 'admin';
$route['admin/import-plex'] = 'importplex/import_plex';
$route['ajax/import-plex/(:num)'] = 'importplex/ajaxImportPlex/$1';

$route['admin/export-plex'] = 'importplex/export_plex';
$route['ajax/export-plex'] = 'importplex/ajaxExportPlex';

$route['admin/settings'] = 'admin/settings';
$route['ajax/save-settings'] = 'admin/ajaxSaveSettings';

$route['admin/prerolls'] = 'preroll/manage';
$route['admin/prerolls/edit/(:num)'] = 'preroll/edit/$1';
$route['admin/prerolls/create'] = 'preroll/create';

$route['admin/trailers'] = 'trailer/manage';
$route['admin/trailers/edit/(:num)'] = 'trailer/edit/$1';
$route['admin/trailers/create'] = 'trailer/create';

$route['admin/films'] = 'film/manage';
$route['admin/films/edit/(:num)'] = 'film/edit/$1';
$route['admin/films/create'] = 'film/create';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
