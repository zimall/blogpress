<?php
	$prefix = '';
	if( defined('TABLE_PREFIX') ) $prefix = TABLE_PREFIX;
	else {
	 	if(!defined('BASEPATH')) define('BASEPATH', './');
		if(!defined('ENVIRONMENT')) define('ENVIRONMENT', $_SERVER['CI_ENV'] ?? 'production');
		$config = realpath('./shared/config/' . ENVIRONMENT . '/database.php');
		if(file_exists($config)) {
			include $config;
			$group = $active_group ?? "default";
			$prefix = $db[$group]['dbprefix'] ?? '';
		}
	}

	return [
		'table_storage' => [
			'table_name' => $prefix.'migrations',
			'version_column_name' => 'version',
			'version_column_length' => 1024,
			'executed_at_column_name' => 'executed_at',
			'execution_time_column_name' => 'execution_time',
		],

		'migrations_paths' => [
			'migrations' => './migrations/versions',
		],

		'all_or_nothing' => true,
		'check_database_platform' => true,
		'organize_migrations' => 'none',
		'connection' => null,
		'em' => null,
	];