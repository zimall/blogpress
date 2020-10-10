<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Name: bootstrap my adminn panel theme Config
*
* 
*/
$theme_config['name'] = 'radmin';
$theme_config['description'] = 'Radmin Bootstrap 3.1.0';

$theme_config['styles'] = array( 'molle', 'bootstrap.min', 'icon-style', 'radmin', 'radmin-responsive', 
	'radmin-plugins', 'bootstrap-datetimepicker.min' );

$theme_config['scripts'] = array('bootstrap.min', 'jquery.cloneposition', 'theme', 'gallery', 'moment', 'bootstrap-datetimepicker.min', 'custom' );

//'flot/jquery.flot', 'flot/jquery.flot.resize', 'flot/jquery.flot.tooltip', 'charts', 'sparkline',


$theme_config['pagination']['cur_tag_open'] = '<li class="active"><a>';
$theme_config['pagination']['cur_tag_close'] = '</a></li>';
$theme_config['pagination']['prev_tag_open'] = '<li>';
$theme_config['pagination']['prev_tag_close'] = '</li>';
$theme_config['pagination']['next_tag_open'] = '<li>';
$theme_config['pagination']['next_tag_close'] = '</li>';
$theme_config['pagination']['first_tag_open'] = '<li>';
$theme_config['pagination']['first_tag_close'] = '</li>';
$theme_config['pagination']['last_tag_open'] = '<li>';
$theme_config['pagination']['last_tag_close'] = '</li>';
$theme_config['pagination']['num_tag_open'] = '<li>';
$theme_config['pagination']['num_tag_close'] = '</li>';
$theme_config['pagination']['next_link'] = 'more &raquo;';
$theme_config['pagination']['prev_link'] = '&laquo; prev';

/* End of file bootstrap_theme.php */
/* Location: ./myadmin/config/bootstrap_theme.php */
