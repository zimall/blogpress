<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imagier
{

	function __construct() 
	{
		$this->ci = &get_instance();
		$this->ci->output->enable_profiler(false);
	}

	public function articles($size='', $file='')
	{
		$image = "images/articles/{$size}/{$file}";
		if(file_exists($image) && is_file($image))
		{
			$this->output($image,$size);
		}
		else
		{
			$image = "images/articles/{$size}/placeholder.jpg";
			$this->output($image,$size);
		}
	}

	public function create_thumb($index)
	{
		$data['error'] = TRUE;
		$data['form'] = 'blog_article';
		$temp = $_FILES[$index]['tmp_name'];
		$file = basename($_FILES[$index]['name']);
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		$file = $this->safe_file_name($file,'-',TRUE,$ext); 
		$stamp = time();
		$folder = realpath('./images/articles/').'/';
		$xs = $folder.'xs/'.$stamp.$file;
		$sm = $folder.'sm/'.$stamp.$file;
		$md = $folder.'md/'.$stamp.$file;
		$lg = $folder.'lg/'.$stamp.$file;
		$xl = $folder.'xl/'.$stamp.$file;

		if( !is_dir($folder) ) mkdir( $folder, 0777 );

		if( !is_writable($folder) ) return array( 'error'=>$folder.' is not writable' );

		try
		{
			if( !is_dir($folder.'xs/') ) mkdir( $folder.'xs', 0777 );
			if( !is_dir($folder.'sm/') ) mkdir( $folder.'sm', 0777 );
			if( !is_dir($folder.'md/') ) mkdir( $folder.'md', 0777 );
			if( !is_dir($folder.'lg/') ) mkdir( $folder.'lg', 0777 );
			if( !is_dir($folder.'xl/') ) mkdir( $folder.'xl', 0777 );

			if( !is_writable($folder.'xs/') ) chmod( $folder.'xs', 0777 );
			if( !is_writable($folder.'sm/') ) chmod( $folder.'sm', 0777 );
			if( !is_writable($folder.'md/') ) chmod( $folder.'md', 0777 );
			if( !is_writable($folder.'lg/') ) chmod( $folder.'lg', 0777 );
			if( !is_writable($folder.'xl/') ) chmod( $folder.'xl', 0777 );

			if( !is_writable($folder.'xs/') ) return array( 'error'=>$folder.'xs/ is not writable' );
			if( !is_writable($folder.'sm/') ) return array( 'error'=>$folder.'sm/ is not writable' );
			if( !is_writable($folder.'md/') ) return array( 'error'=>$folder.'md/ is not writable' );
			if( !is_writable($folder.'lg/') ) return array( 'error'=>$folder.'lg/ is not writable' );
			if( !is_writable($folder.'xl/') ) return array( 'error'=>$folder.'xl/ is not writable' );
		}
		catch(Exception $e)
		{
			$data['error'] =  "Error saving image. ".$e;
			return $data;
		}

		include_once( SHAREDPATH."third_party/wideimage/WideImage.php");
		$wi = new WideImage();

		$image = $wi->load($temp);

		$from['h'] = $image->getHeight();
		$from['w'] = $image->getWidth();

		$this->ci->load->model('settings_model');
		$theme_name = $this->ci->config->item('site_theme');
		$theme = $this->ci->settings_model->load_theme($theme_name, 'site');

		$to = $this->get_sizes( $theme, 'xs', $from );
		$this->simple_resize( $image, $from, $to, $xs );

		$to = $this->get_sizes( $theme, 'sm', $from );
		$this->simple_resize( $image, $from, $to, $sm );
		
		$to = $this->get_sizes( $theme, 'md', $from );
		$this->simple_resize( $image, $from, $to, $md );
		
		$to = $this->get_sizes( $theme, 'lg', $from );
		$this->simple_resize( $image, $from, $to, $lg );
		
		$to = $this->get_sizes( $theme, 'xl', $from );
		$this->simple_resize( $image, $from, $to, $xl );
	
		try
		{
			$wi->load($xs)&&$wi->load($sm)&&$wi->load($md)&&$wi->load($lg)&&$wi->load($xl);
			$data['image'] = $stamp.$file;
			$data['success'] = TRUE;
			$data['error_msg'] = 'success';
			return $data;
		}
		catch(Exception $e)
		{
			$data['error'] =  "error saving image. {$xs}. {$e}";
			return $data;
		}

	}

	private function get_sizes( $config, $size, $from )
	{
		$width = $from['w'];
		$height = $from['h'];
		if(isset($config['image_sizes']))
		{
			$image_sizes = $config['image_sizes']; 
			if(isset($image_sizes[$size]))
			{
				$sizes = explode('x', $image_sizes[$size]);
				if( isset($sizes[0]) && is_numeric($sizes[0]) ) $w = $sizes[0];
				else $w = FALSE;


				if(isset($sizes[1]) && is_numeric($sizes[1]) ) $h = $sizes[1];
				else $h = FALSE;
				
				return $this->auto_size( ['w'=>$width,'h'=>$height], ['w'=>$w,'h'=>$h] );
			}
		}
		return $this->auto_size( ['w'=>$width,'h'=>$height], ['w'=>500,'h'=>FALSE] );
	}

	private function auto_size($from, $to)
	{
		$oldWidth = $from['w'];
		$oldHeight = $from['h'];
		$newWidth = $to['w'];
		$newHeight = $to['h'];

		if( $oldWidth && $oldHeight )
		{
			$aspectRatio = ( $oldWidth / $oldHeight );
			if($newWidth && !$newHeight) $newHeight = ( $newWidth / $aspectRatio );
			elseif ($newHeight && !$newWidth) $newWidth = ( $newHeight * $aspectRatio );
		}
		
		return [ 'w'=>$newWidth, 'h'=>$newHeight ];
	}

	private function simple_resize( $image, $from, $to, $save )
	{
		$w = $from['w'];
		$h = $from['h'];
		$fw = $to['w'];
		$fh = $to['h'];

		$resize = $image->resize($fw, null, 'outside', 'up');
		$h2 = $resize->getHeight();
		if($h2>$fh)
		{
			$resize = $resize->resize(null, $fh, 'inside', 'down');
		}

		return $resize->saveToFile($save);
	}

	private function output($file,$size='lg')
	{
		if(file_exists($file) && is_file($file))
		{
			$type = exif_imagetype($file);
			$mime = image_type_to_mime_type($type);
			$this->ci->output
        		->set_content_type($type)
        		->set_output(file_get_contents($file));
		}
		else
		{
			$this->ci->output
        		->set_content_type('image/jpeg')
        		->set_output(file_get_contents(''));
		}
	}

	private function not_found($size)
	{
		$theme = $this->ci->config->item('theme');

		if(isset($theme['image_sizes']))
		{
			$image_sizes = $theme['image_sizes']; 
			if(isset($image_sizes[$size]))
			{
				$sizes = explode('x', $image_sizes[$size]);
				if( isset($sizes[0]) && is_numeric($sizes[0]) ) $w = $sizes[0];
				else $w = FALSE;
				if(isset($sizes[1]) && is_numeric($sizes[1]) ) $h = $sizes[1];
				else $h = FALSE;
				if( $w && $h )
				{
					$site_name = $this->ci->config->item('site-name');
					$my_img = imagecreate( $w, $h );
					$background = imagecolorallocate( $my_img, 0, 0, 255 );
					$text_colour = imagecolorallocate( $my_img, 255, 255, 0 );
					$line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
					imagestring( $my_img, 4, 30, 25, "{$site_name} \n\n Not Found", $text_colour );
					imagesetthickness ( $my_img, 5 );
					imageline( $my_img, 30, 45, 165, 45, $line_colour );
					header( "Content-type: image/png" );
					imagepng( $my_img );
					imagecolordeallocate( $line_color );
					imagecolordeallocate( $text_color );
					imagecolordeallocate( $background );
					imagedestroy( $my_img );
				}
			}
		}
		
		
	}

	private function safe_file_name($str, $separator = '-', $lowercase = FALSE, $ext = '')
	{
		if ($separator === 'dash')
		{
			$separator = '-';
		}
		elseif ($separator === 'underscore')
		{
			$separator = '_';
		}

		$q_separator = preg_quote($separator, '#');

		$trans = array(
			'&+?;'			=> '',
			'[^\w\d _-]'		=> '',
			'\s+'			=> $separator,
			'('.$q_separator.')+'	=> $separator
		);

		$str = strip_tags($str);
		foreach ($trans as $key => $val)
		{
			$str = preg_replace('#'.$key.'#i'.(UTF8_ENABLED ? 'u' : ''), $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}

		$name = trim(trim($str, $separator));
		return str_replace($ext, ".{$ext}", $name);
	}

	public function index()
	{
		$this->_process_form();
		$this->ci->pc->page_control('article_list', 10 );
		$continue = $this->_process_get();
		
		if($continue)
		{
			$count = array( 'count'=>TRUE);
			$function = 'get_articles';
			$paginate = $this->ci->pc->paginate($count, $function, 'article_model');
			
			$args = array( 'start'=>$paginate['start'], 'limit'=>$paginate['limit'], 'sort'=>'at_date_posted desc' );
			$count['count'] = NULL;
			$args = array_merge($args, $count);
			
			$this->ci->data['articles'] = $this->ci->article_model->get_articles( $args );
		}
		
		$this->ci->load->view( "{$this->ci->data['theme']}/articles.tpl", $this->ci->data );
	}

	public function popular_articles()
	{
		$select = 'at_id, at_summary, at_title, at_segment, at_date_posted, at_image, at_show_xl_image';
		$where = '';//array( 'at_section'=>5 );
		$args = array( 'where'=>$where, 'sort'=>'at_hits desc', 'limit'=>6, 'select'=>$select );
		$this->ci->data['popular_articles'] = $this->ci->article_model->get_articles($args);
	}

	public function new_article()
	{
		$this->_process_form();
		
		$this->ci->data['ckeditor'] = TRUE;
		$this->ci->data['scripts'][] = 'ajaxupload';
		$this->ci->data['scripts'][] = 'images';
		
		$this->ci->data['sections'] = $this->ci->article_model->get_sections();
		
		$this->ci->data['section'] = 'new_article';
		$this->ci->load->view( "{$this->ci->data['theme']}/articles.tpl", $this->ci->data );
	}

	public function news()
	{
		$this->_process_form();
		$this->ci->pc->page_control('news_list', 10 );
		$continue = $this->_process_get();
		
		if($continue)
		{
			$where = array( 'at_section'=>5 );
			$count = array( 'where'=>$where, 'count'=>1 );
			
			$paginate = $this->ci->pc->paginate($count, 'get_articles', 'article_model');
			$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id desc' ) );
			$this->ci->data['articles'] = $this->ci->article_model->get_articles( $args );
			$this->ci->data['innertitle'] = 'News';
		}
		
		$this->ci->load->view( "{$this->ci->data['theme']}/articles.tpl", $this->ci->data );
	}

	public function projects()
	{
		$this->_process_form();
		$this->ci->pc->page_control('projects_list', 10 );
		$continue = $this->_process_get();
		
		if($continue)
		{
			$where = array( 'at_section'=>6 );
			$count = array( 'where'=>$where, 'count'=>1 );
			
			$paginate = $this->ci->pc->paginate($count, 'get_articles', 'article_model');
			$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id desc' ) );
			$this->ci->data['articles'] = $this->ci->article_model->get_articles( $args );
			$this->ci->data['innertitle'] = 'Projects';
		}
		
		$this->ci->load->view( "{$this->ci->data['theme']}/articles.tpl", $this->ci->data );
	}

	public function reports()
	{
		$this->_process_form();
		$this->ci->pc->page_control('projects_list', 10 );
		$continue = $this->_process_get();
		
		if($continue)
		{
			$where = array( 'at_section'=>10 );
			$count = array( 'where'=>$where, 'count'=>1 );
			
			$paginate = $this->ci->pc->paginate($count, 'get_articles', 'article_model');
			$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id desc' ) );
			$this->ci->data['articles'] = $this->ci->article_model->get_articles( $args );
			$this->ci->data['innertitle'] = 'Reports';
		}
		
		$this->ci->load->view( "{$this->ci->data['theme']}/articles.tpl", $this->ci->data );
	}

	public function gallery($id=FALSE)
	{
		$this->_process_form();
		$this->ci->pc->page_control('gallery_list', 10 );
		$continue = $this->_process_get();
		
		if($continue)
		{
			if( $id > 0 )
			{
				$this->ci->data['section'] = 'gallery_images';
				$this->ci->data['innertitle'] = 'Gallery Images';
				$this->ci->data['uploader'] = 'fine';
				$where = array( 'at_section'=>11, 'at_id'=>$id );
				$this->ci->data['gallery'] = $this->ci->article_model->get_articles(  array( 'where'=>$where, 'one'=>TRUE ) );
				$this->ci->data['images'] = $this->ci->article_model->get_gallery(  array( 'at_id'=>$id ) );
			}
			else
			{
				$where = array( 'at_section'=>11 );
				$count = array( 'where'=>$where, 'count'=>1 );
				$paginate = $this->ci->pc->paginate($count, 'get_articles', 'article_model');
				$args = array_merge( $paginate, array( 'where'=>$where, 'sort'=>'at_id desc' ) );
				$this->ci->data['articles'] = $this->ci->article_model->get_articles( $args );
				$this->ci->data['innertitle'] = 'Gallery';
			}
		}
		
		$this->ci->load->view( "{$this->ci->data['theme']}/articles.tpl", $this->ci->data );
	}
	

	/****** PROCESS FORM DATA ************/
	public function _process_form($r=FALSE)
	{
		$name = $this->ci->input->post('form_name');
		$action = $this->ci->input->post('form_type');
		if( !is_null($name) && !is_null($action) )
		{
			$this->ci->load->library('form_validation');
			
			if( $name == 'article' && $action == 'insert' )
			{
				$this->ci->form_validation->set_rules( 'text', 'Article Body', 'required' );
				$this->ci->form_validation->set_rules( 'title', 'Title', 'required' );
				if( $this->ci->form_validation->run() )
				{
					$error = $this->ci->article_model->post_article();
					sem($error);
					if(!$r) $r = 'articles';
					if( !$error['error'] )
						redirect( $r );
				}
			}
			elseif( $name == 'article' && $action == 'update' )
			{
				$this->ci->form_validation->set_rules( 'text', 'Article Body', 'required' );
				$this->ci->form_validation->set_rules( 'title', 'Title', 'required' );
				$this->ci->form_validation->set_rules( 'id', 'Article ID', 'required|numeric' );
				if( $this->ci->form_validation->run() )
				{
					$error = $this->ci->article_model->update_article();
					sem($error);
					if(!$r) $r = current_url();
					if( !$error['error'] )
						redirect( $r );
				}
			}
			elseif( $name == 'article' && $action == 'delete' )
			{
				$this->ci->form_validation->set_rules( 'confirm_delete', 'Yes I\'m sure', 'required' );
				$this->ci->form_validation->set_rules( 'id', 'Article ID', 'required|numeric' );
				if( $this->ci->form_validation->run() )
				{
					$id = $this->ci->input->get('id');
					$error = $this->ci->article_model->delete_article($id);
					sem($error);
					if(!$r) $r = current_url();
					if( !$error['error'] )
						redirect( $r );
				}
			}
			elseif( $name == 'gallery_images' && $action == 'insert' )
			{
				$this->ci->form_validation->set_rules( 'id', 'Gallery ID', 'required|numeric|integer' );
				if( $this->ci->form_validation->run() )
				{
					$error = $this->ci->article_model->update_gallery_images();
					sem($error);
					if(!$r) $r = current_url();
					if( !$error['error'] )
						redirect( $r );
				}
			}
		}
		//else sem('no form data found');
		return 0;
	}
	
	
	public function _process_get()
	{
		$continue = TRUE;
		$action = $this->ci->input->get('action');
		if($p = $this->ci->input->get('p'));else $p = FALSE;		// p = property to toggle
		if($v = $this->ci->input->get('v'));else $v=FALSE;		// v = value used for toggle operations
		if($id = $this->ci->input->get('id'));else $id=FALSE;		// id of item to change
		if( $action )
		{
			if( $action=='add_user' )
			{
				$this->ci->data['add_user'] = TRUE;
				$this->ci->data['innertitle'] = 'Add User';
				$this->ci->data['groups'] = $this->ci->user_model->get_groups();
				$continue = FALSE;
			}
			elseif( $action=='edit_article' )
			{
				if( $id = $this->ci->input->get('id') )
				{
					$this->ci->data['article'] = $this->ci->article_model->get_articles( array('id'=>$id) );
					$this->ci->data['sections'] = $this->ci->article_model->get_sections();
					$this->ci->data['section'] = 'edit_article';
					$this->ci->data['innertitle'] = 'Edit Article';
					$this->ci->data['ckeditor'] = TRUE;
					$this->ci->data['scripts'][] = 'ajaxupload';
					$this->ci->data['scripts'][] = 'images';
					$continue = FALSE;
				}
			}
			elseif( $action=='remove_account' )
			{
				if( $id = $this->ci->input->get('id') )
				{
					$this->ci->data['account'] = $this->ci->user_model->get_users( array('id'=>$id) );
					$this->ci->data['section'] = 'remove_account';
					$continue = FALSE;
				}
			}
			elseif( $action=='toggle_state' && is_numeric($id) && $p )
			{
				$id = $this->ci->input->get('id');
				$data = array('id'=>$id, 'state'=>$v, 'property'=>$p);
				$e = $this->ci->article_model->toggle_state( $data );
				sem($e);
				redirect( current_url() );
				$continue = TRUE;
			}
			elseif( $action=='delete_article' && is_numeric($id) )
			{
				$this->ci->data['article'] = $this->ci->article_model->get_articles( array('id'=>$id) );
				$this->ci->data['section'] = 'delete_article';
				$continue = FALSE;
			}
		}
		return $continue;
	}
}
