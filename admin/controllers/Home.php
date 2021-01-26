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
		$this->data['ana_days'] = $d = $this->input->get('days') ? $this->input->get('days') : 7;
		$start = mysql_date( time() - (60*60*24*$d) );
		$end = mysql_date();
		$this->data['ana_start'] = $start;
		$this->data['ana_end'] = $end;
		$this->data['ana_summary'] = $this->reporting->summary( $start, $end );
		$this->load->view( "{$this->data['theme']}/home.tpl", $this->data );
	}

	public function fix_session_hosts($host='other'){
		$d = $this->reporting->fix_ss_hosts($host);
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
