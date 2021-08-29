<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller
{

	function __construct() 
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('user_model');
		$this->load->model('article_model');
		$this->load->library('al');
		$this->data['nav_element'] = 'about';
		$this->data['section'] = 'index';
		$this->data['innertitle'] = 'About';
	}


	public function index($option='')
	{
		$this->al->_process_form('about/index');
		$continue = $this->al->_process_get();
		
		$this->data['title'] = $this->data['innertitle'] = 'About Us';
		$this->data['segment'] = 'index';
		
		if( $option == 'new' )
		{
			$this->data['section'] = 'add_about';
			$this->data['ckeditor'] = TRUE;
			$this->data['scripts'][] = 'ajaxupload';
			$this->data['scripts'][] = 'images';
			$this->data['sections'] = $this->article_model->get_sections();
			$this->load->model('settings_model');
			$theme_name = $this->config->item('site_theme');
			$theme = $this->settings_model->load_theme( $theme_name, 'site' );
			if(isset($theme['image_sizes'])) $this->data['image_sizes'] = $theme['image_sizes'];
			else $data['image_sizes']['xl'] = 'max: 2000px / 2MB';
			$continue = FALSE;
		}
		
		if($continue)
		{
			$where = array( 'at_permalink'=>1, 'at_segment'=>'about' );
			$args = array( 'where'=>$where, 'one'=>1 );
			
			$this->data['about'] = $this->data['article'] = $this->article_model->get_articles( $args );
			if( $option == 'edit' )
			{
				$this->data['ckeditor'] = TRUE;
				$this->data['scripts'][] = 'ajaxupload';
				$this->data['scripts'][] = 'images';
				$this->data['sections'] = $this->article_model->get_sections();
				$this->data['section'] = 'edit_about';
				$this->load->model('settings_model');
				$theme_name = $this->config->item('site_theme');
				$theme = $this->settings_model->load_theme( $theme_name, 'site' );
				if(isset($theme['image_sizes'])) $this->data['image_sizes'] = $theme['image_sizes'];
				else $data['image_sizes']['xl'] = 'max: 2000px / 2MB';
			}
			else $this->data['section'] = 'index';
		}
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}

	public function leadership($option='')
	{
		$this->data['section'] = 'index';
		$this->data['title'] = 'About Us - Leadership';
		$this->data['segment'] = 'about';
		$this->data['innertitle'] = 'Our Team';
		
		$this->al->_process_form('about/leadership');
		$this->pc->page_control('leadership_list', 10 );
		$continue = $this->al->_process_get();
		
		if($continue)
		{
			$where = array( 'at_section'=>$this->config->item('section_team') );
			$count = array( 'where'=>$where, 'count'=>1 );
			
			$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
			$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id asc' ) );
			$this->data['articles'] = $this->article_model->get_articles( $args );
			
			if( $option == 'edit' )
			{
				$this->data['ckeditor'] = TRUE;
				$this->data['scripts'][] = 'ajaxupload';
				$this->data['scripts'][] = 'images';
				$this->data['sections'] = $this->article_model->get_sections();
				$this->data['innertitle'] = 'Edit Leadership';
			}
			//else $this->data['section'] = 'leadership';
		}
		$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
	}

	public function other($option='')
	{
		$this->data['section'] = 'index';
		$this->data['title'] = 'About Us - Vision, Mission...';
		$this->data['segment'] = 'about';
		$this->data['innertitle'] = 'Our Vision, Mission...';
		
		$this->al->_process_form('about/other');
		$this->pc->page_control('other_list', 10 );
		$continue = $this->al->_process_get();
		
		if($continue)
		{
			$where = [ 'at_section'=>$this->config->item('section_about_other') ];
			$count = [ 'where'=>$where, 'count'=>1 ];
			
			$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
			$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id asc' ) );
			$this->data['articles'] = $this->article_model->get_articles( $args );
			
			if( $option == 'edit' )
			{
				$this->data['ckeditor'] = TRUE;
				$this->data['scripts'][] = 'ajaxupload';
				$this->data['scripts'][] = 'images';
				$this->data['sections'] = $this->article_model->get_sections();
				$this->data['innertitle'] = 'Edit About Us - Other';
			}
			//else $this->data['section'] = 'leadership';
		}
		$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
	}

	public function philosophy($option='')
	{
		$this->al->_process_form('about/philosophy');
		$continue = $this->al->_process_get();
		
		$this->data['title'] = $this->data['innertitle'] = 'Our Philosophy';
		$this->data['segment'] = 'philosophy';
		
		if( $option == 'new' )
		{
			$this->data['section'] = 'add_about';
			$this->data['ckeditor'] = TRUE;
			$this->data['scripts'][] = 'ajaxupload';
			$this->data['scripts'][] = 'images';
			$this->data['sections'] = $this->article_model->get_sections();
			$continue = FALSE;
		}
		
		if($continue)
		{
			$where = array( 'at_permalink'=>1, 'at_segment'=>'about/philosophy' );
			$args = array( 'where'=>$where, 'one'=>1 );
			
			$this->data['about'] = $this->article_model->get_articles( $args );
			if( $option == 'edit' )
			{
				$this->data['ckeditor'] = TRUE;
				$this->data['scripts'][] = 'ajaxupload';
				$this->data['scripts'][] = 'images';
				$this->data['sections'] = $this->article_model->get_sections();
				$this->data['section'] = 'edit_about';
			}
			else $this->data['section'] = 'club';
		}
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}

	public function approach($option='')
	{
		$this->al->_process_form('about/approach');
		$continue = $this->al->_process_get();
		
		$this->data['title'] = $this->data['innertitle'] = 'Our Approach';
		$this->data['segment'] = 'approach';
		
		if( $option == 'new' )
		{
			$this->data['section'] = 'add_about';
			$this->data['ckeditor'] = TRUE;
			$this->data['scripts'][] = 'ajaxupload';
			$this->data['scripts'][] = 'images';
			$this->data['sections'] = $this->article_model->get_sections();
			$continue = FALSE;
		}
		
		if($continue)
		{
			$where = array( 'at_permalink'=>1, 'at_segment'=>'about/approach' );
			$args = array( 'where'=>$where, 'one'=>1 );
			
			$this->data['about'] = $this->article_model->get_articles( $args );
			if( $option == 'edit' )
			{
				$this->data['ckeditor'] = TRUE;
				$this->data['scripts'][] = 'ajaxupload';
				$this->data['scripts'][] = 'images';
				$this->data['sections'] = $this->article_model->get_sections();
				$this->data['section'] = 'edit_about';
			}
			else $this->data['section'] = 'club';
		}
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}
	/****** PROCESS FORM DATA ************/
	private function _process_form($r=FALSE)
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
			elseif( $name == 'article' && $action == 'insert' )
			{
				$this->form_validation->set_rules( 'text', 'Article Body', 'required' );
				$this->form_validation->set_rules( 'title', 'Title', 'required' );
				if( $this->form_validation->run() )
				{
					$error = $this->article_model->post_article();
					sem($error);
					if( !$error['error'] )
					{
						if($r) redirect($r);
						else redirect( 'articles' );
					}
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
				else
				{
					$_GET['action'] = 'remove_account';
					$_GET['id'] = $this->input->post('id');
				}
			}
			elseif( $_POST['form_name']=="permission" && $_POST['form_type']=="insert" ){
				
				$this->form_validation->set_rules('name', 'Permission Name', 'required');
				if ($this->form_validation->run() === TRUE){
					$this->data['error'] = $this->user_model->add_permission();
					sem( $this->data['error'] );
					if($this->data['error']=='fail') $_GET['action'] = 'add_permission';
				}
				else $_GET['action'] = 'add_permission';
				
				
			}
			elseif( $_POST['form_name']=="permission" && $_POST['form_type']=="delete" ){
				
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
			elseif( $_POST['form_name']=="permission" && $_POST['form_type']=="update" ){
				
				$this->form_validation->set_rules('name', 'Permission Name', 'required');
				$this->form_validation->set_rules('id', 'Permission ID', 'required');
				if ($this->form_validation->run() === TRUE){
					$this->data['error'] = $this->user_model->update_permission();
					sem( $this->data['error'] );
				}
				else {
					$_GET['action'] = 'edit_permission';
					$_GET['id'] = $this->input->post('id');
				}
			}
			
			elseif( $_POST['form_name']=="group" && $_POST['form_type']=="delete" ){
				
				$this->form_validation->set_rules('id', 'Item ID', 'required');
				if ($this->form_validation->run() === TRUE){
					$this->data['error'] = $this->user_model->delete_group();
					sem( $this->data['error'] );
				}
				else {
					$_GET['action'] = 'delete_group';
					$_GET['id'] = $this->input->post('id');
				}
			}
			elseif( $_POST['form_name']=="group" && $_POST['form_type']=="update" ){
				
				$this->form_validation->set_rules('name', 'Group Name', 'required');
				$this->form_validation->set_rules('id', 'Group ID', 'required');
				if ($this->form_validation->run() === TRUE){
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
				
			elseif( $_POST['form_name']=="user" && $_POST['form_type']=="insert" ){
				
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
				$continue = FALSE;
			}
			elseif( $action=='update_account' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['account'] = $this->user_model->get_users( array('id'=>$id) );
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
			
		}
		return $continue;
	}
}
