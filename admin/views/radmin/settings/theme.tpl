<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info"><?php echo ucfirst($site_theme['name']);?> Theme Settings</span>
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
			<i class="radmin-icon radmin-user"></i> Theme Options
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel-group" id="accordion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="display:block">
						Theme Options
					</a>
				</h3>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
				<div class="panel-body">
					<?php echo form_open( current_url(), 'class="form form-horizontal" name="site_theme"' );?>
						<div class="col-sm-6">
							<?php if(isset($site_theme['color'])):?>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Color</label>
									<div class="input-group input-group-lg">
										<span class="input-group-addon" id="theme_color"
											style="background-color:<?php echo $site_theme['color']?>">&nbsp;&nbsp;</span>
										<select name="color" class="form-control theme_color" required>
											<?php if(isset($site_theme['colors'])) $colors = $site_theme['colors'];
												else $colors = array();
											foreach($colors as $c):
												if( $c==$site_theme['color'] ) $s='selected'; else $s='';
											?>
												<option value="<?php echo $c;?>" <?php echo $s;?>><?php echo ucfirst($c)?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
							<?php endif;?>
							<?php if(isset($site_theme['page_loader'])):?>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Page Loader Options</label>
									<select name="page_loader" class="form-control">
										<?php if(isset($site_theme['page_loader_options'])) $options = $site_theme['page_loader_options'];
											else $options = array();
										foreach($options as $k=>$v):
											if( $k==$site_theme['page_loader'] ) $s='selected'; else $s='';
										?>
											<option value="<?php echo $k;?>" <?php echo $s;?>><?php echo $v;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php endif;?>
							<?php if(isset($site_theme['header'])):?>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Top Bar</label>
									<select name="header" class="form-control" required>
										<?php if(isset($site_theme['header_options'])) $options = $site_theme['header_options'];
											else $options = array();
										foreach($options as $k=>$v):
											if( $k==$site_theme['header'] ) $s='selected'; else $s='';
										?>
											<option value="<?php echo $k;?>" <?php echo $s;?>><?php echo $v;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php endif;?>
							<?php if(isset($site_theme['top_bar'])):?>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Menu Bar</label>
									<select name="top_bar" class="form-control">
										<?php if(isset($site_theme['top_bar_options'])) $options = $site_theme['top_bar_options'];
											else $options = array();
										foreach($options as $k=>$v):
											if( $k==$site_theme['top_bar'] ) $s='selected'; else $s='';
										?>
											<option value="<?php echo $k;?>" <?php echo $s;?>><?php echo $v;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php endif;?>
							<?php if(isset($site_theme['layout'])):?>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Page Layout</label>
									<select name="layout" class="form-control" required>
										<?php if(isset($site_theme['layout_options'])) $options = $site_theme['layout_options'];
											else $options = array();
										foreach($options as $k=>$v):
											if( $k==$site_theme['layout'] ) $s='selected'; else $s='';
										?>
											<option value="<?php echo $k;?>" <?php echo $s;?>><?php echo $v;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php endif;?>
							<?php if(isset($site_theme['background'])):?>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Background Image <small>(for boxed layout only)</small></label>
									<div class="input-group input-group-lg">
										<span class="input-group-addon" id="background_image"
											style="background-image: url(<?php echo base_url('site/views/'.$site_theme['name'].'/images/patterns/'.$site_theme['background']);?>);background-size:contain;">&nbsp;&nbsp;</span>
										<select name="background" class="form-control background_image" required>
											<?php if(isset($site_theme['background_images'])) 
												$options = $site_theme['background_images'];
												else $options = array();
											foreach($options as $k=>$v):
												if( $v==$site_theme['background'] ) $s='selected'; else $s='';
											?>
												<option value="<?php echo $v;?>" <?php echo $s;?>><?php echo $v;?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
							<?php endif;?>

							<?php if(isset($site_theme['image_sizes'])):
								$image_sizes = $site_theme['image_sizes'];
								$isd = $this->config->item('images_size_definitions');
							?>
								<div class="form-group">
									<div class="col-sm-10">
										<br clear="all">
										<legend>Site Image Sizes <small> [ Width x Height ]</small></legend>
									</div>
								<?php 
								foreach ($image_sizes as $key => $value):
							?>
							
								<div class="col-sm-5">
									<label>
											Image <?php echo strtoupper($key); 
											if(isset($isd[$key])) echo "&nbsp; &nbsp;<i style='font-weight:normal'>[ <small>{$isd[$key]}</small> ]</i>";?>
									</label>
									<input type="text" name="image_sizes[<?=$key?>]" value="<?=$value?>" class="form-control">
									<br clear="all">
								</div>
							
							<?php endforeach;?>
							</div>
							<?php endif;?>
							
						</div>
						<!-- col-sm-6 -->
				
						<div class="col-sm-6">
						<?php $folder="site/views/{$site_theme['name']}/images/logos/";?>
						<?php if(isset($site_theme['logo'])):?>
							<div class="form-group">
								<div class="col-sm-10">
									<label>Site Logo</label>
									<?php $options = $site_theme['logo_options'];
										$t = '';
										foreach($options as $k=>$v) $t .= "$k: $v, ";
										$t = trim($t, ', ');
									?>
									<span id="upload" class="uneditable-input form-control">
										no file chosen... <small>(recommended <?php echo $t;?>)</small>
									<span>
								</div>
							</div>

							<?php $folder="site/views/{$site_theme['name']}/images/logos/";?>
							<div class="form-group">
								<div class="col-sm-10">
									<div id="status"></div>
									<ul id="upload_files" class="thumbnails unstyled">
										<?php $image = $site_theme['logo'];
											$file = "site/views/{$site_theme['name']}/images/logos/{$image}";
										if( file_exists($file) ):?>
											<li id="site_logo_<?php echo $image;?>">
												<div class="thumbnail" style="background-color: <?=$site_theme['color']?>">
													<?php $href = base_url($file);
													$src = base_url($file);?>
													<a target="_blank" class="fancybox" rel="gp" 
														href="<?php echo $href;?>"><?php echo img($src);?>
													</a>
													<br clear="all">
													<p>
														<button class="btn btn-xs btn-default product_img_fs" type="button" 
															value="<?php echo $image;?>" data-folder="<?php echo $folder;?>"
															onclick="delete_logo(this)">delete</button>
														<input type="hidden" name="logo" id="image_name" 
															value="<?php echo $image?>">
													</p>
												</div>
											</li>
										<?php endif;?>
									</ul>
								</div>
								
							</div>
							<!-- form-group -->
							<?php endif;?>

							<div class="form-group">
								
								<div class="col-sm-10">
									<br clear="all"><br clear="all">
									<label>Theme Active</label>
									<?php //echo form_hidden('activate_theme',0);
										if($this->config->item('site_theme')==$site_theme['name']) $s = 'checked disabled';
										else $s = '';
									?>
									<input type="checkbox" name="activate_theme" value="1" <?=$s;?> data-toggle="toggle">
									<br clear="all"><br clear="all"><br clear="all"><br clear="all">
								</div>
							</div>

							<div class="form-group">
								<input type="hidden" name="site_theme" value="<?php echo $site_theme['name']?>" id="site_theme">
								<input type="hidden" name="action_string" 
									value="<?php echo  base_url('site_logo.php').'?folder='.$folder;?>" 
									id="action_string">
								<input type="hidden" name="script" 
									value="site_logo.php?folder=<?php echo $folder?>" id="action_file">
								<input type="hidden" name="form_name" value="site">
								<input type="hidden" name="form_type" value="update_theme">
								<span style="padding-left: 15px;">
									<?php echo anchor( 'settings', 'Cancel', 'class="btn btn-default btn-lg"' )?>
									&nbsp;&nbsp;&nbsp; 
									<button type="submit" name="site_theme" class="btn btn-primary btn-lg" 
										value="<?php echo $site_theme['name']?>">Submit</button>
								</span>
							</div>
						</div>
						<!-- col-sm-6 -->
					</form>
				</div>
				<!-- panel-body -->
			</div>
			<!-- panel-collapse -->
		</div>
		<!-- panel-group -->
	</div>
</div>
