<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PC
{

	private $admin_debug = TRUE;
	private $theme = 'radmin';
	private $theme_config = array();

	function __construct()
	{
		$this->ci = &get_instance();
		$this->theme = $this->ci->config->item('admin_theme');
		$this->admin_debug = $this->ci->session->userdata( 'admin_debug' );
		$this->ci->load->config( $this->theme.'_theme' );
		$this->theme_config = $this->ci->config->item('theme');

		$this->ci->load->library('carabiner');
		$carabiner_config = array(
			'script_dir' => 'scripts/',
			'style_dir'  => APPNAME.'/views/'.$this->theme.'/css/',
			'cache_dir'  => APPNAME.'/asset_cache/',
			'combine'    => TRUE,
			'dev'        => ENVIRONMENT=='development'?true:false
		);
		$this->ci->carabiner->config($carabiner_config);

		$this->styles = $this->theme_config['styles'];
		// add a css file
		foreach( $this->styles as $s )
		{
			if( is_array($s) )
				$this->ci->carabiner->css( $s['href'], $s['media'] );
			else $this->ci->carabiner->css("$s.css");
		}

		$this->scripts = array( 'jquery-1.9.1.min' );
		//@ sourceMappingURL=jquery.min.map

		foreach( $this->scripts as $s )
		{
			$this->ci->carabiner->js("$s.js");
		}

		$this->theme_scripts = $this->theme_config['scripts'];

		$this->ci->sc = new Carabiner();
		$sc_config = array(
			'script_dir'  => APPNAME.'/views/'.$this->theme.'/js/',
			'cache_dir'  => APPNAME.'/asset_cache/',
			'combine'    => TRUE,
			'dev'        => ENVIRONMENT=='development'?true:false
		);
		$this->ci->sc->config($sc_config);

		foreach( $this->theme_scripts as $s )
		{
			$this->ci->sc->js("$s.js");
		}
		$this->theme_scripts = array();

		$this->ci->data = array(
			'theme'=>$this->theme, 'section' => 'login', 'error_msg'=>'',
			'theme_scripts'=>$this->theme_scripts, 'views'=>APPNAME."/views/{$this->theme}/"
		);
		$this->ci->data['menu'] = $this->get_menu();
	}

	public function get_menu(){
		$this->ci->load->model('pages_model');
		return $this->ci->pages_model->get_pages(['select'=>'sc_name as title, sc_value as segment', 'where'=>['sc_enabled'=>1,'sc_parent'=>0]]);
	}

	public function debug_status()
	{
		if( $this->admin_debug ) return 'Disable Debug Mode';
		else return 'Enable Debug Mode';
	}

	public function required_content()
	{
		if( $this->ci->flexi_auth->is_privileged('Run Debug Mode') )
		{
			if( $this->ci->input->get('toggle_debug_mode') )
			{
				$this->ci->session->set_userdata( 'admin_debug', !$this->admin_debug );
				redirect( current_url() );
			}
			
			$ds = $this->admin_debug?TRUE:FALSE;
			$this->ci->output->enable_profiler( $ds );
		}
		elseif( $this->ci->input->get('toggle_debug_mode') )
		{
			$this->ci->session->set_userdata( 'admin_debug', FALSE );
			sem('You have no permission to run debug mode.');
			redirect( current_url() );
		}
		else $this->ci->output->enable_profiler(FALSE);
		return NULL;
	}

	public function record_count( $args=array() )
	{	
		if(! isset($args['table']) ) return 0;
		if(! isset($args['or_where']) ) $args['or_where'] = FALSE;
		if(! isset($args['where']) ) $args['where'] = FALSE;
		if(! isset($args['like']) ) $args['like'] = FALSE;
		
		$this->ci->db->from($args['table']);
		
		if($args['where'])
			$this->ci->db->where( $args['where'] );
		if($args['or_where'])
			$this->ci->db->or_where( $args['or_where'] );

		if($args['like'])
		{
			$like = $args['like'];
			foreach($like as $k=>$v)
			{
				if( is_array($v) )
					$this->ci->db->like($k, $v['value'], $v['wc']);
				else $this->ci->db->like($k,$v);
			}
		}
		return $this->ci->db->count_all_results();
    }


	/***************** PAGINATION FUNCTION *******************/

	/**
	 * configures the pagination parameters for the current page
	 *
	 * @param array $count set arguments for retrieving records from database
	 * @param string $function the function used for counting records from database
	 * @param string $model the model containg the counting function above
	 *
	 * @return array containing start position and the record limit per page
	 */

	public function paginate( $count, $function='record_count', $model=FALSE )
	{
		$segments = $this->get_segments();
		//sem(print_r($segments,TRUE));
		$count['count'] = TRUE;
		$this->ci->load->library('pagination');
		//$page = $this->ci->uri->uri_to_assoc($page_pos-1);
		
		$config = $this->ci->config->item('pagination');
		
		//$config['page_query_string'] = TRUE;
		$config['uri_segment'] = $segments['page_pos'];
		//sem("page pos: {$config['uri_segment']} => ".$this->ci->uri->segment($config['uri_segment']),0);
		$config['base_url'] = site_url().'/'.$segments['args'].'/page';
		
		if( $model===FALSE && $function=='record_count' )
			$config['total_rows'] = $this->$function( $count );
		else
			$config['total_rows'] = $this->ci->$model->$function( $count );
		
		$config['per_page'] = $this->ci->data['per'];
		
		//$this->ci->$db->limit($config['per_page']);
		$this->ci->pagination->initialize($config);
		
		if( $segments['page'] ) $start = $segments['page'];
		else $start = 0;
		$this->ci->data['total_rows'] = $config['total_rows'];
		if( $config['total_rows']>0 )
			$this->ci->data['start'] = $start+1;
		else $this->ci->data['start'] = $start;
		
		if($start==0 && $config['total_rows'] <= $config['per_page'])
			$this->ci->data['current_rows'] = $config['total_rows'];
		elseif( $start>0 && ( $start+$config['per_page'] )<$config['total_rows'] )	
			$this->ci->data['current_rows'] = $start+$config['per_page'];
		elseif( $start>0 && ( $start+$config['per_page'] )>$config['total_rows'] )
			$this->ci->data['current_rows'] = $config['total_rows'];
		else $this->ci->data['current_rows'] = $config['per_page'];
		
		return array( 'start'=>$start, 'limit'=>$config['per_page'] );
	}


	/**
	 * configures page display options: sets page limit, sorts page data and filters page data.
	 * @param string $table 
	 * @param string $filter_settings contains jason encoded rules used for selecting records in database 
	 */
	
	public function page_control($table, $default_per=10, $default_sort=false, $filter_settings=false)
	{
		$p = $changed = $this->ci->input->post('per_page');
		if(!$p) $p = $this->ci->input->cookie($this->ci->config->item('cookie_prefix').$table.'_per_page');
		$per = $p && is_numeric($p) ? $p : $default_per;

		$cookie = array(
			'name'   => $table.'_per_page',
			'value'  => $per,
			'expire' => '60000',
			'secure' => FALSE
		);
		if( ! $this->ci->input->set_cookie($cookie) ) log_message( 'warning', 'Unable to set pagination cookie' );
		$this->ci->data['per'] = $per;

		/************* SORT ********************/
		$sort = $this->ci->input->post('change_page_sort');
		if( is_null($sort) || $sort===FALSE )
		{
			$sort = $this->ci->input->cookie(config('cookie_prefix').$table.'_asort');
			//echo '<br>problem here';
		}
		if( $sort===FALSE || is_null($sort) ) $sort = $default_sort ?: config('default_sort_by');

		$cookie = [ 'name'   => $table.'_asort', 'value'  => $sort, 'expire' => '60000', 'secure' => FALSE ];
		$this->ci->input->set_cookie($cookie);
		$this->ci->data['sort_id'] = $sort;
		$sorts = config('default_sort_fields');
		if(isset($sorts[$sort])) $this->ci->data['sort'] = "{$sorts[$sort]['f']} {$sorts[$sort]['s']}";
		else $this->ci->data['sort'] = FALSE;
		
		/*********** FILTERS ***********/
		
		
		if($this->ci->input->post('form_type')=="filter" )
		{
			if( $filter_settings || isset($this->ci->data['filter_settings']) )
			{
				if( $filter_settings )
					$filter = json_decode( $filter_settings );
					
				elseif( isset($this->ci->data['filter_settings']) )
					$filter = json_decode( $this->ci->data['filter_settings'] );
					
				else $filter = array();
				
				$options = (array)$filter;
				foreach( $options as $k=>$v )
				{
					$options[$k]->v = $this->ci->input->post($k);	
				}
					
				$section = array('name'=>$this->ci->input->post('form_name'), 'data'=>$options );
				$this->ci->session->set_userdata('filter', $section );
				redirect( current_url() );
			}
			else
			{
				$this->ci->data['error'] = array('error'=>'bad', 'error_msg'=>'filter settings not found');
			}
		}
		
		
		$clear = $this->ci->input->get('clear_filter');
		if($clear){
			$this->ci->session->unset_userdata('filter');
			redirect(current_url());
		}
		
		$filter = $this->ci->session->userdata('filter');
		if($filter)
		{
			if( isset($filter['name']) && isset($filter['data']) ){
				if($filter['name']==$table){
					$data = $filter['data'];
					$this->ci->data['filter'] = $data;
					//print_r($data);
				}
				else $this->ci->session->unset_userdata('filter');
			}
		}
		
		if( $this->ci->input->post('per_page') || $this->ci->input->post('change_page_sort') ) {
			$c = full_url();
			$c = preg_replace( "/page\/\d*/", '', $c );
			$c = str_replace( '/.', '.', $c );
			redirect($c);
		}
	}

	/**
	 * extracts data from URI for pagination and filtering options
	 * @return array with URI segments 
	 */
	public function get_segments()
	{
		$ip = $this->ci->config->item('index_page');
		$url = $ip.'/'.uri_string();
		//sem("uri_string = $url",0);
		$data = array( 'segment'=>$url, 'args'=>FALSE, 'page_pos'=>4 );
		$keys = array(); $values = array();
		$assoc = array( 'page'=>FALSE, 'view'=>FALSE, 'filter'=>FALSE, 'name'=>FALSE, 'parent'=>FALSE );
		$pos = strpos( $url, '/_' )+1;
		$posp = strpos( $url.'/', '/page/' )+1;
		if( ($pos !== FALSE && $pos>3) )
		{
			$argstring = substr( $url, $pos+1 );		//get string of arguments
			//echo 'args string: '.$argstring.'<br>';
			$args = explode( '/', $argstring );
			//print_r($args);
			$l = count($args);
			if($l>0){
				foreach( $args as $i=>$k ){
					if( ($i%2)==0 )		//its a key
						$keys[] = $k;
					elseif( ($i%2)==1 )	//its a value
						$values[] = $k;
				}
				foreach( $keys as $i=>$v ){
					if(!isset($values[$i]))
						$values[$i] = FALSE;
					$assoc[$v] = $values[$i];
				}
				
			}
			
			$data['segment'] = substr_replace( $url, '', $pos-1  );
			
			$pag = strpos($url.'/', '/page/');
			
			if( $pag!==FALSE & $pag>0 ){
				$data['args'] = substr_replace( $url, '', $pag );
				$allargs = explode( '/', $url );
				$n = count($allargs);
				$data['page_pos'] = $n;
			}
			else $data['args'] = $url;
		}
		elseif( ($posp !== FALSE && $posp>3) )
		{
			//$segment = $data['segment'];
			
			
			$argstring = substr( $url, $posp );		//get string of arguments
			//echo 'args string: '.$argstring.'<br>';
			$args = explode( '/', $argstring );
			//print_r($args);
			$l = count($args);
			if($l>0){
				foreach( $args as $i=>$k ){
					if( ($i%2)==0 )		//its a key
						$keys[] = $k;
					elseif( ($i%2)==1 )	//its a value
						$values[] = $k;
				}
				foreach( $keys as $i=>$v ){
					if(!isset($values[$i]))
						$values[$i] = FALSE;
					$assoc[$v] = $values[$i];
				}
				
			}
			$pag = strpos($url.'/', '/page/');
			
			if( $pag!==FALSE & $pag>0 ){
				$data['args'] = substr_replace( $url, '', $pag );
				$allargs = explode( '/', $url );
				$n = count($allargs);
				$data['page_pos'] = $n;
			}
			else $data['args'] = $url;
			$data['segment'] = substr_replace( $url, '', $posp-1  );
		}
		
		$data['args'] = str_replace( "$ip/", "", $data['args'] );
		$data['segment'] = str_replace( "$ip/", "", $data['segment'] );
		
		if( strlen($data['args'])<2 ) $data['args'] = $data['segment'];
		
		$merged = array_merge($data, $assoc);
		return $merged;
	}
}
