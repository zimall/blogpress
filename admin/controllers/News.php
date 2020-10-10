<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller
{

	function __construct() 
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('user_model');
		$this->load->model('article_model');
		$this->data['nav_element'] = 'articles';
		$this->data['section'] = 'index';
		$this->load->library('al');
	}

	public function index()
	{
		$this->al->news();
	}

	public function new_article()
	{
		$this->_process_form();
		
		$this->data['ckeditor'] = TRUE;
		$this->data['scripts'][] = 'ajaxupload';
		$this->data['scripts'][] = 'images';
		
		$this->data['sections'] = $this->article_model->get_sections();
		
		$this->data['section'] = 'new_article';
		$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
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
			elseif( $name == 'article' && $action == 'insert' )
			{
				$this->form_validation->set_rules( 'text', 'Article Body', 'required' );
				$this->form_validation->set_rules( 'title', 'Title', 'required' );
				if( $this->form_validation->run() )
				{
					$error = $this->article_model->post_article();
					sem($error);
					if( !$error['error'] );
						redirect( 'articles' );
				}
			}
			elseif( $name == 'article' && $action == 'update' )
			{
				$this->form_validation->set_rules( 'text', 'Article Body', 'required' );
				$this->form_validation->set_rules( 'title', 'Title', 'required' );
				$this->form_validation->set_rules( 'id', 'Article ID', 'required|numeric' );
				if( $this->form_validation->run() )
				{
					$error = $this->article_model->update_article();
					sem($error);
					if( !$error['error'] );
						redirect( current_url() );
				}
			}
			elseif( $name == 'article' && $action == 'delete' )
			{
				$this->form_validation->set_rules( 'confirm_delete', 'Yes I\'m sure', 'required' );
				$this->form_validation->set_rules( 'id', 'Article ID', 'required|numeric' );
				if( $this->form_validation->run() )
				{
					$error = $this->article_model->delete_article();
					sem($error);
					if( !$error['error'] );
						redirect( current_url() );
				}
			}
		}
		//else sem('no form data found');
		return 0;
	}
	
	
	private function _process_get()
	{
		$continue = TRUE;
		$action = $this->input->get('action');
		if($p = $this->input->get('p'));else $p = FALSE;		// p = property to toggle
		if($v = $this->input->get('v'));else $v=FALSE;		// v = value used for toggle operations
		if($id = $this->input->get('id'));else $id=FALSE;		// id of item to change
		if( $action )
		{
			if( $action=='add_user' )
			{
				$this->data['add_user'] = TRUE;
				$this->data['innertitle'] = 'Add User';
				$this->data['groups'] = $this->user_model->get_groups();
				$continue = FALSE;
			}
			elseif( $action=='edit_article' )
			{
				if( $id = $this->input->get('id') )
				{
					$this->data['article'] = $this->article_model->get_articles( array('id'=>$id) );
					$this->data['sections'] = $this->article_model->get_sections();
					$this->data['section'] = 'edit_article';
					$this->data['innertitle'] = 'Edit Article';
					$this->data['ckeditor'] = TRUE;
					$this->data['scripts'][] = 'ajaxupload';
					$this->data['scripts'][] = 'images';
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
			elseif( $action=='toggle_state' && is_numeric($id) && $p )
			{
				$id = $this->input->get('id');
				$data = array('id'=>$id, 'state'=>$v, 'property'=>$p);
				$e = $this->article_model->toggle_state( $data );
				sem($e);
				redirect( current_url() );
				$continue = TRUE;
			}
			elseif( $action=='delete_article' && is_numeric($id) )
			{
				$this->data['article'] = $this->article_model->get_articles( array('id'=>$id) );
				$this->data['section'] = 'delete_article';
				$continue = FALSE;
			}
		}
		return $continue;
	}
}
