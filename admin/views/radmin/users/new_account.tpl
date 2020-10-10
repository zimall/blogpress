<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">New User Account</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( current_url(), '<i class="radmin-icon radmin-user"></i>User Accounts' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-pencil"></i> New Account
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
						<input type="text" class="form-control" placeholder="First Name and Initials" name="register_first_name" 
							value="<?php echo $this->input->post('register_first_name');?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Last Name</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" placeholder="Surname" name="register_last_name" 
							value="<?php echo $this->input->post('register_last_name');?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Phone Number</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" placeholder="e.g +263771234567" name="register_phone_number" 
							value="<?php echo $this->input->post('register_phone_number');?>" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-3 control-label">Bio</label>
					<div class="col-lg-9">
						<textarea name="register_bio" class="form-control" rows=3><?php 
							echo html_entity_decode($this->input->post('register_bio'));?></textarea>
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
						<input type="email" class="form-control" placeholder="Email Address" name="register_email_address" 
							value="<?php echo $this->input->post('register_email_address');?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Username</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" placeholder="Username" name="register_username" 
							value="<?php echo $this->input->post('register_username');?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Group</label>
					<div class="col-lg-9">
						<select name="register_user_group" class="form-control input-sm" required>
						<?php foreach($groups as $k=>$v):
							$s = ( $this->input->post('register_user_group')==$v['ugrp_id'] )?'selected':'';?>
							<option value="<?php echo $v['ugrp_id'];?>" <?php echo $s;?>><?php echo $v['ugrp_name']?></option>
						<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<div class="checkbox">
							<?php $c = ($this->input->post('register_suspend'))?'':'checked';?>
							<label>
								<input type="hidden" name="register_account" value="0">
								<?php echo form_checkbox( 'register_suspend', 1, $this->input->post('register_suspend') );?> Active
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-3 col-lg-9">
						<input type="hidden" name="form_name" value="account">
						<input type="hidden" name="form_type" value="insert">
						<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default"' );?>
						<button type="submit" name="register_user_account" value="1" class="btn btn-primary">Create Account</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- col-sm-6-->

</form>
