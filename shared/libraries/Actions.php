<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Michael M Chiwere
 * Description: PAGINATION AND SORTING - Page Control class
 * This is only viewable to those members that are logged in
 */
class Actions{
	
	function __construct(){
		$this->ci = &get_instance();
	}

	public function check()
	{
		if( $this->ci->input->post() )
			$this->check_post();
		if( $this->ci->input->get() )
			$this->check_get();
	}

	private function check_post()
	{
		$f = $this->ci->input->post('form_name');
		if($f)
		{
			if ( method_exists($this,$f) )
				$this->$f();
		}
	}
	
	private function check_get()
	{
		$get = $this->ci->input->get();
		$f = FALSE;
		if( is_array($get) && !empty($get) )
		{
			foreach( $this->requests as $a )
			{
				if( in_array( $a, $get ) )
				{
					$f = $a;
					break;
				}
			}
		}
		if($f)
		{
			if ( method_exists($this,$f) )
				$this->$f();
		}
	}

	private function test_err()
	{
		$this->sem('but it seems to be working');
	}


	public function standard_weight( $w, $c )
	{
		$this->ci->db->select('ratio');
		$q = $this->ci->db->get_where( 'measurements', array( 'id'=>$c ) );
		if($q->num_rows()==1)
		{
			return $w / $q->row()->ratio;
		}
		else return $w;
	}


	/* Set Error Message */
	public function sem($msg='', $f=1, $log=0, $show=1)
	{
		$success = $this->ci->config->item('success_class');
		if(!$success) $success = 'alert alert-success';
		$info = $this->ci->config->item('info_class');
		if(!$info) $info = 'alert alert-info';
		$error = $this->ci->config->item('error_class');
		if(!$error) $error = 'alert alert-error alert-warning';
		
		if( !empty($msg) && $msg != FALSE )
		{
			if( is_array($msg) )
			{
				if( isset($msg['error_msg']) && isset($msg['error']) )
				{
					$ar = $msg;
					$msg = $ar['error_msg'];
					$f = $ar['error'];
				}
			}
			if( $f===FALSE || $f===0 ) $f = $success;
			else
			{
				if( $f===2 ) $f = $info;
				else $f = $error;
			}
			
			$old = $this->ci->session->userdata('error_msg');
			
			if($show)
			{
				$usermsg = $msg;
				if( $log==1 || $log===TRUE ) log_message('error', $msg);
			}
			else
			{
				if( $log==1 || $log===TRUE ) log_message('error', $msg);
				return;
			}
			
			$key = md5($usermsg);
			//$this->ci->load->library('password');
			if(empty($this->ci->data['error_msg']))
			{
				// $this->ci->password->gp(5,3)
				$this->ci->data['error_msg'] = array( $key=>array('msg'=>$usermsg,'f'=>$f) );
				
				if( is_array($old) && !isset($old[$key]) )
					$this->ci->data['error_msg'] = array_merge( $old, $this->ci->data['error_msg'] );
				
				//if($p==1 || $p===TRUE )
				//	$this->ci->session->set_userdata( 'error_msg', $this->ci->data['error_msg'] );
			}
			else
			{
				$error = $this->ci->data['error_msg'];
				//$this->ci->password->gp(5,3)
					$error[$key] = array('msg'=>$usermsg,'f'=>$f);
				$this->ci->data['error_msg'] = $error;
				
				if( is_array($old) && !isset($old[$key]) )
					$this->ci->data['error_msg'] = array_merge( $old, $this->ci->data['error_msg'] );
				
				//if($p==1 || $p===TRUE )
				//	$this->ci->session->set_userdata( 'error_msg', $this->ci->data['error_msg'] );
			}
		}
		elseif( !empty($this->ci->data['error_msg']) )
			$this->ci->session->set_userdata( 'error_msg', $this->ci->data['error_msg'] );
	}

	/* Get Error Message */
	public function gem($clear=TRUE)
	{
		$msg = $this->ci->session->userdata('error_msg');
		if(is_array($msg))
		{
			if($clear) $this->ci->session->unset_userdata('error_msg');
			else $this->sem();
			$local = $this->ci->data['error_msg'];
			if( is_array($local) )
				return array_merge( $local, $msg );
			else return $msg;
		}
		else
		{
			$msg = $this->ci->data['error_msg'];
			if( is_array($msg) )
			{
				if(!$clear) sem();
				return $msg;
			}
			else return array();
		}
	}


}
