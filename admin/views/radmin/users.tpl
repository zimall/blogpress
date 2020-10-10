<?php $this->load->view($theme.'/common/header.tpl'); ?>
	<div class="container-fluid main-container">
		<div class="col-lg-12">
			<?php $this->load->view( $theme.'/common/navbar.tpl' );?>
			<div class="container-fluid content-wrapper">
				<?php $this->load->view( "$theme/users/$section.tpl" );?>
			</div> <!-- end of container-fluid content-wrapper -->
		</div> <!-- end of span12 (main)-->
	</div> <!-- end of container-fluid main-container (main)-->
<?php $this->load->view($theme.'/common/footer.tpl'); ?>
