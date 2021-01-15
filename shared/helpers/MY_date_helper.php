<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	// ------------------------------------------------------------------------

	if ( ! function_exists('mysql_date'))
	{
		function mysql_date($time=FALSE)
		{
			return $time ? date( 'Y-m-d H:i:s', $time ) : date( 'Y-m-d H:i:s' );
		}
	}

	/* End of file MY_date_helper.php */
	/* Location: ./shared/helpers/MY_date_helper.php */