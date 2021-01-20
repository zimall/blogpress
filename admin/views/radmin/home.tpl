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
						<h4 class="title">
                            Recent Activity&nbsp;- Last <?php echo $ana_days==1?'24 Hours':"{$ana_days} Days";?>&nbsp;&nbsp;&nbsp; <a href="#ana_days" data-toggle="modal" class="btn btn-default btn-xs"><span class="radmin radmin-pencil"></span></a>
                        </h4>
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
									<td class="text">Total Visitors</td><td class="numbers"><?php echo $ana_summary['visitors'];?></td>
								</tr>
								<tr>
									<td class="text">New Visitors</td><td class="numbers"><?php echo $ana_summary['new_visitors'];?></td>
								</tr>
                                <tr>
                                    <td class="text">Returning Visitors</td><td class="numbers"><?php echo $ana_summary['visitors']-$ana_summary['new_visitors'];?></td>
                                </tr>
								<tr>
									<td class="text">Page Views</td><td class="numbers"><?php echo $ana_summary['pageviews']['total'];?></td>
								</tr>
                                <tr>
                                    <td class="text">Average Page Load Time</td>
                                    <td class="numbers"><?php echo pretty_time($ana_summary['pageviews']['average_load_duration']*1 );?></td>
                                </tr>
                                <tr>
                                    <td class="text">Total Sessions</td><td class="numbers"><?php echo $ana_summary['sessions']['total'];?></td>
                                </tr>
                                <tr>
                                    <td class="text">Average Session Duration</td>
                                    <td class="numbers"><?php echo pretty_time($ana_summary['sessions']['average_duration']*1);?></td>
                                </tr>
                                <tr>
                                    <td class="text">Bounce Rate</td><td class="numbers"><?php echo ($ana_summary['sessions']['bounce_rate']*100),'%';?></td>
                                </tr>
                                <tr>
                                    <td class="text">Page views per session</td><td class="numbers"><?php echo $ana_summary['sessions']['pageviews'];?></td>
                                </tr>
                            <?php foreach($ana_summary['devices'] as $name=>$d):?>
                                <tr>
                                    <td class="text"><?php echo ucwords($name);?></td>
                                    <td class="numbers"><?php echo $ana_summary['visitors'] ? round(($d*100)/$ana_summary['visitors'],1) : 0?>%</td>
                                </tr>
                            <?php endforeach;?>
							</tbody>
						</table>
					</div>
			
					<div class="col-sm-7">
						<h4 class="title">Top 10 Pages</h4>
						<div class="squiggly-border"></div>
                        <table class="table table-index">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Views</th>
                                <th>Ave. time on page</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $ana_summary['top10pages'] as $k=>$v ):?>
                            <tr>
                                <td class="text"><?php echo $k+1;?></td>
                                <td class="text"><?php echo anchor( $v['pv_url'], $v['pv_title'], ['target'=>'_blank'] );?></td>
                                <td class="numbers"><?php echo $v['viewed'];?></td>
                                <td class="numbers"><?php echo pretty_time($v['pv_duration']*1);?></td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
					</div>
			
					<div class="col-sm-12">&nbsp;</div>
				
			
					<div class="col-sm-12">&nbsp;</div>
			
					<div class="col-sm-12">
						<h4 class="title">Recent Posts</h4>
						<div class="squiggly-border"></div>
					</div>

					<div class="col-sm-12">&nbsp;</div>
			
					<div class="col-sm-12">&nbsp;</div>
			</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="ana_days" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Internal Analytics</h4>
            </div>
            <div class="modal-body">
                <form method="get" action="" class="form form-horizontal" id="ana_days_form">
                    <div class="form-group">
                        <div class="col-xs-12 col-md-10">
                            <label>How many days of reports would you like to see?</label>
                            <input type="number" max="400" name="days" class="form-control input-lg">
                        </div>
                    </div>
                    <div class="hidden">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('ana_days_form').submit()">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("$theme/common/footer.tpl"); ?>
