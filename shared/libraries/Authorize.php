<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authorize
{

	function __construct()
	{
		$this->ci = &get_instance();
		date_default_timezone_set('Africa/Harare');
		$this->ipage = $this->ci->config->item('index_page');
		$this->admin_panels = $this->ci->config->item('admin_panels');
		
		// IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
 		// It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
		$this->set_return_url();
	}

	public function validate_user()
	{
		// IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
 		// It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
		$this->ci->auth = new stdClass;

		// Load 'standard' flexi auth library by default.
		$this->ci->load->library('flexi_auth');	
		$this->ci->load->model('user_model');
		
		$mat = preg_match( '/^xac/', uri_string() );			
		$aja = preg_match( '/^ajax/', uri_string() );
		
		if( !$this->ci->flexi_auth->is_logged_in() )
		{
			$cookie = array(
				'name'   => 'last_url',
				'value'  => full_url(),
				'expire' => '200'
			);
			
			
			//if( $this->remember_path( uri_string() ) && $mat<1 && $aja<1 )
			//	$this->ci->input->set_cookie($cookie);
			if( in_array( $this->ipage, $this->admin_panels ) )
			{
				sem('You need to login to continue');
				$this->set_return_url( 'login_return', current_url() );
				redirect( 'login' );
			}
			
			$this->ci->data['logged_in'] = FALSE;
		}
		else
		{
			$this->ci->data['logged_in'] = TRUE;
			if( in_array( $this->ipage, $this->admin_panels ) )
			{
				if( $this->ci->flexi_auth->is_admin() )
				{
					if( !$this->ci->flexi_auth->is_logged_in_via_password() )
					{
						sem( 'You need to login via password' );
						$this->set_return_url( 'login_return', current_url() );
						redirect( 'login' );
					}
				}
				else
				{
					sem('the page you are trying to access does not exist');
					redirect( base_url() );
				}
			}
			
			$this->ci->data['user_data'] = $this->ci->user_model->get_user_data();
		}
		$this->ci->pc->required_content();
	}

	public  function set_return_url($id=FALSE,$url=FALSE)
	{
		if( $url && $id )
		{
			$this->ci->session->set_userdata($id,$url);
		}
		else
		{
			$mat = preg_match( '/^xac/', uri_string(), $match );			
			$aja = preg_match( '/^ajax/', uri_string() );
			//$dh = $this->ci->config->item('download_helper');
			//$download = preg_match( '/^'.$dh.'/', uri_string() );
			if( !$this->forget_path( uri_string() ) && $aja<1 && $mat<1 ) //&& $download<1
			{
				$this->ci->session->set_flashdata( $this->ipage.'return_url', current_url() );
				if( in_array($this->ipage, $this->admin_panels) )
					$this->ci->session->keep_flashdata( 'return_url');
				foreach($this->admin_panels as $p)
				{
					if( $p != $this->ipage )
						$this->ci->session->keep_flashdata( $p.'return_url');
				}
			}
			else
			{
				$this->ci->session->keep_flashdata('return_url');
				foreach($this->admin_panels as $p)
				{
					$this->ci->session->keep_flashdata( $p.'return_url');
				}
			}
		}
	}


	public function get_return_url($id=FALSE)
	{
		if( $id && ($url = $this->ci->session->userdata($id)) )
		{
			$this->ci->session->unset_userdata($id);
			return $url;
		}		
		return $this->ci->session->flashdata( $this->ipage.'return_url');
	}

	private function forget_path($path='account')
	{
		$forget = array(
			'login',
			'login/register',
			'login/forgotten_password',
			'login/logout',
			'account/address_book/add_address',
			'account/add_address',
			'account/address_book',
			'account/fb_login',
			'account/fb_register',
			'account/subscribe',
			'account/login',
			'account/register',
			'account/forgotten_password',
			'account/activate_account',
			'account/logout',
			'fb/fb_login',
			'fb/fb_register',
			'fbstore/account/fb_login',
			'fbstore/account/fb_register',
			'fbstore/fb/fb_login',
			'fbstore/fb/fb_register',
			'shopping_cart/discount_coupon',
			'shopping_cart/remove_coupon',
			'images/fav.ico',
			'favicon.ico',
			'shop/quotation'
		);
		//$c = '/activate_account/';
		if(in_array($path,$forget)) return TRUE;
		//elseif( preg_match( $c, $path ) ) return FALSE;
		return FALSE;
	}

}
?>
