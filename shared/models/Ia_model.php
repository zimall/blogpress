<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Ia_model extends CI_Model
	{
		private $ana;

		function __construct()
		{
			parent::__construct();
			$this->load->library('user_agent');
			$this->ana = $this->load->database('analytics', TRUE);
			$this->load->helper('date');
		}

		function start($beacon)
		{
			$user_agent = $this->agent->agent_string();
			$ip = $this->input->ip_address();
			$hash = $user_agent ? md5($user_agent) : md5($ip);
			$visitor = $this->get_visitor($hash);
			if($visitor){
				$data = [ 'vs_last_updated'=>mysql_date() ];
				$this->ana->where('vs_id', $visitor['vs_id']);
				$this->ana->update( 'visitors', $data );
			}
			else{
				$visitor = [];
				$visitor['vs_id'] = $this->create_visitor($beacon,$user_agent);
			}

			$session = $this->update_session( $visitor['vs_id'], $beacon );
			$pageview = $this->update_pageview( $visitor['vs_id'], $session, $beacon );
			$response = array_merge( $beacon, [ 'visitor'=>$visitor['vs_id'], 'session'=>$session, 'pageview'=>$pageview ] );
			return $response;
		}

		function get_status($beacon){
			return !empty($beacon['state']) && in_array( $beacon['state'], ['started','loaded','ready','visible','submitted'] ) ? 1 : 0;
		}

		function update_pageview( $visitor_id, $session_id, $beacon ){
			$pageview = $beacon['state']=='started' ? false : $this->get_pageview( $visitor_id, $session_id, $beacon );
			if($pageview){
				$data = [ 'pv_last_updated'=>mysql_date() ];
				if( !empty($beacon) && ( $beacon['state']=='loaded' || $beacon['state']=='ready' ) ){
					$pageview['pv_loaded_time'] = $data['pv_loaded_time'] = mysql_date();
					$data['pv_load_duration'] = (strtotime( $pageview['pv_loaded_time'] )*1) - (strtotime( $pageview['pv_start_time'] )*1);
				}
				$data['pv_duration'] = time() - strtotime($pageview['pv_loaded_time']);
				$this->ana->where('pv_id', $pageview['pv_id']);
				$this->ana->update( 'pageviews', $data );
			}
			else{
				$pageview = [];
				$this->create_pageview($visitor_id, $session_id, $beacon);
				$pageview['pv_id'] = $this->ana->insert_id();
			}
			return $pageview['pv_id'];
		}

		function create_pageview($visitor_id, $session_id, $beacon)
		{
			$ref = empty($beacon['referrer']) ? 'direct' : $beacon['referrer'];
			$origin = $beacon['origin'];
			$url = $beacon['location'];
			$uri = str_replace( $origin, '', $url );
			$suffix = $this->config->item('url_suffix');
			$len = strlen($suffix);
			$uri_len = strlen($uri);
			if( $len>0 && substr( $uri, -$len ) === $suffix ) $uri = substr( $uri, 0, $uri_len-$len );
			$date = mysql_date();
			$data = [
				'pv_user'=>$visitor_id,
				'pv_session'=>$session_id,
				'pv_start_time'=>$date,
				'pv_loaded_time'=>$date,
				'pv_last_updated'=>$date,
				'pv_load_duration'=>0,
				'pv_duration'=>0,
				'pv_url'=>$beacon['location'],
				'pv_uri'=>$uri,
				'pv_referrer'=>$ref,
				'pv_title'=>$beacon['title']
			];
			$this->ana->insert( 'pageviews', $data );
		}

		function get_pageview($visitor_id, $session_id, $beacon){
			// get the last pageview from this user in current session
			$this->ana->where( 'pv_user', $visitor_id );
			//$this->ana->where('pv_session', $session_id );
			//$this->ana->where( 'pv_url', $beacon['location'] );
			$this->ana->order_by( 'pv_id', 'desc' );
			$this->ana->limit(1);
			$r = $this->ana->get( 'pageviews' );
			if($r->num_rows()>0){
				$pv = $r->row_array();
				if( $session_id==$pv['pv_session'] && $beacon['location']==$pv['pv_url'] ) return $pv;
			}
			return false;
		}

		function update_session( $visitor_id, $beacon ){
			$session = $this->get_session($visitor_id);
			if($session){
				$status = $this->get_status($beacon);
				$d = time() - strtotime($session['ss_created']);
				$data = [
					'ss_status'=>$status,
					'ss_last_updated'=>mysql_date(),
					'ss_duration'=>$d
				];
				$this->ana->where( 'ss_id', $session['ss_id'] );
				$this->ana->update( 'sessions', $data );
			}
			else{
				$session = [];
				$session['ss_id'] = $this->create_session( $visitor_id, $beacon );
			}
			return $session['ss_id'];
		}

		function create_session( $visitor_id, $beacon )
		{
			$ref = empty($beacon['referrer']) ? 'direct' : $beacon['referrer'];
			$data = [
				'ss_user'=>$visitor_id,
				'ss_created'=>mysql_date(),
				'ss_last_updated'=>mysql_date(),
				'ss_duration'=>0,
				'ss_ip'=>$this->input->ip_address(),
				'ss_referrer'=>$ref,
				'ss_status'=>1
			];
			$this->ana->insert( 'sessions', $data );
			return $this->ana->insert_id();
		}

		function get_session($visitor_id){
			// find session closed in the last 10 minutes or session open session in the last 1 hour
			$last_hour = mysql_date( time() - (60*60) );
			$last_ten = mysql_date( time() - (60*10) );
			$this->ana->where( 'ss_user', $visitor_id );
			$this->ana->group_start();
			$this->ana->group_start();
			$this->ana->where( 'ss_status', 1 );
			$this->ana->where( 'ss_last_updated >', $last_hour );
			$this->ana->group_end();
			$this->ana->or_group_start();
			$this->ana->where( 'ss_status', 0 );
			$this->ana->where( 'ss_last_updated >', $last_ten );
			$this->ana->group_end();
			$this->ana->group_end();
			$this->ana->order_by( 'ss_last_updated', 'desc' );
			$r = $this->ana->get('sessions');
			if($r->num_rows()>0) return $r->row_array();
			return false;
		}

		function create_visitor($beacon, $user_agent=false)
		{
			$user_agent = $user_agent ? $user_agent : $this->agent->agent_string();
			$ip = $this->input->ip_address();
			$hash = $user_agent ? md5($user_agent) : md5($ip);
			if($this->agent->is_mobile()){
				$device_type = 'mobile';
				$device = $this->agent->mobile();
			}
			elseif( $this->agent->is_robot() ){
				$device_type = 'robot';
				$device = $this->agent->robot();
			}
			else{
				$device_type = 'desktop';
				$device = 'desktop';
			}
			$language = $this->agent->languages();
			$language = is_array($language)?$language:[];
			$ref = empty($beacon['referrer']) ? 'direct' : $beacon['referrer'];
			$visitor = [
				'vs_hashkey'=>$hash,
				'vs_user_agent'=>$user_agent?$user_agent:$ip,
				'vs_browser'=>$this->agent->browser(),
				'vs_browser_ver'=>$this->agent->version(),
				'vs_device_type'=>$device_type,
				'vs_device_name'=>$device,
				'vs_os'=>$this->agent->platform(),
				'vs_language'=>implode( ',', $language ),
				'vs_referrer'=>$ref
			];
			$this->ana->insert( 'visitors', $visitor );
			return $this->ana->insert_id();
		}

		function get_visitor($hash){
			$this->ana->where( 'vs_hashkey', $hash );
			$r = $this->ana->get('visitors');
			if($r->num_rows()>0) return $r->row_array();
			return false;
		}
	}
?>
