<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Products extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->authorize->validate_user();
			$this->data['menu'] = 'products';
			$this->load->model('article_model');
			$this->load->library('al');
			$this->al->sidebar();
			$this->data['title'] = 'Products';
			$this->data['section'] = 'items';

		}

		public function index( $page_id )
		{
			$this->data['page'] = $page = $this->pages_model->get_pages($page_id);

			if(!empty($page))
			{
				$this->pc->page_control( $page['sc_value'].'_list', $page['sc_items'], $page['sc_order'] );
				$where = array( 'at_enabled'=>1, 'at_section'=>$page['sc_id'] );
				//$or = ['sc_parent'=>$page['sc_id']];
				$where = "`at_enabled`=1 AND ( `at_section` = {$page['sc_id']} OR `sc_parent`= {$page['sc_id']} )";
				$count = array( 'where'=>$where, 'count'=>1 );
				$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');
				$sort = $this->data['sort'] ?? get_sort($page['sc_order']);
				//if( !isset($this->data['sort']) || !$this->data['sort'] ) $this->data['sort'] = $sort?:'at_id desc';
				$paginate['sort'] = $sort?:'at_id desc';
				$args = array_merge( $paginate, ['where'=>$where] );
				$this->data['articles'] = $this->article_model->get_articles( $args );
				$this->data['title'] = $page['sc_name'];
				$this->data['innertitle'] = $page['sc_name'];
				$this->data['menu'] = $page['sc_value'];
				$this->data['tags'] = $this->al->tags($page_id);
				$this->data['sub_sections'] = $this->al->get_sub_sections($page_id);
				$this->pc->get_route_content('Pages','index', [ 'where'=>['at_section'=>$page['sc_id']] ]);

				$view = $this->al->get_view($page['sc_view']??'');
				$this->load->view( "{$this->data['theme']}/{$view}.tpl", $this->data );
			}
			else redirect( site_url("home/search")."?q={$page_id}" );
		}

		public function article( $page_id, $id=FALSE)
		{
			if( is_numeric($id) && $id>0 )
			{
				$where = array( 'at_id'=>$id );
				$this->data['article'] = $d = $this->article_model->get_articles(  array( 'where'=>$where, 'one'=>TRUE ) );
				$this->data['tags'] = [];
				if(isset($d['at_id']))
				{
					$this->data['title'] = $d['at_title'];
					$this->data['description'] = $d['at_summary'];
					$this->data['keywords'] = $d['at_keywords'];
					$this->data['menu'] = $d['sc_value'];
					$this->data['tags'] = $this->al->tags($d['at_section']);
					$this->data['images'] = $g = $d['sc_has_gallery'] ? $this->article_model->get_gallery(['at_id'=>$id]) : [];
					if($g){
						$this->data['theme_scripts'][] = 'lightgallery-all.min';
						$this->data['print_scripts'][] = '$(document).ready(function(){ $("#ul-li").lightGallery(); });';
					}
				}
				$view = $this->al->get_view($d['sc_view']??'');
				$this->data['sub_sections'] = $this->al->get_sub_sections($page_id);
				$this->data['section'] = 'article';
				$this->pc->get_route_content('Courses','details');
				$this->load->view("{$this->data['theme']}/{$view}.tpl", $this->data );
			}
			else
			{
				sem( 'The page you requested could not be found' );
				$this->index();
			}
		}

	}