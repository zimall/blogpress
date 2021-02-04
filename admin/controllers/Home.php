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
	}

	public function index()
	{
		$this->al->_process_form('home');
		$this->pc->page_control('home_list', 10 );
		$continue = $this->al->_process_get();

		if($continue) {
			$this->prepare_report();
			$this->pc->page_control('recent_articles', 10);
			$select = 'at_id';
			$where = array('at_enabled' => 1, 'at_date_posted <' => time());
			$count = array('where' => $where, 'count' => 1);
			$paginate = $this->pc->paginate($count, 'get_articles', 'article_model');

			$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_main_image,sc_name,sc_value,at_hits';
			$args = array_merge($paginate, ['where' => $where, 'sort' => 'at_date_posted desc', 'select' => $select]);

			$this->data['recent_articles'] = $this->article_model->get_articles($args);
			$this->load->view( "{$this->data['theme']}/home.tpl", $this->data );
		}
		else{
			$this->load->view( "{$this->data['theme']}/articles.tpl", $this->data );
		}
	}

	private function prepare_report(){
		$range = $this->input->get('daterange') ? $this->input->get('daterange') : false;
		if($range){
			$dates = explode( ' - ', $range );
			$start = isset($dates[0]) ? strtotime($dates[0]) : false;
			$end = isset($dates[1]) ? strtotime( $dates[1] ) : false;
		}
		else{
			$start = time() - (60*60*24*7); // take 7 days by default
			$end = time();
		}

		if( !$start || !$end ){
			$start = time() - (60*60*24*7); // take 7 days by default
			$end = time();
		}

		$this->data['ana_start'] = date( 'd M Y, H:i:s', $start );
		$this->data['ana_end'] = date( 'd M Y, H:i:s', $end );
		$my_start = mysql_date($start);
		$my_end = mysql_date($end);

		$add = ( date('d', $end+1) == date('d', $end) ) ? 0 : 1;

		$this->data['ana_days'] = pretty_time( $end - $start+$add, 'human' );
		$this->data['ana_summary'] = $this->reporting->summary( $my_start, $my_end );
	}

	public function fix_session_hosts($host='other', $exact=1){
		$d = $this->reporting->fix_ss_hosts($host,$exact);
		echo json_encode($d);
	}
}
