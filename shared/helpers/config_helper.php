<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('config')){
	function config($item){
		$CI = &get_instance();
		return $CI->config->item($item);
	}
}
