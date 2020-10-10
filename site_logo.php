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
2.	350x260
3.	100x75 
4.	900x600
*/



private function createThumb()
{
$base = $_GET['folder'];
$data['error'] = TRUE;
$data['form'] = 'site_logo';
$data['folder'] = $base;
$temp = $_FILES['uploadfile']['tmp_name'];
$file = basename($_FILES['uploadfile']['name']); 
$file = str_replace( ' ', '-', $file);
$stamp = time();
$folder = "./{$base}";
$main = $folder.$stamp.$file;

if( !is_dir($folder) ) mkdir( $folder, 0777 );

if( !is_writable($folder) ) return array( 'error'=>$folder.' is not writable' );

include_once( "./shared/third_party/wideimage/WideImage.php");
	$wi = new WideImage();

// Load watermarks
	//$wm30 = $wi->load('./images/zimall-30px.png');
	//$wm50 = $wi->load('./images/zimall-50px.png');
	//$wm75 = $wi->load('./images/zimall-75px.png');
	//$wm140 = $wi->load('./images/zimall-140px.png');


/************* max-height of 400px **************/	
	$image = $wi->load($temp);
	$h = $image->getHeight();
	if($h>400)
	{
		$c = $image->resize(null, 120, 'inside', 'down');
	}
	else $c = $image;
	$c->saveToFile($main);

	if( $wi->load($main) )
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
