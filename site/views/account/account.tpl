<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Account Operations - <?php echo $this->config->item('site-name');?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" href="<?php echo base_url("images/logos/fav.ico");?>" type="image/ico">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('site/views/account/');?>css/main.css">
<!--===============================================================================================-->
</head>
<body style="background-color: #999999;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="col-sm-6 col-md-8 login100-more" style="background-image: url('<?php echo base_url('site/views/account/');?>images/bg-02.jpg');">
				<div class="logo">
					<?php 
						if( $theme_config['logo'] == 'logo' )
							$logo = strtolower($this->config->item('club-type')).'.png';
						else $logo = $theme_config['logo'];
						$img = array( 'src'=>"site/views/$theme/images/logos/{$logo}", 
							'class'=>'img-responsive', 'style'=>'max-height:250px;' );
						echo anchor( base_url(), img($img).'&nbsp;', 
							'style="padding:0px 0;"' );
					?>
				</div>
				<div class="errors">
					<?php $this->load->view("$theme/common/error.tpl");?>
				</div>
			</div>
			<div class="col-sm-6 col-md-4 wrap-login100 p-l-50 p-r-50 p-t-50 p-b-50">
				<?php $this->load->view("account/{$section}.tpl");?>
			</div>
		</div>
	</div>
<!--===============================================================================================-->
	<script src="<?php echo base_url('site/views/account/');?>vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('site/views/account/');?>vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('site/views/account/');?>vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url('site/views/account/');?>vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('site/views/account/');?>vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('site/views/account/');?>vendor/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url('site/views/account/');?>vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('site/views/account/');?>vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('site/views/account/');?>js/main.js"></script>

</body>
</html>
