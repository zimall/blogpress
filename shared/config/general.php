<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config["live"] = ENVIRONMENT!="development";
$config["admin_panels"] = array( "admin" );
$config["site-name"] = "BlogPress";
$config["club-type"] = "Default";
$config["address"] = "555 Test Address, Test City";
$config["full-address"] = $config["address"];
$config["phone"] = "0123456789";
$config["meetings"] = "Mon - Fri, 08:00 - 16:00";
$config["site-email"] = "info@example.com";
$config["no-reply"] = "no-reply@example.com";
$config["year-founded"] = "2019";
$config["facebook"] = "https://facebook.com/";
$config["twitter"] = "";
$config["twitter-widget-id"] = "";
$config["youtube"] = "";
$config["instagram"] = "";
$config["linkedin"] = "";
$config["general"] = "1";
$config["author"] = "BIT Technologies";
$config["author-url"] = "http://www.bittechnologyz.com";
$config["images_size_definitions"] = [ "xs"=>"Extra Small", "sm"=>"Small", "md"=>"Medium", "lg"=>"Large", "xl"=>"Extra Large" ];
$config['enable_profiler'] = false;
$config['article_sort'] = [
	1=>[ 'n'=>'Best Match', 'f'=>'at_date_posted', 's'=>'desc' ],
	2=>[ 'n'=>'Recently Posted', 'f'=>'at_date_posted', 's'=>'desc' ],
	3=>[ 'n'=>'Popularity', 'f'=>'at_hits', 's'=>'desc' ],
	4=>[ 'n'=>'Title (A-Z)', 'f'=>'at_title', 's'=>'asc' ],
	5=>[ 'n'=>'Title (Z-A)', 'f'=>'at_title', 's'=>'desc' ],
];
$config['default_sort_by'] = 2;
$config['google-analytics'] = "";
$config['google_recaptcha_site_key'] = "";
$config['google_recaptcha_secret'] = "";
$config['file-manager-access-key'] = "";