<?php $this->load->view($theme.'/common/login-header.tpl'); ?>
			
			<div class="login-wrapper row">
				<div class="login-inner">
					<h2 class="sign-in">Register</h2>
					<small class="muted">Please enter your details to create an account</small>
					<div class="squiggly-border"></div>
					<div class="login-inner col-sm-10 col-sm-offset-1">
						<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>
							<div class="form-group">
								<div class="input-group input-group-md">
									<span class="input-group-addon"><i class="radmin-icon radmin-user-2"></i></span>
									<input type="text" name="register_first_name" class="form-control" placeholder="First Name">
								</div>
							</div>
							
							<div class="form-group">
								<div class="input-group input-group-md">
									<span class="input-group-addon"><i class="radmin-icon radmin-user-3"></i></span>
									<input type="text" name="register_last_name" class="form-control" placeholder="Surname">
								</div>
							</div>
							
							<div class="form-group">
								<div class="input-group input-group-md">
									<span class="input-group-addon"><i class="radmin-icon radmin-mobile"></i></span>
									<input type="text" name="register_phone_number" class="form-control" placeholder="Phone Number">
								</div>
							</div>
							
							<div class="form-group">
								<div class="input-group input-group-md">
									<span class="input-group-addon"><i class="radmin-icon radmin-mail"></i></span>
									<input type="email" name="register_email_address"class="form-control" placeholder="Email Address">
								</div>
							</div>
							
							<div class="form-group">
								<div class="input-group input-group-md">
									<span class="input-group-addon"><i class="radmin-icon radmin-user"></i></span>
									<input type="text" name="register_username" class="form-control" placeholder="Username">
								</div>
							</div>
							
							<div class="form-group">
								<div class="input-group input-group-md">
									<span class="input-group-addon"><i class="radmin-icon radmin-key"></i></span>
									<input type="password" name="register_password" class="form-control" placeholder="Password">
								</div>
							</div>
							
							<div class="form-group">
								<div class="input-group input-group-md">
									<span class="input-group-addon"><i class="radmin-icon radmin-key"></i></span>
									<input type="password" name="register_confirm_password" class="form-control" 
										placeholder="Confirm Password">
								</div>
							</div>
							
							<div class="form-group">
								<span class="pull-left">Already have an account? 
									<?php echo anchor( 'login', 'Login here' )?>
								</span>
								<button type="submit" name="register_user" value="1" 
									class="btn btn-primary btn-lg pull-right">Register</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-sm-3"></div>
			<br clear="all"><br clear="all"><br clear="all">
		</div>

	</div>



<?php $this->load->view($theme.'/common/login-footer.tpl'); ?>
