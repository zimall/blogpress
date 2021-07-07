<?php
if(!file_exists('../thumbs')){
	$dir = mkdir('../thumbs', '0775');
	if(!$dir) die("Unable to create thumbs folder!");
	$file = fopen("../thumbs/index.html", "w") or die("Unable to write to thumbs folder!");
	$html = '<!DOCTYPE html><html lang="en"><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
	fwrite($file, $html);
	fclose($file);
}
?>