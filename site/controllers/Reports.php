<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->data['menu'] = 'reports';
		$this->load->model('article_model');
		$this->data['title'] = 'Reports';
		$this->data['section'] = 'items';
	}

	public function index()
	{
		$this->pc->page_control( 'reports', 10 );
		
		$where = array( 'at_enabled'=>1, 'at_section'=>10 );
		$count = array( 'where'=>$where, 'count'=>1 );
		$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
		$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id desc' ) );
		$this->data['articles'] = $this->article_model->get_articles( $args );
		$this->data['innertitle'] = 'Reports';
		$this->load->view( "{$this->data['theme']}/reports.tpl", $this->data );
	}

	public function i($id=FALSE)
	{
		if( is_numeric($id) && $id>0 )
		{
			$where = array( 'at_section'=>10, 'at_id'=>$id );
			$this->data['article'] = $this->article_model->get_articles(  array( 'where'=>$where, 'one'=>TRUE ) );
			if(isset($this->data['article']['at_id']))
			{
				$this->data['title'] = $this->data['article']['at_title'];
				$this->data['description'] = $this->data['article']['at_summary'];
				$this->data['keywords'] = $this->data['article']['at_keywords'];
			}
			$this->data['section'] = 'article';
			$this->load->view("{$this->data['theme']}/reports.tpl", $this->data );
		}
		else
		{
			sem( 'The page you requested could not be found' );
			$this->index();
		}
	}
	
}
