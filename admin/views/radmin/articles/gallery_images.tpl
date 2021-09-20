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
						<?php echo anchor( home_url("{$gallery['sc_value']}/{$gallery['at_id']}/{$gallery['at_segment']}"), 'View Gallery',
						'class="btn btn-default btn-xs" target="_blank"' );?>
					</span>
				</h3>
			</div>
			<div class="panel-body">
				
				<div class="form-group">
					<div class="col-sm-7">
						<div id="file-uploader" class="form-control">no file chosen...</div>
						<ol id="upload-progress"></ol>
						<div id="messages" class="alert"></div>
					</div>
					<div class="col-sm-5" style="text-align:right;">
						<?php echo anchor( "{$gallery['sc_value']}", 'Cancel', 'class="btn btn-default"' );?>
						<button type="submit" name="submit" value="1" class="btn btn-primary">Save</button>
						<input type="hidden" name="form_name" value="gallery_images">
						<input type="hidden" name="form_type" value="insert">
						<input type="hidden" name="action_string" value="<?php echo  base_url('images/article_thumbs');?>"
							id="action_string">
						<input type="hidden" name="script" value="images/article_thumbs" id="action_file">
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
						<?php echo anchor( $gallery['sc_value'], 'Done', 'class="btn btn-primary btn-xs" title="Uploaded images will not be saved"' );?>
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
											<?php $href = base_url("images/articles/lg/{$image}");
											$src = base_url("images/articles/sm/{$image}");?>
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
										<?php $href = base_url("images/articles/lg/{$i['gi_file']}");
											$g = get_image($i['gi_file'],'sm');
											$src = base_url($g['webp']);
										?>
										<a target="_blank" class="fancybox" rel="gp<?php echo $gallery['at_id'];?>" 
											href="<?php echo $href;?>"><?php echo img($src);?></a>
										<div class="image_options">
											<table style="width:100%">
												<tr>
													<?php
														$p = $i['gi_private']?'make public':'make private';
														$c = $i['gi_caption']?'edit caption':'add caption';
														$a = $i['gi_caption']?'pencil':'comments';
													?>
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
													<td>
														<a href="#" class="caption-modal" data-id="<?php echo $i['gi_id'];?>"
														   data-toggle="modal" data-target="#caption-modal" title="<?php echo $c;?>">
															<i class="radimn radmin-<?php echo $a;?>"></i>
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

<?php echo form_close()?>


<div id="caption-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog" role="document">
		<?php echo form_open( "/admin/ajax/gallery_caption", ['class'=>'modal-content'] )?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add / Edit Image Caption</h4>
			</div>
			<div class="modal-body">
				<div class="alert hidden"></div>
				<fieldset class="form-group">
					<label class="control-label">Image Caption</label>
					<input type="text" name="caption" class="image-caption form-control">
				</fieldset>
			</div>
			<div class="modal-footer">
				<input type="hidden" class="image-id" name="id">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		<?php echo form_close()?>
	</div>
</div>
