<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open( current_url(), 'class="login100-form validate-form"' );?>
<form class="login100-form validate-form">
	<span class="login100-form-title p-b-59">
		Sign In
	</span>

	<div class="wrap-input100 validate-input" data-validate="username/email is required">
		<span class="label-input100">Username / Email</span>
		<input type="text" name="login_identity" class="input100" placeholder="Username / Email">
		<span class="focus-input100"></span>
	</div>

	<div class="wrap-input100 validate-input" data-validate = "Password is required">
		<span class="label-input100">Password</span>
		<input type="password" name="login_password" class="input100" placeholder="Password">
		<span class="focus-input100"></span>
	</div>

	<div class="flex-m w-full p-b-33">
		<div class="contact100-form-checkbox">
			<input class="input-checkbox100" id="ckb1" type="checkbox" name="login_remember">
			<label class="label-checkbox100" for="ckb1">
				<span class="txt1">
					Remember me
				</span>
			</label>
		</div>
	</div>

	<div class="container-login100-form-btn">
		<div class="wrap-login100-form-btn">
			<div class="login100-form-bgbtn"></div>
			<button class="login100-form-btn" type="submit" name="login_user" value="1">
				Sign In
			</button>
		</div>

		<a href="<?php echo site_url('account/register')?>" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
			Sign Up
			<i class="fa fa-long-arrow-right m-l-5"></i>
		</a>
	</div>
	<div class="flex-m w-full p-b-33 p-t-40">
		<div class="contact100-form-checkbox">
			<label class="label" for="ckb2">
				<span class="txt1">
					<a href="<?php echo site_url('account/lost_password')?>" class="txt2 hov1">
						Forgot Password?
					</a>
					<br><br>
					<a class="txt2 hov1" href="<?php echo site_url('account/resend_activation_token')?>" id="lost-password">Account not activated?</a>
				</span>
			</label>
		</div>
	</div>
</form>
