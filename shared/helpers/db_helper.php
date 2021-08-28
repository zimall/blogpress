<?php

	if( !function_exists('query_args') ){
		function query_args( $db, $args ){
			if( !empty($args['select'])) $db->select($args['select']);

			if( !empty($args['where']) ) $db->where($args['where']);
			if( !empty($args['or_where']) ) $db->or_where($args['or_where']);
			if( !empty($args['where_in']) )
			{
				foreach($args['where_in'] as $k=>$v)
				{
					$db->where_in( $k, $v );
				}
			}

			if( !empty($args['like']) )
			{
				$like = $args['like'];
				foreach($like as $k=>$v)
				{
					if( is_array($v) ) $db->like($k, $v['value'], $v['wc']);
					else $db->like($k,$v);
				}
			}

			if(!empty($args['filter']))
			{
				$filter = $args['filter'];
				foreach($filter as $v)
				{
					if( $v->q=='l' && strlen($v->v)>0 ) $db->like( $v->t.'.'.$v->f, $v->v );
					elseif( $v->q=='w' && $v->v>0 ) $db->where( $v->t.'.'.$v->f, $v->v );
				}
			}

			if(!empty($args['sort']))
			{
				$sort = $args['sort'];
				if( is_array( $sort ) )
				{
					foreach( $sort as $k=>$v ) $db->order_by($k,$v);
				}
				else $db->order_by($args['sort']);
			}

			if( isset($args['limit']) && isset($args['start']) ){
				if($args['limit']) $db->limit($args['limit'], $args['start']);
			}
			elseif( !empty($args['limit']) ){
				$db->limit( $args['limit'] );
			}
		}
	}

	if(!function_exists('get_rows')){
		function get_rows($db){
			$r = $db->get();
			if( $r->num_rows() > 0 ){
				return $r->result_array();
			}
			return [];
		}
	}

	if(!function_exists('get_row')){
		function get_row($db){
			$r = $db->get();
			if( $r->num_rows() > 0 ){
				return $r->row_array();
			}
			return [];
		}
	}

	if(!function_exists('get_field')){
		function get_field($db, $field, $table=null){
			$r = $db->get($table);
			if( $r->num_rows() > 0 ){
				return $r->row()->$field;
			}
			return null;
		}
	}
