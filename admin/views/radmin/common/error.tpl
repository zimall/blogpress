<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	$err = $this->actions->gem();
	$fa = FALSE;//$this->session->flashdata('message');
	if($fa):?> 
		<div class="row">
			<div style="margin-top:10px;">
				<?php echo $fa;?>
			</div>
	</div>
	<?php endif;
	if( !empty( $err ) ):?>
		<div class="row" id="error_div">
		<?php
		foreach( $err as $e ):?>
			<div class="alert alert-<?php echo $e['f']?>"><a href="#" class="close" data-dismiss="alert">&times;</a>
				<?php echo $e['msg'];?>
			</div>
		<?php endforeach;?>
		</div>
	<?php endif;
	$ve = validation_errors();
	if(!empty($ve)):?>
		<div class="row">
			<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">&times;</a>
				<?php echo $ve;?>
			</div>
		</div>
	<?php endif;?>
