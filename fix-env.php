<?php
if(!file_exists('./.env')){
	$file = fopen("./.env", "w") or die("Unable to open file!");
	$env = $_SERVER['CI_ENV']??'production';
	fwrite($file, 'CI_ENV="'.$env.'"');
	fclose($file);
}
?>