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
		$this->load->view( "{$this->data['theme']}/home.tpl", $this->data );
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
