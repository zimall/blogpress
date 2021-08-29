<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open( current_url(), 'class="login100-form validate-form"' );?>
<form class="login100-form validate-form">
	<span class="login100-form-title p-b-59">
		Resend Activation Token
	</span>

	<div class="wrap-input100 validate-input" data-validate="username/email is required">
		<span class="label-input100">Username / Email</span>
		<input type="text" name="activation_token_identity" class="input100" placeholder="Username / Email">
		<span class="focus-input100"></span>
	</div>

	<div class="container-login100-form-btn">
		<div class="wrap-login100-form-btn">
			<div class="login100-form-bgbtn"></div>
			<button class="login100-form-btn" type="submit" name="send_activation_token" value="1">
				Resend Token
			</button>
		</div>

		<a href="<?php echo site_url('account/login')?>" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
			Sign In
			<i class="fa fa-long-arrow-right m-l-5"></i>
		</a>
	</div>
	<div class="flex-m w-full p-b-33 p-t-40">
		<div class="contact100-form-checkbox">
			<label class="label" for="ckb2">
				<span class="txt1">
					<a href="<?php echo site_url('account/register')?>" class="txt2 hov1">
						No Account? Sign Up Here
					</a>
				</span>
			</label>
		</div>
	</div>
</form>
