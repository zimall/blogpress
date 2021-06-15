<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Loader Class
 *
 * Loads framework components.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Loader
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/loader.html
 */
class MY_Loader extends CI_Loader
{

	/**
	 * List of paths to load libraries from
	 *
	 * @var	array
	 */
	protected $_ci_library_paths =	array(APPPATH, SHAREDPATH, BASEPATH);

	/**
	 * List of paths to load models from
	 *
	 * @var	array
	 */
	protected $_ci_model_paths =	array(APPPATH, SHAREDPATH);
	
	/**
	 * List of paths to load helpers from
	 *
	 * @var	array
	 */
	protected $_ci_helper_paths =	array(APPPATH, SHAREDPATH, BASEPATH);
	//$view = SHAREDPATH;
	protected $_ci_view_paths =	array(VIEWPATH	=> TRUE, SHAREDVIEW=>TRUE );



	// --------------------------------------------------------------------

	/**
	 * Database Loader
	 *
	 * @param	mixed	$params		Database configuration options
	 * @param	bool	$return 	Whether to return the database object
	 * @param	bool	$query_builder	Whether to enable Query Builder
	 *					(overrides the configuration setting)
	 *
	 * @return	object|bool	Database object if $return is set to TRUE,
	 *					FALSE on failure, CI_Loader instance in any other case
	 */
	public function database($params = '', $return = FALSE, $query_builder = NULL)
	{
		// Grab the super object
		$CI =& get_instance();

		// Do we even need to load the database class?
		if ($return === FALSE && $query_builder === NULL && isset($CI->db) && is_object($CI->db) && ! empty($CI->db->conn_id))
		{
			return FALSE;
		}

		if( file_exists( SHAREDPATH.'database/DB.php' ) ) require_once(SHAREDPATH.'database/DB.php');
		else require_once(BASEPATH.'database/DB.php');

		if ($return === TRUE)
		{
			return DB($params, $query_builder);
		}

		// Initialize the db variable. Needed to prevent
		// reference errors with some configurations
		$CI->db = '';

		// Load the DB class
		$CI->db =& DB($params, $query_builder);
		return $this;
	}

	public function theme($name=FALSE,$source=APPPATH)
	{
		$CI =& get_instance();
		$data = array();
		if( $source == APPPATH )
		{
			$a = explode( '/', $source );
			$n = count($a)-1;
			if( isset( $a[$n] ) ) $a;
			else
			{
				sem("Theme folder not found/defined");
				return array();
			}
		}
		if( $name && strlen($name)>1 )
		{
			$theme = "{$name}_theme";
			$file = "$source/views/{$name}/{$theme}.php";
			$example = "$source/views/{$name}/{$theme}.example.php";

			if( !file_exists($file) && file_exists($example) ){
				copy( $example, $file );
			}

			if( file_exists($file) )
			{
				require($file);
				$CI->config->set_item( $theme, $theme_config );
				$data = $CI->config->item($theme);
			}
			else
			{
				sem("The theme <strong>$name</strong> does not exist or is not properly configured");
			}
		}
		else{
			sem("<strong>$source/views/$name</strong> is not a valid theme name.");
		}
		return $data;
	}

	/**
	 * View Loader
	 *
	 * Loads "view" files.
	 *
	 * @param	string	$view	View name
	 * @param	array	$vars	An associative array of data
	 *				to be extracted for use in the view
	 * @param	bool	$return	Whether to return the view output
	 *				or leave it to the Output class
	 * @return	object|string
	 */
	public function view($view, $vars = array(), $return = FALSE)
	{
		$CI = &get_instance();
		$accept = $CI->input->get_request_header('accept', TRUE);
		if($accept==='application/json'){
			return $this->send_response($vars, $return);
		}
		return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_prepare_view_vars($vars), '_ci_return' => $return));
	}

	private function send_response($data, $return=false){
		header('Content-Type: application/json');
		$CI = &get_instance();
		$CI->load->helper('json');
		$js = new Json_helper();
		$json = $js->encode($data, JSON_UNESCAPED_UNICODE);
		$je = $js->get_last_error();
		if( $je == JSON_ERROR_NONE ){
			if($return) return $json;
			else echo $json;
		}
		else{
			if($return) return $js->encode(['error'=>true,'message'=>$js->get_last_error_msg()]);
			echo $js->encode(['error'=>true,'message'=>$js->get_last_error_msg()]);
		}
		if(isset($_GET['print_response'])) print_r($data);
		return '';
	}

}
