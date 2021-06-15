<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info"><?=$sad_title;?></span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li class="active">
			<?php echo anchor( $sad_url, '<i class="radmin radmin-blocked"></i>'.$sad_title );?>
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel-body alert alert-warning" style="color: #555;">
		<div class="col-sm-6" style="border-right: 1px solid #ccc;">
			<h1 class="" style="font-size:13em;text-align:center;">
				<span class="fa fa-exclamation-circle" style="font-size:0.5em;"></span>
				<span>403</span>
			</h1>
			<h2>
				Oops!..... Access has been denied <span class="fa fa-hand-paper-o"></span>
			</h2>
		</div>
		<div class="col-sm-6">
			<h2><?=$sad_title;?></h2>
			<h4><?=$sad_msg;?></h4>
			<br clear="all">
			<h4>Options</h4>
			<style>
                .options-403 li
                {
                    padding: 3px 10px;
                }
			</style>
			<ol class="options-403">
				<li><a href="javascript:history.go(-1)">Back To Previous Page</a></li>
				<li><?php echo anchor( 'home', 'Return Home' );?></li>
				<li><?php echo anchor( base_url(), 'Go To Site' );?></li>
				<li><?php echo anchor( $sad_url, 'Retry' );?></li>
				<!-- <li><?php echo safe_mailto( $this->config->item('system-support-email'), 'Request Access' );?></li> -->
			</ol>
		</div>
		<!-- col-sm-6 -->
	</div>
	<!-- panel-body -->
</div>
<!-- col-sm-12 -->
