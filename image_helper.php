<?php
include_once( "./shared/libraries/wideimage/WideImage.php");
/*
 *	@function name: blur
 *	@description: load an image, stretch it, crop it then blur it.
 *	@parameter: $file - the file path for the image to load
 *	@parameter:	$fw - the final width of the cropped image.
 *	@parameter:	$fh - the final height of the cropped image.
 *	@parameter:	$save - path to save the final file.
*/
function filter_bg($file,$fw,$fh,$save, $filter=IMG_FILTER_GAUSSIAN_BLUR)
{
	$wi = new WideImage();
	$image = $wi->load($file);
	$w = $image->getWidth();
	$h = $image->getHeight();

	if($w > $h)
	{
		$stretch = $image->resize(null, $fh, 'outside', 'up');
		$crop = $stretch->crop('center', 'center', $fw, $fh);
		$blur = $crop->applyFilter($filter);
		//return $blur;
	}
	else
	{
		$stretch = $image->resize($fw, null, 'outside', 'up');
		$crop = $stretch->crop('center', 'center', $fw, $fh);
		$blur = $crop->applyFilter($filter);
		//return $blur;
	}

	$resize = $image->resize($fw, null, 'inside', 'down');
	$h2 = $resize->getHeight();
	if($h2>$fh)
	{
		$resize2 = $resize->resize(null, $fh, 'inside', 'down');
		$w2 = $resize2->getWidth();
		$c = $blur->merge( $resize2, 'center', 'center', 100 );
	}
	else
	{
		$c = $blur->merge( $resize, 'center', 'center', 100 );
	}

	return $c->saveToFile($save);
}

function simple_resize($file,$fw,$fh,$save)
{
	$wi = new WideImage();
	$image = $wi->load($file);
	$w = $image->getWidth();
	$h = $image->getHeight();

	$resize = $image->resize($fw, null, 'outside', 'up');
	$h2 = $resize->getHeight();
	if($h2>$fh)
	{
		$resize = $resize->resize(null, $fh, 'inside', 'down');
	}

	return $resize->saveToFile($save);
}

function transparent_bg( $file, $fw, $fh, $save, $blank )
{
	$image = $wi->load($file);
	$blank = $wi->load($blank);
	$t1 = $image->resize($fw, null, 'inside', 'down');
	$h = $t1->getHeight();
	if($h>$fh)
	{
		$t2 = $t1->resize(null, $fh, 'inside', 'down');
		$w2 = $t2->getWidth();
		//$a = $t1->getTransparentColor();
		$a = $blank->getTransparentColor();
		$d = ($fw - $w2)/2;
		$c = $t2->resizeCanvas( $fw, $fh, 'center', 'center', $a );
		//$c = $c0->merge($wm50, 'center', 'bottom – 5', 100);
	}
	else
	{
		$d = ($fh - $h)/2;
		$a = $t1->getTransparentColor();
		$c = $t1->resizeCanvas( $fw, $fh, 'center', 'center', $a );
		//$c = $c0->merge($wm50, 'center', 'bottom – 5', 100);
	}
	return $c->saveToFile($save);
}

function white_bg($file, $fw, $fh, $save)
{
	$image = $wi->load($file);
	$t1 = $image->resize($fw, null, 'inside', 'down');
	$h = $t1->getHeight();
	if($h>$fh)
	{
		$t2 = $t1->resize(null, $fh, 'inside', 'down');
		$w2 = $t2->getWidth();
		$a = $t1->getTransparentColor();
		$d = ($fw - $w2)/2;
		$c = $t2->resizeCanvas( $fw, $fh, 'center', 'center', $a );
		//$c = $c0->merge($wm75, 'center', 'bottom – 5', 100);
	}
	else
	{
		$d = ($fh - $h)/2;
		$a = $t1->getTransparentColor();
		$c = $t1->resizeCanvas( $fw, $fh, 'center', 'center', $a );
		//$c = $c0->merge($wm75, 'center', 'bottom', 100);
	}
	return $c->saveToFile($save);
}

function safe_file_name($str, $separator = '-', $lowercase = FALSE, $ext = '')
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

?>