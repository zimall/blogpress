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