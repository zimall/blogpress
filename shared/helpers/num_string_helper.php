<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

if ( ! function_exists('num_string'))
{
	function num_string($num)
	{
		
		$words = [
			0=>'Zero',
			1=>'First',
			2=>'Second',
			3=>'Third',
			4=>'Fourth',
			5=>'Fifth',
			6=>'Sixth',
			7=>'Seventh',
			8=>'Eigth',
			9=>'Ninth',
			10=>'Tenth'
		];
		
		if(isset($words[$num])) return $words[$num];
		else return $num."th";
	}
}

/* End of file num_string_helper.php */
/* Location: ./system/helpers/error_helper.php */
