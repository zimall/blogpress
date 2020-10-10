<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">New Article</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'articles/gallery', '<i class="radmin-icon radmin-newspaper"></i>Gallery' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-picture"></i> Gallery Images
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>


<?php echo form_open( current_url(), 'class="form form-horizontal gallery_form" name="blog_post"' );?>

	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<span>Upload New Images</span>
					<span class="pull-right">
						<?php echo anchor( home_url("gallery/i/{$gallery['at_id']}"), 'View Gallery', 
						'class="btn btn-default btn-xs" target="_blank"' );?>
					</span>
				</h3>
			</div>
			<div class="panel-body">
				
				<div class="form-group">
					<div class="col-sm-4">
						<span id="file-uploader" class="btn btn-danger">
							no file chosen... <small>(recommended 900x623px)</small>
						<span>
						<br><br>
						<div id="messages" class="alert"></div>
					</div>
					<div class="col-sm-7" style="text-align:right;">
						<?php echo anchor( 'articles/gallery', 'Cancel', 'class="btn btn-default"' );?>
						<button type="submit" name="submit" value="1" class="btn btn-primary">Save</button>
						<input type="hidden" name="form_name" value="gallery_images">
						<input type="hidden" name="form_type" value="insert">
						<input type="hidden" name="action_string" value="<?php echo  base_url('article_thumb.php');?>" 
							id="action_string">
						<input type="hidden" name="script" value="article_thumb.php" id="action_file">
						<input type="hidden" name="id" value="<?php echo $gallery['at_id'];?>">
					</div>
				</div>
				
			</div>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<span>Images</span>
					<span class="pull-right">
						<?php echo anchor( 'articles/gallery', 'Done', 'class="btn btn-primary btn-xs" title="Uploaded images will not be saved"' );?>
					</span>
				</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-sm-12">
						<div id="status"></div>
						<ul id="upload_files" class="thumbnails unstyled">
							<?php $post = $this->input->post('images')?$this->input->post('images'):array();
							if( is_array($post) ):
								foreach($post as $image):?>
									<li id="<?php echo $image;?>">
										<div class="thumbnail">
											<?php $href = base_url("images/articles/main/{$image}");
											$src = base_url("images/articles/250x180/{$image}");?>
											<a target="_blank" class="fancybox" rel="gp" 
											href="<?php echo $href;?>"><?php echo img($src);?></a>
											<p>
												<button class="btn btn-xs btn-default product_img_fs" type="button" 
													value="<?php echo $image;?>" onclick="delete_piffs(this)">delete</button>
												<input type="hidden" name="image" id="image_name" value="<?php echo $image?>">
											</p>
										</div>
									</li>
								<?php endforeach;?>
							<?php endif;?>
						</ul>
						
						<ul id="uploaded_files" class="thumbnails">
							<?php foreach($images as $i):?>
								<li id="li_<?php echo $i['gi_id'];?>" style="margin-bottom:30px;">
									<div class="thumbnail">
										<?php $href = base_url("images/articles/main/{$i['gi_file']}");
											$src = base_url("images/articles/250x180/{$i['gi_file']}");
										?>
										<a target="_blank" class="fancybox" rel="gp<?php echo $gallery['at_id'];?>" 
											href="<?php echo $href;?>"><?php echo img($src);?></a>
										<div class="image_options">
											<table style="width:100%">
												<tr>
													<?php $p = $i['gi_private']?'make public':'make private';?>
													<td>
														<a data-img_id="<?php echo $i['gi_id']?>" data-file="<?php echo $i['gi_file']?>" 
															class="delete_img">delete</a> 
													</td>
													<td id="<?php echo $i['gi_id'];?>">
														<a data-img_id="<?php echo $i['gi_id']?>" data-article_id="<?php echo $gallery['at_id']?>" 
															data-file="<?php echo $i['gi_file']?>" data-privacy="<?php echo $i['gi_private']?>" 
															class="img_privacy">
															<?php echo $p;?>
														</a> 
													</td>
												</tr>
											</table>
										</div>
									</div>
								</li>
							<?php endforeach;?>
						</ul>
						<div class="hidden"><select id="image_names" name="images[]" multiple>
							<?php $post = $this->input->post('images')?$this->input->post('images'):array();
							if( is_array($post) ):
								foreach($post as $image):?>
									<option value="<?php echo $image?>" selected><?php echo $image;?></option>
								<?php endforeach;
							endif;?>
						</select></div>
					</div>
				</div>
			</div>
		</div>
	</div>

</form>
