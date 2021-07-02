<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$theme_config['name'] = 'sahtc2';
$theme_config['description'] = 'Colorlib Sahtc2 - Bootstrap v4.1.3 Wide Blog Template';

$theme_config['colors'] = array( 'green','blue' );
$theme_config['color'] = 'green';

$theme_config['top_bar_options'] = array( '0'=>'Light (Default)', 'dark-bar'=>'Dark Bar', 'color-bar'=>'Color Bar' );
$theme_config['top_bar'] = 'color-bar';

$theme_config['layout_options'] = array( '0'=>'Wide', 'boxed-page'=>'Boxed' );
$theme_config['layout'] = 'boxed-page';

$theme_config['background_images'] = array();
$theme_config['background'] = '';

$theme_config['logo_options'] = array( 'height'=>'60px', 'width'=>'auto' );
$theme_config['logo'] = 'sahtclogo2.png';

$theme_config['image_sizes'] = ['xs'=>'100x75','sm'=>'250x180','md'=>'350x225','lg'=>'1024x768','xl'=>'1280xauto'];
$theme_config['slider']['size'] = array( 'width'=>'1200', 'height'=>'550' );
$theme_config['slider']['title_animation'] = 
	array('animated1'=>'Animation 1','animated2'=>'Animation 2','animated3'=>'Animation 3',
		'animated4'=>'Animation 4','animated5'=>'Animation 5','animated6'=>'Animation 6',
		'animated7'=>'Animation 7','animated8'=>'Animation 8' );
$theme_config['slider']['title_styles'] = array('default', 'white');
$theme_config['slider']['title'] = array( 'tag'=>'<h2>', 'text'=>'<span>', 'special'=>'<strong>' );
$theme_config['slider']['subtitle'] = array( 'tag'=>'<h3>', 'text'=>'<span>', 'special'=>FALSE );
$theme_config['slider']['action'] = array( 'tag'=>'<p>', 'text'=>'<a>', 'special'=>FALSE );

$theme_config['styles'] = 
	[ 'bootstrap.min', 'font-awesome.min', 'owl-carousel/owl.carousel.min', 'owl-carousel/owl.theme.default.min', 'owl-carousel/carousel.min',
		'nucleo-webfonts/mini/css/nucleo-mini', 'nucleo-webfonts/outline/css/nucleo-outline', 'style', 'responsive', "colors/{$theme_config['color']}" ];

$theme_config['scripts'] =
	[ 'jquery-3.2.1.min', 'jquery-migrate-3.0.1.min','popper.min', 'bootstrap.min', 'owl.carousel.min', 'nivo-lightbox.min',
		'jquery.waypoints.min', 'jquery.owl-filter', 'jquery.counterup.min', 'jquery.countdown.min','main', 'twitter.widgets' ];

$theme_config['fonts'] = []; //['https://fonts.googleapis.com/css?family=Roboto'];
//['https://fonts.googleapis.com/css?family=Josefin+Sans:300, 400,700|Inconsolata:400,700'];
									
$theme_config['pagination'] = array(
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

$theme_config['error_class'] = 'alert alert-warning alert-dismissable';
$theme_config['success_class'] = 'alert alert-success alert-dismissable';
$theme_config['info_class'] = 'alert alert-info alert-dismissable';


$theme_config['required_content'] = [
	Home::class => [
		'popular_courses'=>[
			'where' => ['at_section']
		]
	]
];

/* End of file margo_theme.php */
/* Location: ./site/config/margo_theme.php */
