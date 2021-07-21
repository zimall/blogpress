<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Courses extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->authorize->validate_user();
			$this->data['menu'] = 'courses';
			$this->load->model('article_model');
			$this->load->library('al');
			$this->al->sidebar();
			$this->data['title'] = 'Search';
			$this->data['section'] = 'items';

		}

		public function index(){
			$page = $this->article_model->get_section('courses');
			if($page) {
				$this->pc->page_control('courses_list', $page['sc_items'], $page['sc_order']);
				$where = array('at_enabled' => 1, 'at_section' => $page['sc_id']); //
				$count = array('where' => $where, 'count' => 1);
				$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
				$sort = $this->data['sort'] ?? get_sort($page['sc_order']);
				$args = array_merge($paginate, array('where' => $where, 'sort' => $sort));
				$this->data['courses'] = $this->article_model->get_articles($args);
				$this->data['title'] = $page['sc_name'];
				$this->data['innertitle'] = $page['sc_name'];
				$this->data['menu'] = $page['sc_value'];
				$this->data['tags'] = $this->tags($page['sc_id']);
				$this->pc->get_route_content(self::class);
				$this->load->view("{$this->data['theme']}/courses.tpl", $this->data);
			}
			else{
				redirect("pages/search?q=courses");
			}
		}

		public function details($id)
		{
			if( is_numeric($id) && $id>0 )
			{
				$where = array( 'at_id'=>$id );
				$this->data['course'] = $d = $this->article_model->get_articles(  array( 'where'=>$where, 'one'=>TRUE ) );
				$this->data['tags'] = [];
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
				$this->data['section'] = 'details';
				$this->pc->get_route_content(self::class,'details');
				$this->load->view("{$this->data['theme']}/courses.tpl", $this->data );
			}
			else
			{
				sem( 'The page you requested could not be found' );
				$this->index();
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

	}