<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info">Delete Article</span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
			<span class="divider">/</span>
		</li>
		<li>
			<?php echo anchor( current_url(), 'Articles' );?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-remove-2"></i> Delete
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">
				<span>Delete Article</span>
			</h3>
		</div>
		<div class="panel-body">
			<?php echo form_open( full_url(), 'class="form form-horizontal"' );?>
				<h3>
					Are you sure you want to delete the article <strong><?php echo "{$article['at_title']}";?></strong> from the <?php 
					echo $article['sc_name']?> section?
				</h3>
				<h4>Warning: This action cannot be undone!</h4>
				<div class="form-group">
					<label class="checkbox col-sm-offset-2 col-lg-6">
						<input type="checkbox" name="confirm_delete" value="1">Yes, I'm sure
					</label>
				</div>
				<div class="form-group">
					<div class="col-lg-6 col-sm-offset-3">
						<?php echo anchor( current_url(), 'Cancel', 'class="btn btn-default"' );?>
						<button class="btn btn-danger" type="submit" name="delete" value="1">Delete</button>
					</div>
				</div>
				<input type="hidden" name="form_name" value="article">
				<input type="hidden" name="form_type" value="delete">
				<input type="hidden" name="id" value="<?php echo $article['at_id'];?>">
			</form>
		</div>
	</div>
</div>
