<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_Model extends CI_Model
{
	public function general_settings()
	{
		$settings = $this->input->post();
		if( isset($settings['form_name']) ) unset($settings['form_name']);
		if( isset($settings['form_type']) ) unset($settings['form_type']);
		if( isset($settings['general']) ) unset($settings['general']);
		$keys = array();
		foreach( $settings as $k=>$v )
		{
			$keys['$config["'.$k.'"]'] = $v;
		}
		$set = '';
		$general = SHAREDPATH."config/general.php";
		
		$myfile = fopen($general, "r+");
		while(!feof($myfile))
		{
			$setting = fgets($myfile);
		    $line = explode(' = ',$setting);
		
			// take-off old "\r\n"
			if( isset($line[0]) ) $key = trim($line[0]);
			else $key = FALSE;

			// search for keys/value pairs to set
			if( $key && isset( $keys[$key] ) )
				$set .= $key.' = "'.$keys[$key].'";'."\n";
			else $set .= $setting;
		}
		
		// using file_put_contents() instead of fwrite()
		$e = file_put_contents( $general, $set);
		fclose($myfile);

		if( $settings['site-name'] != $this->config->item('site-name') || $settings['site-email']!=$this->config->item('site-email') )
		{
			// flexi_auth.php
			$set = '';
			$flexi_auth = SHAREDPATH."config/flexi_auth.php";
			$myfile = fopen($flexi_auth, "r+");
			while(!feof($myfile))
			{
				$setting = fgets($myfile);
			    $line = explode(' = ',$setting);
	
				// take-off old "\r\n"
				if( isset($line[0]) ) $key = trim($line[0]);
				else $key = FALSE;

				// search for keys/value pairs to set
				if( $key && $key == '$config["email"]["site_title"]' )
					$set .= $key.' = "'.$settings['site-name'].'";'."\n";
				elseif( $key && $key == '$config["email"]["reply_email"]' )
					$set .= $key.' = "'.$settings['site-email'].'";'."\n";
	
				else $set .= $setting;
			}
			// using file_put_contents() instead of fwrite()
			$e = file_put_contents( $flexi_auth, $set);
			fclose($myfile);
		}
		if( $e!==FALSE ) return array( 'error'=>FALSE, 'error_msg'=>'Settings updated successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'Could not update settings' );
	}

	public function theme_settings()
	{
		$settings = $this->input->post();
		$source = $settings['form_name'];
		$name = $settings['site_theme'];
		$file = "{$name}_theme.php";
		
		unset($settings['form_name']);
		unset($settings['form_type']);
		unset($settings['site_theme']);

		if(isset($settings['activate_theme']))
		{
			unset($settings['activate_theme']);
			$e = $this->set_theme(['site_theme'=>$name]);
			sem($e);
		}
		//$p = print_r($settings,TRUE);

		

		$keys = array();
		foreach( $settings as $k=>$v )
		{
			if(is_array($v))
			{
				$array = "[";
				foreach ($v as $key => $value) {
					$array .= "'{$key}'=>'{$value}',";
				}
				$array = rtrim( $array, ',' )."];\n";
				$keys['$theme_config[\''.$k.'\']'] = $array;
			}
			else $keys['$theme_config[\''.$k.'\']'] = "'".$v."';\n";
		}
		//sem( print_r($keys,TRUE) );
		//return array( 'error'=>FALSE, 'error_msg'=>'its done' );
		$set = '';
		$general = $source."/views/{$name}/{$file}";
		
		$myfile = fopen($general, "r+");
		while(!feof($myfile))
		{
			$setting = fgets($myfile);
		    $line = explode(' = ',$setting);
		
			// take-off old "\r\n"
			if( isset($line[0]) ) $key = trim($line[0]);
			else $key = FALSE;

			// search for keys/value pairs to set
			if( $key && isset( $keys[$key] ) )
				$set .= $key.' = '.$keys[$key];
			else
			{
				//sem("{$key} not found");
				$set .= $setting;
			}
		}
		// using file_put_contents() instead of fwrite()
		$e = file_put_contents( $general, $set);
		fclose($myfile);

		if( $e!==FALSE ) return array( 'error'=>FALSE, 'error_msg'=>'Theme Settings updated successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'Could not update theme settings' );
	}

	public function load_themes($source=APPPATH)
	{
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
		$dir = "$source/views/";
		$files = scandir($dir);
		//sem( 'Admin Views: '.print_r($files,TRUE) );
		$themes = array();
		foreach( $files as $f )
		{
			if( $f!='.' && $f!='..' && is_dir( $dir.$f ) )
			{
				$t = "{$dir}{$f}/{$f}_theme.php";
				if( file_exists($t) ) $themes[$f] = $this->load_theme( $f, $source );
			}
			//else sem("{$dir}{$f} is not a valid theme");
		}
		return $themes;
	}

	public function load_theme($name=FALSE,$source=APPPATH)
	{
		return $this->load->theme( $name, $source );
	}

	public function get_banners($args=array())
	{
		if(! isset($args['table']) ) $args['table'] = 'banners';
		if(! isset($args['select']) ) $args['select'] = FALSE;
		if(! isset($args['or_where']) ) $args['or_where'] = FALSE;
		if(! isset($args['where']) ) $args['where'] = FALSE;
		if(! isset($args['enabled']) ) $args['enabled'] = 'n/a';
		if(! isset($args['like']) ) $args['like'] = FALSE;
		if(! isset($args['one']) ) $args['one'] = FALSE;
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['sort']) ) $args['sort'] = FALSE;
		if(! isset($args['filter']) ) $args['filter'] = FALSE;
		
		$this->db->from($args['table']);
		if($args['limit'])
			$this->db->limit($args['limit'], $args['start']);
		if($args['select'])
			$this->db->select($args['select']);
		else
		{
			$this->db->select('banners.*, articles.at_id, articles.at_segment, articles.at_permalink, articles.at_section, 
				sections.sc_value');
			$this->db->join('articles', 'banners.bn_at_id=articles.at_id', 'left');
			$this->db->join('sections', 'sections.sc_id = articles.at_section', 'left');
		}
		
		//$this->db->where( 'articles.enabled', $args['enabled'] );
		
		//if( !$this->flexi_auth->is_privileged('View Private Content') )
		//	$this->db->where( 'articles.at_private', 0);
		
		if($args['where'])
			$this->db->where($args['where']);
		if($args['or_where'])
			$this->db->or_where($args['or_where']);
		
		if($args['id'])
			$this->db->where( 'banners.bn_id', $args['id']);
		if( is_numeric($args['enabled']) )
			$this->db->where( 'banners.bn_enabled', $args['enabled']);
		
		if($args['like'])
		{
			$like = $args['like'];
			foreach($like as $k=>$v)
			{
				if( is_array($v) )
					$this->db->like($k, $v['value'], $v['wc']);
				else $this->db->like($k,$v);
			}
		}
		
		if($args['filter'])
		{
			$filter = $args['filter'];
			foreach($filter as $v)
			{
				if( $v->q=='l' && strlen($v->v)>0 )
					$this->db->like( $v->t.'.'.$v->f, $v->v );
				elseif( $v->q=='w' && $v->v>0 )
					$this->db->where( $v->t.'.'.$v->f, $v->v );
			}
		}
		
		if($args['sort'])
		{
			$sort = $args['sort'];
			if( is_array( $sort ) )
			{
				foreach( $sort as $k=>$v )
					$this->db->order_by($k,$v);
			}
			else $this->db->order_by($args['sort']);
		}
		else $this->db->order_by('banners.bn_id', 'desc' );
		
		$q = $this->db->get();
		$n = $q->num_rows();
		if($args['count'])
			return $n;
		if($n>0)	
		{
			if( $args['one'] || $args['id'] )
			{
				$data = $q->row_array();
				return $data;
			}
			$rows = $q->result_array();
			return $rows;
		}
		
		return array();
	}

	public function new_banner()
	{
		$data = array();
		$data['bn_title'] = $this->input->post('title')?$this->input->post('title'):'';
		$data['bn_title_special'] = $this->input->post('title_special')?$this->input->post('title_special'):'';
		$data['bn_title_style'] = $this->input->post('title_style')?$this->input->post('title_style'):'';
		$data['bn_title_animation'] = $this->input->post('title_animation')?$this->input->post('title_animation'):'';
		$data['bn_subtitle'] = $this->input->post('subtitle')?$this->input->post('subtitle'):'';
		$data['bn_subtitle_animation'] = $this->input->post('subtitle_animation')?$this->input->post('subtitle_animation'):'';
		$data['bn_subtitle_style'] = $this->input->post('subtitle_style')?$this->input->post('subtitle_style'):'';
		$data['bn_action'] = $this->input->post('action')?$this->input->post('action'):'';
		$data['bn_action_animation'] = $this->input->post('action_animation')?$this->input->post('action_animation'):'';
		$data['bn_action_style'] = $this->input->post('action_style')?$this->input->post('action_style'):'';
		$data['bn_link'] = $this->input->post('link')?$this->input->post('link'):'';
		$data['bn_link_target'] = $this->input->post('link_target')?$this->input->post('link_target'):'';
		$data['bn_position'] = $this->input->post('position');
		$data['bn_enabled'] = $this->input->post('enabled');
		$data['bn_image'] = $this->input->post('image');
		$data['bn_theme'] = $this->input->post('site_slider');
		
		$e = $this->db->insert( 'banners', $data );
		if( $e ) return array( 'error'=>FALSE, 'error_msg'=>'Slider image added successfully' );
		return array( 'error'=>TRUE, 'error_msg'=>"Could not add banner, please check your data and try again" );
	}

	public function update_banner()
	{
		$data = array();
		$data['bn_title'] = $this->input->post('title')?$this->input->post('title'):'';
		$data['bn_title_special'] = $this->input->post('title_special')?$this->input->post('title_special'):'';
		$data['bn_title_style'] = $this->input->post('title_style')?$this->input->post('title_style'):'';
		$data['bn_title_animation'] = $this->input->post('title_animation')?$this->input->post('title_animation'):'';
		$data['bn_subtitle'] = $this->input->post('subtitle')?$this->input->post('subtitle'):'';
		$data['bn_subtitle_animation'] = $this->input->post('subtitle_animation')?$this->input->post('subtitle_animation'):'';
		$data['bn_subtitle_style'] = $this->input->post('subtitle_style')?$this->input->post('subtitle_style'):'';
		$data['bn_action'] = $this->input->post('action')?$this->input->post('action'):'';
		$data['bn_action_animation'] = $this->input->post('action_animation')?$this->input->post('action_animation'):'';
		$data['bn_action_style'] = $this->input->post('action_style')?$this->input->post('action_style'):'';
		$data['bn_link'] = $this->input->post('link')?$this->input->post('link'):'';
		$data['bn_link_target'] = $this->input->post('link_target')?$this->input->post('link_target'):'';
		$data['bn_position'] = $this->input->post('position');
		$data['bn_enabled'] = $this->input->post('enabled');
		$data['bn_image'] = $this->input->post('image');
		$data['bn_theme'] = $this->input->post('site_slider');
		
		$id = $this->input->post('id');
		$this->db->where('bn_id',$id);
		$e = $this->db->update( 'banners', $data );
		if( $e ) return array( 'error'=>FALSE, 'error_msg'=>'Slider image updated successfully' );
		return array( 'error'=>TRUE, 'error_msg'=>"Could not update banner, please check your data and try again" );
	}


	public function set_theme($data){
		$admin = isset($data['admin_theme']) ? $data['admin_theme'] : $this->config->item('admin_theme');
		$site =  isset($data['site_theme']) ? $data['site_theme'] : $this->config->item('site_theme');
		$string = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";
		$string .= "\$config['site_theme'] = '{$site}';\n";
		$string .= "\$config['admin_theme'] = '{$admin}';\n";
		$e = false;
		try {
			if(defined('SHAREDPATH')) {
				$folder = SHAREDPATH . "config/" . ENVIRONMENT . "/";
				$path = $folder."themes.php";
				if( !is_writable($folder) ) throw new Exception("The directory {$folder} is not writable");

				$file = fopen($path, "w");
				if (! $file) {
					throw new Exception("Could not open the file {$path} for writing");
				}
				$e = file_put_contents($path, $string);
				fclose($file);
			}
		}
		catch(Exception $e){
			return[ 'error'=>true, 'error_msg'=>$e->getMessage() ];
		}
		if( $e!==FALSE ) return array( 'error'=>FALSE, 'error_msg'=>'Active theme updated successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'Could not update active theme' );
	}







}?>
