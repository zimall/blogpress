<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('article_model');
		$this->load->library('al');
		$this->data['section'] = 'about';
		$this->data['menu'] = 'about';
		$this->al->sidebar();
	}

	public function index()
	{
		$where = array( 'at_permalink'=>1, 'at_segment'=>'about' );
		$args = array( 'where'=>$where, 'one'=>1, 'enabled'=>1 );
		$this->data['aboutus'] = $this->data['article'] = $this->article_model->get_articles( $args );
		if(isset($this->data['aboutus']['at_id']))
		{
			$this->data['title'] = 'About Us';
			$this->data['description'] = $this->data['aboutus']['at_summary'];
			$this->data['keywords'] = $this->data['aboutus']['at_keywords'];
		}
		
		$where = array( 'at_enabled'=>1, 'at_featured'=>1, 'at_section'=>1 );
		$this->data['about'] = $this->article_model->get_articles( array( 'where'=>$where ) );
		
		$where = array( 'sc_value'=>'about/team' );
		$args = array( 'where'=>$where, 'enabled'=>1, 'sort'=>'at_date_posted asc' );
		$this->data['team'] = $this->article_model->get_articles( $args );

		$this->data['rotary'] = $this->section_items('about/rotary');
		
		$where = array( 'sc_value'=>'about/pp' );
		$args = array( 'where'=>$where, 'enabled'=>1, 'sort'=>'at_title desc' );
		$this->data['pp'] = $this->article_model->get_articles( $args );

		$this->pc->get_route_content('About');
		
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}

	private function section_items($section, $limit=5, $order='at_id desc'){
		$where = array( 'sc_value'=>$section );
		$args = array( 'where'=>$where, 'enabled'=>1, 'sort'=>array('at_date_posted'=>$order), 'limit'=>$limit );
		return $this->article_model->get_articles( $args );
	}

	public function i($id=FALSE)
	{
		if(is_numeric($id)&&$id>0)
		{
			$where = array( 'at_enabled'=>1, 'at_id'=>$id );
			$args = array( 'where'=>$where, 'one'=>TRUE );
			$this->data['article'] = $this->article_model->get_articles( $args );
			$this->data['section'] = 'article';
			if(isset($this->data['article']['at_id']))
			{
				$this->data['title'] = $this->data['article']['at_title'];
				$this->data['description'] = $this->data['article']['at_summary'];
				$this->data['keywords'] = $this->data['article']['at_keywords'];
			}
		}
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}

	public function team($id=FALSE)
	{
		if(is_numeric($id)&&$id>0)
		{
			$where = array( 'at_enabled'=>1, 'at_id'=>$id );
			$args = array( 'where'=>$where, 'one'=>TRUE );
			$this->data['article'] = $this->article_model->get_articles( $args );
			$this->data['section'] = 'article';
			if(isset($this->data['article']['at_id']))
			{
				$this->data['title'] = $this->data['article']['at_title'];
				$this->data['description'] = $this->data['article']['at_summary'];
				$this->data['keywords'] = $this->data['article']['at_keywords'];
			}
			$this->data['rotary'] = $this->section_items('about/rotary');
		}
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}
	public function pp($id=FALSE)
	{
		if(is_numeric($id)&&$id>0)
		{
			$where = array( 'at_enabled'=>1, 'at_id'=>$id );
			$args = array( 'where'=>$where, 'one'=>TRUE );
			$this->data['article'] = $this->article_model->get_articles( $args );
			$this->data['section'] = 'article';
			if(isset($this->data['article']['at_id']))
			{
				$this->data['title'] = $this->data['article']['at_title'];
				$this->data['description'] = $this->data['article']['at_summary'];
				$this->data['keywords'] = $this->data['article']['at_keywords'];
			}
			$this->data['rotary'] = $this->section_items('about/rotary');
		}
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}
	public function rotary($id=FALSE)
	{
		if(is_numeric($id)&&$id>0)
		{
			$where = array( 'at_enabled'=>1, 'at_id'=>$id );
			$args = array( 'where'=>$where, 'one'=>TRUE );
			$this->data['article'] = $this->article_model->get_articles( $args );
			$this->data['section'] = 'article';
			if(isset($this->data['article']['at_id']))
			{
				$this->data['title'] = $this->data['article']['at_title'];
				$this->data['description'] = $this->data['article']['at_summary'];
				$this->data['keywords'] = $this->data['article']['at_keywords'];
			}
			$this->data['rotary'] = $this->section_items('about/rotary', 5, 'at_id desc');
		}
		$this->load->view( "{$this->data['theme']}/about.tpl", $this->data );
	}
}
