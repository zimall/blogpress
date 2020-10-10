<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info"><?php echo $innertitle?></span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'articles', '<i class="radmin-icon radmin-clipboard"></i>Articles' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-clipboard-2"></i> <?php echo $innertitle?>
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
				<th>Title / Section</th>
				<th>Summary</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($articles as $k=>$v):?>
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
									$link = home_url( "{$v['sc_value']}/{$v['at_id']}/{$v['at_segment']}" );
							}
							$try = "images/articles/xs/{$v['at_image']}";
							if( strlen($v['at_image'])>3 && file_exists($try) ) $file = $try;
							else $file = 'images/articles/xs/placeholder.jpg';
							$img = array( 'src'=>$file, 'class'=>'thumbnail' );
							echo anchor( $link, img($img), 'target="_blank"' );
						?>
					</td>
					<td>
						<strong><?php echo $v['at_title'];?></strong>
						<br>
						<?php echo $v['sc_name'];?>
					</td>
					<td>
						<?php echo $v['at_summary'];?>
						<br>
						<strong>Posted: </strong><?php echo date( 'd M Y, H:i', $v['at_date_posted'] );?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<strong>Updated: </strong><?php echo date( 'd M Y, H:i', $v['at_date_updated'] );?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						Read by: <strong><?php echo $v['at_hits'];?></strong>
					</td>
					<td>
						<?php echo anchor( current_url()."?action=edit_article&id={$v['at_id']}", 
							'<span class="radmin radmin-pencil"></span>', 
							'class="btn btn-xs btn-link" title="edit article"' );
							
							$e = $v['at_enabled']?'radmin-checkbox':'radmin-checkbox-unchecked';
							$et = $v['at_enabled']?'Disable':'Enable';
							
							$f = $v['at_featured']?'radmin-star-3':'radmin-star';
							$ft = $v['at_featured']?'Remove from featured':'Add to featured';
							
							$p = $v['at_private']?'radmin-locked':'radmin-unlocked';
							$pt = $v['at_private']?'Make Public':'Make Private';
							
							echo anchor( current_url()."?action=toggle_state&p=enabled&v={$v['at_enabled']}&id={$v['at_id']}", 
								'<span class="radmin '.$e.'" style="font-size:13px;"></span>', 
								'title="'.$et.'" class="btn btn-link btn-xs"' );
							echo anchor( current_url()."?action=toggle_state&p=featured&v={$v['at_featured']}&id={$v['at_id']}", 
								'<span class="radmin '.$f.'" style="font-size:15px;"></span>', 
								'title="'.$ft.'" class="btn btn-link btn-xs"' );
							echo anchor( current_url()."?action=toggle_state&p=private&v={$v['at_private']}&id={$v['at_id']}", 
								'<span class="radmin '.$p.'" style="font-size:15px;"></span>', 
								'title="'.$pt.'" class="btn btn-link btn-xs"' );
							echo anchor( current_url()."?action=delete_article&id={$v['at_id']}", 
								'<span class="radmin radmin-remove-3" style="color:red"></span>', 
								'title="Delete" class="btn btn-xs btn-link"' );
							if( $v['sc_id']==11 )
								echo '<br>'.anchor( "articles/gallery/{$v['at_id']}", 
									'<span class="radmin radmin-picture"></span> Gallery Images', 'class="btn btn-link"' );
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
