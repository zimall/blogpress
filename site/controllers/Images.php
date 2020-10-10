<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Images extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->authorize->validate_user();
		$this->load->model('article_model');
		$this->load->library('imagier');
	}

	public function articles($size='',$file='')
	{
		$this->imagier->articles($size,$file);
	}

	public function article_thumbs($index='uploadfile')
	{
		if( $this->flexi_auth->is_logged_in() )
		{
			if( isset($_FILES[$index]) )
				$res = $this->imagier->create_thumb($index);
			else $res = array( 'error'=>'no image uploaded' );
		}
		else $res = [ 'error'=>'You need to be logged in to upload images. Please refresh your page and try again.' ];
		echo json_encode($res);
	}


}
