<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Pages</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'pages', '<i class="radmin-icon radmin-book"></i>Site Pages' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-file"></i> View All
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>


<div class="col-sm-12">
	<table class="table table-condensed table-dotted table-hover">
		<tr>
			<th>ID</th>
			<th>Page Name</th>
			<th>Menu Position</th>
			<th>Options</th>
		</tr>
		
		<?php foreach($pages as $k=>$page):?>
		<tr>
			<td><?php echo $page['sc_id'];?></td>
			<td><?php echo anchor( home_url($page['sc_value']), $page['sc_name'], 'target="_blank"');?></td>
			<td><?php echo $page['sc_menu'];?></td>
			<td>
				<?php echo anchor( current_url()."?action=edit_page&id={$page['sc_id']}", '<span class="btn btn-default btn-xs"> <i class="radmin-icon radmin-pencil"></i> </span>' );?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
</div>
