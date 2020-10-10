<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->data['menu'] = 'gallery';
		$this->load->model('article_model');
		$this->data['title'] = 'Gallery';
		$this->data['section'] = 'items';
	}

	public function index()
	{
		$this->pc->page_control( 'gallery', 10 );
		
		$where = array( 'at_enabled'=>1, 'at_section'=>11 );
		$count = array( 'where'=>$where, 'count'=>1 );
		$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
		$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id desc' ) );
		$this->data['articles'] = $this->article_model->get_articles( $args );
		$this->data['innertitle'] = 'Gallery';
		$this->load->view( "{$this->data['theme']}/gallery.tpl", $this->data );
	}

	public function i($id=FALSE)
	{
		if( is_numeric($id) && $id>0 )
		{
			$this->data['theme_scripts'][] = 'lightgallery-all.min';
			$this->data['print_scripts'][] = '$(document).ready(function(){ $("#ul-li").lightGallery(); });';
			
			$where = array( 'at_section'=>11, 'at_id'=>$id );
			$img = array( 'at_id'=>$id );
			$this->data['article'] = $this->article_model->get_articles(  array( 'where'=>$where, 'one'=>TRUE ) );
			$this->data['images'] = $this->article_model->get_gallery( $img );
			if(isset($this->data['article']['at_id']))
			{
				$this->data['title'] = $this->data['article']['at_title'];
				$this->data['description'] = $this->data['article']['at_summary'];
				$this->data['keywords'] = $this->data['article']['at_keywords'];
			}
			else
			{
				sem('Could not find the page you are looking for. Login in to your account and try again');
				redirect('gallery');
			}
			$this->data['section'] = 'album';
			$this->load->view("{$this->data['theme']}/gallery.tpl", $this->data );
		}
		else
		{
			sem( 'The page you requested could not be found' );
			$this->index();
		}
	}
	
}
