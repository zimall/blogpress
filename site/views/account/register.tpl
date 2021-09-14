<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open( current_url(), 'class="login100-form validate-form"' );?>
<form class="login100-form validate-form">
	<span class="login100-form-title p-b-59">
		Sign Up
	</span>

	<div class="wrap-input100 validate-input" data-validate="Name is required">
		<span class="label-input100">First Name</span>
		<input type="text" name="register_first_name" class="input100" placeholder="First Name" value="<?php echo $this->input->post('register_first_name');?>">
		<span class="focus-input100"></span>
	</div>
	<div class="wrap-input100 validate-input" data-validate="Name is required">
		<span class="label-input100">Last Name</span>
		<input type="text" name="register_last_name" class="input100" placeholder="Surname" value="<?php echo $this->input->post('register_last_name');?>">
		<span class="focus-input100"></span>
	</div>
	<div class="wrap-input100 validate-input" data-validate="Name is required">
		<span class="label-input100">Phone Number</span>
		<input type="text" name="register_phone_number" class="input100" placeholder="Phone Number"	value="<?php echo $this->input->post('register_phone_number');?>">
		<span class="focus-input100"></span>
	</div>
	<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
		<span class="label-input100">Email Address</span>
		<input type="email" name="register_email_address"class="input100" placeholder="Email Address" value="<?php echo $this->input->post('register_email_address');?>">
		<span class="focus-input100"></span>
	</div>

	<div class="wrap-input100 validate-input" data-validate="Username is required">
		<span class="label-input100">Username</span>
		<input type="text" name="register_username" class="input100" placeholder="Username" value="<?php echo $this->input->post('register_username');?>">
		<span class="focus-input100"></span>
	</div>

	<div class="wrap-input100 validate-input" data-validate = "Password is required">
		<span class="label-input100">Password</span>
		<input type="password" name="register_password" class="input100" placeholder="Password" value="<?php echo $this->input->post('register_password');?>">
		<span class="focus-input100"></span>
	</div>

	<div class="wrap-input100 validate-input" data-validate = "Repeat Password is required">
		<span class="label-input100">Repeat Password</span>
		<input type="password" name="register_confirm_password" class="input100" placeholder="Confirm Password" value="<?php echo $this->input->post('register_confirm_password');?>">
		<span class="focus-input100"></span>
	</div>

	<div class="flex-m w-full p-b-33">
		<div class="contact100-form-checkbox">
			<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me" required>
			<label class="label-checkbox100" for="ckb1">
				<span class="txt1">
					I agree to the
					<a href="<?php echo site_url('about')?>" class="txt2 hov1">
						Terms of this website
					</a>
				</span>
			</label>
		</div>
	</div>

	<div class="container-login100-form-btn">
		<div class="wrap-login100-form-btn">
			<div class="login100-form-bgbtn"></div>
			<button class="login100-form-btn" type="submit" name="register_user" value="1">
				Sign Up
			</button>
		</div>

		<a href="<?php echo site_url('account/login')?>" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30">
			Sign in
			<i class="fa fa-long-arrow-right m-l-5"></i>
		</a>
	</div>
</form>
