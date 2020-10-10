<?php $this->load->view($theme.'/common/login-header.tpl'); ?>

			<div class="login-wrapper row">
				<div class="login-inner">
					<h2 class="sign-in">Sign in</h2>
					<small class="muted">Please sign in using your registered account details</small>
					<div class="squiggly-border"></div>
					<div class="login-inner col-sm-10 col-sm-offset-1">
						<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>
							<div class="form-group">
								<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="radmin-icon radmin-user"></i></span>
									<input type="text" name="login_identity" class="form-control" placeholder="Username / Email">
								</div>
							</div>
							<br clear="all">
							<div class="form-group">
								<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="radmin-icon radmin-locked"></i></span>
									<input type="password" name="login_password" class="form-control" placeholder="Password">
								</div>
							</div>
							
							<div class="form-group">
								<a class="btn-link pull-left" href="#" id="lost-password">Lost your password?</a>
								<button type="submit" name="login_user" value="1" class="btn btn-primary btn-lg pull-right">Login</button>
								<br>
								<span class="pull-left">No account? 
									<?php echo anchor('login/register', 'Create your account here')?>
								</span>
								
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
