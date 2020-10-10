<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model
{
	public function add_group()
	{
		if( !$this->flexi_auth->is_privileged('Update User Groups') )
			return array( 'error'=>TRUE, 'error_msg'=>'you do not have permission to add groups' );
		
		$error['error'] = TRUE;
		$error['error_msg'] = "could not add group";
		$parent = $this->get_groups( array( 'id'=>$this->input->post('parent') ) );
		$user = $this->get_user_group();
		$node = array( 'id'=>FALSE );
		if( isset($parent['l']) && isset($user['l']) )
		{
			$parent['id'] = $parent['ugrp_id'];
			$user['id'] = $user['ugrp_id'];
			if( $parent['l'] < $user['l'] )
			{
				//$this->db->trans_rollback();
				return array( 'error_msg'=>'You cannot create a group to or beyond your group level', 'error'=>TRUE );
			}
			elseif( $parent['l'] >= $user['l'] )
			{
				$data = array(
					'ugrp_name' => $this->input->post('name'),
					'ugrp_desc' => $this->input->post('description'),
					'ugrp_parent'=>$parent['id']
				);
				$this->load->library('tree');
				$node = $this->tree->nstNewNextSibling( $this->db->dbprefix('user_groups'), $parent, $data );
				if(isset($node['id']))
				{
					$error['error_msg'] = "Group added successfully";
					$error['error'] = FALSE;
				}
			}
		}
		return $error;
	}


	public function update_group()
	{
		if( !$this->flexi_auth->is_privileged('Update User Groups') )
			return array( 'error'=>TRUE, 'error_msg'=>'you do not have permission to edit user groups' );
		
		$parent = $this->get_groups( array( 'id'=>$this->input->post('parent') ) );
		$user = $this->get_user_group();
		$current = $this->get_groups( array( 'id'=>$this->input->post('id') ) );
		$error = array( 'error'=>TRUE, 'error_msg'=>"Group update failed. Please check your data." );
		
		if( isset($parent['l']) && isset($user['l']) && isset($current['l']) )
		{
			$parent['id'] = $parent['ugrp_id'];
			$user['id'] = $user['ugrp_id'];
			$current['id'] = $current['ugrp_id'];
			if( $parent['l'] < $user['l'] )
			{
				//sem( "parent: {$parent['l']} > {$user['l']} user" );
				return array( 'error_msg'=>'Update failed. You cannot upgrade a group to or beyond your group level', 'error'=>TRUE );
			}
			elseif( $parent['l'] != $current['l'] && $parent['l'] >= $user['l'] )
			{
				$this->load->library('tree');
				$this->tree->nstMoveToNextSibling( $this->db->dbprefix('user_groups'), $current, $parent );
				$data = array(
					'ugrp_name' => $this->input->post('name'),
					'ugrp_desc' => $this->input->post('description'), 
					'ugrp_parent'=>$parent['id']
				);
				$this->db->where( 'ugrp_id', $current['id'] );
				$this->db->update( 'user_groups', $data );
				$error = array( 'error'=>FALSE, 'error_msg' => "Group updated successfully" );	
			}
			else
			{
				$error = array( 'error'=>TRUE, 'error_msg'=>"Group update failed. Please check your data. Maybe updating this group is not 
					allowed by the system." );
			}
		}
		return $error;
	}


	private function get_user_group($id=FALSE)
	{
		if(!$id && $this->flexi_auth->is_logged_in())
			$id = $this->flexi_auth->get_user_id();
		if( is_numeric($id) && $id>0 )
		{
			$this->db->from('user_accounts');
			$this->db->select( 'user_groups.ugrp_id, user_groups.l, user_groups.r, user_groups.ugrp_name' );
			$this->db->join( 'user_groups', 'user_groups.ugrp_id = user_accounts.uacc_group_fk', 'inner' );
			$this->db->where( 'user_accounts.uacc_id', $id );
			$q = $this->db->get();
			if( $q->num_rows()==1 )
				return $q->row_array();
		}
		return FALSE;
	}


	public function get_groups($args = array())
	{
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['id !=']) ) $args['id !='] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['all']) ) $args['all'] = FALSE;
		
		if( $args['id !='] )
			$this->db->where('ugrp_id !=', $args['id !='] );
		
		if($args['all']===FALSE)
			$grp = $this->get_user_group();
		
		if(isset($grp['l']))
			$this->db->where( 'l >=', $grp['l'] );
		
		if( $args['limit'] )
		{
			$this->db->limit( $args['limit'], $args['start'] );
		}
		
		if( $args['id'] )
		{
			$this->db->where( 'ugrp_id', $args['id'] );
			$query = $this->db->get('user_groups');
			if( $query->num_rows()==1 )
			return $query->row_array();
		}
		
		$this->db->order_by('l asc');
		
		$query = $this->db->get('user_groups');
		
		if($args['count'])
			return $query->num_rows();
		
		if( $query->num_rows() > 0 )
			return $query->result_array();
		
		return array();
	}

	public function get_user_data()
	{
		$user_data = $this->session->userdata('user_data');
		if( isset($user_data['uacc_id']) ) return $user_data;
		elseif( $this->flexi_auth->is_logged_in() )
		{
			$id = $this->flexi_auth->get_user_id();
			$args = array( 'id'=>$id );
			$user_data = $this->get_users($args);
			$this->session->set_userdata( 'user_data', $user_data );
			return $user_data;
		}
		return array();
	}

	public function get_users($args = array())
	{
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['group']) ) $args['group'] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['sort']) ) $args['sort'] = FALSE;
		if(! isset($args['select']) ) $args['select'] = FALSE;
		if(! isset($args['like']) ) $args['like'] = FALSE;
		if(! isset($args['where']) ) $args['where'] = FALSE;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['filter']) ) $args['filter'] = FALSE;
		if(! isset($args['one']) ) $args['one'] = FALSE;
		if(! isset($args['table']) ) $args['table'] = $this->auth->tbl_user_account;
		$table = $args['table'];
		$profile = 'user_profiles';
		$group = 'user_groups';
		
		if( $args['limit'] !== FALSE)
			$this->db->limit( $args['limit'], $args['start'] );
		$this->db->from( $table );
		
		if($args['select'])
			$this->db->select($args['select']);
		else
		{
			$this->db->select
			("
				{$table}.uacc_id, {$table}.uacc_email, {$table}.uacc_username, {$table}.uacc_suspend, {$table}.uacc_active, 
				{$table}.uacc_date_added, {$table}.uacc_date_last_login, 
				{$profile}.first_name, {$profile}.last_name, {$table}.uacc_group_fk, {$profile}.newsletter,
				{$profile}.bio, {$profile}.phone, {$profile}.photo, {$group}.ugrp_name 
			");
			
			$this->db->join($profile, "{$profile}.uacc_fk = {$table}.uacc_id", 'inner');
			$this->db->join($group, "{$group}.ugrp_id = {$table}.uacc_group_fk", 'inner');
		}
		
		if( $args['id'] !== FALSE )
		{
			$this->db->where( "{$table}.uacc_id", $args['id'] );
			$query = $this->db->get();
			if( $query->num_rows()==1 )
			return $query->row_array();
		}
		if($args['where'])
		{
			$where = $args['where'];
			foreach($where as $k=>$v)
			{
				if( is_numeric($v) )
				{
					if($v>0)
						$this->db->where( $table.'.'.$k, $v );
				}
				elseif( strlen($v)>2 )
					$this->db->where( $table.'.'.$k, $v );
			}
		}
		
		if($args['like'])
		{
			$like = $args['like'];
			foreach($like as $k=>$v)
			{
				if( strlen($v)>2 )
					$this->db->like( $table.'.'.$k, $v );
			}
		}
		
		if($args['filter'])
		{
			$filter = $args['filter'];
			foreach($filter as $v)
			{
				if( $v->q=='l' && strlen($v->v)>0 )
					$this->db->like( $v->t.'.'.$v->f, $v->v );
				elseif( $v->q=='w' && $v->v>0 )
					$this->db->where( $v->t.'.'.$v->f, $v->v );
			}
		}
		
		
		if($args['sort'])
			$this->db->order_by($args['sort']);
		else $this->db->order_by('id', 'DESC');
		
		$query = $this->db->get();
		$n = $query->num_rows();
		
		if($args['count'])
			return $n;
		
		if( $n > 0 )
		{
			if($args['one']) return $query->row_array();
			else return $query->result_array();
		}
		return array();
	}

	public function toggle_user_state( $args=array() )
	{
		if(! isset($args['state']) ) return array('error'=>'bad', 'error_msg'=>'Could not determine which state to update to');
		if(! isset($args['id']) ) return array('error'=>'bad', 'error_msg'=>'Could not determine what to update');
		
		if( $this->flexi_auth->is_privileged('Update Users') )
		{
			$state = $args['state']?0:1;
			$data = array( 'uacc_suspend'=>$state );
			$this->db->where( 'uacc_id', $args['id'] );
			$e = $this->db->update( 'user_accounts', $data );
			if($e) return array( 'error'=>FALSE, 'error_msg'=>'Account state updated successfully' );
			return array( 'error'=>TRUE, 'error_msg'=>'Could not change user state.' );
		}
		return array('error'=>TRUE, 'error_msg'=>'You do not have permission to suspend/activate users');
	}

	public function delete_user($id)
	{
		if( $this->flexi_auth->is_privileged('Delete Users') )
		{
			if( is_numeric($id) && $id!=$this->flexi_auth->get_user_id() )
			{
				$this->db->trans_start();
				$this->db->where('uacc_fk', $id);
				$this->db->delete('user_profiles');
				/*
				$this->db->where('user_id', $id);
				$this->db->delete('user_locations');
				$this->db->where('user_id', $id);
				$this->db->delete('cart_data');
				$this->db->where('user_id', $id);
				$this->db->delete('cart_orders');
				$this->db->where('id', $id);
				$this->db->delete('account');
				$this->db->where('user_id', $id);
				$this->db->delete('company_users');
				$this->db->where('user_id', $id);
				$this->db->delete('news');
				$this->db->where('user_id', $id);
				$this->db->delete('news_comments');
				$this->db->where('user_id', $id);
				$this->db->delete('product_ratings');
				$this->db->where('user_id', $id);
				$this->db->delete('product_reviews');
				$this->db->where('user_id', $id);
				$this->db->delete('articles');
				$this->db->where('user_id', $id);
				$this->db->delete('article_comments');
				$this->db->where('user_id', $id);
				$this->db->delete('article_ratings');
				$this->db->where('user_id', $id);
				$this->db->delete('company_ratings');
				$this->db->where('user_id', $id);
				$this->db->delete('company_reviews');
				$this->db->where('user_id', $id);
				$this->db->delete('cp_reviews');
				$this->db->where('user_id', $id);
				$this->db->delete('cp_ratings');
				*/
				$this->db->where('uacc_id', $id);
				$this->db->delete('user_accounts');
				$this->db->trans_complete();
				return array( 'error'=>FALSE, 'error_msg'=>'User account deleted successfully' );
			}
		}
		else return array('error'=>TRUE, 'error_msg'=>'You do not have permission to delete users');
	}

	public function get_group_permissions($args=array())
	{
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['id !=']) ) $args['id !='] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['all']) ) $args['all'] = FALSE;
		
		
		$this->db->select( 'user_privileges.*, user_privilege_groups.*, user_groups.*' );

		$this->db->join( 'user_privileges', 'user_privileges.upriv_id = user_privilege_groups.upriv_groups_upriv_fk' );
		$this->db->join( 'user_groups', 'user_groups.ugrp_id = user_privilege_groups.upriv_groups_ugrp_fk' );
		
		if( $args['id !='] )
			$this->db->where('ugrp_id !=', $args['id !='] );
		
		if( $args['limit'] )
			$this->db->limit( $args['limit'], $args['start'] );
		
		if( $args['id'] )
			$this->db->where( 'ugrp_id', $args['id'] );
		
		$this->db->order_by('upriv_name asc');
		
		$query = $this->db->get('user_privilege_groups');
		
		if($args['count'])
			return $query->num_rows();
		
		if( $query->num_rows() > 0 )
			return $query->result_array();
		
		return array();
	}

	public function get_available_permissions($selected=array())
	{
		$wni = array();
		
		foreach( $selected as $v )
			$wni[] = $v['upriv_id'];
		
		if( !empty($wni) )
			$this->db->where_not_in( 'upriv_id', $wni);
		
		$q = $this->db->get('user_privileges');
		
		if( $q->num_rows()>0 )
			return $q->result_array();
		return array();
	}

	public function get_privileges($args=array())
	{
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['all']) ) $args['all'] = FALSE;
		
		
		if( $args['limit'] )
			$this->db->limit( $args['limit'], $args['start'] );
		
		if( $args['id'] )
			$this->db->where( 'upriv_id', $args['id'] );
		
		$this->db->order_by('upriv_name asc');
		
		$query = $this->db->get('user_privileges');
		
		if($args['count'])
			return $query->num_rows();
		
		if( $query->num_rows() > 0 )
		{
			if( $args['id'] ) return $query->row_array();
			return $query->result_array();
		}
		
		return array();
	}

	public function add_privilege()
	{
		$error = array( 'error'=>TRUE, 'error_msg'=>'Could not add user privilege, please check your data and try again.' );
		
		if( !$this->flexi_auth->is_privileged('Insert Privileges') )
			return $error;
		
		$data = array(
			'upriv_name'=>$this->input->post('name'),
			'upriv_desc'=>$this->input->post('description')
		);
		
		$this->db->where( 'upriv_name', $data['upriv_name'] );
		$q = $this->db->get('user_privileges');
		if( $q->num_rows()>0 )
			return array( 'error'=>TRUE, 'error_msg'=>"The User Privilege you are trying to add already exists." );
		
		$e = $this->db->insert( 'user_privileges', $data );
		
		if($e)
			return array( 'error'=>FALSE, 'error_msg'=>'User Privilege added successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'Could not add user privilege, please check your data and try again.' );
	}

	public function update_privilege()
	{
		$error = array( 'error'=>TRUE, 'error_msg'=>'Could not update user privilege, please check your data and try again.' );
		
		if( !$this->flexi_auth->is_privileged('Update Privileges') )
			return $error;
		
		
		$data = array(
			'upriv_name'=>$this->input->post('name'),
			'upriv_desc'=>$this->input->post('description')
		);
		
		$this->db->where( 'upriv_id', $this->input->post('id') );
		$e = $this->db->update( 'user_privileges', $data );
		if($e)
			return array( 'error'=>FALSE, 'error_msg'=>'User Privilege updated successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'Could not update user privilege, please check your data and try again.' );
	}

	public function delete_privilege()
	{
		$error = array( 'error'=>TRUE, 'error_msg'=>'Could not delete user privilege, please check your data and try again.' );
		
		if( !$this->flexi_auth->is_privileged('Delete Privileges') )
			return $error;
		
		$this->db->where( 'upriv_id', $this->input->post('id') );
		$e = $this->db->delete( 'user_privileges' );
		if($e)
			return array( 'error'=>FALSE, 'error_msg'=>'User Privilege deleted successfully' );
		else return $error;
	}

	public function update_group_privileges()
	{
		if( !$this->flexi_auth->is_privileged('Update User Groups') )
			return array( 'error'=>TRUE, 'error_msg'=>'you do not have permission to edit group permissions' );
		
		$group = $this->input->post('group');
		
		$this->db->select('upriv_groups_id');
		$q = $this->db->get_where( 'user_privilege_groups', array( 'upriv_groups_ugrp_fk' => $this->input->post('group') ) );
		$n = $q->num_rows();
		if($n>0)
			$keys = $q->result_array();
		else $keys = array();
		
		$e1 = $this->db->delete('user_privilege_groups', array('upriv_groups_ugrp_fk' => $group ) );
		$e = FALSE;
		
		$permissions = $this->input->post('permissions');
		
		if( !is_array($permissions) )
			$permissions = array();
		
		$data = array();
		foreach( $permissions as $k=>$value )
		{
			if(isset($keys[$k]))
			{
				$data[] = array( 'upriv_groups_id'=>$keys[$k]['upriv_groups_id'], 'upriv_groups_upriv_fk'=>$value, 
					'upriv_groups_ugrp_fk'=>$group );
			}
			else
			{
				$data[] = array( 'upriv_groups_id'=>NULL, 'upriv_groups_upriv_fk'=>$value, 'upriv_groups_ugrp_fk'=>$group );
			}
		}
		
		if( count($data)>0 )
		{
			$e = $this->db->insert_batch('user_privilege_groups', $data);
		}
		
		if( $e || $e1 ) return array( 'error'=>FALSE, 'error_msg'=>'Group Privileges updated successfully' );
		return array( 'error'=>TRUE, 'error_msg'=>'Could not update group privileges, please check your data and try again.' );
	}

}?>
