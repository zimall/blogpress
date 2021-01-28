<?php defined('BASEPATH') OR exit('No direct script access allowed');
$route['gallery/page'] = 'gallery/list';
$route['gallery/page/(.+)'] = 'gallery/list/$1';
$route['read/(:num)'] = 'read/i/$1';
$route['read/(:num)/.+'] = 'read/i/$1';
$route['read/page'] = 'read/list';
$route['read/page/(.+)'] = 'read/list/$1';

$route['page'] = 'home/index';
$route['page/(.+)'] = 'home/index';

// manual override about page
$route['about'] = 'about/index';

$route['default_controller'] = 'home';
$route['404_override'] = 'read/index';
$route['translate_uri_dashes'] = FALSE;
