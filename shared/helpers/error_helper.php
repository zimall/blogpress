<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * CodeIgniter Error Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/currency_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Default Currency
 *
 * Fetches the default currency from Database
 *
 * @access	public
 * @param	null
 * @return	string
 */
if ( ! function_exists('sem'))
{
	function sem($msg='', $f=1, $log=0, $show=1)
	{
		$ci =& get_instance();
		$ci->load->library('Actions');
		$ci->actions->sem($msg,$f,$log,$show);
	}
}

if ( ! function_exists('gem'))
{
	function gem()
	{
		$ci =& get_instance();
		$ci->load->library('Actions');
		$ci->actions->gem();
	}
}

if(!function_exists('clear_cache'))
{
	function clear_cache($path=FALSE)
	{
		if($path)
		{
			$files = glob($path.'*');
			$ignore = $path.'index.html';
		}
		else
		{
			$files = glob( APPPATH.'cache/*' ); // get all file names
			$ignore = APPPATH.'cache/index.html';
		}
		foreach($files as $file) // iterate files
		{
			if( is_file($file) && $file!=$ignore )
			unlink($file); // delete file
		}
	}
}

if(!function_exists('is_rotary')){
	function is_rotary(){
		$ci = &get_instance();
		return in_array($ci->config->item('club-type'),['Rotary','Rotaract','Interact']);
	}
}

	if ( ! function_exists('sade'))
	{
		function sade( $msg='You do not have permission to view this page', $title='Access Denied', $url=FALSE )
		{
			$ci =& get_instance();
			$ci->data['sad_msg'] = $msg;
			$ci->data['sad_title'] = $title;
			$ci->data['sad_url'] = $url?$url:full_url();
			$ci->data['section'] = '403';
			$ci->load->view($ci->data['theme'].'/common.tpl', $ci->data);
			return 403;
		}
	}

	if ( ! function_exists('bad_request'))
	{
		function bad_request( $msg='Your request could not be processed. Please check your URL or submitted data', $title='Bad Request', $url=FALSE )
		{
			$ci =& get_instance();
			$ci->data['sad_msg'] = $msg;
			$ci->data['sad_title'] = $title;
			$ci->data['sad_url'] = $url?$url:full_url();
			$ci->data['section'] = '400';
			$ci->load->view($ci->data['theme'].'/common.tpl', $ci->data);
			return 400;
		}
	}

/* End of file error_helper.php */
/* Location: ./system/helpers/error_helper.php */
