<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<link rel="icon" href="<?php echo base_url('images/fav.ico');?>" type="image/ico" />
	<title><?php echo $this->config->item('site-name')?> Admin Panel - Login</title>
	<meta name="author" content="BIT Technologies" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<?php $this->carabiner->display('css');	?>
	
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
<body id="body-login">
	<div class="container-fluid">
		<div class="col-sm-3"></div>
		<div class="col-sm-6 login-col-sm-">
			<div class="login-radmin align-center">
				<h1 class="brand">
					<span class="rad"><?php echo $this->config->item('site-name')?></span>
				</h1>
			</div>
			<?php $this->load->view("$theme/common/error.tpl");?>
