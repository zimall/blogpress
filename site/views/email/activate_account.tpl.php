<html>
<body>
	<h2>Activate account for <?php echo $identity;?></h2>
	
	<p>Please click this link to <?php echo anchor('account/activate_account/'. $user_id .'/'. $activation_token, 'Activate Your Account');?>.</p>
</body>
</html>
