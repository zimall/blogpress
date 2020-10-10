<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view("$theme/common/header.tpl"); ?>

<div class="container-fluid main-container">
		<div class="col-lg-12">

			<?php $this->load->view("$theme/common/navbar.tpl"); ?>
			
			<div class="container-fluid content-wrapper">

					<div class="col-sm-12">
						<h2 class="welcome">
							Welcome to 
							<span class="text-info"><?php echo $this->config->item('site-name');?></span>
						</h2>
					</div>
			
					<div class="col-sm-5">
						<h4 class="title">Recent Activity&nbsp;&nbsp;&nbsp;&nbsp;</h4>
					<div class="squiggly-border"></div>
						<table class="table table-index">
							<thead>
								<tr>
									<th>Description</th>
									<th>Number</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text">Total Visits</td>
									<td class="numbers">
										
									</td>
								</tr>
								<tr>
									<td class="text">New Visits</td>
									<td class="numbers">
									</td>
								</tr>
								<tr>
									<td class="text">Page Views</td>
									<td class="numbers">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
			
					<div class="col-sm-7">
						<h4 class="title">User Activity</h4>
						<div class="squiggly-border"></div>
					</div>
			
					<div class="col-sm-12">&nbsp;</div>
				
			
					<div class="col-sm-12">&nbsp;</div>
			
					<div class="col-sm-12">
						<h4 class="title">Recent News</h4>
						<div class="squiggly-border"></div>
					</div>
			
					<div class="col-sm-12">
						<h4 class="title">Recent Reports</h4>
						<div class="squiggly-border"></div>
					</div>

					<div class="col-sm-12">&nbsp;</div>
			
					<div class="col-sm-12">&nbsp;</div>
			</div>
	</div>
</div>
<?php $this->load->view("$theme/common/footer.tpl"); ?>
