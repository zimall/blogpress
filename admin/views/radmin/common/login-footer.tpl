<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<form class="hidden">
	<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url()?>">
	<input type="hidden" name="site_url" id="site_url" value="<?php echo site_url()?>">
	<input type="hidden" name="current_url" id="current_url" value="<?php echo current_url()?>">
</form>
<form class="hidden" action="<?php echo current_url()?>" method="get" id="refresh"></form>

<?php 
	$this->carabiner->display('js');
	$this->sc->display('js');
?>

</body>
</html>
