<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Settings</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( 'settings', '<i class="radmin-icon radmin-cog"></i>Settings' );?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-user"></i> View All
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Admin Panel</h3>
		</div>
		<div class="panel-body">
			Admin Settings
		</div>
	</div>
</div>

<div class="col-sm-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Site</h3>
		</div>
		<div class="panel-body">
			Site Settings
		</div>
	</div>
</div>

