<!-- Global site tag (gtag.js) - Google Analytics -->
<?php
	$analytics_code = config('google-analytics');
	if($analytics_code):
?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $analytics_code;?>"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', '<?php echo $analytics_code;?>');
	</script>
<?php endif;?>