<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Michael M Chiwere
 * Description: AJAX Functions controller class
 * This is accessible to all members
 */
class Ajax extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->output->enable_profiler(FALSE);
	}


	public function index()
	{
		$data['error'] = TRUE;
		$data['error_msg'] = 'invalid request';
		echo json_encode($data);
	}

	public function get_other_traffic_sources(){
		$start = $this->input->get('start');
		$end = $this->input->get('end');
		$data = $start && $end ? $this->reporting->get_traffic_sources($start,$end,false) : [];
		echo json_encode($data);
	}

	public function get_make()
	{
		$this->load->model('list_model');
		$this->pc->page_control('vehicle_make');
		$count = array( 'count'=>TRUE);
		$function = 'get_make';
		$paginate = $this->pc->paginate($count, $function, 'list_model');
		$args = array( 'start'=>$paginate['start'], 'limit'=>$paginate['limit'] );
		$count['count'] = NULL;
		$args = array_merge($args, $count);
		$this->data['make'] = $this->list_model->get_make($args);
		$this->data['paginate'] = TRUE;
		echo $this->load->view( "{$this->data['theme']}/listings/makes.tpl", $this->data, TRUE );
	}

	public function get_model()
	{
		$this->load->model('list_model');
		$this->pc->page_control('vehicle_model');
		$count = array( 'count'=>TRUE);
		$function = 'get_model';
		$paginate = $this->pc->paginate($count, $function, 'list_model');
		$args = array( 'start'=>$paginate['start'], 'limit'=>$paginate['limit'] );
		$count['count'] = NULL;
		$args = array_merge($args, $count);
		$this->data['model'] = $this->list_model->get_model($args);
		$this->data['makes'] = $this->list_model->get_make();
		$this->data['paginate'] = TRUE;
		echo $this->load->view( "{$this->data['theme']}/listings/models.tpl", $this->data, TRUE );
	}

	public function get_property()
	{
		$t = $this->input->get('pp');
		$p = $this->input->get('pf');
		$tmp = array( 'table'=>$t, 'prefix'=>$p, 'order'=>$p.'name asc' );
		$this->load->model('list_model');
		$this->pc->page_control('vehicle_'.$t);
		$args = array( 'count'=>TRUE);
		$count = array_merge($args, $tmp);
		$function = 'get_property';
		$paginate = $this->pc->paginate($count, $function, 'list_model');
		$args = array( 'start'=>$paginate['start'], 'limit'=>$paginate['limit'] );
		$count['count'] = NULL;
		$body = array_merge($args, $tmp);
		$this->data[$t] = $this->list_model->get_property($body);
		$this->data['paginate'] = TRUE;
		echo $this->load->view( "{$this->data['theme']}/listings/{$t}.tpl", $this->data, TRUE );
	}










	public function image_privacy()
	{
		$this->load->model('article_model');
		$img_id = $this->input->get('img_id');
		$file = $this->input->get('file');
		$article = $this->input->get('id');
		$p = $this->input->get('privacy');
		
		$error = $this->article_model->image_privacy( array( 	'file'=>$file, 'id'=>$article, 'img_id'=>$img_id, 'privacy'=>$p ) );
		$data = $this->article_model->get_image_privacy($img_id);
		if( isset($data['gi_id']) )
		{
			$data['text'] = $data['gi_private']?'make public':'make private';
		}
		else $data = array( 'gi_id'=>$img_id, 'gi_file'=>$file, 'gi_at_id'=>$article, 'gi_private'=>$p, 'text'=>'can\'t update' );
		echo json_encode($data);
	}

	public function delete_gallery_img()
	{
		$file = $this->input->get('file');
		$id = $this->input->get('img_id');
		if( $id && $file )
		{
			$this->load->model('article_model');
			$data = $this->article_model->delete_gallery_image( array( 'im_file'=>$file, 'im_id'=>$id ) );
			echo json_encode($data);
		}
		else
		{
			echo json_encode(array('error'=>TRUE,'error_msg'=>'Image ID and Image Path are required'));
		}
	}

	public function delete_article_image()
	{
		if($this->flexi_auth->is_logged_in())
		{
			$file = $this->input->get('file');
			$id = $this->input->get('at_id');
			
			if($id>0)
			{
				$this->load->model('article_model');
				$data = $this->article_model->delete_article_image( ['at_image'=>$file, 'at_id'=>$id ] );
				echo json_encode($data);
			}
			else
			{
				$folder = realpath('./images/articles');
				$sizes = [ 'xs','sm','md','lg','xl' ];
				try{
					foreach($sizes as $s){
						$webp = false;
						$fp = "{$folder}/{$s}/{$file}";
						if(file_exists($fp)) {
							$ext = pathinfo($fp, PATHINFO_EXTENSION);
							$webp = str_replace( ".{$ext}", '.webp', $fp );
							unlink($fp);
						}
						if( $webp && file_exists($webp) ) unlink($webp);
					}
					echo json_encode( [ 'error'=>1 ] );
				}
				catch(Exception $e)
				{
					echo json_encode( [ 'error'=>0, 'error_msg'=>"Can`t delete {$file}. <br>{$e}" ] );	
				}
				
			}
			
		}
		else echo json_encode(['error'=>0,'error_msg'=>'You need to be logged in to delete an image. Please login and try again']);
	}

	public function delete_logo()
	{
		$file = $this->input->get('file');
		$folder = $this->input->get('folder');
		if( unlink('./'.$folder.$file) ) echo json_encode( [ 'error'=>1 ] );
		else echo json_encode( [ 'error'=>0 ] );
	}

	public function delete_slider()
	{
		$file = $this->input->get('file');
		$folder = "images/slider/";
		if( unlink('./'.$folder.$file) ) echo json_encode( [ 'error'=>1 ] );
		else echo json_encode( [ 'error'=>0 ] );
	}

	public function make_prod_img_main()
	{
		$this->load->model('list_model');
		$data = $this->list_model->make_prod_img_main( 
			array( 
				'file'=>$this->input->get('file'), 'id'=>$this->input->get('id') ) );
		echo json_encode($data);
	}
	
	public function get_cities(){
		$data = $this->location->get_cities( array( 'country'=>$this->input->get('id') ) );
		echo json_encode($data);
	}
	
	public function get_districts(){
		$data = $this->location->get_districts( array( 'city'=>$this->input->get('id') ) );
		echo json_encode($data);
	}
	
	public function get_townships_by_city(){
		$data = $this->location->get_townships( array( 'city'=>$this->input->get('id') ) );
		echo json_encode($data);
	}
	
	public function get_townships_by_district(){
		$data = $this->location->get_townships( array( 'district'=>$this->input->get('id') ) );
		echo json_encode($data);
	}


	public function get_users()
	{
		$where = array( 'uacc_email'=>$this->input->get('email') );
		$this->load->model('admin/user_model');
		$data = $this->user_model->get_users( array( 'where'=>$where, 'one'=>1, 'table'=>'user_accounts' ) );
		//$data = array('what the f');
		echo json_encode($data);
	}
	
	
	public function add_business_users()
	{
		$this->load->model('admin/shop_model');
		$data = $this->shop_model->update_users();
		//$data = array('what the f');
		echo json_encode($data);
	}
	
	
	public function get_products()
	{
		$q = $this->input->get('name');
		$cid = $this->input->get('company_id');
		if(is_numeric($q))
			$where = array( 'id'=>$q );
		else $where = array( 'name'=>$q );
		
		$select = 'products.name, products.category, products.id, products.image, products.model, product_categories.name as category_name';
		
		$this->load->model('admin/product_model');
		if(is_numeric($q))
			$data = $this->product_model->get_products( array( 'where'=>$where, 'select'=>$select, 'join'=>1 ) );
		else $data = $this->product_model->get_products( array( 'like'=>$where, 'select'=>$select, 'join'=>1 ) );
		$prod = array();
		
		if( !isset($data['error']) )
		{
			$this->load->model('admin/shop_model');
			$this->load->model('admin/catalog_model');
			foreach($data as $k=>$v)
			{
				$p = $this->shop_model->get_company_products( array( 'select'=>$select, 'join'=>1,'product_id'=>$v['id'], 'company_id'=>$cid, 'one'=>1 ) );
				if( !isset($p['error']) )
					$prod[] = $p;
				else
				{
					$data[$k]['min_order'] = 1;
					$data[$k]['price'] = 0;
					$data[$k]['quantity'] = 0;
					$data[$k]['exists'] = 0;
					$prod[] = $data[$k];
				}
			}
			$tax = $this->catalog_model->get_taxes();
			echo json_encode( array( 'tax'=>$tax, 'products'=>$prod ) );
		}
		else echo json_encode($data);
	}
	
	public function add_shop_products()
	{
		$this->load->model('admin/shop_model');
		$data = $this->shop_model->update_products();
		//$data = array('error'=>TRUE,'error_msg'=>'success');
		echo json_encode($data);
	}


	public function get_product_list()
	{
		$q = $this->input->get('name');
		$where = array( 'name'=>$q );
		$select = 'products.id,products.name, products.model, products.image, product_categories.name as category_name';
		$this->load->model('admin/product_model');
		$data = $this->product_model->get_products( array( 'like'=>$where, 'select'=>$select, 'join'=>1, 'limit'=>6 ) );
		echo json_encode($data);
	}
	
	public function get_product_data()
	{
		$id = $this->input->get('id');
		$where = array( 'id'=>$id, 'limit'=>1 );
		$this->load->model('admin/product_model');
		$data = $this->product_model->get_product( $id );
		if( isset($data['id']) )
		{
			if(isset($data['d_description']))
				$data['d_description'] = html_entity_decode($data['d_description']);
			echo json_encode($data);
		}
		else echo json_encode($data);
	}


	public function get_orders_chart()
	{
		$this->load->model('admin/catalog_model');
		$d = $this->catalog_model->get_orders_chart();
		echo json_encode($d);
	}


	public function get_users_chart()
	{
		$this->load->model('admin/user_model');
		$d = $this->user_model->get_users_chart();
		echo json_encode($d);
	}


	public function get_analytics()
	{
		try
		{
			$this->load->library('analytics');
		}
		catch( Exception $ex )
		{
			sem($ex);
		}

		$this->analytics->requestReportData( 76222405, array( 'hostName', 'pagePath', 'pageTitle' ), array( 'uniquePageViews' ),
		array('-uniquePageViews'), null, null, null, 1, 100 );
		
		$objResults = $this->analytics->getResults();
		foreach( (array)$objResults as $objResult )
		{
			$strHostName = $objResult->getHostname();
			$strPagePath = $objResult->getPagepath();
			$strPageTitle = $objResult->getPagetitle();
			$intUniquePageViews = $objResult->getUniquePageViews();
			sem( 'Hostname: ' . $strHostName . ', Pagepath: ' . $strPagePath . ', Page Title: ' . $strPageTitle . ', Unique Pageviews: ' .
			$intUniquePageViews . '<br>');
		}
	}

}
