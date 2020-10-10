<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['theme']['name'] = 'wordify';
$config['theme']['description'] = 'Colorlib Wordify - Bootstrap v4.1.3 Minimal Blog Template';

$config['theme']['colors'] = array( 'purple','darkslateblue' );
$config['theme']['color'] = 'purple';

$config['theme']['top_bar_options'] = array( '0'=>'Light (Default)', 'dark-bar'=>'Dark Bar', 'color-bar'=>'Color Bar' );
$config['theme']['top_bar'] = 'color-bar';

$config['theme']['layout_options'] = array( '0'=>'Wide', 'boxed-page'=>'Boxed' );
$config['theme']['layout'] = 'boxed-page';

$config['theme']['background_images'] = array( '1.png','2.png','3.png','4.png','5.png','6.png','7.png','8.png','9.png','10.png',
	'11.png','12.png','13.png','14.png', '15.jpg' );
$config['theme']['background'] = '1.png';

$config['theme']['logo_options'] = array( 'height'=>'60px', 'width'=>'auto' );
$config['theme']['logo'] = '1554997257dbl-black-BG-1000x.png';

$config['theme']['image_sizes'] = ['xs'=>'100x75','sm'=>'250x180','md'=>'350x225','lg'=>'1024x768','xl'=>'1280xauto'];
$config['theme']['slider']['size'] = array( 'width'=>'1200', 'height'=>'550' );
$config['theme']['slider']['title_animation'] = 
	array('animated1'=>'Animation 1','animated2'=>'Animation 2','animated3'=>'Animation 3',
		'animated4'=>'Animation 4','animated5'=>'Animation 5','animated6'=>'Animation 6',
		'animated7'=>'Animation 7','animated8'=>'Animation 8' );
$config['theme']['slider']['title_styles'] = array('default', 'white');
$config['theme']['slider']['title'] = array( 'tag'=>'<h2>', 'text'=>'<span>', 'special'=>'<strong>' );
$config['theme']['slider']['subtitle'] = array( 'tag'=>'<h3>', 'text'=>'<span>', 'special'=>FALSE );
$config['theme']['slider']['action'] = array( 'tag'=>'<p>', 'text'=>'<a>', 'special'=>FALSE );

$config['theme']['styles'] = 
	[ 'bootstrap', 'font-awesome.min', 'animate', 'owl.carousel.min', '../fonts/ionicons/css/ionicons.min', '../fonts/fontawesome/css/font-awesome.min','../fonts/flaticon/font/flaticon', 'lightgallery.min', 'style', "colors/{$config['theme']['color']}" ];
									//, 'simplePagination' 'icon-style' 'auctions' 'jquery-ui-1.10.1.min',
$config['theme']['scripts'] = 
	[ 'jquery-migrate-3.0.1.min','popper.min', 'bootstrap.min', 'owl.carousel.min', 'nivo-lightbox.min', 
		'jquery.waypoints.min', 'jquery.stellar.min', 'main' ];

$config['theme']['fonts'] = ['https://fonts.googleapis.com/css?family=Roboto'];
//['https://fonts.googleapis.com/css?family=Josefin+Sans:300, 400,700|Inconsolata:400,700'];
									
$config['pagination'] = array(
		'cur_tag_open' => '<li class="page-item active"><a class="page-link">',
		'cur_tag_close' => '</a></li>',
		'prev_tag_open' => '<li class="page-item">',
		'prev_tag_close' => '</li>',
		'next_tag_open' => '<li class="page-item">',
		'next_tag_close' => '</li>',
		'num_tag_open' => '<li class="page-item">',
		'num_tag_close' => '</li>',
		'next_link' => '&gt;',
		'prev_link' => '&lt;',
		'first_link' => 'First',
		'first_tag_open' => '<li class="page-item">',
		'first_tag_close' => '</li>',
		'last_link' => 'Last',
		'last_tag_open' => '<li class="page-item">',
		'last_tag_close' => '</li>',
		'attributes' => [ 'class'=>'page-link' ]
	);

$config['error_class'] = 'alert alert-warning alert-dismissable';
$config['success_class'] = 'alert alert-success alert-dismissable';
$config['info_class'] = 'alert alert-info alert-dismissable';

/* End of file margo_theme.php */
/* Location: ./site/config/margo_theme.php */
