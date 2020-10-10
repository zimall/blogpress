<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authorize
{

	function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->auth = new stdClass;
		$this->ci->load->model('user_model');
		
		//print_r($this->ci->auth);
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// LOGIN / VALIDATION FUNCTIONS
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * login
	 * Verifies a users identity and password, if valid, they are logged in.
	 *
	 * @return void
	 * @author Mathew Davies
	 */
	public function login($identity = FALSE, $password = FALSE, $remember_user = FALSE) 
	{
		return $this->ci->user_model->login($identity, $password, $remember_user);
	}

	public function add_user()
	{
		$this->ci->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'register_first_name', 'label' => 'First Name', 'rules' => 'required'),
			array('field' => 'register_last_name', 'label' => 'Last Name', 'rules' => 'required'),
			array('field' => 'register_phone_number', 'label' => 'Phone Number', 'rules' => 'required'),
			array('field' => 'register_newsletter', 'label' => 'Newsletter', 'rules' => 'integer'),
			array('field' => 'register_email_address', 'label' => 'Email Address', 'rules' => 'required|valid_email|identity_available'),
			array('field' => 'register_username', 'label' => 'Username', 'rules' => 'required|min_length[4]|identity_available'),
			array('field' => 'register_password', 'label' => 'Password', 'rules' => 'required|validate_password'),
			array('field'=>'register_confirm_password', 'label' => 'Confirm Password', 'rules' => 'required|matches[register_password]')
		);
		
		$this->ci->form_validation->set_rules($validation_rules);

		// Run the validation.
		if($this->ci->form_validation->run())
		{
			// Get user login details from input.
			$account['email'] = $this->ci->input->post('register_email_address');
			$account['username'] = $this->ci->input->post('register_username');
			$account['password'] = $this->ci->input->post('register_password');
			
			// Get user profile data from input.
			// You can add whatever columns you need to customise user tables.
			$profile_data = array(
				'fname' => $this->ci->input->post('register_first_name'),
				'surname' => $this->ci->input->post('register_last_name'),
				'phone' => $this->ci->input->post('register_phone_number')
			);
			// Set whether to instantly activate account.
			// This var will be used twice, once for registration, then to check if to log the user in after registration.
			$instant_activate = FALSE;
			
			//print_r($account);
			
			$user_id = $this->ci->user_model->insert_user($account,$profile_data);
			
			if( is_numeric($user_id) )
			{
				// Check whether to auto activate the user.
				if($instant_activate)
				{
					// If an account activation time limit is set by the config file, retain activation token.
					$clear_token = ($this->ci->auth->auth_settings['account_activation_time_limit'] > 0) ? FALSE : TRUE;
					$this->ci->user_model->activate_user($user_id, FALSE, FALSE, $clear_token);		
				}
				
				$sql_select = array(
					$this->ci->auth->primary_identity_col,
					$this->ci->auth->tbl_col_user_account['activation_token']
				);
			
				$sql_where[$this->ci->auth->tbl_col_user_account['id']] = $user_id;
				$args['where'] = $sql_where;
				$args['select'] = $sql_select;
				$user = $this->ci->user_model->get_users($args); 

				if(!is_object($user))
					return array('error'=>TRUE,'error_msg'=>'Could not create your account, please check your data and try again.');
			
				$identity = $user->{$this->ci->auth->db_settings['primary_identity_col']};
				$activation_token = $user->{$this->ci->auth->database_config['user_acc']['columns']['activation_token']};
			
				// Prepare account activation email.
				// If the $activation_token is not empty, the account must be activated via email before the user can login.
				if (!empty($activation_token))
				{
					// Set email data.
					$email_to = $account['email'];
					$email_title = ' - Account Activation';
			
					$user_data = array(
						'user_id' => $user_id,
						'identity' => $identity,
						'activation_token' => $activation_token
					);
					$template = $this->ci->auth->email_settings['email_template_directory'].$this->ci->auth->email_settings['email_template_activate'];

					if ($this->ci->flexi_auth_model->send_email($email_to, $email_title, $user_data, $template))
					{
						//$this->ci->flexi_auth_model->set_status_message('activation_email_successful', 'config');
						return array( 'id'=>$user_id, 'error'=>FALSE, 
							'error_msg'=>'Your account has been successfully created and activatated');
					}

					//$this->ci->flexi_auth_model->set_error_message('activation_email_unsuccessful', 'config');
					return array('error'=>TRUE, 
						'error_msg'=>'Account created successfully but an activation email could not be sent.');
				}
			
				//$this->ci->flexi_auth_model->set_status_message('account_creation_successful', 'config');
				return array( 'id'=>$user_id, 'error'=>FALSE, 
					'error_msg'=>'Your account has been successfully created. Please check your email to activate your account.');
			}
			else
			{
				//$this->ci->flexi_auth_model->set_error_message('account_creation_unsuccessful', 'config');
				return $user_id;
			}
		}
		return FALSE;
	}

	/**
	 * min_password_length
	 * Gets the minimum valid password character length.
	 *
	 * @return int
	 * @author Rob Hussey
	 */
	public function min_password_length()
	{
		return $this->ci->auth->auth_security['min_password_length'];
	}

	/**
	 * valid_password_chars
	 * Validate whether the submitted password only contains valid characters defined by the config file.
	 *
	 * @return bool
	 * @author Rob Hussey
	 */
	public function valid_password_chars($password = FALSE)
	{
		return (bool) preg_match("/^[".$this->ci->auth->auth_security['valid_password_chars']."]+$/i", $password);
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
	
	/**
	 * ip_login_attempts_exceeded
	 * Validates whether the number of failed login attempts from a unique IP address has exceeded a defined limit.
	 * The function can be used in conjunction with showing a Captcha for users repeatedly failing login attempts.
	 *
	 * @return bool
	 * @author Rob Hussey
	 */
	public function ip_login_attempts_exceeded()
	{
		return $this->ci->user_model->ip_login_attempts_exceeded();
	}
	
	/**
	 * recaptcha
	 * Generates the html for Google reCAPTCHA.
	 * Note: If the reCAPTCHA is located on an SSL secured page (https), set $ssl = TRUE.
	 *
	 * @return string
	 * @author Rob Hussey
	 */
	public function recaptcha($ssl = FALSE)
	{
		return $this->ci->user_model->recaptcha($ssl);
	}
	
	/**
	 * validate_recaptcha
	 * Validates if a Google reCAPTCHA answer submitted via http POST data is correct.
	 *
	 * @return bool
	 * @author Rob Hussey
	 */
	public function validate_recaptcha()
	{
		return $this->ci->user_model->validate_recaptcha();
	}
	
	/**
	 * math_captcha
	 * Generates a math captcha question and answer.
	 * The question is returned as a string, whilst the answer is set as a CI flash session. 
	 * Use the 'validate_math_captcha()' function to validate the users submitted answer.
	 *
	 * @return string
	 * @author Rob Hussey
	 */
	public function math_captcha()
	{
		$captcha = $this->ci->user_model->math_captcha();
		
		$this->ci->session->set_flashdata($this->ci->auth->session_name['math_captcha'], $captcha['answer']);
		
		return $captcha['equation'];
	}
	
	/**
	 * validate_math_captcha
	 * Validates if a submitted math captcha answer is correct.
	 *
	 * @return bool
	 * @author Rob Hussey
	 */
	public function validate_math_captcha($answer = FALSE)
	{
		return ($answer == $this->ci->session->flashdata($this->ci->auth->session_name['math_captcha']));
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###

}
?>
