<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">New User Group</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>

		</li>
		<li>
			<?php echo anchor( 'users', '<i class="radmin-icon radmin-user-2"></i>Users' );?>

		</li>
		<li class="active">
			<i class="radmin-icon radmin-user"></i> New User Group
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">User Group</h3>
		</div>
		<div class="panel-body">
			<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>

				<div class="form-group">
					<label class="control-label col-sm-3">Group Name</label>
					<div class="col-sm-6">
						<input type="text" name="name" class="form-control" value="<?php echo $this->input->post('name');?>" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-sm-3">Parent Group</label>
					<div class="col-sm-6">
						<select name="parent" class="form-control" required>
							<?php foreach($groups as $k=>$v):
								$s = ($v['ugrp_id']==$this->input->post('parent'))?'selected':'';
							?>
								<option value="<?php echo $v['ugrp_id'];?>" <?php echo $s;?>><?php echo $v['ugrp_name'];?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Admin Panel Access</label>
					<div class="col-sm-6">
						<?php echo form_hidden('ugrp_admin',0)?>
						<input type="checkbox" name="ugrp_admin" value="1" <?=$this->input->post('ugrp_admin')?'checked':'';?> 
							data-toggle="toggle" data-on="Yes" data-off="No" data-size="small">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Group Description</label>
					<div class="col-sm-6">
						<textarea name="description" class="form-control" rows="5"><?php echo 
							$this->input->post('description')?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Add Group</label>
					<div class="col-sm-6">
						<?php echo anchor( 'users/groups', 'Cancel', 'class="btn btn-default"' );?>
						<button type="submit" name="add_group" value="1" class="btn btn-primary">Submit</button>
					</div>
				</div>
				<div class="hidden">
					<input type="hidden" name="form_name" value="user_group">
					<input type="hidden" name="form_type" value="insert">
				</div>
			</form>
		</div>
	</div>
</div>
