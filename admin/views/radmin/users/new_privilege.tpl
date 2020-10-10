<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">New User Privilege</span>
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
			<i class="radmin-icon radmin-user"></i> New User Privilege
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">User Privilege</h3>
		</div>
		<div class="panel-body">
			<?php echo form_open( current_url(), 'class="form form-horizontal"' );?>

				<div class="form-group">
					<label class="control-label col-sm-3">Privilege Name</label>
					<div class="col-sm-6">
						<input type="text" name="name" class="form-control" 
							value="<?php echo $this->input->post('name');?>" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-sm-3">Privilege Description</label>
					<div class="col-sm-6">
						<textarea name="description" class="form-control" rows="5"><?php echo 
							$this->input->post('description')?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Add Privilege</label>
					<div class="col-sm-6">
						<?php echo anchor( 'users/privileges', 'Cancel', 'class="btn btn-default"' );?>
						<button type="submit" name="add_privilege" value="1" class="btn btn-primary">Submit</button>
					</div>
				</div>
				<div class="hidden">
					<input type="hidden" name="form_name" value="user_privilege">
					<input type="hidden" name="form_type" value="insert">
				</div>
			</form>
		</div>
	</div>
</div>
