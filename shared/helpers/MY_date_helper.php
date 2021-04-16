<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	// ------------------------------------------------------------------------

	if ( ! function_exists('mysql_date'))
	{
		function mysql_date($time=FALSE)
		{
			return $time ? date( 'Y-m-d H:i:s', $time ) : date( 'Y-m-d H:i:s' );
		}
	}

	if(!function_exists('pretty_time'))
	{
		function pretty_time($timestamp, $format='clock'){
			$time = '';
			$timestamp *= 1;
			// days: 1 day = 60*60*24 = 86400
			if($timestamp >= 86400){
				$days = floor( $timestamp/86400 );
				$timestamp = $timestamp%86400;
				$time = $days==1 ? "1 Day " : "$days Days ";
			}

			// hours: 1 hour = 60*60 = 3600
			if($timestamp >= 3600 ){
				$hours = floor($timestamp/3600);
				$timestamp = $timestamp%3600;
				if($hours>0) {
					$time .= $format == 'clock' ? str_pad("$hours", 2, '0', STR_PAD_LEFT) . ':' : "{$hours}h ";
				}
				else{
					$time .= $format == 'clock' ? str_pad("$hours", 2, '0', STR_PAD_LEFT) . ':' : "";
				}
			}
			else $time .= $format=='clock' ?  "00:" : '';

			// minutes: 1 minute = 60*1 = 60
			if($timestamp >= 60 ){
				$minutes = floor($timestamp/60);
				$timestamp = $timestamp%60;
				if($minutes>0) {
					$time .= $format == 'clock' ? str_pad("$minutes", 2, '0', STR_PAD_LEFT) . ':' : "{$minutes}m ";
				}
				else{
					$time .= $format == 'clock' ? str_pad("$minutes", 2, '0', STR_PAD_LEFT) . ':' : "";
				}
			}
			else $time .= $format=='clock' ?  "00:" : '';

			// seconds
			$seconds = number_format( $timestamp, 0 );
			if($timestamp < 10) {
				if( $timestamp < 1 ){
					$ms = $timestamp*1000;
					$ms = round($ms, 0);
					if($ms>0) {
						$time .= $format == 'clock' ? "00 ({$ms}ms)" : "{$ms}ms";
					}
					else{
						$time .= $format == 'clock' ? "00 ({$ms}ms)" :( $time ? '' : "{$ms}ms");
					}
				}
				else{
					$time .= $format == 'clock' ? "0{$seconds}" : "{$seconds}s";
				}
			}
			else $time .= $format == 'clock' ? "{$seconds}" : "{$seconds}s";
			return $time;
		}
	}

	if(!function_exists('pretty_date')){
		function pretty_date( $date=false, $format='d M Y, H:i' ){
			if(!$date) $date = mysql_date();
			$d = new DateTime($date);
			return $d->format($format);
		}
	}

	/* End of file MY_date_helper.php */
	/* Location: ./shared/helpers/MY_date_helper.php */