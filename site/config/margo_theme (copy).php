<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Name: modern theme Config
*
* 
*/
$config['theme']['name'] = 'margo';
$config['theme']['description'] = 'Margo Bootstrap 3.3.6';

$config['theme']['colors'] = array( 'beige','blue','cyan','green','jade','orange','peach','pink','purple','red',
	'sky-blue','yellow' );
$config['theme']['color'] = 'yellow';

$config['theme']['top_bar_options'] = array( '0'=>'Light (Default)', 'dark-bar'=>'Dark Bar', 'color-bar'=>'Color Bar' );
$config['theme']['top_bar'] = '0';

$config['theme']['layout_options'] = array( '0'=>'Wide', 'boxed-page'=>'Boxed' );
$config['theme']['layout'] = 'boxed-page';

$config['theme']['background_images'] = array( '1.png','2.png','3.png','4.png','5.png','6.png','7.png','8.png','9.png','10.png',
	'11.png','12.png','13.png','14.png' );
$config['theme']['background'] = '1.png';

$config['theme']['logo_options'] = array( 'height'=>'60px', 'width'=>'auto' );
$config['theme']['logo'] = '1458035166rchc-logo.png';

$config['theme']['styles'] = 
	array( 'bootstrap.min', 'font-awesome.min', 'slicknav', 'style', 'responsive', 'animate', "colors/{$config['theme']['color']}", 
		'lightgallery.min', 'custom' );
									//, 'simplePagination' 'icon-style' 'auctions' 'jquery-ui-1.10.1.min',
$config['theme']['scripts'] = 
	array( 'jquery.migrate', 'modernizrr', 'bootstrap.min', 'jquery.fitvids', 'owl.carousel.min', 'nivo-lightbox.min', 
		'jquery.isotope.min', 'jquery.appear', 'count-to', 'jquery.textillate', 'jquery.lettering', 'jquery.easypiechart.min', 
		'jquery.nicescroll.min', 'jquery.parallax', 'mediaelement-and-player', 'jquery.slicknav', 'script' );

									
$config['pagination'] = array(
		'cur_tag_open' => '<li class="active"><a>',
		'cur_tag_close' => '</a></li>',
		'prev_tag_open' => '<li>',
		'prev_tag_close' => '</li>',
		'next_tag_open' => '<li>',
		'next_tag_close' => '</li>',
		'num_tag_open' => '<li>',
		'num_tag_close' => '</li>',
		'next_link' => 'more &raquo;',
		'prev_link' => '&laquo; prev',
		'first_link' => 'First',
		'first_tag_open' => '<li>',
		'first_tag_close' => '</li>',
		'last_link' => 'Last',
		'last_tag_open' => '<li>',
		'last_tag_close' => '</li>',
	);

$config['error_class'] = 'alert alert-warning alert-dismissable';
$config['success_class'] = 'alert alert-success alert-dismissable';
$config['info_class'] = 'alert alert-info alert-dismissable';

/* End of file margo_theme.php */
/* Location: ./site/config/margo_theme.php */
