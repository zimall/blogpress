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
			$this->load->helper('date');
		}

		function summary( $start, $end ){
			$data = ['visitors'=>0, 'new_visitors'=>0,
				'pageviews'=>['total'=>0,'average_load_duration'=>0, 'min_load_duration'=>0, 'max_load_duration'=>0],
				'sessions'=>[ 'total'=>0, 'average_duration'=>0, 'bounce_rate', 'pageviews'=>0 ],
				'top10pages'=>[], 'devices'=>[]
			];
			//Total Visits
			$this->ana->where( 'vs_last_updated >=', $start );
			$this->ana->where( 'vs_last_updated <=', $end );
			$data['visitors'] = $this->ana->count_all('visitors');

			// New Visits
			$this->ana->where( 'vs_date_created >=', $start );
			$this->ana->where( 'vs_date_created <=', $end );
			$data['new_visitors'] = $this->ana->count_all('visitors');

			//Device Visits
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
					$data['devices'][$k] =$v['total'];
				}
			}

			//Pageviews
			$this->ana->where( 'pv_start_time >=', $start );
			$this->ana->where( 'pv_start_time <=', $end );
			$this->ana->select_avg('pv_load_duration', 'average_load_duration');
			$this->ana->select_min('pv_load_duration', 'min_load_duration');
			$this->ana->select_max('pv_load_duration', 'max_load_duration');
			$r = $this->ana->get('pageviews');
			if( $r->num_rows()==1 ){
				$data['pageviews'] = $r->row_array();
				$this->ana->where( 'pv_start_time >=', $start );
				$this->ana->where( 'pv_start_time <=', $end );
				$data['pageviews']['total'] = $this->ana->count_all_results('pageviews');
			}

			//Sessions
			$this->ana->where( 'ss_created >=', $start );
			$this->ana->where( 'ss_created <=', $end );
			$this->ana->select_avg('ss_duration');
			$r = $this->ana->get('sessions');
			if( $r->num_rows()==1 ){
				$data['sessions']['average_duration'] = number_format( $r->row()->ss_duration, 3, '.', '' );
				$this->ana->where( 'ss_created >=', $start );
				$this->ana->where( 'ss_created <=', $end );
				$data['sessions']['total'] = $total_sessions = $this->ana->count_all_results('sessions') * 1;

				// Bounce rate = short_sessions / total
				$this->ana->where( 'ss_created >=', $start );
				$this->ana->where( 'ss_created <=', $end );
				//$this->ana->where( 'ss_duration <=', 10 );  // sessions less than 10 seconds
				$pv = $this->ana->dbprefix('pageviews');
				$this->ana->where( "( SELECT COUNT(pv_id) FROM {$pv} WHERE `pv_session` = `ss_id` )=1", null, false );
				$bounces = $this->ana->count_all_results('sessions')*1;
				$data['sessions']['bounce_rate'] = $bounces && $total_sessions ?  $bounces / $total_sessions : 0;
				$data['sessions']['bounces'] = $bounces;

				// average pageviews per session
				$data['sessions']['pageviews'] = $total_sessions ? floor($data['pageviews']['total'] / $total_sessions) : 0;
			}

			// top 5 articles
			$this->ana->where( 'pv_start_time >=', $start );
			$this->ana->where( 'pv_start_time <=', $end );
			$this->ana->select( "*, count(pv_id) as viewed", null, false );
			$this->ana->select_avg( 'pv_duration' );
			$this->ana->group_by( 'pv_uri' );
			$this->ana->order_by('viewed', 'desc');
			$this->ana->order_by('pv_duration', 'desc');
			$this->ana->limit(10);
			$r = $this->ana->get('pageviews');
			if($r->num_rows()>0){
				$data['top10pages'] = $r->result_array();
			}

			return $data;
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

	}
?>
