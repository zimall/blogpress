<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller
{

	function __construct() 
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('user_model');
		$this->load->model('article_model');
		$this->load->model('settings_model');
		$this->data['section'] = 'index';
		$this->load->library('al');
		$this->data['nav_element'] = 'settings';
	}

	public function index()
	{
		$this->_process_post();
		$this->_process_get();
		$this->data['site_theme'] = $this->config->item('site_theme');
		$this->data['site_themes'] = $this->settings_model->load_themes('site');
		$this->data['admin_theme'] = $this->config->item('admin_theme');
		$this->data['admin_themes'] = $this->settings_model->load_themes('admin');
		//sem( 'Theme: '.print_r($this->data['admin_themes'],TRUE) );
		
		$this->load->view( "{$this->data['theme']}/settings.tpl", $this->data );
	}

	public function theme($name=FALSE, $source='site')
	{
		$this->_process_post();
		$this->_process_get();
		$this->data['site_theme'] = $this->settings_model->load_theme( $name, $source);
		//sem( 'Theme: '.print_r($this->data['admin_themes'],TRUE) );
		$this->data['section'] = 'theme';
		$this->data['scripts'][] = 'ajaxupload';
		$this->data['scripts'][] = 'images';
		$this->load->view( "{$this->data['theme']}/settings.tpl", $this->data );
	}

	public function slider()
	{
		$this->_process_post();
		$this->data['section'] = 'sliders';
		$this->data['innertitle'] = 'Banner Slides';
		$this->pc->page_control('sliders_list');
		if( $this->_process_get() )
		{
			$count = array( 'table'=>'banners', 'count'=>TRUE );
			$paginate = $this->pc->paginate( $count, 'get_banners', 'settings_model' );
			$this->data['sliders'] = $this->settings_model->get_banners( $paginate );
		}
		
		$this->load->view("{$this->data['theme']}/settings.tpl", $this->data);
	}
	
	public function register()
	{
		$this->data['section'] = 'register';
		$this->load->view("{$this->data['theme']}/register.tpl", $this->data);
	}

	private function _process_post()
	{
		$name = $this->input->post('form_name');
		$action = $this->input->post('form_type');
		if( !is_null($name) && !is_null($action) )
		{
			$this->load->library('form_validation');
			
			if( $name == 'general' && $action == 'update' )
			{
				$this->form_validation->set_rules( 'no-reply', 'System Email', 'required|valid_email' );
				if( $this->form_validation->run() )
				{
					$error = $this->settings_model->general_settings();
					sem($error);
					if( !$error['error'] )
						redirect( 'settings' );
				}
			}
			elseif( $name == 'site' && $action == 'update_theme' )
			{
				$this->form_validation->set_rules( 'site_theme', 'Theme Name', 'required' );
				if( $this->form_validation->run() )
				{
					$error = $this->settings_model->theme_settings();
					sem($error);
					if( !$error['error'] )
						redirect( 'settings' );
				}
			}
			elseif( $name == 'slider' && $action == 'insert' )
			{
				$theme_name = $this->input->post('site_slider');
				if($theme_name){
					$theme = $this->settings_model->load_theme( $theme_name, 'site' );
				}

				if( isset($theme['slider']) ){
					$settings = $theme['slider'];
					if( isset($settings['has_subtitle']) && $settings['has_subtitle'] )
						$this->form_validation->set_rules( 'subtitle', 'Subtitle Text', 'required' );
				}

				$this->form_validation->set_rules( 'title', 'Title Text', 'required' );
				$this->form_validation->set_rules( 'image', 'Slider Image', 'required' );
				$this->form_validation->set_rules( 'site_slider', 'Slider Theme', 'required' );
				if( $this->form_validation->run() )
				{
					$error = $this->settings_model->new_banner();
					sem($error);
					if( !$error['error'] ) redirect( current_url() );
					else{
						$_GET['action'] = 'new_slide';
					}
				}
				else{
					$_GET['action'] = 'new_slide';
				}
			}
			elseif( $name == 'slider' && $action == 'update' )
			{
				$theme_name = $this->input->post('site_slider');
				if($theme_name){
					$theme = $this->settings_model->load_theme( $theme_name, 'site' );
				}

				if( isset($theme['slider']) ){
					$settings = $theme['slider'];
					if( isset($settings['has_subtitle']) && $settings['has_subtitle'] )
						$this->form_validation->set_rules( 'subtitle', 'Subtitle Text', 'required' );
				}

				$this->form_validation->set_rules( 'id', 'Banner ID', 'required' );
				$this->form_validation->set_rules( 'title', 'Title Text', 'required' );
				$this->form_validation->set_rules( 'image', 'Slider Image', 'required' );
				$this->form_validation->set_rules( 'site_slider', 'Slider Theme', 'required' );
				if( $this->form_validation->run() )
				{
					$error = $this->settings_model->update_banner();
					sem($error);
					if( !$error['error'] )
						redirect( current_url() );
				}
				$_GET['action'] = 'edit_slide';
				$_GET['id'] = $this->input->post('id');
			}
		}
		//else sem('no form data found');
		return 0;
	}

	private function _process_get()
	{
		$continue = TRUE;
		$action = $this->input->get('action');
		
		if($action)
		{
			if( $action=='new_slide' )
			{
				$this->data['section'] = 'new_slide';
				$this->data['site_theme'] = $theme = $this->settings_model->load_theme( $this->config->item('site_theme'), 'site' );
				if( isset($theme['slider']) ) $this->data['slider_settings'] = $theme['slider'];
				else $this->data['slider_settings'] = array();
				$continue = FALSE;
				$this->data['scripts'][] = 'ajaxupload';
				$this->data['scripts'][] = 'images';
			}
			elseif( $action=='edit_slide' )
			{
				$id = $this->input->get('id');
				$this->data['slider'] = $slider = $this->settings_model->get_banners( array( 'id'=>$id ) );
				if( isset($slider['bn_theme']) )
				{
					$t = $slider['bn_theme'];
					$this->data['section'] = 'edit_slide';
					$this->data['site_theme'] = $theme = $this->settings_model->load_theme( $t, 'site' );
					if( isset($theme['slider']) ) $this->data['slider_settings'] = $theme['slider'];
					else $this->data['slider_settings'] = array();
					$continue = FALSE;
					$this->data['scripts'][] = 'ajaxupload';
					$this->data['scripts'][] = 'images';
				}
				else
				{
					sem("Slider not found.");
				}
				
			}
			elseif( $action == 'delete_group' && $id = $this->input->get('id') )
			{
				$this->data['group'] = $this->user_model->get_groups( array('id'=>$id) );
				$this->data['section'] = 'delete_group';
				$continue = FALSE;
			} 
		}
		return $continue;
	}

}
