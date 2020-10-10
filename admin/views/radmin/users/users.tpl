<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">User Accounts</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( 'users', '<i class="radmin-icon radmin-user-3"></i>User Accounts' );?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-user"></i> View All
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">
			<h3 class="panel-title">
				<span>User Accounts</span>
				<span class="pull-right">
					<?php echo anchor( current_url()."?action=add_user", 'Add User', 'class="btn btn-xs btn-primary"' );?>
				</span>
			</h3>
		</div>
			<table class="table table-condensed table-bordered">
				<thead>
					<tr class="default">
						<th>#</th>
						<th>Name</th>
						<th>Email / Username</th>
						<th>Group</th>
						<th>Status</th>
						<th>Date</th>
						<th>Options</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($users as $k=>$v):?>
						<tr>
							<td><?php echo $k+$start;?></td>
							<td>
								<?php echo "{$v['first_name']} {$v['last_name']}";?>
							</td>
							<td>
								<?php echo safe_mailto( $v['uacc_email'] );?><br>
								<?php echo $v['uacc_username'];?>
							</td>
							<td><?php 
								echo $v['ugrp_name'].'<br>';
								echo anchor( current_url()."?action=manage_group_privileges&id={$v['uacc_group_fk']}", 'Manage Privileges' );
							?>
							</td>
							<td>
								<?php 
									echo $v['uacc_suspend']?'Suspended':'Active';
									echo '<br>';
									echo $v['uacc_active']?'Confirmed':'Not Confirmed';
								?>
							</td>
							<td>
								Last Login: <?php 
									if(!is_numeric($v['uacc_date_last_login'])) 
										echo date( 'd m y, H:i a', strtotime($v['uacc_date_last_login']) ); 
									else echo date( 'd M Y, H:i a', $v['uacc_date_last_login'] );?><br>
								Registered: <?php 
									if(!is_numeric($v['uacc_date_added'])) 
										echo date( 'd M Y, H:i a', strtotime($v['uacc_date_added']) ); 
									else echo date( 'd M Y, H:i a', $v['uacc_date_added'] );?>
							</td>
							<td style="vertical-align:middle;"><?php 
								$state = $v['uacc_suspend']?'-unchecked':'';
								$title = $v['uacc_suspend']?'Activate Account':'Suspend Account';
								echo anchor( current_url()."?action=toggle_user_state&state={$v['uacc_suspend']}&id={$v['uacc_id']}", 
									'<span class="radmin-checkbox'.$state.'"></span>', 'title="'.$title.'"' ).'&nbsp;&nbsp;';
								echo anchor( current_url()."?action=update_account&id={$v['uacc_id']}", 
									'<span style="color:orange;" class="radmin-pencil-2"></span>', 'title="Edit Account"' ).'&nbsp;&nbsp;';
								echo anchor( current_url()."?action=remove_account&id={$v['uacc_id']}", 
									'<span style="color:red;" class="radmin-remove-3"></span>', 'title="Delete Account"' ).'<br>';
								echo anchor( current_url()."?action=reset_password&email={$v['uacc_email']}", 
							'<span class="radmin radmin-key-2"></span>','class="btn btn-xs btn-link" title="Reset Password"' ).' ';
						echo anchor( current_url()."?action=activate_account&email={$v['uacc_email']}", 
							'<span class="radmin radmin-mail-3"></span>', 
							'class="btn btn-xs btn-link" title="Resend Activation Email"' );
							?>
							</td>
						</tr>
					<?php endforeach;?>
			</table>
			<div class="panel-footer">
				<div class="col-xs-12">
					<br clear="all">
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
		</div>
	</div>
</div>
