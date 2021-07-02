<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="col-sm-12">
	<h2 class="welcome">
		<span class="text-info"><?php echo $page['sc_name'].' Page Rules';?></span>
	</h2>
</div>


<div class="col-sm-12">
	<ul class="breadcrumb">
		<li>
			<?php echo anchor( 'home', '<i class="radmin-icon radmin-home"></i>Dashboard' );?>
		</li>
		<li>
			<?php echo anchor( 'pages/categories', '<i class="radmin-icon radmin-page"></i> '.$page['sc_name'] );?>
		</li>
		<li class="active">
			<i class="radmin-icon radmin-cog"></i> Page Rules
		</li>
	</ul>
</div> <!-- end of span12 -->

<div class="squiggly-border col-sm-12"></div>

<div class="col-sm-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Panel title</h3>
		</div>
		<div class="panel-body">
			<pre>
				<?php $headers = $this->input->request_headers(); print_r($headers);?>
			</pre>
		</div>
	</div>
</div>

<div class="col-sm-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Panel title</h3>
		</div>
		<div class="panel-body">
			Panel content
		</div>
	</div>
</div>

<div class="col-sm-12">
	<table class="table table-condensed table-dotted">
		<tr>
			<th>Product</th>
			<th>Shop</th>
			<th>Unit Price</th>
			<th>Qty</th>
			<th>Subtotal</th>
		</tr>
		<tr>
			<td>Product Name</td>
			<td>Shop Name</td>
			<td>$0.00</td>
			<td>15</td>
			<td>$828.00</td>
		</tr>
		<tr>
			<td>Product Name</td>
			<td>Shop Name</td>
			<td>$0.00</td>
			<td>15</td>
			<td>$828.00</td>
		</tr>
		<tr>
			<td>Product Name</td>
			<td>Shop Name</td>
			<td>$0.00</td>
			<td>15</td>
			<td>$828.00</td>
		</tr>
	</table>
</div>


<div class="modal fade" id="new_rule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>