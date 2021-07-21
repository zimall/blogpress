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
$config['default_sort_fields'] = [
	0=>[ 'n'=>'Best Match', 'f'=>'at_date_posted', 's'=>'desc', 'hide'=>false ],
	1=>['f'=>'at_id', 's'=>'asc', 'n'=>'Article ID Ascending', 'hide'=>true],
	2=>[ 'f'=>'at_id', 's'=>'desc', 'n'=>'Article ID Descending', 'hide'=>true ],
	9=>[ 'f'=>'at_hits', 's'=>'asc', 'n'=>'Popularity', 'hide'=>false ],
	3=>[ 'f'=>'at_title', 's'=>'asc', 'n'=>'Title (A-Z)', 'hide'=>false ],
	4=>[ 'f'=>'at_title', 's'=>'desc', 'n'=>'Title (Z-A)', 'hide'=>false ],
	5=>[ 'f'=>'at_date_posted', 's'=>'desc', 'n'=>'Recently Posted', 'hide'=>false ],
	6=>[ 'f'=>'at_date_posted', 's'=>'asc', 'n'=>'Oldest Posts First', 'hide'=>false ],
	7=>[ 'f'=>'at_date_updated', 's'=>'asc', 'n'=>'Date Updated Ascending', 'hide'=>true ],
	8=>[ 'f'=>'at_date_updated', 's'=>'desc', 'n'=>'Date Updated Descending', 'hide'=>true ],
	10=>[ 'f'=>'at_hits', 's'=>'desc', 'n'=>'Article Hits Descending', 'hide'=>true ],
	11=>[ 'f'=>'at_position', 's'=>'asc', 'n'=>'Article Position Ascending', 'hide'=>true ],
	12=>[ 'f'=>'at_position', 's'=>'desc', 'n'=>'Article Position Descending', 'hide'=>true ],
];
$config['default_sort_by'] = 2;
$config['items-per-page'] = '5,10,20,50,100';
$config['google-analytics'] = "";
$config['google_recaptcha_site_key'] = "";
$config['google_recaptcha_secret'] = "";
$config['file-manager-access-key'] = "";