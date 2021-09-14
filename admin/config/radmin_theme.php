<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Name: bootstrap my adminn panel theme Config
*
* 
*/
$config['theme']['name'] = 'radmin';
$config['theme']['description'] = 'Radmin Bootstrap 3.1.0';

$config['theme']['styles'] = array( 'molle', 'bootstrap.min', 'bootstrap-toggle.min', 'icon-style', 'radmin', 'radmin-responsive', 
	'radmin-plugins', 'bootstrap-datetimepicker.min', 'daterangepicker', 'bootstrap-utilities.min' );

$config['theme']['scripts'] = array('bootstrap.min', 'bootstrap-toggle.min', 'jquery.cloneposition', 'theme', 'gallery', 'moment', 'bootstrap-datetimepicker.min', 'daterangepicker.min', 'custom' );

//'flot/jquery.flot', 'flot/jquery.flot.resize', 'flot/jquery.flot.tooltip', 'charts', 'sparkline',


$config['pagination']['cur_tag_open'] = '<li class="active"><a>';
$config['pagination']['cur_tag_close'] = '</a></li>';
$config['pagination']['prev_tag_open'] = '<li>';
$config['pagination']['prev_tag_close'] = '</li>';
$config['pagination']['next_tag_open'] = '<li>';
$config['pagination']['next_tag_close'] = '</li>';
$config['pagination']['first_tag_open'] = '<li>';
$config['pagination']['first_tag_close'] = '</li>';
$config['pagination']['last_tag_open'] = '<li>';
$config['pagination']['last_tag_close'] = '</li>';
$config['pagination']['num_tag_open'] = '<li>';
$config['pagination']['num_tag_close'] = '</li>';
$config['pagination']['next_link'] = 'more &raquo;';
$config['pagination']['prev_link'] = '&laquo; prev';
$config['pagination']['reuse_query_string'] = true;

	/* End of file bootstrap_theme.php */
/* Location: ./myadmin/config/bootstrap_theme.php */
