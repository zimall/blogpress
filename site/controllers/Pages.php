<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->data['menu'] = 'pages';
		$this->load->model('article_model');
		$this->load->library('al');
		$this->al->sidebar();
		$this->data['title'] = 'Search';
		$this->data['section'] = 'items';
		
	}

	public function index( $page_id )
	{
		$this->data['page'] = $page = $this->article_model->get_section($page_id);

		if(!empty($page))
		{
			$this->pc->page_control( 'pages', 15 );
			$where = array( 'at_enabled'=>1, 'at_section'=>$page['sc_id'] );
			$count = array( 'where'=>$where, 'count'=>1 );
			$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
			$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id desc' ) );
			$this->data['articles'] = $this->article_model->get_articles( $args );
			$this->data['title'] = $page['sc_name'];
			$this->data['innertitle'] = $page['sc_name'];
			$this->data['menu'] = $page['sc_value'];
			$this->data['tags'] = $this->tags($page_id);
			$this->load->view( "{$this->data['theme']}/pages.tpl", $this->data );
		}
		else redirect( site_url("home/search")."?q={$page_id}" );
	}

	public function article( $page_id, $id=FALSE)
	{
		if( is_numeric($id) && $id>0 )
		{
			$where = array( 'at_id'=>$id );
			$this->data['article'] = $d = $this->article_model->get_articles(  array( 'where'=>$where, 'one'=>TRUE ) );
			if(isset($d['at_id']))
			{
				$this->data['title'] = $d['at_title'];
				$this->data['description'] = $d['at_summary'];
				$this->data['keywords'] = $d['at_keywords'];
				$this->data['menu'] = $d['sc_value'];
				$this->data['tags'] = $this->tags($d['at_section']);
				$this->data['images'] = $g = $d['sc_has_gallery'] ? $this->article_model->get_gallery(['at_id'=>$id]) : [];
				if($g){
					$this->data['theme_scripts'][] = 'lightgallery-all.min';
					$this->data['print_scripts'][] = '$(document).ready(function(){ $("#ul-li").lightGallery(); });';
				}
			}
			$this->data['section'] = 'article';
			$this->load->view("{$this->data['theme']}/pages.tpl", $this->data );
		}
		else
		{
			sem( 'The page you requested could not be found' );
			$this->index();
		}
	}

	public function search()
	{
		$q = trim($this->input->get('q'));
		$q = strip_tags($q);
		$q = $this->security->xss_clean($q);
		$this->data['search_term'] = $q;
		if( is_string($q) && strlen($q)>0 )
		{
			$this->pc->page_control( 'search', 10 );
			$like = array( 'at_keywords'=>$q ); //'at_section'=>5
			$like = [ "CONCAT(at_title,at_keywords,at_summary,sc_name)"=>$q ];
			$order = [ "FIELD(at_title, '$q')", "FIELD(at_keywords, '$q')", "FIELD(at_summary, '$q')", "FIELD(sc_name, '$q')", 'at_date_posted desc' ];
			$count = array( 'like'=>$like, 'count'=>1 );
			$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
			
			$args = array_merge( $paginate, array( 'like'=>$like, 'sort'=>$order ) );
			$this->data['articles'] = $articles = $this->article_model->get_articles( $args );
				
				$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image';
				$where = array( 'at_section'=>5 );
				$args = array( 'sort'=>'at_date_posted desc', 'limit'=>5, 'select'=>$select, 'where'=>$where );
				$this->data['recent_articles'] = $this->article_model->get_articles($args);
				
				$select = 'at_keywords';
				$where = array( 'at_section'=>5 );
				$args = array( 'sort'=> array('at_id'=>'RANDOM'), 'limit'=>5, 'select'=>$select, 'where'=>$where );
				$tags = $this->article_model->get_articles($args);
				$tags1 = array();
				foreach( $tags as $t )
				{
					$t1 = explode( ',', $t['at_keywords'] );
					foreach( $t1 as $t2 )
					{
						if( strlen($t2)>3 ) $tags1[] = $t2;
					}
				}
				$this->data['tags'] = array_unique($tags1);

			$this->data['section'] = 'items';
			$this->data['innertitle'] = $this->data['title'] = "Search: ".$q;
			$this->load->view("{$this->data['theme']}/pages.tpl", $this->data );
		}
		else
		{
			sem( 'You need to type something in the search box. '.$q.'.' );
			redirect('home/recent');
			//echo 'ok';
		}
	}

	private function tags($page_id){
		$select = 'at_keywords';
		$where = array( 'at_section'=>$page_id );
		$args = array( 'where'=>$where, 'sort'=> array('at_id'=>'RANDOM'), 'limit'=>5, 'select'=>$select );
		$tags = $this->article_model->get_articles($args);
		$tags1 = array();
		foreach( $tags as $t )
		{
			$t1 = explode( ',', $t['at_keywords'] );
			foreach( $t1 as $t2 )
			{
				if( strlen($t2)>3 ) $tags1[] = $t2;
			}
		}
		return array_unique($tags1);
	}

	public function i($id=FALSE)
	{
		if( is_numeric($id) && $id>0 )
		{
			$where = array( 'at_id'=>$id ); //'at_section'=>5
			$this->data['article'] = $article = $this->article_model->get_articles(  array( 'where'=>$where, 'one'=>TRUE ) );
			if(isset($this->data['article']['at_id']))
			{
				$this->data['title'] = $this->data['article']['at_title'];
				$this->data['description'] = $this->data['article']['at_summary'];
				$this->data['keywords'] = $this->data['article']['at_keywords'];
				
				$select = 'at_id, at_summary, at_title, at_segment';
				$where = array( 'at_date_posted >'=>$article['at_date_posted'], 'at_section'=>$article['at_section'] );
				$args = array( 'where'=>$where, 'sort'=>'at_date_posted asc', 'one'=>TRUE, 'limit'=>1, 'select'=>$select );
				$this->data['next_article'] = $this->article_model->get_articles($args);
				
				$where = array( 'at_date_posted <'=>$article['at_date_posted'], 'at_section'=>$article['at_section'] );
				$args = array( 'where'=>$where, 'sort'=>'at_date_posted desc', 'one'=>TRUE, 'limit'=>1, 'select'=>$select );
				$this->data['previous_article'] = $this->article_model->get_articles($args);
				
				$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image';
				$where = array( 'at_section'=>$article['at_section'], 'at_id !='=>$article['at_id'] );
				$args = array( 'where'=>$where, 'sort'=>'at_date_posted desc', 'limit'=>5, 'select'=>$select );
				$this->data['recent_articles'] = $this->article_model->get_articles($args);
				
				$select = 'at_keywords';
				$where = array( 'at_section'=>$article['at_section'] );
				$args = array( 'where'=>$where, 'sort'=> array('at_id'=>'RANDOM'), 'limit'=>5, 'select'=>$select );
				$tags = $this->article_model->get_articles($args);
				$tags1 = array();
				foreach( $tags as $t )
				{
					$t1 = explode( ',', $t['at_keywords'] );
					foreach( $t1 as $t2 )
					{
						if( strlen($t2)>3 ) $tags1[] = $t2;
					}
				}
				$this->data['tags'] = array_unique($tags1);
			}
			$this->data['section'] = 'article';
			$this->load->view("{$this->data['theme']}/read.tpl", $this->data );
		}
		else
		{
			sem( 'The page you requested could not be found' );
			$this->index();
		}
	}
	
}
