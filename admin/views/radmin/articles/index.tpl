<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info"><?php echo $innertitle?></span>
        <span class="pull-right">
            <?php
                $u = uri_string();
                $u = preg_replace( "/page[^s]\/?\d*\/?$/", '', $u );
                $u = $u.'/new';
                $u = str_replace( '//', '/', $u );
                echo isset($search_term)?'':anchor( $u, 'New Article', ['class'=>'btn btn-sm btn-default'] );?>
        </span>
	</h2>
</div>


<div class="col-sm-12">
	<div class="info-bar">
		<div class="row">
			<div class="col-sm-6">
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
			</div>
			<div class="col-sm-6">
				<ul class="breadcrumb breadcrumb-right breadcrumb-left-xs">
					<li class="dropdown" style="margin-right: 30px;">
						Sort by <a href="#" data-toggle="dropdown"><?php echo get_sort_name($sort_id??'');?> <span class="caret"></span></a>
						<ul class="dropdown-menu change_page_sort">
							<?php $default_sort_fields = config_item('default_sort_fields');
								foreach($default_sort_fields as $k=>$v):
									?>
									<li><a href="#" data-id="<?php echo $k;?>"><?php echo $v['n'];?></a></li>
								<?php endforeach;?>
						</ul>
					</li>
					<li class="dropdown">
						Show <a href="#" data-toggle="dropdown"><?php echo $per??'';?> items <span class="caret"></span></a> per page
						<ul class="dropdown-menu change_per_page">
							<?php $list = config_item('items-per-page');
								$list = $list?explode(',',$list):[];
								foreach($list as $v):
									?>
									<li><a href="#"><?php echo trim($v);?></a></li>
								<?php endforeach;?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<table class="table table-dotted table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>Image</th>
				<th class="hidden-xs hidden-sm">Title / Section</th>
				<th class="hidden-xs">Summary</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($articles as $k=>$v):?>
				<tr class="<?php echo $v['at_enabled']?'':'active';?>">
					<td><?php echo $k+$start;?></td>
					<td>
						<?php
							$url = isset($search_term)?site_url($v['sc_value']):current_url();
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
							else $file = 'images/noimage.svg';
							$img = array( 'src'=>$file, 'class'=>'thumbnail' );
							echo anchor( $link, img($img), 'target="_blank" title="Click to view article"' );
						?>
                        <strong class="hidden-md hidden-lg"><?php echo $v['at_title'];?></strong>
					</td>
					<td class="hidden-xs hidden-sm">
						<strong><?php echo anchor( "{$url}?action=edit_article&id={$v['at_id']}", $v['at_title'], ['title'=>'Click to edit article']);?></strong>
						<br>
						<?php echo anchor( site_url('pages/categories')."?action=edit_page&id={$v['sc_id']}", $v['sc_name'], ['title'=>'Click to edit category']);?>
					</td>
					<td class="hidden-xs">
                        <span class="hidden-sm">
                            <?php echo $v['at_summary'];?>
                            <br>
                            <strong>Posted: </strong><?php echo pretty_date( $v['at_date_posted'] );?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <strong>Updated: </strong><?php echo pretty_date( $v['at_date_updated'] );?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
						Opened: <strong><?php echo $v['at_hits'].' time', $v['at_hits']==1?'':'s';?></strong>
						<span style="margin-left: 30px;">Position: <strong><?php echo $v['at_position'];?></strong></span>
					</td>
					<td>
						<?php
							echo anchor( "{$url}?action=edit_article&id={$v['at_id']}",
							'<span class="radmin radmin-pencil"></span>', 
							'class="btn btn-xs btn-link" title="edit article"' );
							
							$e = $v['at_enabled']?'radmin-checkbox':'radmin-checkbox-unchecked';
							$et = $v['at_enabled']?'Disable':'Enable';
							
							$f = $v['at_featured']?'radmin-star-3':'radmin-star';
							$ft = $v['at_featured']?'Remove from featured':'Add to featured';
							
							$p = $v['at_private']?'radmin-locked':'radmin-unlocked';
							$pt = $v['at_private']?'Make Public':'Make Private';
							
							echo anchor( "{$url}?action=toggle_state&p=enabled&v={$v['at_enabled']}&id={$v['at_id']}",
								'<span class="radmin '.$e.'" style="font-size:13px;"></span>', 
								'title="'.$et.'" class="btn btn-link btn-xs"' );
							echo anchor( "{$url}?action=toggle_state&p=featured&v={$v['at_featured']}&id={$v['at_id']}",
								'<span class="radmin '.$f.'" style="font-size:15px;"></span>', 
								'title="'.$ft.'" class="btn btn-link btn-xs"' );
							echo anchor( "{$url}?action=toggle_state&p=private&v={$v['at_private']}&id={$v['at_id']}",
								'<span class="radmin '.$p.'" style="font-size:15px;"></span>', 
								'title="'.$pt.'" class="btn btn-link btn-xs"' );
							echo anchor( "{$url}?action=duplicate&id={$v['at_id']}",
								'<span class="radmin radmin-copy" style="font-size:15px;"></span>',
								'title="Duplicate Article" class="btn btn-link btn-xs"' );
							echo anchor( "{$url}?action=delete_article&id={$v['at_id']}",
								'<span class="radmin radmin-remove-3" style="color:red"></span>', 
								'title="Delete" class="btn btn-xs btn-link"' );
							if( $v['sc_has_gallery'] )
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
