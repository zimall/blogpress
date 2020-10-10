<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Group Privileges</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( 'users', '<i class="radmin-icon radmin-user-2"></i>Users' );?>
			<span class="divider"></span>
		</li>
		<li>
			<?php echo anchor( 'users/groups', '<i class="radmin-icon radmin-user-3"></i>User Groups' );?>
			<span class="divider"></span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-user"></i> <?php echo $group['ugrp_name'];?>
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Group Privileges:  <?php echo $group['ugrp_name'];?></h3>
		</div>
		<div class="panel-body">
			<?php echo form_open( full_url(), 'class="form form-horizontal"' );?>
			
				<div class="col-xs-5">
					<div class="form-group">
						<label class="control-label" for="av_permissions">Available Group Permissions</label>
					</div>
				</div>
				<div class="col-xs-1">&nbsp;</div>
				<div class="col-xs-5">
					<div class="form-group">
						<label class="control-label" for="permissions">Current Group Permissions</label>
					</div>
				</div>
				
				<div class="col-xs-5">
					<div class="form-group">
						<select id="av_permissions" name="av_permissions" size="15" class="form-control">
							<?php foreach( $av_permissions as $k => $v ):?>
								<option value="<?php echo $v['upriv_id']?>"><?php echo $v['upriv_name']?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				
				<div class="col-xs-1">
					<div id="add_to_list" class="add_to_list">
						<input id="add_one" type="button" class="btn btn-xs btn-default" value=">" title="add one"><br>
						<input id="remove_one" type="button" class="btn btn-xs btn-default" value="<" title="remove one"><br>
						<input id="remove_all" type="button" class="btn btn-xs btn-default" value="<<" title="remove all"><br>
					</div>
				</div>
		
				<div class="col-xs-5">
					<div class="form-group">
						<select id="permissions" name="permissions[]" size="15" class="form-control" multiple >
							<?php
								foreach($permissions as $k=>$v):?>
									<option value="<?php echo $v['upriv_id'] ?>" selected><?php echo $v['upriv_name'];?></option>
								<?php endforeach;?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-6">&nbsp;</div>
					<div class="col-sm-6">
						<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default"' );?>
						<button type="submit" class="btn btn-primary" name="update_group_privileges" value="1">Save Changes</button>
					</div>
				</div>
				
				<div class="hidden">
					<input type="hidden" name="form_name" value="group_privileges">
					<input type="hidden" name="form_type" value="update">
					<input type="hidden" name="group" value="<?php echo $group['ugrp_id'];?>">
				</div>
				
			</form>
		</div>
	</div>
</div>
