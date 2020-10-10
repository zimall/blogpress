<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Remove Account</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( current_url(), '<i class="radmin-icon radmin-user-3"></i>User Accounts' );?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-remove"></i> Remove User Account
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-8 col-sm-offset-2">
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">Remove User Account</h3>
		</div>
		<div class="panel-body">
			<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>
				Are you sure you want to remove<strong> <?php echo $account['first_name'];?>'s</strong> account?<br>
				This action cannot be undone! All information linked to this account will also be lost. 
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
						<button type="submit" name="remove_account" value="1" class="btn btn-danger pull-right">Delete</button>
					</div>
				</div>
				<input type="hidden" name="form_name" value="account">
				<input type="hidden" name="form_type" value="delete">
				<input type="hidden" name="id" value="<?php echo $account['uacc_id'];?>">
			</form>
		</div>
	</div>
</div>
