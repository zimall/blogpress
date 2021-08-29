<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info"><?php echo isset($innertitle)?$innertitle:'Pages';?></span>
        <span class="pull-right">
            <?php echo anchor( current_url().'?action=add_category', 'Add Category', ['class'=>'btn btn-sm btn-default'] );?>
        </span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'pages/categories', '<i class="radmin-icon radmin-book"></i>Site Pages' );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-file"></i> View All
		</li>
        <li class="pull-right dropdown">
            <a href="#" data-toggle="dropdown"><?php echo $per;?> items <span class="caret"></span></a> per page
            <ul class="dropdown-menu change_per_page">
                <li><a href="#">5</a></li>
                <li><a href="#">10</a></li>
                <li><a href="#">20</a></li>
                <li><a href="#">50</a></li>
                <li><a href="#">100</a></li>
            </ul>
        </li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>


<div class="col-sm-12">
	<table class="table table-condensed table-dotted table-hover">
		<tr>
			<th>ID</th>
			<th>Page Name &nbsp;&nbsp;&nbsp; (Articles)</th>
			<th>Menu Position</th>
            <th>Status</th>
            <th>Gallery</th>
			<th>Options</th>
		</tr>
		
		<?php foreach($pages as $k=>$page):?>
		<tr>
			<td><?php echo $page['sc_id'];?></td>
			<td>
				<?php echo anchor( $page['sc_value'], $page['sc_name']);?>
				<?php echo anchor( home_url($page['sc_value']), '<i class="radmin radmin-new-tab"></i>', 'target="_blank"');?>
				&nbsp;&nbsp;&nbsp;
				(<?php echo $page['article_count'];?>)
			</td>
			<td><?php echo $page['sc_menu'];?></td>
            <td><?php echo $page['sc_enabled'] ? '<span class="label label-success">Enabled</span>' : '<span class="label label-default">Disabled</span>' ?></td>
            <td><?php echo $page['sc_has_gallery'] ? '<span class="label label-success">Enabled</span>' : '<span class="label label-default">Disabled</span>' ?></td>
			<td>
				<?php echo anchor( current_url()."?action=edit_page&id={$page['sc_id']}", '<span class="btn btn-default btn-xs"> <i class="radmin-icon radmin-pencil"></i> Edit</span>', ['title'=>'Edit'] );?>
				<?php //echo anchor( 'pages/rules/'.$page['sc_id'], '<span class="btn btn-default btn-xs"> <i class="radmin-icon radmin-cog"></i> </span>', ['title'=>'Page Settings'] );?>
				<?php echo anchor( current_url()."?action=delete_category&id={$page['sc_id']}", '<span class="btn btn-default btn-xs btn-danger"> <i class="radmin-icon radmin-remove"></i> Delete</span>', ['title'=>'Delete Category'] );?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>

    <div class="col-xs-12">
        <br clear="all"><br clear="all">
        <ul class="pagination pull-left" style="margin: 0;">
            <li class="disabled">
                <a style="border:none;">
                    Showing <?php echo $start?> to <?php echo $current_rows?> of <?php echo $total_rows?> items
                </a>
            </li>
        </ul>
        <ul class="pagination pull-right" style="margin: 0 0 5px 0;">
			<?php echo $this->pagination->create_links(); ?>
        </ul>
        <br clear="all"><br clear="all">
    </div>
    <!--end pagination-->
</div>
