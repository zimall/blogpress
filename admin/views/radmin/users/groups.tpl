<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">User Groups</span>
		<?php echo anchor( 'users/new_group', 'New User Group', 'class="btn btn-primary btn-sm pull-right"' );?>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>

		</li>
		<li>
			<?php echo anchor( 'users', '<i class="radmin-icon radmin-user-3"></i>Users' );?>

		</li>
		<li class="active">
			<i class="radmin-icon radmin-user"></i> User Groups
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<table class="table table-condensed table-dotted">
		<tr>
			<th>#</th>
			<th>Group Name</th>
			<th>Description</th>
			<th>Admin?</th>
			<th>Options</th>
		</tr>
		<?php foreach($groups as $k=>$v):?>
		<tr>
			<td><?php echo $k+1;?></td>
			<td><?php echo $v['ugrp_name'];?></td>
			<td><?php echo $v['ugrp_desc'];?></td>
			<td><?php echo $v['ugrp_admin']?'Yes':'No';?></td>
			<td><?php 
				echo anchor( current_url().'?action=edit_group&id='.$v['ugrp_id'], 
					'<span class="radmin radmin-pencil"></span>', 'class="btn btn-primary btn-xs" title="edit group info"' ).'&nbsp;';
				echo anchor( current_url().'?action=manage_group_privileges&id='.$v['ugrp_id'], 
					'<span class="radmin radmin-cog"></span>', 'class="btn btn-warning btn-xs" title="edit group privileges"' ).'&nbsp;';
				echo anchor( current_url().'?action=delete_group&id='.$v['ugrp_id'], 
					'<span class="radmin radmin-remove"></span>', 'class="btn btn-danger btn-xs" title="delete group"' );
			?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
</div>
