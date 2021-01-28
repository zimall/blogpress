<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<footer class="black-gradient row-fluid">
	<div class="square-turtle col-sm-6">
		<p>
			2012 - <?php echo date('Y');?> &copy; All rights reserved &nbsp;&nbsp;&nbsp;&nbsp;
			powered by <a href="http://www.bittechnologyz.com" target="_blank">Zimall Web Services</a>
		</p>
	</div>
	<div class="col-sm-6" style="text-align:right">
		<a class="brand" href="<?php echo site_url('home');?>">
			<span class="rad">Site</span> Admin
		</a>
	</div>
</footer>


<form class="hidden">
	<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url()?>">
	<input type="hidden" name="site_url" id="site_url" value="<?php echo site_url()?>">
	<input type="hidden" name="current_url" id="current_url" value="<?php echo current_url()?>">
</form>

<form class="hidden" action="<?php echo current_url()?>" method="get" id="refresh"></form>

<?php
    echo form_open(full_url(), 'id="change_per_page_form"');
    echo form_input([ 'name'=>'per_page', 'value'=>'', 'type'=>'hidden', 'id'=>'change_per_page' ]);
    echo form_close();

$this->carabiner->display('js');

if( isset($scripts) ):
	if( is_array($scripts) ):
	//$scripts = array_unique($scripts);
	foreach( $scripts as $n=>$f ):
			if(is_array($f)):
				if($f['echo']):
					echo '<script>';
					$this->load->file("scripts/$n.js");
					echo '</script>';
				endif;
			else:
				echo '<script src="'.base_url().'scripts/'.$f.'.js"></script>';
			endif;
	endforeach;
	endif;
endif;

$this->sc->display('js');

if( isset($ckeditor) ):?>
	<script type="text/javascript" src="<?php echo base_url() ?>scripts/ckeditor/ckeditor.js"></script>

	<?php if($ckeditor=='basic'):?>
		<script>
					CKEDITOR.replace( 'ckeditor1',
					{
						toolbar : 'Basic',
						//uiColor : '#9AB8F3',
						enterMode : CKEDITOR.ENTER_BR
					});
		</script>
	<?php else:?>
	
		<script>
					CKEDITOR.replace( 'ckeditor1',
					{
						enterMode : CKEDITOR.ENTER_BR
					});
		</script>
				
<?php endif; endif;

if( isset($theme_scripts) ):
	if( is_array($theme_scripts) ):
	$theme_scripts = array_unique($theme_scripts);
	foreach( $theme_scripts as $f ):
		echo '<script src="'.$views.'/js/'.$f.'.js"></script>';
	endforeach;
	endif;
endif;


if(isset($uploader)):
	if($uploader=='ajax'):?>
	<script src="<?php echo base_url() ?>scripts/ajaxupload.js"></script>
	<script src="<?php echo base_url() ?>scripts/images.js"></script>
<?php elseif( $uploader=='fine' || $uploader=='music'):?>
<script src="<?php echo base_url() ?>scripts/fine/header.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/util.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/button.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/handler.base.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/handler.form.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/handler.xhr.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/uploader.basic.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/dnd.js"></script>
<script src="<?php echo base_url() ?>scripts/fine/uploader.js"></script>
<script src="<?php echo base_url() ?>scripts/<?php echo $uploader;?>.js"></script>
<?php endif;endif;?>



<!--[if lte IE 8]>
<script language="javascript" type="text/javascript" src="<?php echo base_url();?>myadmin/views/<?php echo $theme;?>/js/flot/excanvas.min.js"></script>
<![endif]-->

<script type="text/javascript">
	jQuery(function($){
		/*
		flot_activity('flot-line-graph', flot_options_sin_cos);
		flot_sales( 'flot-sales-graph', flot_options_sin_cos );
		flot_line('flot-line-graph', flot_options_sin_cos);
		flot_line('flot-line-graph', flot_options_line);
		flot_sin_cos('flot-multiple-line', flot_options_sin_cos);
		flot_line_b('flot-gradient-line', flot_options_line);
		*/
		$('a[rel=tooltip], button[rel=tooltip], input[rel=tooltip]').tooltip({
			animation:false
		});
		
		$('a[data-toggle=popover], button[data-toggle=popover], input[data-toggle=popover]').popover({
			animation:false,
			placement:'top',
			trigger:'hover'
		});
		
		
		$('.dropdown-toggle').dropdown();
		
	});
</script>

<?php if(isset($fancybox)):?><script>productFancyBox();</script><?php endif;?>

</body>
</html>
