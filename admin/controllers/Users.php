<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

	function __construct() 
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('user_model');
		$this->data['nav_element'] = 'users';
		$this->data['section'] = 'users';
	}

	public function index()
	{
		$filter = array(
				'fname'=>array( 'q'=>'l', 't'=>'profiles', 'f'=>'fname' ),
				'surname'=>array( 'q'=>'l', 't'=>'profiles', 'f'=>'surname' ),
				'uacc_group_fk'=>array( 'q'=>'w', 't'=>'user_accounts', 'f'=>'uacc_group_fk' )
		);
		//$this->session->unset_userdata('filter_settings');
		$this->_process_form();
		$this->pc->page_control('users', 10, json_encode($filter) );
		$continue = $this->_process_get();
		
		if($continue)
		{
			$count = array( 'count'=>TRUE);
			$function = 'get_users';
			
			if( isset($this->data['where']) )
				$count['where'] = $this->data['where'];
			if( isset($this->data['like']) )
				$count['like'] = $this->data['like'];
			if( isset($this->data['filter']) )
				$count['filter'] = $this->data['filter'];
			
			$paginate = $this->pc->paginate($count, $function, 'user_model');
			
			$args = array( 'sort'=>$this->data['sort'], 'start'=>$paginate['start'], 'limit'=>$paginate['limit'] );
			$count['count'] = NULL;
			$args = array_merge($args, $count);
			
			$this->data['users'] = $this->user_model->get_users( $args );
			
			$this->data['innertitle'] = 'Users';
			$this->data['user_list'] = TRUE;	
			$this->data['groups'] = $this->user_model->get_groups();
		}
		
		$this->load->view( "{$this->data['theme']}/users.tpl", $this->data );
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Privileges
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
 	 * manage_privileges
 	 * View and manage a table of all user privileges.
 	 * This example allows user privileges to be deleted via checkoxes within the page.
 	 */
    function privileges()
    {
		// Check user has privileges to view user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Privileges'))
		{
			sem( 'You do not have access privileges to view user privileges.');
			redirect('home');		
		}
		
		$this->_process_form();
		$this->pc->page_control('privilege_list', 20 );
		$continue = $this->_process_get();
		
		if($continue)
		{
			$count = array( 'count'=>TRUE);
			$function = 'get_privileges';
			
			$paginate = $this->pc->paginate($count, $function, 'user_model');
			
			$args = array( 'start'=>$paginate['start'], 'limit'=>$paginate['limit'] );
			$count['count'] = NULL;
			$args = array_merge($args, $count);
			
			$this->data['privileges'] = $this->user_model->get_privileges( $args );
			
			$this->data['innertitle'] = 'User Privileges';
			$this->data['section'] = 'privileges';	
		}
		
		$this->load->view( "{$this->data['theme']}/users.tpl", $this->data );
	}

 	/**
 	 * manage_privileges
 	 * View and manage a table of all user privileges.
 	 * This example allows user privileges to be deleted via checkoxes within the page.
 	 */
    function manage_privileges()
    {
		// Check user has privileges to view user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view user privileges.</p>');
			redirect('users');		
		}
		
		// If 'Manage Privilege' form has been submitted and the user has privileges to delete privileges.
		if ($this->input->post('delete_privilege') && $this->flexi_auth->is_privileged('Delete Privileges')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->manage_privileges();
		}

		// Define the privilege data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
				
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		//$this->load->view('demo/admin_examples/privileges_view', $this->data);
		$this->load->view( "{$this->data['theme']}/users.tpl", $this->data );
	}
	
 	/**
 	 * insert_privilege
 	 * Insert a new user privilege.
 	 */
	function new_privilege()
	{
		// Check user has privileges to insert user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Insert Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to insert new user privileges.</p>');
			redirect('users/privileges');		
		}

		$this->_process_form();
		$continue = $this->_process_get();
		if($continue)
		{
			// Set any returned status/error messages.
			$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
			$this->data['section'] = 'new_privilege';	
		}
		$this->load->view( "{$this->data['theme']}/users.tpl", $this->data );
	}
	
	
 	/**
 	 * update_user_privileges
 	 * Update the access privileges of a specific user.
 	 */
    function update_user_privileges($user_id)
    {
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('auth_admin/manage_user_accounts');		
		}

		// If 'Update User Privilege' form has been submitted, update the user privileges.
		if ($this->input->post('update_user_privilege')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->update_user_privileges($user_id);
		}

		// Get users profile data.
		$sql_select = array(
			'user_profiles.uacc_fk', 
			'first_name', 
			'last_name',
			$this->flexi_auth->db_column('user_acc', 'group_id'),
			$this->flexi_auth->db_column('user_group', 'name')
        );
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array($sql_select, $sql_where);		

		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
		// Get user groups current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $this->data['user'][$this->flexi_auth->db_column('user_acc', 'group_id')]);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
        $this->data['group_privileges'] = array();
        foreach($group_privileges as $privilege)
        {
            $this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
        }
                
		// Get users current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => $user_id);
		$user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);
	
		// For the purposes of the example demo view, create an array of ids for all the users assigned privileges.
		// The array can then be used within the view to check whether the user has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['user_privileges'] = array();
		foreach($user_privileges as $privilege)
		{
			$this->data['user_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_users', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
		$this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];
                
		$this->load->view('demo/admin_examples/user_privileges_update_view', $this->data);		
    }
    
 	/**
 	 * update_group_privileges 
 	 * Update the access privileges of a specific user group.
 	 */
    function update_group_privileges($group_id)
    {
		// Check user has privileges to update group privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update group privileges.</p>');
			redirect('auth_admin/manage_user_accounts');		
		}

		// If 'Update Group Privilege' form has been submitted, update the privileges of the user group.
		if ($this->input->post('update_group_privilege')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->update_group_privileges($group_id);
		}
		
		// Get data for the current user group.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
                
		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
		// Get data for the current privilege group.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $group_id);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
		// For the purposes of the example demo view, create an array of ids for all the privileges that have been assigned to a privilege group.
		// The array can then be used within the view to check whether the group has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['group_privileges'] = array();
		foreach($group_privileges as $privilege)
		{
			$this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
        $this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];
                
		$this->load->view('demo/admin_examples/user_group_privileges_update_view', $this->data);		
    }


	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Groups
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * manage_user_groups
 	 * View and manage a table of all user groups.
 	 * This example allows user groups to be deleted via checkoxes within the page.
 	 */
    function groups()
    {
		// Check user has privileges to view user groups, else display a message to notify the user they do not have valid privileges.
		/*
		if (! $this->flexi_auth->is_privileged('View User Groups'))
		{
			sem('You do not have privileges to view user groups');
			redirect('home');		
		}
		*/
		$this->_process_form();
		$continue = $this->_process_get();
		
		/*
		// If 'Manage User Group' form has been submitted and user has privileges, delete user groups.
		if ($this->input->post('delete_group') && $this->flexi_auth->is_privileged('Delete User Groups')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->manage_user_groups();
		}
		*/
		if($continue)
		{
			$this->data['groups'] = $this->user_model->get_groups();
			$this->data['section'] = 'groups';
			// Set any returned status/error messages.
			$this->data['message'] = (!isset($this->data['message']))?$this->session->flashdata('message'):$this->data['message'];
		}

		$this->load->view( "{$this->data['theme']}/users.tpl", $this->data );
    }

	public function new_group()
	{
		$this->_process_form();
		$this->data['groups'] = $this->user_model->get_groups( array('all'=>TRUE) );
		$this->data['section'] = 'new_group';
		$this->load->view( "{$this->data['theme']}/users.tpl", $this->data );
	}

	/****** PROCESS FORM DATA ************/
	private function _process_form()
	{
		$name = $this->input->post('form_name');
		$action = $this->input->post('form_type');
		
		if( !is_null($name) && !is_null($action) )
		{
			$this->load->library('form_validation');
			
			if( $name=="account" && $action=='update' )
			{
				$id = $this->input->post('id');
				$this->load->model('auth_admin_model');
				$e = $this->auth_admin_model->update_user_account($id);
				if($e==FALSE)
				{
					$_GET['action'] = 'update_account';
					$_GET['id'] = $id;
				}
			}
			elseif( $_POST['form_name']=="account" && $_POST['form_type']=="delete" )
			{
				$this->form_validation->set_rules('id', 'Item ID', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$e = $this->user_model->delete_user( $this->input->post('id') );
					sem($e);
					redirect( current_url() );
				}
				$_GET['action'] = 'remove_account';
				$_GET['id'] = $this->input->post('id');
			}

			elseif( $_POST['form_name']=="sms" && $_POST['form_type']=="send" )
			{
				$this->form_validation->set_rules('phone', 'Phone Number', 'required');
				$this->form_validation->set_rules('message', 'Message', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$phone = $this->input->post('phone');
					$msg = $this->input->post('message');
					$this->load->helper('mms');
					sem( send_sms( $phone, $msg ) );
				}
			}
				
			elseif( $_POST['form_name']=="account" && $_POST['form_type']=="insert" )
			{
				foreach( $this->input->post() as $k=>$v )
				{
					if( is_null( $_POST[$k] ) ) $_POST[$k] = FALSE;
				}
				$this->load->library('password'); 
				$_POST['register_password'] = $_POST['register_confirm_password'] = $this->password->gp();
				$this->load->model('auth_model');
				$id = $this->auth_model->register_account(FALSE,TRUE);
				if ( is_numeric($id) && $id>0 )
				{
					$email = $this->input->post('register_email_address');
					$name = $this->input->post('register_first_name');
					$surname = $this->input->post('register_last_name');
					$username = $this->input->post('register_username');
					//$admin = $this->data['fname'].' '.$this->data['surname'];
					$site = $this->config->item('site_name');
					$pass=$this->input->post('register_password');
					$url=base_url('account');
					$change=base_url('account/change_pass');
					$this->load->library('email');
					$this->email->from( $this->config->item('system_email'), $site );
					$this->email->to( $email );
					$this->email->bcc( $this->config->item('bcc') );
					$this->email->subject( "New account created for {$name} {$surname} on {$site}" );
					$this->email->message("
						You are receiving this email because an account was created for you on $site.<br><br>
						<strong>Email </strong>$email<br>
						<strong>Username </strong>$username<br>
						<strong>Password </strong>$pass.<br><hr>
						We strongly recommend you <a href='$change'>change this password</a> right away.<br><hr>
						You can check out your account <a href='$url'>here</a><br><br><br>
						Please open this link to set a new password for your account:<br> 
						{$change} <br><br><br>
						Regards,<br> 
						{$site} Admin<br>
					");
					$this->email->send();
				}
				else $_GET['action'] = 'add_user';
			}
			
			elseif( $name=="user_group" && $action=="insert" )
			{
				$this->form_validation->set_rules('name', 'Group Name', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$e = $this->user_model->add_group();
					sem( $e );
					if( !$e['error'] )
						redirect('users/groups');
				}
			}
			elseif( $name=="user_privilege" && $action=="insert" )
			{
				$this->form_validation->set_rules('name', 'Privilege Name', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$e = $this->user_model->add_privilege();
					sem( $e );
					if( !$e['error'] )
						redirect('users/privileges');
				}
			}
			elseif( $name=="user_group" && $action=="update" )
			{
				$this->form_validation->set_rules('name', 'Group Name', 'required');
				$this->form_validation->set_rules('parent', 'Parent Group', 'required|integer');
				$this->form_validation->set_rules('id', 'Group ID', 'required|integer');
				if ($this->form_validation->run() === TRUE)
				{
					$e = $this->user_model->update_group();
					sem( $e );
					if( !$e['error'] )
						redirect(current_url());
				}
			}
			elseif( $name=="user_privilege" && $action=="update" )
			{
				$this->form_validation->set_rules('name', 'Privilege Name', 'required');
				$this->form_validation->set_rules('id', 'Privilege ID', 'required|integer');
				if ($this->form_validation->run() === TRUE)
				{
					$e = $this->user_model->update_privilege();
					sem( $e );
					if( !$e['error'] )
						redirect(current_url());
				}
			}
			elseif( $name=="user_privilege" && $action=="delete" )
			{
				$this->form_validation->set_rules('id', 'Privilege ID', 'required|integer');
				$this->form_validation->set_rules('confirm_delete', 'Delete Confirmation', 'required|integer');
				if ($this->form_validation->run() === TRUE)
				{
					$e = $this->user_model->delete_privilege();
					sem( $e );
					if( !$e['error'] ) redirect(current_url());
				}
				$_GET = ['action'=>'delete_privilege','id'=>$this->input->post('id')];
			}
			elseif( $name=="group_privileges" && $action=="update" )
			{
				$this->form_validation->set_rules('group', 'Group ID', 'required|integer');
				if ($this->form_validation->run() === TRUE)
				{
					$e = $this->user_model->update_group_privileges();
					sem( $e );
					if( !$e['error'] )
						redirect(current_url());
				}
			}
			elseif( $_POST['form_name']=="permission" && $_POST['form_type']=="insert" )
			{
				$this->form_validation->set_rules('name', 'Permission Name', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$this->data['error'] = $this->user_model->add_permission();
					sem( $this->data['error'] );
					if($this->data['error']=='fail') $_GET['action'] = 'add_permission';
				}
				else $_GET['action'] = 'add_permission';
				
				
			}
			elseif( $_POST['form_name']=="permission" && $_POST['form_type']=="delete" )
			{
				$this->form_validation->set_rules('id', 'Item ID', 'required');
				$this->form_validation->set_rules('del', 'Text', 'required|alpha|matches[DELETE]');
				if ($this->form_validation->run() === TRUE){
					$this->data['error'] = $this->user_model->delete_permission();
					sem( $this->data['error'] );
				}
				else {
					$_GET['action'] = 'delete_permission';
					$_GET['id'] = $this->input->post('id');
				}
			}
			elseif( $_POST['form_name']=="permission" && $_POST['form_type']=="update" )
			{
				$this->form_validation->set_rules('name', 'Permission Name', 'required');
				$this->form_validation->set_rules('id', 'Permission ID', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$this->data['error'] = $this->user_model->update_permission();
					sem( $this->data['error'] );
				}
				else
				{
					$_GET['action'] = 'edit_permission';
					$_GET['id'] = $this->input->post('id');
				}
			}
			
			elseif( $_POST['form_name']=="group" && $_POST['form_type']=="delete" )
			{
				$this->form_validation->set_rules('id', 'Item ID', 'required');
				$this->form_validation->set_rules('confirm_delete', 'Delete Confirmation', 'required|integer');
				if ($this->form_validation->run() === TRUE){
					$this->data['error'] = $this->user_model->delete_group();
					sem( $this->data['error'] );
				}
				else {
					$_GET['action'] = 'delete_group';
					$_GET['id'] = $this->input->post('id');
				}
			}
			elseif( $_POST['form_name']=="group" && $_POST['form_type']=="update" )
			{
				$this->form_validation->set_rules('name', 'Group Name', 'required');
				$this->form_validation->set_rules('id', 'Group ID', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$this->data['error'] = $this->user_model->update_group();
					sem( $this->data['error'] );
				}
				else {
					$_GET['action'] = 'edit_group';
					$_GET['id'] = $this->input->post('id');
				}
			}
			
			elseif( $_POST['form_name']=="sms" && $_POST['form_type']=="send" )
			{
				
				$this->form_validation->set_rules('phone', 'Phone Number', 'required');
				$this->form_validation->set_rules('message', 'Message', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$phone = $this->input->post('phone');
					$msg = $this->input->post('message');
					$this->load->helper('mms');
					sem( send_sms( $phone, $msg ) );
				}
			}
				
			elseif( $_POST['form_name']=="user" && $_POST['form_type']=="insert" )
			{
				$this->form_validation->set_rules('name', 'User Name', 'required');
				$this->form_validation->set_rules('surname', 'Surname', 'required');
				$this->form_validation->set_rules('email', 'Email', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('group', 'Group', 'required');
				if ($this->form_validation->run() === TRUE)
				{
					$this->data['error'] = $this->user_model->add_user();
					sem( $this->data['error'] );
					$email = $this->input->post('email');
					$name = $this->input->post('name');
					$surname = $this->input->post('surname');
					$admin = $this->data['fname'].' '.$this->data['surname'];
					$site = SITE_NAME;
					$pass=$this->input->post('password');
					$url=base_url().'profile';
					$change=base_url().'profile/change_pass';
					$this->load->library('email');
					$this->email->from( $this->data['email'], $admin );
					$this->email->to( $email );
					$this->email->bcc( ADMIN_EMAIL );
					$this->email->subject(' New account created for '.$name.' '.$surname.' on '.SITE_NAME );
					$this->email->message("
					You are receiving this email because an account was created for you by $admin on $site.<br><br>
					<strong>Username </strong>$email<br>
					<strong>Password </strong>$pass.<br><hr>
					We strongly recommend you <a href='$change'>change this password</a> right away.<br><hr>
					You can check out your account <a href='$url'>here</a><br>
					");
					$this->email->send();
				}
				else $_GET['action'] = 'add_user';
			}
			
		}
		return 0;
	}
	
	
	private function _process_get()
	{
		$continue = TRUE;
		$action = $this->input->get('action');
		if( $action )
		{
			if( $action=='add_user' )
			{
				$this->data['add_user'] = TRUE;
				$this->data['innertitle'] = 'Add User';
				$this->data['groups'] = $this->user_model->get_groups();
				$this->data['section'] = 'new_account';
				$continue = FALSE;
			}
			elseif( $action=='update_account' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['account'] = $this->user_model->get_users( array('id'=>$id, 'guarantor'=>1, 'bene'=>1 ) );
					$this->data['groups'] = $this->user_model->get_groups( array() );
					$this->data['section'] = 'update_account';
					$this->data['innertitle'] = 'Edit User';
					$continue = FALSE;
				}
			}
			elseif( $action=='remove_account' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['account'] = $this->user_model->get_users( array('id'=>$id) );
					$this->data['section'] = 'remove_account';
					$continue = FALSE;
				}
			}
			elseif( $action=='toggle_user_state' )
			{
				$id = $this->input->get('id');
				$state = $this->input->get('state');
				$data = array('id'=>$id, 'table'=>'user_accounts', 'state'=>$state);
				$e = $this->user_model->toggle_user_state( $data );
				sem( $e );
				redirect( current_url() );
				$continue = TRUE;
			}
			elseif( $action=='edit_group' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['group'] = $this->user_model->get_groups( array('id'=>$id) );
					$this->data['groups'] = $this->user_model->get_groups( array() );
					$this->data['section'] = 'edit_group';
					$this->data['innertitle'] = 'Edit User Group';
					$continue = FALSE;
				}
			}
			elseif( $action=='edit_privilege' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['privilege'] = $this->user_model->get_privileges( array('id'=>$id) );
					$this->data['section'] = 'edit_privilege';
					$this->data['innertitle'] = 'Edit User Privilege';
					$continue = FALSE;
				}
			}
			elseif( $action=='delete_privilege' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['privilege'] = $this->user_model->get_privileges( array('id'=>$id) );
					$this->data['section'] = 'delete_privilege';
					$this->data['innertitle'] = 'Delete User Privilege';
					$continue = FALSE;
				}
			}
			elseif( $action=='manage_group_privileges' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['group'] = $this->user_model->get_groups( array('id'=>$id) );
					$this->data['section'] = 'group_privileges';
					$this->data['innertitle'] = 'Manage Group Privileges';
					$this->data['scripts'][] = 'permissions';
					
					$gp = $this->user_model->get_group_permissions( array('id'=>$id) );
					$this->data['av_permissions'] = $this->user_model->get_available_permissions($gp);
					$this->data['permissions'] = $gp;
					
					$continue = FALSE;
				}
			}
			elseif( $this->input->get('action') == 'reset_password' )
			{
				if ($this->input->get('email'))
				{
					$response = $this->flexi_auth->forgotten_password($this->input->get('email'));
					//sem( $this->flexi_auth->get_messages(), !$response );
					$continue = TRUE;
				}
				else sem( 'User Email is required to reset password');
				redirect(current_url());
			}
			elseif( $this->input->get('action') == 'activate_account' )
			{
				if ($this->input->get('email'))
				{
					$response = $this->flexi_auth->resend_activation_token($this->input->get('email'));
					//sem( $this->flexi_auth->get_messages(), !$response );
					$continue = TRUE;
				}
				else sem( 'User Email is required to reset password');
				redirect(current_url());
			}
		}
		return $continue;
	}
}
