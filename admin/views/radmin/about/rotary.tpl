<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Rotary International</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( 'about', '<i class="radmin-icon radmin-clipboard"></i>About' );?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-clipboard-2"></i> Rotary International
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
				<th>Title</th>
				<th>Summary</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($rotary as $k=>$v):?>
				<tr>
					<td><?php echo $k+$start;?></td>
					<td>
						<?php 
							if( $v['at_permalink'] )
								$link = home_url($v['at_segment']);
							else
							{
								$sections = explode( '/', $v['sc_value'] );
								if( count($sections)>1 )
									$link = home_url( "{$v['sc_value']}/{$v['at_id']}/{$v['at_segment']}" );
								else 
									$link = home_url( "{$v['sc_value']}/i/{$v['at_id']}/{$v['at_segment']}" );
							}
							$try = "images/articles/100x75/{$v['at_image']}";
							if( strlen($v['at_image'])>3 && file_exists($try) ) $file = $try;
							else $file = 'images/noimage100x75.jpg';
							$img = array( 'src'=>$file, 'class'=>'thumbnail' );
							echo anchor( $link, img($img), 'target="_blank"' );
						?>
					</td>
					<td>
						<strong><?php echo $v['at_title'];?></strong>
					</td>
					<td><?php echo $v['at_summary'];?></td>
					<td>
						<?php echo anchor( current_url()."?action=edit_article&id={$v['at_id']}", 
							'<span class="radmin radmin-pencil"></span>', 
							'class="btn btn-xs btn-primary" title="edit article"' );?>
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
