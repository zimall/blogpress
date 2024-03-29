<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Settings</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>

		</li>
		<li>
			<?php echo anchor( 'settings', '<i class="radmin-icon radmin-cog"></i>Settings' );?>

		</li>
		<li class="active">
			<i class="radmin-icon radmin-user"></i> View All
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Site Themes</h3>
		</div>
		<div class="panel-body">
			<ul class="media-list">
			<?php foreach($site_themes as $k=>$v)
			if(isset($v['name'])):
				$img = array( 'src'=>"site/views/{$v['name']}/{$v['name']}.jpg", 'style'=>'max-width:100px;', 
					'class'=>'media-object' );
			?>
				<li class="media col-sm-6">
					<?php if( file_exists($img['src']) ):?>
						<a data-toggle="modal" href="#<?php echo $v['name'];?>" 
							class="pull-left">
							<?php echo img($img);?>
						</a>
					<?php endif;?>
					<div class="media-body">
						<h4 class="media-heading">
							<?php echo anchor( "settings/theme/{$v['name']}/site", ucfirst($v['name']), 'title="Configure Theme"' );
								if( $v['name']==$site_theme ) echo ' <small>(<strong>Active</strong>)</small>';
							?> 
						</h4>
						<p><?php echo $v['description']?></p>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="<?php echo $v['name']?>" tabindex="-1" role="dialog" 
						aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title"><?php echo $v['description']?></h4>
								</div>
								<div class="modal-body">
									<?php 
										$img['style'] = '';$img['class']="img-responsive";
										echo img($img);
									?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
				</li>
				<!-- media -->
			<?php endif;?>
			</ul>
			<!-- media-list -->
		</div>
		<!-- panel-body -->
	</div>
	<!-- panel panel-default -->
