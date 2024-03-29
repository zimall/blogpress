<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller
{

	function __construct() 
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('user_model');
		$this->load->model('article_model');
		$this->load->model('pages_model');
		$this->load->helper('num_string');
		$this->load->library('al');
		$this->data['nav_element'] = 'articles';
		$this->data['section'] = 'index';
		$this->data['innertitle'] = 'Site Pages';
	}

	public function index2( $page_id )
	{
		$this->data['page'] = $page = $this->article_model->get_section($page_id);
		if(!empty($page)) {
			if($continue) {
				$this->_process_form($page['sc_value']);
				$this->pc->page_control($page['sc_value'].'_list' );
				$continue = $this->_process_get();

				$this->data['nav_element'] = 'articles';
				$this->data['section'] = 'index';
				$this->pc->page_control('pages', 10);
				$where = array('at_section' => $page['sc_id']);
				$count = array('where' => $where, 'count' => 1);
				$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
				$args = array_merge($paginate, array('where' => $where, 'sort' => 'at_id desc'));
				$this->data['articles'] = $this->article_model->get_articles($args);
				$this->data['title'] = $page['sc_name'];
				$this->data['innertitle'] = $page['sc_name'];
				$this->data['menu'] = $page['sc_value'];
				//$this->load->view("{$this->data['theme']}/articles.tpl", $this->data);
			}
			$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
		}
		else redirect(site_url("articles/search") . "?q={$page_id}");
	}

	public function index($page_id, $option='')
	{
		$this->data['page'] = $page = $this->article_model->get_section($page_id);
		if(empty($page)) redirect(site_url("articles/search") . "?q={$page_id}");

		$this->data['section'] = 'index';
		$this->data['title'] = $page['sc_name'];
		$this->data['segment'] = $page['sc_value'];
		$this->data['innertitle'] = $page['sc_name'];
		if( preg_match('/about\/?.*/', $page['sc_value']) ) $this->data['nav_element'] = 'about';

		$this->al->_process_form($page['sc_value']);
		$this->pc->page_control($page['sc_value'].'_list', $page['sc_items'], $page['sc_order'] );
		$continue = $this->al->_process_get();

		if( $option == 'new' )
		{

			$this->load->model('settings_model');
			$theme_name = $this->config->item('site_theme');
			$theme = $this->settings_model->load_theme( $theme_name, 'site' );
			if(isset($theme['image_sizes'])) $this->data['image_sizes'] = $theme['image_sizes'];
			else $this->data['image_sizes']['xl'] = 'max: 2000px / 2MB';

			$this->data['section'] = 'new_article';
			$this->data['ckeditor'] = TRUE;
			$this->data['scripts'][] = 'ajaxupload';
			$this->data['scripts'][] = 'images';
			$this->data['sections'] = $this->article_model->get_sections();
			$_POST['section'] = $page_id;
			$continue = FALSE;
			$this->data['innertitle'] = 'New Article';
		}

		if($continue)
		{
			$where = "( `at_section` = {$page['sc_id']} OR `sc_parent`= {$page['sc_id']} )";
			$count = array( 'where'=>$where, 'count'=>1 );
			$sort = $this->data['sort'] ?? get_sort($page['sc_order']);
			$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
			$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>$sort ) );
			$this->data['articles'] = $this->article_model->get_articles( $args );

			if( $option == 'edit' )
			{
				$this->data['ckeditor'] = TRUE;
				$this->data['scripts'][] = 'ajaxupload';
				$this->data['scripts'][] = 'images';
				$this->data['sections'] = $this->article_model->get_sections();
				$this->data['innertitle'] = 'Edit Article';
			}
			//else $this->data['section'] = 'leadership';
		}
		$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
	}

	public function categories()
	{
		$this->data['innertitle'] = "Article Categories";
		$this->_process_form('pages/categories');
		$this->pc->page_control('pages_list', 20 );
		$continue = $this->_process_get();
		
		if($continue)
		{
			$count = array( 'count'=>TRUE);
			$function = 'get_pages';
			$paginate = $this->pc->paginate($count, $function, 'pages_model');
			
			$args = [ 'start'=>$paginate['start'], 'limit'=>$paginate['limit'], 'article_count'=>true ];
			$count['count'] = NULL;
			$args = array_merge($args, $count);
			$this->data['nav_element'] = 'articles';
			$this->data['pages'] = $this->pages_model->get_pages( $args );
			$this->data['section'] = 'categories';
		}
		
		$this->load->view( "{$this->data['theme']}/pages.tpl", $this->data );
	}

	public function rules($page_id){
		if( !$page_id || !is_numeric($page_id) ) return bad_request();

		$this->data['page'] = $this->pages_model->get_pages(['id'=>$page_id]);

		$this->data['section'] = 'rules';
		$this->data['nav_element'] = 'articles';
		$this->load->view( "{$this->data['theme']}/pages.tpl", $this->data );
	}

	public function leadership($option='')
	{
		$this->data['section'] = 'index';
		$this->data['title'] = 'About Us';
		$this->data['segment'] = 'about';
		$this->data['innertitle'] = 'Club Leadership';
		
		$this->al->_process_form('about/leadership');
		$this->pc->page_control('leadership_list', 10 );
		$continue = $this->al->_process_get();
		
		
		
		if( $option == 'new' )
		{
			$this->data['section'] = 'add_article';
			$this->data['ckeditor'] = TRUE;
			$this->data['scripts'][] = 'ajaxupload';
			$this->data['scripts'][] = 'images';
			$this->data['sections'] = $this->article_model->get_sections();
			$continue = FALSE;
			$this->data['innertitle'] = 'New Leadership';
		}
		
		if($continue)
		{
			$where = array( 'at_section'=>2 );
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

	public function pp($option='')
	{
		$this->data['section'] = 'index';
		$this->data['title'] = 'About Us';
		$this->data['segment'] = 'about';
		$this->data['innertitle'] = 'Past Presidents';
		
		$this->al->_process_form('about/pp');
		$this->pc->page_control('pp_list', 10 );
		$continue = $this->al->_process_get();
		
		
		
		if( $option == 'new' )
		{
			$this->data['section'] = 'add_article';
			$this->data['ckeditor'] = TRUE;
			$this->data['scripts'][] = 'ajaxupload';
			$this->data['scripts'][] = 'images';
			$this->data['sections'] = $this->article_model->get_sections();
			$continue = FALSE;
			$this->data['innertitle'] = 'Add Past President';
		}
		
		if($continue)
		{
			$where = array( 'at_section'=>3 );
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
				$this->data['innertitle'] = 'Edit Past President';
			}
			//else $this->data['section'] = 'leadership';
		}
		$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
	}

	public function rotary($option='')
	{
		$this->data['section'] = 'index';
		$this->data['innertitle'] = 'Rotary International';
		$this->data['segment'] = 'about/rotary';
		
		$this->al->_process_form('about/rotary');
		$this->pc->page_control('rotary_list', 10 );
		$continue = $this->al->_process_get();
		
		
		
		if( $option == 'new' )
		{
			$this->data['section'] = 'add_article';
			$this->data['ckeditor'] = TRUE;
			$this->data['scripts'][] = 'ajaxupload';
			$this->data['scripts'][] = 'images';
			$this->data['sections'] = $this->article_model->get_sections();
			$continue = FALSE;
			$this->data['innertitle'] = 'New Article (Rotary)';
		}
		
		if($continue)
		{
			$where = array( 'at_section'=>4 );
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
				$this->data['innertitle'] = 'Edit Article (Rotary)';
			}
			//else $this->data['section'] = 'leadership';
		}
		$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
	}

	public function club($option='')
	{
		$this->al->_process_form('about/club');
		$continue = $this->al->_process_get();
		
		$this->data['title'] = 'About Us';
		$this->data['segment'] = 'about';
		
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
			$where = array( 'at_permalink'=>1, 'at_segment'=>'about' );
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
			
			if( $name=="page" && $action=='update' )
			{
				$this->form_validation->set_rules( 'title', 'Page Name', 'required' );
				$this->form_validation->set_rules( 'id', 'Page ID', 'required|numeric' );
				if( $this->form_validation->run() )
				{
					$error = $this->pages_model->update_page();
					sem($error);
					if(!$r) $r = current_url();
					if( !$error['error'] )
						redirect( $r );
				}
			}
			elseif( $name=="page" && $action=='insert' )
			{
				$this->form_validation->set_rules( 'title', 'Category Name', 'required' );
				if( $this->form_validation->run() )
				{
					$error = $this->pages_model->update_page();
					sem($error);
					if(!$r) $r = current_url();
					if( !$error['error'] )
						redirect( $r );
					else{
						$_GET['action'] = 'add_category';
					}
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
			elseif( $name == 'category' && $action == 'delete' )
			{
				$this->form_validation->set_rules( 'confirm_delete', 'Yes I\'m sure', 'required' );
				$this->form_validation->set_rules( 'id', 'Category ID', 'required|numeric' );
				if( $this->form_validation->run() )
				{
					$id = $this->input->post('id');
					$error = $this->pages_model->delete_category($id);
					sem($error);
					if( !$error['error'] );
					redirect( current_url() );
				}
			}
		}
		return 0;
	}

	private function _process_get()
	{
		$continue = TRUE;
		$action = $this->input->get('action');
		$id = $this->input->get('id');
		if( $action )
		{
			if( $action=='add_category' )
			{
				$this->data['sections'] = $this->pages_model->get_pages( ['where'=>[ 'sc_enabled'=>1 ] ] );
				$this->data['section'] = 'add_category';
				$this->data['innertitle'] = 'Add new category';
				$continue = FALSE;
			}
			elseif( $action=='edit_page' && $id )
			{
				$this->data['page'] = $this->pages_model->get_pages( ['id'=>$id] );
				$this->data['sections'] = $this->pages_model->get_pages( ['where'=>[ "sc_id !="=>$id, 'sc_parent'=>0 ] ] );
				$this->data['section'] = 'edit_page';
				$this->data['innertitle'] = 'Edit Page';
				$continue = FALSE;

			}
			elseif( $action=='delete_category' && $id && is_numeric($id) )
			{
				$this->data['page'] = $this->pages_model->get_pages($id);
				$this->data['section'] = 'delete_category';
				$continue = FALSE;
			}
			
		}
		return $continue;
	}
}
