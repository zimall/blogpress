<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<meta charset="utf-8">
	<link rel="icon" href="<?php echo base_url('images/logos/fav.ico');?>" type="image/ico" />
	<title><?php echo $this->config->item('site-name')?> Admin Panel</title>
	<meta name="author" content="BIT Technologies" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<?php
	$this->carabiner->display('css');
	if( isset($stylesheet) ):
		foreach( $stylesheet as $css ):
			if( is_array($css) ):
				$css['href'] = "$views/css/".$css['href'].".css";
				echo link_tag($css);
			else:
				echo link_tag("$views/css/$css.css");
			endif;
		endforeach;
	endif;
	?>
	<?php if(isset($uploader)):?>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url().$views?>css/fineuploader.css">
	<?php endif;?>
	
	<!--[if lte IE 7]>
	<script src="<?php echo base_url()."myadmin/views/$theme/";?>js/lte-ie7.js"></script>
	<![endif]-->
	
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
	<![endif]-->
	<!--[if lte IE 9]>
	<script src="<?php echo base_url();?>scripts/placeholder.js" type="text/javascript"></script>
	<![endif]-->
</head>

<body id="body-<?php if(isset($nav_element)) echo $nav_element; else echo 'index';?>">


	
	<nav class="navbar navbar-inverse black-gradient" role="navigation">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php echo anchor( 'home', '<span class="rad">Site</span> Admin', 'class="navbar-brand brand"' );?>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav navbar-right">
				<form class="navbar-form navbar-left" style="min-width:220px;" role="search">
					<div style="max-width:200px;" class="row">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Go!</button>
							</span>
						</div><!-- /input-group -->
					</div><!-- /.col-lg-6 -->
				</form>
				<li>
					<?php echo anchor( base_url(), 'Site' );?>
				</li>
				<li>
					<?php echo anchor( base_url(), '<span class="glyphicon glyphicon-share-alt"></span>', 'target="_blank"' );?>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php echo "{$user_data['first_name']} {$user_data['last_name']}"?> <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo home_url('account');?>">My Account</a></li>
						<?php if($this->flexi_auth->is_privileged('Run Debug Mode')):?>
							<li><a href="?toggle_debug_mode=1"><?php echo $this->pc->debug_status()?></a></li>
						<?php endif;?>
						<li class="divider"></li>
						<li><?php echo anchor( home_url('account/logout'), 'Logout');?></li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</nav>


	<div class="col-sm-12">&nbsp;</div>

	<div class="col-sm-12 hidden-phone hidden-tablet top-stats">
			<ul class="top-nav-boxes">
				<li class="top-stats-arrow" title="Hide Top Stats" id="hide-top-stats">
					<?php echo img( array( 'src'=>"{$views}img/chevron-left.png", 'alt'=>'Hide Top Stats' ) );?>
				</li>
				<li class="first">
					<?php echo anchor( 'home', '<i class="radmin-home"></i> <span>Home</span>' );?>
				</li>
				<li>
					<?php echo anchor( 'about/index', '<i class="radmin-bookmark"></i> <span>About Us</span>' );?>
				</li>
				<li>
					<?php echo anchor( 'articles/index', '<i class="radmin-newspaper"></i> <span>All Articles</span>' );?>
				</li>
				<li>
					<?php echo anchor( 'articles/new_article', '<i class="radmin-pencil"></i> <span>New Article</span>' );?>
				</li>
				<li id="color-switcher-control">
					<?php echo anchor( 'settings', '<i class="radmin-cog"></i> <span>Settings</span>' );?>
				</li>
				<li id="color-switcher-control">
					<?php echo anchor( 'users', '<i class="radmin-user-2"></i> <span>Users</span>' );?>
				</li>
			</ul>

			<!--
			<div class="notifications-wrapper">
				<div class="color-switcher" id="color-switcher" style="display:none">
					<small>Choose style</small>
					<br />
					<span class="default color-switcher-color"></span>
					<span class="pink color-switcher-color"></span>
					<span class="green color-switcher-color"></span>
				</div>
			</div>
			-->
			
	</div>
	<div class="col-sm-12">&nbsp;</div>
	<div class="col-sm-1">&nbsp;</div>
	<div class="col-sm-10">
		<?php $this->load->view("$theme/common/error.tpl");?>
	</div>
