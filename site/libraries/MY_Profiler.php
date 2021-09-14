<?php

	class MY_Profiler extends CI_Profiler
	{

		/**
		 * List of profiler sections available to show
		 *
		 * @var array
		 */
		protected $_available_sections = array(
			'benchmarks',
			'uri_string',
			'controller_info',
			'views',
			'get',
			'post',
			'queries',
			'memory_usage',
			'http_headers',
			'session_data',
			'config'
		);


		/*
	 * $_ci_CI =& get_instance();
		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
		{
			if ( ! isset($this->$_ci_key))
			{
				$this->$_ci_key =& $_ci_CI->$_ci_key;
			}
		}
	 */

		// --------------------------------------------------------------------

		/**
		 * Compile $_GET Data
		 *
		 * @return	string
		 */
		protected function _compile_views()
		{
			$_ci_CI =& get_instance();
			$views = $_ci_CI->_loaded_views??[];
			$output = "\n\n"
				.'<fieldset id="ci_profiler_views" style="border:1px solid #cd6e00;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
				."\n"
				.'<legend style="color:#cd6e00;">&nbsp;&nbsp;'."Loaded Views&nbsp;&nbsp;</legend>\n";

			if (count($views) === 0)
			{
				$output .= '<div style="color:#cd6e00;font-weight:normal;padding:4px 0 4px 0;">No views loaded</div>';
			}
			else
			{
				$output .= "\n\n<table style=\"width:100%;border:none;\">\n";
				foreach ($views as $key => $val)
				{
					is_int($key) OR $key = "'".htmlspecialchars($key, ENT_QUOTES, config_item('charset'))."'";
					$val = (is_array($val) OR is_object($val))
						? '<pre>'.htmlspecialchars(print_r($val, TRUE), ENT_QUOTES, config_item('charset')).'</pre>'
						: htmlspecialchars($val, ENT_QUOTES, config_item('charset'));

					$output .= '<tr><td style="color:#000;background-color:#ddd;padding:5px;">'
						.($key+1).'.&nbsp;&nbsp; </td><td style="padding:5px;color:#cd6e00;font-weight:normal;background-color:#ddd;">'
						.$val."</td></tr>\n";
				}

				$output .= "</table>\n";
			}

			return $output.'</fieldset>';
		}

	}
