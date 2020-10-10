<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class MY_Config extends CI_Config
{
	/**
	 * List of all loaded config values
	 *
	 * @var	array
	 */
	public $config = array();

	/**
	 * List of all loaded config files
	 *
	 * @var	array
	 */
	public $is_loaded =	array();

	/**
	 * List of paths to search when trying to load a config file.
	 *
	 * @used-by	CI_Loader
	 * @var		array
	 */
	public $_config_paths =	array(APPPATH, SHAREDPATH);

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Sets the $config data from the primary config.php file as a class variable.
	 *
	 * @return	void
	 */
	function home_url($uri = '')
	{
		if ($uri == '')
		{
			return $this->slash_item('base_url');
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').$this->_uri_string($uri).$suffix;
		}
		else
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').$suffix.'?'.$this->_uri_string($uri);
		}
	}
}
