<?php defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->data['menu'] = 'news';
		$this->load->model('article_model');
		$this->data['title'] = 'News';
		$this->data['section'] = 'items';
	}

	public function index()
	{
		$this->pc->page_control( 'news', 10 );
		
		$where = array( 'at_enabled'=>1, 'at_section'=>5 );
		$count = array( 'where'=>$where, 'count'=>1 );
		$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
		$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_date_posted desc' ) );
		$this->data['articles'] = $this->article_model->get_articles( $args );
		
		$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image';
		$where = array( 'at_section'=>5 );
		$args = array( 'where'=>$where, 'sort'=>'at_hits desc', 'limit'=>5, 'select'=>$select );
		$this->data['popular_articles'] = $this->article_model->get_articles($args);
		
		$select = 'at_keywords';
		$where = array( 'at_section'=>5 );
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
		
		$this->data['innertitle'] = 'News';
		$this->load->view( "{$this->data['theme']}/news.tpl", $this->data );
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
			$this->load->view("{$this->data['theme']}/news.tpl", $this->data );
		}
		else
		{
			sem( 'The page you requested could not be found' );
			$this->index();
		}
	}
	
}
