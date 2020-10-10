<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Delete User Privilege</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'users', '<i class="radmin-icon radmin-group"></i>Users' );?>
		</li>
		<li>
			<?php echo anchor( current_url(), '<i class="radmin-icon radmin-spanner"></i>User Privileges' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-remove"></i> Delete Privilege
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-8 col-sm-offset-2">
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">Delete Privilege</h3>
		</div>
		<div class="panel-body">
			<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>
				Are you sure you want to remove<strong> <?php echo $privilege['upriv_name'];?></strong> from user privileges?<br>
				This action cannot be undone! All access to features requiring this privilege will also be lost. 
				<br>
				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="checkbox">
							<input type="checkbox" class="" name="confirm_delete" value="1"> I understand the risks
						</label>
					</div>
				</div>
				<br>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-1">
						<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default"' );?>
						<button type="submit" name="delete_privilege" value="1" class="btn btn-danger pull-right">Delete</button>
					</div>
				</div>
				<input type="hidden" name="form_name" value="user_privilege">
				<input type="hidden" name="form_type" value="delete">
				<input type="hidden" name="id" value="<?php echo $privilege['upriv_id'];?>">
			</form>
		</div>
	</div>
</div>
