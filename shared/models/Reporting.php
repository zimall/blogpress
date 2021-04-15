<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	ini_set('display_errors', 1);
	error_reporting(E_ERROR|E_WARNING);
	class Reporting extends CI_Model
	{
		private $ana;

		function __construct()
		{
			parent::__construct();
			$this->load->library('user_agent');
			$this->ana = $this->load->database('analytics', TRUE);
			$CI =& get_instance();
			$CI->ana =& $this->ana;
			$this->load->helper('date');
		}

		function summary( $start, $end ){
			$data = ['visitors'=>0, 'new_visitors'=>0,
				'pageviews'=>['total'=>0,'average_load_duration'=>0, 'min_load_duration'=>0, 'max_load_duration'=>0],
				'sessions'=>[ 'total'=>0, 'average_duration'=>0, 'bounce_rate', 'pageviews'=>0 ],
				'top_pages'=>[], 'devices'=>[]
			];
			//Total Visits
			$data['visitors'] = $this->get_total_visitors($start,$end);

			// New Visits
			$data['new_visitors'] = $this->get_new_visitors($start,$end);

			//Device Visits
			$data['devices'] = $this->get_devices($start,$end);

			//Pageviews
			$data['pageviews'] = $this->get_page_views($start,$end);

			//Sessions
			$data['sessions'] = $this->get_sessions($start,$end, $data['pageviews']['total'] );

			// Top traffic sources
			$data['sources'] = $this->get_traffic_sources($start,$end,true);

			// top 10 articles
			$data['top_pages'] = $this->get_top_pages($start,$end,10);

			return $data;
		}

		function get_traffic_sources($start,$end,$summarize=false){
			$hosts = [];
			$other = 0;
			$this->ana->where( 'ss_created >=', $start );
			$this->ana->where( 'ss_created <=', $end );
			$this->ana->select( 'ss_host as host, count(ss_host) as sessions' );
			$this->ana->group_by('host');
			$this->ana->order_by('sessions desc');
			$r = $this->ana->get('sessions');
			if( $r && $r->num_rows()>0 ){
				$hosts = $r->result_array();
				if($summarize) {
					$total = array_reduce($hosts, function($t, $h) {
						$t += $h['sessions'];
						return $t;
					});
					$total = $total ? $total : 1;
					foreach($hosts as $k => $v) {
						$p = ($v['sessions'] / $total) * 100;
						if($p < 2.5) {
							$other += ($v['sessions'] * 1);
							unset( $hosts[$k] );
						}
					}
					if($other>0) $hosts[] = [ 'host'=>'other', 'sessions'=>$other ];
				}
			}
			return $hosts;
		}

		function get_new_visitors($start,$end){
			$this->ana->where( 'vs_date_created >=', $start );
			$this->ana->where( 'vs_date_created <=', $end );
			return $this->ana->count_all_results('visitors');
		}

		function get_total_visitors($start,$end){
			$this->ana->where( 'vs_last_updated >=', $start );
			$this->ana->where( 'vs_last_updated <=', $end );
			return $this->ana->count_all_results('visitors');
		}

		function get_devices($start,$end){
			$devices = [];
			$this->ana->select('count(vs_id) as total, vs_device_type as device', null, false);
			$this->ana->where( 'vs_last_updated >=', $start );
			$this->ana->where( 'vs_last_updated <=', $end );
			$this->ana->group_by('device');
			$this->ana->order_by('device');
			$r = $this->ana->get('visitors');
			if($r->num_rows()>0){
				$d = $r->result_array();
				foreach($d as $v){
					$k = $v['device'] ? $v['device'] : 'other';
					$devices[$k] =$v['total'];
				}
			}
			return $devices;
		}

		function get_page_views($start,$end){
			$pageviews = ['total'=>0,'average_load_duration'=>0, 'min_load_duration'=>0, 'max_load_duration'=>0];
			$this->ana->where( 'pv_start_time >=', $start );
			$this->ana->where( 'pv_start_time <=', $end );
			$this->ana->select_avg('pv_load_duration', 'average_load_duration');
			$this->ana->select_min('pv_load_duration', 'min_load_duration');
			$this->ana->select_max('pv_load_duration', 'max_load_duration');
			$r = $this->ana->get('pageviews');
			if( $r->num_rows()==1 ){
				$pageviews = $r->row_array();
				$this->ana->where( 'pv_start_time >=', $start );
				$this->ana->where( 'pv_start_time <=', $end );
				$pageviews['total'] = $this->ana->count_all_results('pageviews');
			}
			return $pageviews;
		}

		function  get_top_pages($start,$end,$limit=10){
			$top = [];
			$this->ana->where( 'pv_start_time >=', $start );
			$this->ana->where( 'pv_start_time <=', $end );
			$this->ana->select( "*, count(pv_id) as viewed", null, false );
			$this->ana->select_avg( 'pv_duration' );
			$this->ana->select_avg('pv_load_duration', 'average_load_duration');
			$this->ana->group_by( 'pv_uri' );
			$this->ana->order_by('viewed', 'desc');
			$this->ana->order_by('pv_duration', 'desc');
			$this->ana->limit(10);
			$r = $this->ana->get('pageviews');
			if($r->num_rows()>0){
				$top = $r->result_array();
			}
			return $top;
		}

		function get_sessions($start,$end, $pageviews=0){
			$sessions = [ 'total'=>0, 'average_duration'=>0, 'bounce_rate', 'pageviews'=>0 ];
			$this->ana->where( 'ss_created >=', $start );
			$this->ana->where( 'ss_created <=', $end );
			$this->ana->select_avg('ss_duration');
			$r = $this->ana->get('sessions');
			if( $r->num_rows()==1 ){
				$sessions['average_duration'] = number_format( $r->row()->ss_duration, 3, '.', '' );
				$this->ana->where( 'ss_created >=', $start );
				$this->ana->where( 'ss_created <=', $end );
				$sessions['total'] = $total_sessions = $this->ana->count_all_results('sessions') * 1;

				// Bounce rate = short_sessions / total
				$this->ana->where( 'ss_created >=', $start );
				$this->ana->where( 'ss_created <=', $end );
				//$this->ana->where( 'ss_duration <=', 10 );  // sessions less than 10 seconds
				$pv = $this->ana->dbprefix('pageviews');
				$this->ana->where( "( SELECT COUNT(pv_id) FROM {$pv} WHERE `pv_session` = `ss_id` )=1", null, false );
				$bounces = $this->ana->count_all_results('sessions')*1;
				$sessions['bounce_rate'] = $bounces && $total_sessions ?  $bounces / $total_sessions : 0;
				$sessions['bounces'] = $bounces;

				// average pageviews per session
				$sessions['pageviews'] = $total_sessions ? floor($pageviews / $total_sessions) : 0;
			}
			return $sessions;
		}
		
		function top_articles( $start, $end, $args=[] )
		{
			$this->ana->where( 'pv_start_time >=', $start );
			$this->ana->where( 'pv_start_time <=', $end );
			$this->ana->where( "pv_uri REGEXP '^\/[a-zA-Z\-_]+\/[0-9]+\/?'", null, false );
			$this->ana->select( "*, count(pv_id) as viewed", null, false );
			$this->ana->select_avg( 'pv_duration' );
			$this->ana->group_by( 'pv_uri' );
			$this->ana->order_by('viewed', 'desc');
			$this->ana->order_by('pv_duration', 'desc');
			if( isset($args['limit']) && $args['limit'] ) $this->ana->limit( $args['limit'] );
			$r = $this->ana->get('pageviews');
			$ids = [];
			$u = [];
			if($r->num_rows()>0){
				$data = $r->result_array();
				$pattern = "/\/[a-zA-Z\-_]+\/(\d+).*/";
				foreach($data as $k=>$v){
					$uri = $v['pv_uri'];
					$u[$k] = $uri;
					$matches = [];
					preg_match( $pattern, $uri, $matches );
					if(isset($matches[1])) $ids[$k] = $matches[1];
				}
			}
			return $ids;
		}

		function fix_ss_hosts($host='other', $exact=true){
			$n = 0;
			$unknown = [];
			$this->ana->select('ss_id, ss_referrer');
			if($host=='other') $this->ana->where('ss_host IS NULL');
			else{
				$exact ? $this->ana->where('ss_host',$host) : $this->ana->like( 'ss_host', $host );
			}
			$this->ana->where('ss_referrer !=', 'direct');
			$r = $this->ana->get('sessions');
			if($r->num_rows()>0){
				$data = $r->result_array();
				foreach($data as $k=>$v){
					$parts = parse_url($v['ss_referrer']);
					$new_host = empty($parts['host']) ? 'unknown' : $parts['host'];
					if($new_host=='unknown') $unknown[$v['ss_referrer']] = $parts;
					if(strlen($new_host)>0) {
						$new_host = preg_replace("/^[a-z0-9]{32}\./", '', $new_host);
					}
					$this->ana->where('ss_id', $v['ss_id']);
					$this->ana->update( 'sessions', ['ss_host'=>$new_host] );
					$n++;
				}
			}
			$this->ana->where('ss_referrer','direct');
			$this->ana->where('ss_host IS NULL');
			$this->ana->update( 'sessions', ['ss_host'=>'direct'] );
			$m = $this->ana->affected_rows();

			return [ 'direct'=>$m, 'other'=>$n, 'unknown'=>$unknown ];
		}

	}
?>
