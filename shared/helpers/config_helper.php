<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('config')){
	function config($item){
		$CI = &get_instance();
		return $CI->config->item($item);
	}
}

if(!function_exists('get_sort') ){
	function get_sort($sort_id){
		if(!$sort_id) return '';
		$sort_fields = config_item('default_sort_fields');
		if( isset($sort_fields[$sort_id]) ){
			$sort = $sort_fields[$sort_id];
			return $sort['f'].' '.$sort['s'];
		}
		elseif( is_string($sort_id) ) return $sort_id;
		return '';
	}
}

	if(!function_exists('get_sort_name') ){
		function get_sort_name($sort_id){
			if(!$sort_id) return '???';
			$sort_fields = config_item('default_sort_fields');
			if( isset($sort_fields[$sort_id]) ){
				return $sort_fields[$sort_id]['n'];
			}
			elseif( is_string($sort_id) ) return $sort_id;
			return '????';
		}
	}

	if(!function_exists('get_controllers')){
		function get_controllers($dir){
			$map = directory_map( $dir, 1, false );
			$map = array_filter( $map, function($item){
				return str_ends_with($item, '.php');

			} );
			$data = [];
			foreach( $map as $item){
				$n = str_replace('.php','', $item);
				$data[$n] = $n;
			}
			return $data;
		}
	}

	if(!function_exists('str_ends_with')){
		function str_ends_with($haystack, $needle) {
			$length = strlen($needle);
			return $length > 0 ? substr($haystack, -$length) === $needle : true;
		}
	}

	if(!function_exists('is_allowed')){
		function is_allowed($action){
			$ci = &get_instance();
			return $ci->flexi_auth->is_privileged($action);
		}
	}