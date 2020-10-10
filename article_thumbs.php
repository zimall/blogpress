<?php

$ob = new Upload_product_image();

$ob->invoke();



class Upload_product_image
{

	public function invoke()
	{
		if( isset($_FILES['uploadfile']) )
			$res = $this->createThumb();
		else $res = array( 'error'=>'no image uploaded' );

		echo json_encode($res);
	}

/* THUMB DIMENSIONS

1.	250x180
2.	350x260	y = 1.346153846x 	300 = 1.346153846x 	x = 300/1.346153846
3.	100x75 
4.	900x600
*/



private function createThumb()
{
	define('UTF8_ENABLED', TRUE);
	include_once('image_helper.php');
	$data['error'] = TRUE;
	$data['form'] = 'blog_article';
	$temp = $_FILES['uploadfile']['tmp_name'];
	$file = basename($_FILES['uploadfile']['name']);
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	$file = safe_file_name($file,'-',TRUE,$ext); 
	$stamp = time();
	$folder = './images/articles/';
	$main210 = $folder.'250x180/'.$stamp.$file;
	$main280 = $folder.'350x260/'.$stamp.$file;
	$main100 = $folder.'100x75/'.$stamp.$file;
	$main = './images/articles/main/'.$stamp.$file;

	if( !is_dir($folder) ) mkdir( $folder, 0777 );

	if( !is_writable($folder) ) return array( 'error'=>$folder.' is not writable' );

	if( !is_dir($folder.'250x180/') ) mkdir( $folder.'250x180', 0777 );
	if( !is_dir($folder.'350x260/') ) mkdir( $folder.'350x260', 0777 );
	if( !is_dir($folder.'100x75/') ) mkdir( $folder.'100x75', 0777 );
	if( !is_dir($folder.'main/') ) mkdir( $folder.'main', 0777 );

	if( !is_writable($folder.'250x180/') ) chmod( $folder.'250x180', 0777 );
	if( !is_writable($folder.'350x260/') ) chmod( $folder.'350x260', 0777 );
	if( !is_writable($folder.'100x75/') ) chmod( $folder.'100x75', 0777 );
	if( !is_writable($folder.'main/') ) chmod( $folder.'main', 0777 );

	if( !is_writable($folder.'250x180/') ) return array( 'error'=>$folder.'250x180/ is not writable' );
	if( !is_writable($folder.'350x260/') ) return array( 'error'=>$folder.'350x260/ is not writable' );
	if( !is_writable($folder.'100x75/') ) return array( 'error'=>$folder.'100x75/ is not writable' );
	if( !is_writable($folder.'main/') ) return array( 'error'=>$folder.'main/ is not writable' );

	include_once( "./shared/libraries/wideimage/WideImage.php");
		$wi = new WideImage();

	// Load watermarks
		//$wm30 = $wi->load('./images/zimall-30px.png');
		//$wm50 = $wi->load('./images/zimall-50px.png');
		//$wm75 = $wi->load('./images/zimall-75px.png');
		//$wm140 = $wi->load('./images/zimall-140px.png');


	/************* 350x260 **************/
	simple_resize( $temp, 350, 225, $main280 );
	/*
		$image = $wi->load($temp);
		$blank = $wi->load('images/blanks/350x225.png');
		$t1 = $image->resize(350, null, 'inside', 'down');
		$h = $t1->getHeight();
		if($h>225)
		{
			$t2 = $t1->resize(null, 225, 'inside', 'down');
			$w2 = $t2->getWidth();
			//$a = $t1->getTransparentColor();
			$a = $blank->getTransparentColor();
			$d = (350 - $w2)/2;
			$c = $t2->resizeCanvas( 350, 225, 'center', 'center', $a );
			//$c = $c0->merge($wm50, 'center', 'bottom – 5', 100);
		}
		else
		{
			$d = (225 - $h)/2;
			$a = $t1->getTransparentColor();
			$c = $t1->resizeCanvas( 350, 225, 'center', 'center', $a );
			//$c = $c0->merge($wm50, 'center', 'bottom – 5', 100);
		}
		$c->saveToFile($main280);
	*/
	/************* 250x180 **************/
	simple_resize( $temp, 250, 180, $main210 );
	/*
		$image = $wi->load($temp);
		$t1 = $image->resize(250, null, 'inside', 'down');
		$h = $t1->getHeight();
		if($h>180)
		{
			$t2 = $t1->resize(null, 180, 'inside', 'down');
			$w2 = $t2->getWidth();
			$a = $t1->getTransparentColor();
			$d = (250 - $w2)/2;
			$c = $t2->resizeCanvas( 250, 180, 'center', 'center', $a );
			//$c = $c0->merge($wm75, 'center', 'bottom – 5', 100);
		}
		else
		{
			$d = (180 - $h)/2;
			$a = $t1->getTransparentColor();
			$c = $t1->resizeCanvas( 250, 180, 'center', 'center', $a );
			//$c = $c0->merge($wm75, 'center', 'bottom', 100);
		}
		$c->saveToFile($main210);
	*/
	/************* 100x75 **************/
	simple_resize( $temp, 100, 75, $main100 );
	/*
		$image = $wi->load($temp);
		$t1 = $image->resize(100, null, 'inside', 'down');
		$h = $t1->getHeight();
		if($h>75)
		{
			$t2 = $t1->resize(null, 75, 'inside', 'down');
			$w2 = $t2->getWidth();
			$a = $t1->getTransparentColor();
			$d = (100 - $w2)/2;
			$c = $t2->resizeCanvas( 100, 75, 'center', 'center', $a );
			//$c = $c0->merge($wm30, 'center', 'bottom', 100);
		}
		else
		{
			$d = (75 - $h)/2;
			$a = $t1->getTransparentColor();
			$c = $t1->resizeCanvas( 100, 75, 'center', 'center', $a );
			//$c = $c0->merge($wm30, 'center', 'bottom – 5', 100);
		}
		$c->saveToFile($main100);
	*/

	/************* 900x600 **************/
	simple_resize( $temp, 900, 600, $main );
	/*
		$image = $wi->load($temp);
		//$t0 = $image->merge($wm140, 'left', 'bottom', 100);
		$t1 = $image->resize(900, null, 'inside', 'down');
		$h = $t1->getHeight();
		if($h>600)
		{
			$t2 = $t1->resize(null, 600, 'inside', 'down');
			$w2 = $t2->getWidth();
			$a = $t1->getTransparentColor();
			$d = (900 - $w2)/2;
			$c = $t2->resizeCanvas( 900, 600, 'center', 'center', $a );
			//$c = $c0->merge($wm100, 'center', 'bottom – 10', 100);
		}
		else
		{
			$d = (600 - $h)/2;
			$a = $t1->getTransparentColor();
			$c = $t1->resizeCanvas( 900, 600, 'center', 'center', $a );
			//$c = $c0->merge($wm100, 'center', 'bottom – 10', 100);
		}
		$c->saveToFile($main);
	*/
		/*
		if( $i = $wi->load($main) )
		{
			$c = $i->merge($wm75, 'center', 'bottom', 100);
			$c->saveToFile($main);
		}
		*/
		
		if( $wi->load($main)&&$wi->load($main210)&&$wi->load($main280)&&$wi->load($main100) )
		{
			$data['image'] = $stamp.$file;
			$data['success'] = TRUE;
			$data['error_msg'] = 'success';
			return $data;
		}
		else
		{
			$data['error'] =  " error saving main image";
			return $data;
		}
	}

}

?>
