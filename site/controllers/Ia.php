<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Ia extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model('ia_model');
			$this->output->set_content_type('application/json');
		}

		public function index()
		{
			$beacon = $this->input->post();
			if(isset($beacon['location'])) {
				$r = $this->ia_model->start($beacon);
				echo json_encode($r);
			}
			else{
				$this->output->set_status_header(400);
				echo "invalid request";
			}
		}
	}
