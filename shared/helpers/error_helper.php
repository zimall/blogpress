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

/* End of file error_helper.php */
/* Location: ./system/helpers/error_helper.php */
