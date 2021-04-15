<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Add New Article Category</span>
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
			<i class="radmin-icon radmin-pencil"></i> Add New Article Category
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<?php echo form_open( full_url(), 'class="form form-horizontal" name="new_category"' );?>

	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Add New Article Category</h3>
			</div>
			<div class="panel-body">
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-sm-3">Category Title/Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="title" value="<?php echo $this->input->post('title');?>" required>
						</div>
					</div>
				
					<div class="form-group">
						<label class="control-label col-sm-3">URI String</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="segment" value="<?php echo $this->input->post('segment');?>">
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-3 control-label">Parent</label>
						<div class="col-sm-8">
							<select class="form-control" name="parent">
								<option value="0" <?php if($this->input->post('parent')==0) echo 'selected';?>>None</option>
								<?php foreach($sections as $k=>$v):
									if( $this->input->post('parent') == $v['sc_id'] ) $s='selected';else $s='';?>
									<option value="<?php echo $v['sc_id']?>" <?php echo $s;?>><?php echo $v['sc_name']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Menu Position</label>
						<div class="col-sm-8">
							<select class="form-control" name="menu">
								<option value="99" <?php if($this->input->post('menu')==99) echo 'selected';?>>Do not show</option>
								<?php foreach( range( 1, count($sections)+3 ) as $v):
									if( $this->input->post('menu') == $v ) $s='selected';else $s='';?>
									<option value="<?php echo $v?>" <?php echo $s;?>><?php echo num_string($v);?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
				
				</div>
				<!-- col-md-6 -->
				
				<div class="col-md-6">

                    <div class="form-group">
                        <label class="control-label col-sm-3 col-xs-6" for="enabled">Status:</label>
                        <div class="col-sm-9 col-xs-6">
							<?php echo form_hidden('enabled',0)?>
                            <input type="checkbox" name="enabled" value="1" <?=$this->input->post('enabled')?'checked':'';?> data-toggle="toggle" data-on="Enabled" data-off="Disabled">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3 col-xs-6" for="enabled">Gallery:</label>
                        <div class="col-sm-9 col-xs-6">
							<?php echo form_hidden('has_gallery',0)?>
                            <input type="checkbox" name="has_gallery" value="1" <?=$this->input->post('has_gallery')?'checked':'';?> data-toggle="toggle" data-on="Enabled" data-off="Disabled">
                        </div>
                    </div>
				
				</div>
				
				<br clear="all">
				<hr>
				
				<div class="form-group">
					<div class="col-sm-6" style="text-align:right;">
						<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default btn-lg"' );?>&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="submit" name="submit" value="1" class="btn btn-primary btn-lg">Submit</button>
						<input type="hidden" name="form_name" value="page">
						<input type="hidden" name="form_type" value="insert">
					</div>
				</div>
				
			</div>
		</div>
	</div>
</form>



