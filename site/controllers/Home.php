<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('article_model');
		$this->load->library('al');
		$this->load->helper('num_string');
		$this->data['menu'] = 'home';
		$this->data['rotary_theme'] = $this->article_model->get_month_theme();
		$this->al->sidebar();
		$this->time = time();
	}

	public function index()
	{
		$where = array( 'at_enabled'=>1, 'at_featured'=>1, 'at_section'=>1, 'at_date_posted <'=>$this->time );
		
		$this->data['about'] = $this->article_model->get_articles( array( 'where'=>$where ) );

		$this->pc->page_control( 'recent_articles', 10 );
		$select = 'at_id';
		$where = array(  'at_enabled'=>1, 'at_date_posted <'=>$this->time );

		if( in_array( $this->config->item('club-type'), ['Rotary', 'Rotaract'] ) ){
			$where['sc_value'] = 'news';
		}

		$count = array( 'where'=>$where, 'count'=>1 );
		$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
		
		$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image,sc_name,sc_value';
		$args = array_merge( $paginate, [ 'where'=>$where, 'sort'=>'at_date_posted desc', 'select'=>$select ] );

		$this->data['recent_articles'] = $this->article_model->get_articles($args);
		
		/*
		$where = array( 'bn_enabled'=>1, 'bn_theme'=>$this->data['theme'] );
		$args = array( 'where'=>$where, 'limit'=>4 );
		$this->data['slides'] = $this->article_model->get_banners($args);
		*/
		
		$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image,sc_value,sc_name,sc_id';
		$where = array( 'at_featured'=>1, 'at_enabled'=>1, 'at_date_posted <'=>$this->time );
		$args = array( 'where'=>$where, 'sort'=>'at_date_posted desc', 'limit'=>5, 'select'=>$select );
		$this->data['featured'] = $this->article_model->get_articles($args);
		
		$this->data['innertitle'] = 'Latest Posts';
		$this->data['title'] = 'Home';

		$this->load->view( "{$this->data['theme']}/home.tpl", $this->data );
	}

	public function recent()
	{

		$this->pc->page_control( 'recent_articles', 10 );
		$select = 'at_id';
		$where = array(  'at_enabled'=>1, 'at_date_posted <'=>$this->time );
		$count = array( 'where'=>$where, 'count'=>1 );
		$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
		
		$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image,sc_name,sc_value';
		$args = array_merge( $paginate, [ 'where'=>$where, 'sort'=>'at_date_posted desc', 'select'=>$select ] );

		$this->data['articles'] = $this->article_model->get_articles($args);
		
		$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image,sc_value,sc_name,sc_id';
		$where = array( 'at_featured'=>1, 'at_enabled'=>1, 'at_date_posted <'=>$this->time );
		$args = array( 'where'=>$where, 'sort'=>'at_date_posted desc', 'limit'=>5, 'select'=>$select );
		$this->data['featured'] = $this->article_model->get_articles($args);
		
		$this->data['section'] = 'recent';
		$this->data['innertitle'] = $this->data['title'] ='Latest Posts';

		$this->load->view( "{$this->data['theme']}/home.tpl", $this->data );
	}

	public function post_installation_cleanup()
	{
		copy( 'install/setup/Account.php', 'site/controllers/Account.php' );
		sem('Installation completed successfully');
		redirect(base_url());
	}

}
