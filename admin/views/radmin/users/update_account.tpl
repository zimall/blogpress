<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Update User Account</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( current_url(), '<i class="radmin-icon radmin-user"></i>User Accounts' );?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-pencil"></i> Update Account
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>

	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Profile Details</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-3 control-label">First Name</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" placeholder="First Name and Initials" name="update_first_name" 
							value="<?php echo $account['first_name'];?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Last Name</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" placeholder="Surname" name="update_last_name" 
							value="<?php echo $account['last_name'];?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Phone Number</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" placeholder="Username" name="update_phone" 
							value="<?php echo $account['phone'];?>" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<div class="checkbox">
							<?php $c = ($account['newsletter'])?'checked':'';?>
							<label>
								<input type="hidden" name="update_newsletter" value="0">
								<input name="update_newsletter" value="1" type="checkbox" <?php echo $c;?>> Newsletter
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Bio</label>
					<div class="col-lg-9">
						<textarea name="update_bio" class="form-control" rows=5><?php 
							echo html_entity_decode($account['bio']);?></textarea>
					</div>
				</div>
				
				
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Account Details</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-3 control-label">Email</label>
					<div class="col-lg-9">
						<input type="email" class="form-control" placeholder="Email Address" name="update_email_address" 
							value="<?php echo $account['uacc_email'];?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Username</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" placeholder="Username" name="update_username" 
							value="<?php echo $account['uacc_username'];?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Group</label>
					<div class="col-lg-9">
						<select name="update_user_group" class="form-control input-sm" required>
						<?php foreach($groups as $k=>$v):
							$s = ( $account['uacc_group_fk']==$v['ugrp_id'] )?'selected':'';?>
							<option value="<?php echo $v['ugrp_id'];?>" <?php echo $s;?>><?php echo $v['ugrp_name']?></option>
						<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<div class="checkbox">
							<?php $c = ($account['uacc_suspend'])?'':'checked';?>
							<label>
								<input type="hidden" name="update_account" value="0">
								<input name="update_suspend" value="1" type="checkbox" <?php echo $c;?>> Active
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Update Account</h3>
			</div>
			<div class="panel-body">
				<input type="hidden" name="form_name" value="account">
				<input type="hidden" name="form_type" value="update">
				<input type="hidden" name="id" value="<?php echo $account['uacc_id'];?>">
				<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default"' );?>
				<button type="submit" name="update_user_account" value="1" class="btn btn-primary">Save Changes</button>
			</div>
		</div>
	</div>

</form>
