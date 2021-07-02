<?php
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();
	$r = [];
	$table_prefix = '';
	if(!defined('BASEPATH')) define('BASEPATH', './');
	if(!defined('ENVIRONMENT')) define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
	$config = realpath('./shared/config/'.ENVIRONMENT.'/database.php');
	if(file_exists($config)) {
		include($config);
		$group = $active_group??"default";
		$r = [
			'dbname' =>  $db[$group]['database']??false,
			'user' => $db[$group]['username']??false,
			'password' => $db[$group]['password']??false,
			'host' => $db[$group]['hostname']??false,
			'driver' => $db[$group]['dbdriver']??'pdo_mysql'
		];
		$table_prefix = $db[$group]['dbprefix']??'';
		if($r['driver']==='pdo') $r['driver'] = 'pdo_mysql';
	}
	define('TABLE_PREFIX', $table_prefix);
	return $r;