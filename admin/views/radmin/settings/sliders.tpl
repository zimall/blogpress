<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info"><?php echo $innertitle?></span>
		<span class="pull-right">
			<?php echo anchor( current_url().'?action=new_slide', 'New Banner', 'class="btn btn-primary btn-xs"' );?>
		</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'settings', '<i class="radmin-icon radmin-cog"></i>Settings' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-picture"></i> <?php echo $innertitle?>
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<table class="table table-dotted">
		<thead>
			<tr>
				<th>#</th>
				<th>Image</th>
				<th>Title / Action</th>
				<th>Summary</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($sliders as $k=>$v):?>
				<tr>
					<td><?php echo $k+$start;?></td>
					<td>
						<?php 
							if( $v['bn_link'] )
								$link = $v['bn_link'];
							else
							{
								$link = '#';
							}
							$try = "images/slider/{$v['bn_image']}";
							if( strlen($v['bn_image'])>3 && file_exists($try) ) $file = $try;
							else $file = 'images/noimage100x75.jpg';
							$img = array( 'src'=>$file, 'class'=>'thumbnail', 'style'=>'width:300px' );
							echo anchor( $link, img($img), 'target="_blank"' );
						?>
					</td>
					<td>
						<strong><?php echo $v['bn_title'];?></strong>
						<br>
						<?php echo $v['bn_action'];?>
						<br>
						Position: <?php echo $v['bn_position'];?>
					</td>
					<td>
						<?php echo $v['bn_subtitle'];?>
						<br>
						Slider Theme: <?php echo $v['bn_theme'];?>
					</td>
					<td>
						<?php echo anchor( current_url()."?action=edit_slide&id={$v['bn_id']}", 
							'<span class="radmin radmin-pencil"></span>', 
							'class="btn btn-xs btn-link" title="edit slide"' );
							
							$e = $v['bn_enabled']?'radmin-checkbox':'radmin-checkbox-unchecked';
							$et = $v['bn_enabled']?'Disable':'Enable';
							
							echo anchor( current_url()."?action=toggle_state&p=enabled&v={$v['bn_enabled']}&id={$v['bn_id']}", 
								'<span class="radmin '.$e.'" style="font-size:13px;"></span>', 
								'title="'.$et.'" class="btn btn-link btn-xs"' );
							echo anchor( current_url()."?action=delete_slider&id={$v['bn_id']}", 
								'<span class="radmin radmin-remove-3" style="color:red"></span>', 
								'title="Delete" class="btn btn-xs btn-link"' );
							?>
						
						
						
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
		<div class="col-xs-12">
			<br clear="all"><br clear="all">
			<ul class="pagination pull-left" style="margin: 0;">
				<li class="disabled">
					<a style="border:none;">
						Showing <?php echo $start?> to <?php echo $current_rows?> of <?php echo $total_rows?> items
					</a>
				</li>
			</ul>
			<ul class="pagination pull-right" style="margin: 0 0 5px 0;">
				<?php echo $this->pagination->create_links(); ?>
			</ul>
			<br clear="all"><br clear="all">
		</div>
		<!--end pagination-->
</div>
