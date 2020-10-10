<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">New Page Banner</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( 'settings', '<i class="radmin-icon radmin-cog"></i>Settings' );?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-picture"></i> Banner Slide
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">New Banner</h3>
		</div>
		<div class="panel-body">
			<?php echo form_open( current_url(), 'class="form form-horizontal" name="slider"' );?>
				<div class="form-group">
					<div class="col-sm-5">
						<label>Title Text</label>
						<input type="text" name="title" class="form-control input-sm" 
							placeholder="full text including special title text" 
							value="<?php echo $this->input->post('title')?>" required>
					</div>
					<div class="col-sm-5">
						<label>Special Title Text</label>
						<input type="text" name="title_special" class="form-control input-sm" 
							value="<?php echo $this->input->post('title_special')?>">
					</div>
					<div class="col-sm-5">
						<label>Title Animation</label>
						<select name="title_animation" class="form-control input-sm">
							<?php if( isset($slider_settings['title_animation']) ) $ta = $slider_settings['title_animation'];
							else $ta=array();
							foreach($ta as $k=>$v):
								$s = ($this->input->post('title_animation')==$k)?'selected':'';
							?>
								<option value="<?php echo $k?>" <?php echo $s;?>><?php echo $v;?></option>
							<?php endforeach;?>
						</select>
					</div>
					<div class="col-sm-5">
						<label>Title Style</label>
						<select name="title_style" class="form-control input-sm">
							<?php if( isset($slider_settings['title_styles']) ) $ta = $slider_settings['title_styles'];
							else $ta=array();
							foreach($ta as $k=>$v):
								$s = ($this->input->post('title_style')==$k)?'selected':'';
							?>
								<option value="<?php echo $v?>" <?php echo $s;?>><?php echo ucfirst($v);?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<!-- form-group -->
				<div class="form-group">
					<div class="col-sm-5">
						<label>Subtitle Text</label>
						<input type="text" name="subtitle" class="form-control input-sm" 
							placeholder="full text including special subtitle text" 
							value="<?php echo $this->input->post('subtitle')?>" required>
					</div>
					<div class='col-sm-5'>&nbsp;</div>
					<br clear="all">
					<div class="col-sm-5">
						<label>Subtitle Animation</label>
						<select name="subtitle_animation" class="form-control input-sm">
							<?php if( isset($slider_settings['title_animation']) ) $ta = $slider_settings['title_animation'];
							else $ta=array();
							foreach($ta as $k=>$v):
								$s = ($this->input->post('subtitle_animation')==$k)?'selected':'';
							?>
								<option value="<?php echo $k?>" <?php echo $s;?>><?php echo $v;?></option>
							<?php endforeach;?>
						</select>
					</div>
					<div class="col-sm-5">
						<label>Subtitle Style</label>
						<select name="subtitle_style" class="form-control input-sm">
							<?php if( isset($slider_settings['title_styles']) ) $ta = $slider_settings['title_styles'];
							else $ta=array();
							foreach($ta as $k=>$v):
								$s = ($this->input->post('subtitle_style')==$k)?'selected':'';
							?>
								<option value="<?php echo $v?>" <?php echo $s;?>><?php echo ucfirst($v);?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<!-- form-group -->
				
				<div class="form-group">
					<div class="col-sm-5">
						<label>Action Button Text</label>
						<input type="text" name="action" class="form-control input-sm" 
							placeholder="e.g Learn More or Read More" 
							value="<?php echo $this->input->post('action')?>">
					</div>
					<div class="col-sm-5"></div>
					<br clear="all">
					<div class="col-sm-5">
						<label>Action Button Animation</label>
						<select name="action_animation" class="form-control input-sm">
							<?php if( isset($slider_settings['title_animation']) ) $ta = $slider_settings['title_animation'];
							else $ta=array();
							foreach($ta as $k=>$v):
								$s = ($this->input->post('action_animation')==$k)?'selected':'';
							?>
								<option value="<?php echo $k?>" <?php echo $s;?>><?php echo $v;?></option>
							<?php endforeach;?>
						</select>
					</div>
					<div class="col-sm-5">
						<label>Action Button Style</label>
						<select name="action_style" class="form-control input-sm">
							<?php if( isset($slider_settings['title_styles']) ) $ta = $slider_settings['title_styles'];
							else $ta=array();
							foreach($ta as $k=>$v):
								$s = ($this->input->post('action_style')==$k)?'selected':'';
							?>
								<option value="<?php echo $v?>" <?php echo $s;?>><?php echo ucfirst($v);?></option>
							<?php endforeach;?>
						</select>
					</div>
					<div class="col-sm-7">
						<label>Action Button Link</label>
						<input type="url" name="link" class="form-control input-sm" 
							placeholder="http://" 
							value="<?php echo $this->input->post('link')?>">
					</div>
					<div class="col-sm-3">
						<label>Action Button Target</label>
						<?php 
							$options = array( '_self'=>'Same Window', '_blank'=>'New Window');
							echo form_dropdown('link_target', $options, $this->input->post('link_target'), 
								'class="form-control input-sm"');
						?>
					</div>
				</div>
				<!-- form-group -->
				
				<br clear="all"><br clear="all">
				
				<div class="col-sm-6">
					<div class="form-group">
						<label>Slider Image</label>
						<?php $options = $slider_settings['size'];
							$t = '';
							foreach($options as $k=>$v) $t .= "$k: $v, ";
							$t = trim($t, ', ');
						?>
						<span id="upload" class="uneditable-input form-control">
							no file chosen... <small>(recommended <?php echo $t;?>)</small>
						<span>
					</div>
					<div class="form-group">
						<label>Slider Position</label>
						<input type="number" name="position" class="form-control input-sm" placeholder="1" 
							value="<?php echo $this->input->post('position')?>">
					</div>
					<div class="form-group">
						<label>Status</label>
						<?php 
							$options = array( '1'=>'Enabled', '0'=>'Disabled');
							echo form_dropdown('enabled', $options, $this->input->post('enabled'), 'class="form-control input-sm"');
						?>
					</div>
				</div>
				<!-- col-sm-6 -->
				
				<div class="col-sm-6">
					<div class="form-group">
						<div id="status"></div>
						<ul id="upload_files" class="thumbnails unstyled">
							<?php $image = $this->input->post('image');
								$file = "images/slider/{$image}";
							if( file_exists($file) && is_file($file) ):?>
								<li id="slider_<?php echo $image;?>">
									<div class="thumbnail">
										<?php $href = base_url($file);
										$src = base_url($file);?>
										<a target="_blank" class="fancybox" rel="gp" 
											href="<?php echo $href;?>"><?php echo img($src);?>
										</a>
										<br clear="all">
										<p>
											<button class="btn btn-xs btn-default product_img_fs" type="button" 
												value="<?php echo $image;?>"
												onclick="delete_slider(this)">delete</button>
											<input type="hidden" name="image" id="image_name" 
												value="<?php echo $image?>">
										</p>
									</div>
								</li>
							<?php endif;?>
						</ul>
					</div>
					<!-- form-group -->
					<div class="form-group">
						<div class="col-sm-8 pull-right">
							<input type="hidden" name="action_string" 
								value="<?php echo  base_url('slider.php').'?'.http_build_query($slider_settings['size']);?>" 
								id="action_string">
							<input type="hidden" name="script" 
								value="slider.php?<?php echo http_build_query($slider_settings['size']);?>" id="action_file">
							<input type="hidden" name="form_name" value="slider">
							<input type="hidden" name="form_type" value="insert">
							<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default"' )?>
							<button type="submit" name="site_slider" class="btn btn-primary" 
								value="<?php echo $site_theme['name']?>">Submit</button>
						</div>
					</div>
					<!-- form-group -->
					
				</div>
				<!-- col-sm-6 -->
				
			</form>
		</div>
	</div>
</div>