</div>
<div class="col-sm-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Admin Themes</h3>
		</div>
		<div class="panel-body">
			<ul class="media-list">
			<?php foreach($admin_themes as $k=>$v)
			if(isset($v['name'])):
				$img = array( 'src'=>"admin/views/{$v['name']}/{$v['name']}.jpg", 'style'=>'max-width:100px;', 
					'class'=>'media-object' );
			?>
				<li class="media col-sm-6">
					<?php if( file_exists($img['src']) ):?>
						<a data-toggle="modal" href="#<?php echo $v['name'];?>" 
							class="pull-left">
							<?php echo img($img);?>
						</a>
					<?php endif;?>
					<div class="media-body">
						<h4 class="media-heading">
							<?php echo anchor( "settings/theme/{$v['name']}/admin", ucfirst($v['name']), 'title="Configure Theme"' );
								if( $v['name']==$admin_theme ) echo ' <small>(<strong>Active</strong>)</small>';
							?> 
						</h4>
						<p><?php echo $v['description']?></p>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="<?php echo $v['name']?>" tabindex="-1" role="dialog" 
						aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title"><?php echo $v['description']?></h4>
								</div>
								<div class="modal-body">
									<?php 
										$img['style'] = '';$img['class']="img-responsive";
										echo img($img);
									?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
				</li>
				<!-- media -->
			<?php endif;?>
			</ul>
			<!-- media-list -->
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="panel-group" id="accordion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="display:block">
						General Settings
						<small class="pull-right">Use <code>##reset</code> to restore a default value</small>
					</a>
				</h3>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
				<div class="panel-body">
					<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="col-sm-10">
									<label>Business Name <code>site-name</code></label>
									<input type="text" name="site-name" class="form-control" value="<?php echo $this->config->item('site-name');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Website Type</label>
									<?php $ct = $this->config->item('club-type');?>
									<select name="club-type" class="form-control" required>
										<?php foreach( config('club-types') as $type ):
											$s = ($type===$ct)?'selected':'';
										?>
											<option value="<?php echo $type;?>" <?php echo $s;?>><?php echo $type;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Phone Number <code>phone</code></label>
									<input type="text" name="phone" class="form-control" value="<?php echo $this->config->item('phone');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Second Phone Number <code>phone-2</code></label>
									<input type="text" name="phone-2" class="form-control" value="<?php echo $this->config->item('phone-2');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Business Email <code>site-email</code></label>
									<input type="email" name="site-email" class="form-control" 
										value="<?php echo $this->config->item('site-email');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Second Business Email <code>site-email-2</code></label>
									<input type="email" name="site-email-2" class="form-control" 
										value="<?php echo $this->config->item('site-email-2');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>System Email <code>no-reply</code></label>
									<input type="email" name="no-reply" class="form-control" value="<?php echo $this->config->item('no-reply');?>">
									<small>typically no-reply@example.com</small>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-10">
									<label>Google Analytics ID <code>google-analytics</code></label>
									<input type="text" name="google-analytics" class="form-control" value="<?php echo $this->config->item('google-analytics');?>">
									<small>For website traffic statistics. Account ID starting with UA-</small>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Google reCaptcha Site Key <code>google_recaptcha_site_key</code></label>
									<input type="text" name="google_recaptcha_site_key" class="form-control" value="<?php echo config('google_recaptcha_site_key');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Google reCaptcha Secret <code>google_recaptcha_secret</code></label>
									<input type="text" name="google_recaptcha_secret" class="form-control" value="<?php echo config('google_recaptcha_secret');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>File Manager Access Key <code>file-manager-access-key</code></label>
									<input type="text" name="file-manager-access-key" class="form-control" value="<?php echo config('file-manager-access-key');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Items to show per page<code>items-per-page</code></label>
									<input type="text" name="items-per-page" class="form-control" value="<?php echo config('items-per-page');?>">
									<small>This is supposed to be comma seperated list of values, e.g. 5,10,20,50</small>
								</div>
							</div>

						</div>
						<!-- col-sm-6 -->
				
						<div class="col-sm-6">
							<div class="form-group">
								<div class="col-sm-10">
									<label>Year Founded <code>year-founded</code></label>
									<input type="text" name="year-founded" class="form-control" value="<?php echo $this->config->item('year-founded');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Business Hours <code>meetings</code></label>
									<input type="text" name="meetings" class="form-control" value="<?php echo $this->config->item('meetings');?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Short Address <code>address</code></label>
									<input type="text" name="address" class="form-control" value="<?php echo $this->config->item('address');?>" required>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Full Address <code>full-address</code></label>
									<textarea class="form-control" name="full-address"><?php echo $this->config->item('full-address');?></textarea>
									<!-- <input type="text" name="full-address" class="form-control" value="<?php echo $this->config->item('full-address');?>">  -->
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Google Maps Location <code>map-location</code></label>
									<input type="text" name="map-location" class="form-control" value="<?php echo $this->config->item('map-location');?>">
									<div class="help-block">only the src of the iframe, starting with https:://</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Whatsapp Link - <small>full URL</small>  <code>whatsapp</code></label>
									<input type="url" name="whatsapp" class="form-control" value="<?php echo $this->config->item('whatsapp');?>"
										   placeholder="e.g https://api.whatsapp.com/send/?phone=2637720123456">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Facebook Page/Group - <small>full URL</small>  <code>facebook</code></label>
									<input type="url" name="facebook" class="form-control" value="<?php echo $this->config->item('facebook');?>" 
										placeholder="https://">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Instagram <small>username only</small>  <code>instagram</code></label>
									<input type="text" name="instagram" class="form-control" value="<?php echo $this->config->item('instagram');?>"
										placeholder="e.g my_name">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Youtube <small>full URL</small>  <code>youtube</code></label>
									<input type="url" name="youtube" class="form-control" value="<?php echo $this->config->item('youtube');?>" 
										placeholder="https://">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Linkedin <small>full URL</small>  <code>linkedin</code></label>
									<input type="url" name="linkedin" class="form-control" value="<?php echo $this->config->item('linkedin');?>" 
										placeholder="https://">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Twitter Username <code>twitter</code></label>
									<input type="text" name="twitter" class="form-control" value="<?php echo $this->config->item('twitter');?>">
									<small>Username only without the url and without the @ sign</small>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Twitter Widget ID <code>twitter-widget-id</code></label>
									<input type="text" name="twitter-widget-id" class="form-control" 
										value="<?php echo $this->config->item('twitter-widget-id');?>">
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-10">
									<label>Site Cache <code>site-cache</code></label>
									<?php echo form_hidden('site_cache',0)?>
									<input type="checkbox" name="site_cache" value="1" <?=$this->config->item('site_cache')?'checked':'';?> data-toggle="toggle">
								</div>
							</div>
						
						</div>
						<!-- col-sm-6 -->
						<div class="clearfix"></div>
						<div class="form-group" style="margin-top: 60px;">
							<div class="text-center">
								<button type="submit" name="general" value="1" class="btn btn-lg btn-primary">Update Settings</button>
								<input type="hidden" name="form_type" value="update">
								<input type="hidden" name="form_name" value="general">
							</div>
						</div>

					<?php echo form_close();?>
				</div>
				<!-- panel-body -->
			</div>
			<!-- panel-collapse -->
		</div>
		<!-- panel-group -->
	</div>
</div>
