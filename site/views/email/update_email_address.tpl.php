<html>
<body>
	<h2>Change of Email Address from <?php echo $current_email;?> to <?php echo $new_email;?></h2>
	<p>Please click this link to <?php echo anchor('account/update_email/'.$user_id.'/'.$update_email_token, 'confirm changing your email to this address');?>.</p>
</body>
</html>
