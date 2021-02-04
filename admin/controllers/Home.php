<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

	function __construct() 
	{
		parent::__construct();
		
		$this->authorize->validate_user();
	}

	public function index()
	{

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
		$this->load->view( "{$this->data['theme']}/home.tpl", $this->data );
	}

	public function fix_session_hosts($host='other', $exact=1){
		$d = $this->reporting->fix_ss_hosts($host,$exact);
		echo json_encode($d);
	}

	public function about()
	{
		$this->data['section'] = 'template';
		$this->load->view("{$this->data['theme']}/page.tpl", $this->data);
	}
	
	public function news()
	{
		$this->load->view("{$this->data['theme']}/news.tpl");
	}
	
	public function events()
	{
		$this->load->view("{$this->data['theme']}/events.tpl");
	}
	
	public function reports()
	{
		$this->load->view("{$this->data['theme']}/reports.tpl");
	}
	
	public function discussions()
	{
		$this->load->view("{$this->data['theme']}/blog.tpl");
	}
	
	public function gallery()
	{
		$this->load->view("{$this->data['theme']}/gallery.tpl");
	}
}
