<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Edit Article Category</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'pages/categories', '<i class="radmin-icon radmin-book"></i>Article Categories' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-pencil"></i> Edit Article Category
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<?php echo form_open( full_url(), 'class="form form-horizontal" name="edit_page"' );?>

	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Edit - <?php echo $page['sc_name'];?></h3>
			</div>
			<div class="panel-body">
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-sm-3">Category Title/Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="title" value="<?php echo $page['sc_name']?>" required>
						</div>
					</div>
				
					<div class="form-group">
						<label class="control-label col-sm-3">URI String</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="segment" value="<?php echo $page['sc_value']?>">
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-3 control-label">Parent</label>
						<div class="col-sm-8">
							<select class="form-control" name="parent">
								<option value="0" <?php if($page['sc_parent']==0) echo 'selected';?>>None</option>
								<?php foreach($sections as $k=>$v):
									if( $page['sc_parent'] == $v['sc_id'] ) $s='selected';else $s='';?>
									<option value="<?php echo $v['sc_id']?>" <?php echo $s;?>><?php echo $v['sc_name']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Menu Position</label>
						<div class="col-sm-8">
							<select class="form-control" name="menu">
								<option value="99" <?php if($page['sc_menu']==99) echo 'selected';?>>Do not show</option>
								<?php foreach( range( 1, count($sections)+3 ) as $v):
									if( $page['sc_menu'] == $v ) $s='selected';else $s='';?>
									<option value="<?php echo $v?>" <?php echo $s;?>><?php echo num_string($v);?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3">Category Keywords <small>(for SEO)</small></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="keywords" value="<?php echo $page['sc_keywords']?>">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3">Description</label>
						<div class="col-sm-9">
							<textarea class="form-control" name="description"><?php echo $page['sc_description']?></textarea>
						</div>
					</div>
				
				</div>
				<!-- col-md-6 -->
				
				<div class="col-md-6">

					<div class="form-group">
						<label class="control-label col-sm-3">Page Controller</label>
						<div class="col-sm-9">
							<?php
								$controllers = get_controllers( SITEPATH.'/controllers' );
								echo form_dropdown( 'controller', $controllers, $page['sc_controller']?:'Pages', ['class'=>'form-control'] );
							?>
							<div class="form-text">Select "Pages" unless you know what you're doing.</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3">Template Directory Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="view" value="<?php echo $page['sc_view']?>" placeholder="Leave blank if you're not sure">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label">Default Order</label>
						<div class="col-sm-8">
							<select class="form-control" name="order">
								<option value="0">None</option>
								<?php $fields = config_item('default_sort_fields');
									foreach( $fields as $k=>$v): if($k>0):
									if( $page['sc_order'] == $k ) $s='selected';else $s='';?>
									<option value="<?php echo $k?>" <?php echo $s;?>><?php echo $v['n'];?></option>
								<?php endif; endforeach;?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Default Items Per Page</label>
						<div class="col-sm-8">
							<input type="number" min="1" class="form-control" name="items" value="<?php echo $page['sc_items'];?>">
						</div>
					</div>

                    <div class="form-group">
                        <label class="control-label col-sm-3 col-xs-6" for="enabled">Status:</label>
                        <div class="col-sm-9 col-xs-6">
							<?php echo form_hidden('enabled',0)?>
                            <input type="checkbox" name="enabled" value="1" <?=$page['sc_enabled']?'checked':'';?> data-toggle="toggle" data-on="Enabled" data-off="Disabled">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3 col-xs-6" for="enabled">Gallery:</label>
                        <div class="col-sm-9 col-xs-6">
							<?php echo form_hidden('has_gallery',0)?>
                            <input type="checkbox" name="has_gallery" value="1" <?=$page['sc_has_gallery']?'checked':'';?> data-toggle="toggle" data-on="Enabled" data-off="Disabled">
                        </div>
                    </div>
				
				</div>

				<br clear="all"><br clear="all">
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Additional Fields (1 - 4)</h3>
						</div>
						<div class="panel-body">

							<div class="form-group">
								<label class="col-sm-5">Field Title</label>
								<label class="col-sm-7">Example Value / Description</label>
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f1" value="<?php echo  $page['sc_f1'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v1" value="<?php echo  $page['sc_v1'];?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f2" value="<?php echo  $page['sc_f2'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v2" value="<?php echo  $page['sc_v2'];?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f3" value="<?php echo  $page['sc_f3'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v3" value="<?php echo  $page['sc_v3'];?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f4" value="<?php echo  $page['sc_f4'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v4" value="<?php echo  $page['sc_v4'];?>">
								</div>
							</div>


						</div>
					</div>
				</div>
				<!-- col-sm-6 Additional Article Fields -->
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Additional Fields (5 - 8)</h3>
						</div>
						<div class="panel-body">

							<div class="form-group">
								<label class="col-sm-5">Field Title</label>
								<label class="col-sm-7">Example Value / Description</label>
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f5" value="<?php echo  $page['sc_f5'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v5" value="<?php echo  $page['sc_v5'];?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f6" value="<?php echo  $page['sc_f6'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v6" value="<?php echo  $page['sc_v6'];?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f7" value="<?php echo  $page['sc_f7'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v7" value="<?php echo  $page['sc_v7'];?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<input type="text" class="form-control input-sm" name="f8" value="<?php echo  $page['sc_f8'];?>">
								</div>
								<div class="col-sm-7">
									<input type="text" class="form-control input-sm" name="v8" value="<?php echo  $page['sc_v8'];?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- col-sm-6 Additional Article Fields -->
				
				<br clear="all">
				<hr>
				
				<div class="form-group">
					<div class="col-sm-6" style="text-align:right;">
						<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default btn-lg"' );?>&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="submit" name="submit" value="1" class="btn btn-primary btn-lg">Submit</button>
						<input type="hidden" name="form_name" value="page">
						<input type="hidden" name="form_type" value="update">
						<input type="hidden" name="id" value="<?php echo $page['sc_id'];?>">
					</div>
				</div>
				
			</div>
		</div>
	</div>
</form>



