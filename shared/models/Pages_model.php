<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_Model extends CI_Model
{

	public function delete_article($id)
	{
		$this->db->where('at_id', $id);
		$e = $this->db->delete('articles');
		if($e) return array( 'error'=>FALSE, 'error_msg'=>'Article deleted successfully' );
		else return array('error'=>TRUE, 'error_msg'=>'Article could not be deleted');
	}

	public function delete_gallery_image($args=array())
	{
		if(! isset($args['im_file']) ) return array('error'=>TRUE, 'error_msg'=>'The image path is required');
		if(! isset($args['im_id']) ) return array('error'=>TRUE, 'error_msg'=>'Could not find the image to delete');
		if($args['im_id']<=0) return array('error'=>TRUE, 'error_msg'=>'Could not find the image to delete');
		if( strlen($args['im_file'])>0 ); else return array('error'=>TRUE, 'error_msg'=>'Could not find the image to delete');
		
		if(file_exists('./images/articles/main/'.$args['im_file'])) unlink('./images/articles/main/'.$args['im_file']);
		if(file_exists('./images/articles/250x180/'.$args['im_file'])) unlink('./images/articles/250x180/'.$args['im_file']);
		if(file_exists('./images/articles/100x75/'.$args['im_file'])) unlink('./images/articles/100x75/'.$args['im_file']);
		if(file_exists('./images/articles/350x260/'.$args['im_file'])) unlink('./images/articles/350x260/'.$args['im_file']);
		
		$this->db->where('gi_id',$args['im_id']);
		$e = $this->db->delete('gallery_images');
		if($e) return array( 'error'=>FALSE, 'error_msg'=>'Image deleted successfully' );
		return array( 'error'=>TRUE, 'error_msg'=>'Could not delete image' );
	}

	public function toggle_state($args=array())
	{
		if(! isset($args['state']) ) return array('error'=>TRUE, 'error_msg'=>'Could not determine which state to update to');
		if(! isset($args['id']) ) return array('error'=>TRUE, 'error_msg'=>'Could not determine what to update');
		if(! isset($args['property']) ) return array('error'=>TRUE, 'error_msg'=>'Could not determine what to update');
		
		if( $args['property']=='featured' || $args['property']=='enabled' || $args['property']=='private' )
		{
			$state = $args['state']?0:1;
			$data = array( "at_{$args['property']}"=>$state );
			$this->db->where( 'at_id', $args['id'] );
			$e = $this->db->update( 'articles', $data );
			if($e) return array( 'error'=>FALSE, 'error_msg'=>'State updated successfully' );
			return array( 'error'=>TRUE, 'error_msg'=>'Could not change state.' );
		}
		return array('error'=>TRUE, 'error_msg'=>'Could not make the changes you requested, please check your data and try again.');
	}

	public function image_privacy($args=array())
	{
		if(! isset($args['privacy']) ) return array('error'=>TRUE, 'error_msg'=>'Could not determine which state to update to');
		if(! isset($args['id']) ) return array('error'=>TRUE, 'error_msg'=>'Could not determine what to update');
		if(! isset($args['img_id']) ) return array('error'=>TRUE, 'error_msg'=>'Could not determine what to update');
		if(! isset($args['file']) ) return array('error'=>TRUE, 'error_msg'=>'Could not determine what to update');
		
		$state = $args['privacy']?0:1;
		$data = array( "gi_private"=>$state );
		$this->db->where( 'gi_id', $args['img_id'] );
		$e = $this->db->update( 'gallery_images', $data );
		if($e) return array( 'error'=>FALSE, 'error_msg'=>'Image privacy updated successfully' );
		return array( 'error'=>TRUE, 'error_msg'=>'Could not change image privacy settings.' );
	}

	public function get_image_privacy($id)
	{
		if( is_numeric($id) && $id>0 )
		{
			$this->db->where('gi_id',$id);
			$q = $this->db->get('gallery_images');
			if($q->num_rows()==1) return $q->row_array();
		}
		return array();
	}

	public function post_article()
	{
		$title = $this->input->post('title');
		$segment = $this->input->post('segment');
		$image = $this->input->post('image');
		if(is_null($image)) $image = '';
		$cat = $this->input->post('section');
		$permalink = $this->input->post('permalink');
		$smi = $this->input->post('show_image');
		$content = $this->input->post('text');
		$rawtext = remove_HTML( $content,'p|b|strong|u|em|br|img|a|div|span|font|ul|ol|li|table|tr|tbody|thead|th|td|h1|h2|h3|h4|h5|h6|iframe');
		$text = htmlentities( $rawtext, ENT_QUOTES );
		$date = time();
		$dp = $this->input->post('date_posted');
		$date_posted = strtotime($dp);
		$error = array('error'=>TRUE,'error_msg'=>'Could not post article');
		
		$summary = $this->input->post('summary');
		$key = '';
		
		if( strlen($summary) < 5 )
		{
			$pattern = '/^([^.!?\s]*[\.!?\s]+){0,32}/';
			preg_match( $pattern, strip_tags($content), $abstract);
			if(isset($abstract[0]))
				$summary = $abstract[0];
		}
		
		if( strlen($key) < 10 )
		{
			$this->load->library( 'keyword_generator', array('content'=>$rawtext) );
			$key = $this->keyword_generator->get_keywords();
		
			$name = str_replace( ' ', ',', strtolower($title) );
			$exp = explode( ',', $name.','.$key );
			$exp = array_unique($exp);
			$key = implode( ',', $exp );
		}
		
		if( strlen($segment) < 5 )
			$segment = url_title( $title, '-', TRUE );
		
		$date = mysql_date();
		$user_id = $this->flexi_auth->get_user_id();
		$show_author = $this->input->post('show_author');
		$pvt = $this->input->post('private')?$this->input->post('private'):0;
		$en = $this->input->post('enabled')?$this->input->post('enabled'):1;
		
		$data = array(
			'at_title' => $title,
			'at_summary' => $summary,
			'at_section' => $cat,
			'at_permalink' => $permalink,
			'at_text' => $text,
			'at_date_posted' => $date,
			'at_date_updated'=>$date,
			'at_enabled' => $en,
			'at_private' => $pvt,
			'at_author' => $user_id,
			'at_show_author' => $show_author,
			'at_image' => $image, 
			'at_show_main_image' => $smi,
			'at_segment' => $segment, 
			'at_keywords' => $key
		);
		
		for( $i=1; $i<=8; $i++ )
		{
			$data["at_f{$i}"] = $this->input->post("f{$i}");
			$data["at_v{$i}"] = $this->input->post("v{$i}");
		}
		
		if( $date_posted > 0 ) $data['at_date_posted'] = mysql_date($date_posted);
		$error['error'] = !$this->db->insert('articles', $data);
		$error['error_msg'] = "Article Posted successfully";
		return $error;
	}

	public function update_article()
	{
		$id = $this->input->post('id');
		$title = $this->input->post('title');
		$segment = $this->input->post('segment');
		$image = $this->input->post('image');
		if(is_null($image)) $image = '';
		$cat = $this->input->post('section');
		$permalink = $this->input->post('permalink');
		$smi = $this->input->post('show_image');
		$content = $this->input->post('text');
		$rawtext = remove_HTML( $content,'p|b|strong|u|em|br|img|a|div|span|font|ul|ol|li|table|tr|tbody|thead|th|td|h1|h2|h3|h4|h5|h6|iframe');
		$text = htmlentities( $rawtext, ENT_QUOTES );
		$date = time();
		$error = array('error'=>TRUE,'error_msg'=>'Could not update article');
		$dp = $this->input->post('date_posted');
		$date_posted = strtotime($dp);
		
		$summary = $this->input->post('summary');
		$key = $this->input->post('keywords');;
		
		if( strlen($summary) < 5 )
		{
			$pattern = '/^([^.!?\s]*[\.!?\s]+){0,32}/';
			preg_match( $pattern, strip_tags($content), $abstract);
			if(isset($abstract[0]))
				$summary = $abstract[0];
		}
		
		if( strlen($key) < 5 )
		{
			$this->load->library( 'keyword_generator', array('content'=>$rawtext) );
			$key = $this->keyword_generator->get_keywords();
		
			$name = str_replace( ' ', ',', strtolower($title) );
			$exp = explode( ',', $name.','.$key );
			$exp = array_unique($exp);
			$key = implode( ',', $exp );
		}
		
		if( strlen($segment) < 2 )
			$segment = url_title( $title, '-', TRUE );
		
		$date = mysql_date();
		$user_id = $this->flexi_auth->get_user_id();
		$show_author = $this->input->post('show_author');
		
		$data = array(
			'at_title' => $title,
			'at_summary' => $summary,
			'at_section' => $cat,
			'at_permalink' => $permalink,
			'at_text' => $text,
			'at_date_updated'=>$date,
			'at_enabled' => $this->input->post('enabled'),
			'at_private' => $this->input->post('private'),
			'at_author' => $user_id,
			'at_show_author' => $show_author,
			'at_image' => $image, 
			'at_show_main_image' => $smi,
			'at_segment' => $segment, 
			'at_keywords' => $key
		);
		if( $date_posted > 0 ) $data['at_date_posted'] = mysql_date($date_posted);
		
		for( $i=1; $i<=8; $i++ )
		{
			$data["at_f{$i}"] = $this->input->post("f{$i}");
			$data["at_v{$i}"] = $this->input->post("v{$i}");
		}
		
		$this->db->where( 'at_id', $id );
		$e = $this->db->update('articles', $data);
		if($e)
		{
			$error['error_msg'] = "Article updated successfully";
			$error['error'] = FALSE;
		}
		return $error;
	}

	public function update_page()
	{
		$id = $this->input->post('id');
		$title = $this->input->post('title');
		$segment = $this->input->post('segment');
		$parent = $this->input->post('parent');
		$menu = $this->input->post('menu');
		$order = $this->input->post('order')??'';
		$items = $this->input->post('items')??'';
		$error = array('error'=>TRUE,'error_msg'=>'Could not update page');
		
		$old_value = $this->get_pages( ['select'=>'sc_value', 'id'=>$id ] );
				
		if( strlen($segment) < 2 ) {
			$title = str_replace( '/', '__ignore__', $title );
			$segment = url_title($title, '-', TRUE);
		}
		else{
			$segment = str_replace( '/', '__ignore__', $segment );
			$segment = url_title( $segment, '-', TRUE );
		}
		$segment = str_replace('__ignore__', '/', $segment);
		
		$data = array(
			'sc_name' => $title,
			'sc_value' => $segment, 
			'sc_menu' => $menu,
			'sc_parent' => $parent,
			'sc_enabled' => $this->input->post('enabled'),
			'sc_has_gallery' => $this->input->post('has_gallery'),
			'sc_order' => $order,
			'sc_items' => $items
		);
		for( $i=1; $i<=8; $i++ )
		{
			$data["sc_f{$i}"] = $this->input->post("f{$i}");
			$data["sc_v{$i}"] = $this->input->post("v{$i}");
		}

		if($id) {
			$this->db->where('sc_id', $id);
			$e = $this->db->update('sections', $data);
		}
		else{

			$this->db->where( 'sc_value', $data['sc_value'] );
			$n = $this->db->count_all_results('sections');
			if($n>0){
				return [ 'error'=>true, 'error_msg'=>'Unable to add new category. A category with the same URI String already exists' ];
			}
			$e = $this->db->insert( 'sections', $data );
		}

		if($e)
		{
			return $this->rewrite_routes();
		}
		return $error;
	}

	public function get_category_fields($id){
		$select = [];
		for( $i=1; $i<=8; $i++ )
		{
			$select[] = "sc_f{$i}";
			$select[] = "sc_v{$i}";
		}
		$this->db->select($select);
		$this->db->where('sc_id', $id);
		$r = $this->db->get('sections');
		if($r->num_rows()==1){
			return $r->row();
		}
		return [];
	}

	private function update_routes( $new_keys, $old_keys )
	{
		$keys = array();
		foreach( $old_keys as $k=>$v )
		{
			$keys['$route["'.$v.'"]'] = "'default_controller'";
		}
		$set = '';
		$routes = SITEPATH."config/routes.php";
		
		$myfile = fopen($routes, "r+");
		$i=0;
		while(!feof($myfile))
		{
			$setting = fgets($myfile);
		    $line = explode(' = ',$setting);
		
			// take-off old "\r\n"
			if( isset($line[0]) ) $key = trim($line[0]);
			else $key = FALSE;

			// search for keys/value pairs to remove
			if( $key && isset( $keys[$key] ) ){
				//sem( '$key = '.$key );
				//sem( '$old_key = '.$keys[$key] );
			}
			else{
				$set .= $setting;
				//sem( '$key = '.$key );
			}
			
			if($i==0){
				foreach( $new_keys as $nk=>$nv ){
					$set .= '$route["'.$nk.'"] = "'.$nv.'";'."\n";
				}
			}
			
			$i++;
		}
		
		//sem( print_r($keys, TRUE) );
		
		// using file_put_contents() instead of fwrite()
		$e = file_put_contents( $routes, $set);
		fclose($myfile);


		if( $e!==FALSE ) return array( 'error'=>FALSE, 'error_msg'=>'Page updated successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'Could not update page settings' );
	}

	public function rewrite_routes(){
		$e = false;
		// get sections
		$this->db->select('sc_id,sc_value');
		$this->db->where('sc_enabled',1);
		$this->db->order_by('sc_menu asc');
		$r = $this->db->get('sections');
		if( $r->num_rows()>0 ){
			$rows = $r->result_array();
			$routes = "<?php defined('BASEPATH') OR exit('No direct script access allowed');\n";
			foreach($rows as $k=>$v){
				$routes .= "\$route['{$v['sc_value']}'] = 'pages/index/{$v['sc_id']}';\n";
				$routes .= "\$route['{$v['sc_value']}/new'] = 'pages/index/{$v['sc_id']}/new';\n";
				$routes .= "\$route['{$v['sc_value']}/page'] = 'pages/index/{$v['sc_id']}';\n";
				$routes .= "\$route['{$v['sc_value']}/page/(.+)'] = 'pages/index/{$v['sc_id']}/\$1';\n";
				$routes .= "\$route['{$v['sc_value']}/(:num)'] = 'pages/article/{$v['sc_id']}/\$1';\n";
				$routes .= "\$route['{$v['sc_value']}/(:num)/.+'] = 'pages/article/{$v['sc_id']}/\$1';\n";
			}

			try {
				if(defined('SITEPATH')) {
					$folder = SITEPATH . "config/" . ENVIRONMENT . "/";
					$path = $folder."routes.php";
					if( !is_writable($folder) ) throw new Exception("The directory {$folder} is not writable");

					$file = fopen($path, "w");
					if (! $file) {
						throw new Exception("Could not open the file {$path} for writing");
					}
					$e = file_put_contents($path, $routes);
					fclose($file);
					$site_path = SITEPATH;
				} else {
					$site_path = '';
				}

				if(defined('APPPATH') && defined('APPNAME') && $site_path != APPPATH) {
					$folder = APPPATH . "config/" . ENVIRONMENT . "/";
					$path = $folder."routes.php";
					if( !is_writable($folder) ) throw new Exception("The directory {$folder} is not writable");

					$data = str_replace("route['", "route['" . APPNAME . "/", $routes);
					$file = fopen($path, "w");
					if (! $file) {
						throw new Exception("Could not open the file {$path} for writing");
					}
					$e = file_put_contents($path, $data);
					fclose($file);
				}
			}
			catch(Exception $e){
				return[ 'error'=>true, 'error_msg'=>$e->getMessage() ];
			}
		}
		if( $e!==FALSE ) return array( 'error'=>FALSE, 'error_msg'=>'Page routes updated successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'Could not update page routes' );
	}

	public function get_pages($args=[])
	{
		if(! isset($args['where']) ) $args['where'] = FALSE;
		if(! isset($args['one']) ) $args['one'] = FALSE;
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['sort']) ) $args['sort'] = FALSE;
		if(! isset($args['enabled']) ) $args['enabled'] = 'n/a';
		if(! isset($args['select']) ) $args['select'] = FALSE;
	
		if($args['limit']) $this->db->limit($args['limit'], $args['start']);
		if($args['select']) $this->db->select($args['select']);
		if($args['where']) $this->db->where($args['where']);
		if($args['id']) $this->db->where( 'sections.sc_id', $args['id']);
		if( is_numeric($args['enabled']) ) $this->db->where( 'sections.sc_enabled', $args['enabled']);
		
		
		
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
		else
		{
			$this->db->order_by( 'sc_menu', 'asc' );
			$this->db->order_by( 'sc_id', 'asc' );
		}
		
		$q = $this->db->get('sections');
		
		$n = $q->num_rows();
		if($args['count']) return $n;
		if($n>0)	
		{
			if( $args['one'] || $args['id'] )
			{
				$data = $q->row_array();
				$data['sc_parent_name'] = '';
				if( isset($data['sc_parent']) && $data['sc_parent']>0){
					$this->db->select('sc_name');
					$this->db->where('sc_id',$data['sc_parent']);
					$q2 = $this->db->get('sections');
					if($q2->num_rows()>0){
						$parent = $q2->row_array();
						$data['sc_parent_name'] = $parent['sc_name'];
					}
				}
				return $data;
			}
			$rows = $q->result_array();
			return $rows;
		}
		return array();
	}

	public function get_sections()
	{
		$q = $this->db->get('sections');
		if($q->num_rows()>0) 
			return $q->result_array();
		return array();
	}

	public function levels_of_education()
	{
		$q = $this->db->get('education');
		if($q->num_rows()>0) 
			return $q->result_array();
		return array();
	}

	public function cities()
	{
		$q = $this->db->get('cities');
		if($q->num_rows()>0) 
			return $q->result_array();
		return array();
	}

	public function countries()
	{
		$q = $this->db->get('countries');
		if($q->num_rows()>0) 
			return $q->result_array();
		return array();
	}

	public function list_programs()
	{
		$this->db->select( 'pg_id, pg_name, pg_summary, pg_cost, pg_image' );
		$this->db->where('pg_active', 1);
		$q = $this->db->get('programs');
		if($q->num_rows()>0) 
			return $q->result_array();
		return array();
	}

	public function get_gallery($args=array())
	{
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['at_id']) ) $args['at_id'] = FALSE;
		if(! isset($args['where']) ) $args['where'] = FALSE;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['sort']) ) $args['sort'] = FALSE;
		if(! isset($args['one']) ) $args['one'] = FALSE;
		if(! isset($args['private']) ) $args['private'] = FALSE;
		
		if($args['id'])
			$this->db->where( 'gi_id', $args['id']);
		if($args['at_id'])
			$this->db->where( 'gi_at_id', $args['at_id']);
		if($args['where'])
			$this->db->where($args['where']);

		if( !$this->flexi_auth->is_privileged('View Private Content') )
			$this->db->where( 'gi_private', 0);
		
		$q = $this->db->get('gallery_images');
		$n = $q->num_rows();
		if($args['count'])
			return $n;
		if($n>0)	
		{
			if( $args['one'] || $args['id'] )
				return $q->row_array();
			$rows = $q->result_array();
			return $rows;
		}
		return array();
	}

	public function update_gallery_images()
	{
		$images = $this->input->post('images');
		$id = $this->input->post('id');
		$e = FALSE;
		if( is_array($images) )
		{
			$data = array();
			foreach( $images as $image )
			{
				$data[] = array( 'gi_file'=>$image, 'gi_at_id'=>$id );
			}				
			$e = $this->db->insert_batch('gallery_images', $data);
		}
		if($e) return array( 'error'=>FALSE, 'error_msg'=>'Gallery Images updated successfully' );
		else return array( 'error'=>TRUE, 'error_msg'=>'No new gallery images were added.' );
	}


	public function get_month_theme()
	{
		$m = date('n');
		$this->db->where( 'mt_id', $m );
		$q = $this->db->get('rotary_themes');
		if( $q->num_rows()==1 )
		{
			return $q->row_array();
		}
		return array();
	}


	public function contact()
	{
		$data = array(
				'first_name'=>$this->input->post('name'),
				'last_name'=>'',
				'email'=>$this->input->post('email')
		);
		// 'phone'=>$this->input->post('phone')
		$site = $this->config->item('site-name');
		$email = $this->config->item('site-email');
		$noreply = $this->config->item('no-reply');
		$data['message'] = strip_tags( $this->input->post('message') );
		$data['subject'] = "$site Contact Form Message from {$data['first_name']} {$data['last_name']}";
		
		$msg = "The following user left a message on the $site Website:<br><br><em>Name:</em> {$data['first_name']} {$data['last_name']} <br><em>Email:</em> {$data['email']}<br><br><strong>Message:</strong><br><i>".
		nl2br($data['message'])."</i> <br><br>Regards, <br><br>$site Web.";
		
		// <br><em>Phone: </em>{$data['phone']} 
		
		if( isset($data['email']) )
		{
			$this->load->library('email');
			$this->email->from($noreply, $site);
			$this->email->reply_to( $data['email'], "{$data['first_name']} {$data['last_name']}");
			$this->email->to( $email );
			//$this->email->cc('faraimuti@gmail.com');
			$this->email->bcc('michaelmartinc@gmail.com');
			$this->email->subject($data['subject']);
			
			$this->email->message( $msg );
			$e = $this->email->send();
			if($e)
				return array( 'error'=>FALSE, 'error_msg'=>'Thank you for your inquiry, our Secretary will respond to your message soon' );
		}
		return array( 'error'=>TRUE, 'error_msg'=>'Could not send your message, please check your info and try again.' );		
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
		else $this->db->order_by('banners.bn_position', 'asc' );
		
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








	public function add_group()
	{
		if( !$this->authorize->is_admin(3) )
			return array( 'error'=>TRUE, 'error_msg'=>'you do not have permission to add groups' );
		
		$error['error'] = TRUE;
		$error['error_msg'] = "could not add group";
		$parent = $this->get_groups( array( 'id'=>$this->input->post('parent') ) );
		$user = $this->get_user_group();
		$node = array( 'id'=>FALSE );
		if( isset($parent['l']) && isset($user['l']) )
		{
			if( $parent['l'] < $user['l'] )
			{
				$this->db->trans_rollback();
				return array( 'error_msg'=>'You cannot create a group to or beyond your group level', 'error'=>TRUE );
			}
			elseif( $parent['l'] > $user['l'] )
			{
				$data = array(
					'name' => $this->input->post('name'),
					'desc' => $this->input->post('description'),
					'parent'=>$parent['id']
				);
				$this->load->library('tree');
				$node = $this->tree->nstNewNextSibling( 'code_groups', $parent, $data );
				if(isset($node['id']))
				{
					$error['error_msg'] = "Group added successfully";
					$error['error'] = FALSE;
				}
			}
		}
		return $error;
	}


	public function update_group()
	{
		if( !$this->authorize->is_admin(3) )
			return array( 'error'=>TRUE, 'error_msg'=>'you do not have permission to edit groups' );
		
		$parent = $this->get_groups( array( 'id'=>$this->input->post('parent') ) );
		$user = $this->get_user_group();
		$current = $this->get_groups( array( 'id'=>$this->input->post('id') ) );
		
		if( isset($parent['l']) && isset($user['l']) && isset($current['l']) )
		{
			if( $parent['l'] < $user['l'] )
			{
				return array( 'error_msg'=>'Update partially completed. You cannot upgrade a group to or beyond your group level', 'error'=>TRUE );
			}
			elseif( $parent['l'] != $current['l'] && $parent['l'] > $user['l'] )
			{
				$this->load->library('tree');
				$this->tree->nstMoveToNextSibling( $this->db->dbprefix('user_groups'), $current, $parent );
				$data = array(
					'name' => $this->input->post('name'),
					'desc' => $this->input->post('description'), 
					'parent'=>$parent['id']
				);
				$this->db->where( 'id', $current['id'] );
				$this->db->update( 'user_groups', $data );
			}
		}
		$error = array( 'error'=>FALSE, 'error_msg' => "Group updated successfully" );	
		return $error;
	}


	private function get_user_group($id=FALSE)
	{
		if(!$id && $this->flexi_auth->is_logged_in())
			$id = $this->flexi_auth->get_user_id();
		if( is_numeric($id) && $id>0 )
		{
			$this->db->from('user_accounts');
			$this->db->select( 'user_groups.ugrp_id, user_groups.l, user_groups.r, user_groups.ugrp_name' );
			$this->db->join( 'user_groups', 'user_groups.ugrp_id = user_accounts.uacc_group_fk', 'inner' );
			$this->db->where( 'user_accounts.uacc_id', $id );
			$q = $this->db->get();
			if( $q->num_rows()==1 )
				return $q->row_array();
		}
		return FALSE;
	}


	public function get_groups($args = array())
	{
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['id !=']) ) $args['id !='] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		
		if( $args['id !='] )
			$this->db->where('ugrp_id !=', $args['id !='] );
		$grp = $this->get_user_group();
		if(isset($grp['l']))
			$this->db->where( 'l <=', $grp['l'] );
		
		if( $args['limit'] )
		{
			$this->db->limit( $args['limit'], $args['start'] );
		}
		
		if( $args['id'] )
		{
			$this->db->where( 'ugrp_id', $args['id'] );
			$query = $this->db->get('user_groups');
			if( $query->num_rows()==1 )
			return $query->row_array();
		}
		
		$this->db->order_by('l asc');
		
		$query = $this->db->get('user_groups');
		
		if($args['count'])
			return $query->num_rows();
		
		if( $query->num_rows() > 0 )
			return $query->result_array();
		
		return array();
	}


	public function get_users($args = array())
	{
		if(! isset($args['id']) ) $args['id'] = FALSE;
		if(! isset($args['group']) ) $args['group'] = FALSE;
		if(! isset($args['limit']) ) $args['limit'] = FALSE;
		if(! isset($args['start']) ) $args['start'] = 0;
		if(! isset($args['sort']) ) $args['sort'] = FALSE;
		if(! isset($args['select']) ) $args['select'] = FALSE;
		if(! isset($args['like']) ) $args['like'] = FALSE;
		if(! isset($args['where']) ) $args['where'] = FALSE;
		if(! isset($args['count']) ) $args['count'] = FALSE;
		if(! isset($args['filter']) ) $args['filter'] = FALSE;
		if(! isset($args['one']) ) $args['one'] = FALSE;
		if(! isset($args['table']) ) $args['table'] = $this->auth->tbl_user_account;
		$table = $args['table'];
		$profile = 'user_profiles';
		$group = 'user_groups';
		
		if( $args['limit'] !== FALSE)
			$this->db->limit( $args['limit'], $args['start'] );
		$this->db->from( $table );
		
		if($args['select'])
			$this->db->select($args['select']);
		else
		{
			$this->db->select
			("
				{$table}.uacc_id, {$table}.uacc_email, {$table}.uacc_username, {$table}.uacc_suspend, {$table}.uacc_active, 
				{$table}.uacc_date_added, {$table}.uacc_date_last_login, 
				{$profile}.first_name, {$profile}.last_name, {$table}.uacc_group_fk, {$profile}.newsletter,
				{$profile}.bio, {$profile}.phone, {$profile}.photo, {$group}.ugrp_name 
			");
			
			$this->db->join($profile, "{$profile}.uacc_fk = {$table}.uacc_id", 'inner');
			$this->db->join($group, "{$group}.ugrp_id = {$table}.uacc_group_fk", 'inner');
		}
		
		if( $args['id'] !== FALSE )
		{
			$this->db->where( "{$table}.uacc_id", $args['id'] );
			$query = $this->db->get();
			if( $query->num_rows()==1 )
			return $query->row_array();
		}
		if($args['where'])
		{
			$where = $args['where'];
			foreach($where as $k=>$v)
			{
				if( is_numeric($v) )
				{
					if($v>0)
						$this->db->where( $table.'.'.$k, $v );
				}
				elseif( strlen($v)>2 )
					$this->db->where( $table.'.'.$k, $v );
			}
		}
		
		if($args['like'])
		{
			$like = $args['like'];
			foreach($like as $k=>$v)
			{
				if( strlen($v)>2 )
					$this->db->like( $table.'.'.$k, $v );
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
			$this->db->order_by($args['sort']);
		else $this->db->order_by('id', 'DESC');
		
		$query = $this->db->get();
		$n = $query->num_rows();
		
		if($args['count'])
			return $n;
		
		if( $n > 0 )
		{
			if($args['one']) return $query->row_array();
			else return $query->result_array();
		}
		return array();
	}

	public function toggle_user_state( $args=array() )
	{
		if(! isset($args['state']) ) return array('error'=>'bad', 'error_msg'=>'Could not determine which state to update to');
		if(! isset($args['id']) ) return array('error'=>'bad', 'error_msg'=>'Could not determine what to update');
		
		if( $this->flexi_auth->is_privileged('Update Users') )
		{
			$state = $args['state']?0:1;
			$data = array( 'uacc_suspend'=>$state );
			$this->db->where( 'uacc_id', $args['id'] );
			$e = $this->db->update( 'user_accounts', $data );
			if($e) return array( 'error'=>FALSE, 'error_msg'=>'Account state updated successfully' );
			return array( 'error'=>TRUE, 'error_msg'=>'Could not change user state.' );
		}
		return array('error'=>TRUE, 'error_msg'=>'You do not have permission to suspend/activate users');
	}

	public function delete_user($id)
	{
		if( $this->flexi_auth->is_privileged('Delete Users') )
		{
			if( is_numeric($id) && $id!=$this->flexi_auth->get_user_id() )
			{
				$this->db->trans_start();
				$this->db->where('uacc_fk', $id);
				$this->db->delete('user_profiles');
				/*
				$this->db->where('user_id', $id);
				$this->db->delete('user_locations');
				$this->db->where('user_id', $id);
				$this->db->delete('cart_data');
				$this->db->where('user_id', $id);
				$this->db->delete('cart_orders');
				$this->db->where('id', $id);
				$this->db->delete('account');
				$this->db->where('user_id', $id);
				$this->db->delete('company_users');
				$this->db->where('user_id', $id);
				$this->db->delete('news');
				$this->db->where('user_id', $id);
				$this->db->delete('news_comments');
				$this->db->where('user_id', $id);
				$this->db->delete('product_ratings');
				$this->db->where('user_id', $id);
				$this->db->delete('product_reviews');
				$this->db->where('user_id', $id);
				$this->db->delete('articles');
				$this->db->where('user_id', $id);
				$this->db->delete('article_comments');
				$this->db->where('user_id', $id);
				$this->db->delete('article_ratings');
				$this->db->where('user_id', $id);
				$this->db->delete('company_ratings');
				$this->db->where('user_id', $id);
				$this->db->delete('company_reviews');
				$this->db->where('user_id', $id);
				$this->db->delete('cp_reviews');
				$this->db->where('user_id', $id);
				$this->db->delete('cp_ratings');
				*/
				$this->db->where('uacc_id', $id);
				$this->db->delete('user_accounts');
				$this->db->trans_complete();
				return array( 'error'=>FALSE, 'error_msg'=>'User account deleted successfully' );
			}
		}
		else return array('error'=>TRUE, 'error_msg'=>'You do not have permission to delete users');
	}

}?>
