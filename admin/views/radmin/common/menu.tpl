<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<nav id="navbar-menu" class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php echo anchor( 'home', 'Site<span class="rad">Admin</span>', 'class="navbar-brand brand"' );?>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li>
					<?php echo anchor( 'home',
                        '<span class="box"> <i class="radmin-icon radmin-home"></i> </span>'.'<span>Dashboard</span>'
					);?>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="box"><i class="radmin-icon radmin-clipboard-2"></i></span>
                        <span>Site Pages</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
		                    <?php echo anchor( 'articles/new_article', '<i class="radmin-pencil"></i> <span>New Article</span>' );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'pages/categories',
			                    '<span class="box"> <i class="radmin-icon radmin-book"></i> </span>'.
			                    '<span>Article Categories</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'articles/featured',
			                    '<span class="box"> <i class="radmin-icon radmin-star"></i> </span>'.
			                    '<span>Featured Articles</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'articles/published',
			                    '<span class="box"> <i class="radmin-icon radmin-checkbox"></i> </span>'.
			                    '<span>Published Articles</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'articles/unpublished',
			                    '<span class="box"> <i class="radmin-icon radmin-checkbox-partial"></i> </span>'.
			                    '<span>Unpublished Articles</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'articles',
			                    '<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
			                    '<span>All Articles</span>'
		                    );?>
                        </li>
                    </ul>
                </li>
                <?php if( isset($menu) && is_array($menu) && !empty($menu) ):?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="box"><i class="radmin-icon radmin-clipboard-2"></i></span>
                        <span>Categories</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach($menu as $v):?>
                        <li>
							<?php echo anchor( $v['segment'], '<i class="radmin-arrow-right-5"></i>&nbsp;&nbsp;<span>'.$v['title'].'</span>' );?>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </li>
                <?php endif;?>
                <li>
					<?php echo anchor( 'about/index',
						'<span class="box"> <i class="radmin-icon radmin-briefcase"></i> </span>'.
						'<span>About Us</span>'
					);?>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="box"><i class="radmin-icon radmin-cog"></i></span>
                        <span>Settings</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
		                    <?php echo anchor( 'settings/index',
			                    '<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
			                    '<span>General Settings</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'settings/slider',
			                    '<span class="box"> <i class="radmin-icon radmin-picture"></i> </span>'.
			                    '<span>Banner Slider</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'users/index',
			                    '<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
			                    '<span>User Accounts</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'users/groups',
			                    '<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
			                    '<span>User Groups</span>'
		                    );?>
                        </li>
                        <li>
		                    <?php echo anchor( 'users/privileges',
			                    '<span class="box"> <i class="radmin-icon radmin-cog"></i> </span>'.
			                    '<span>User Privileges</span>'
		                    );?>
                        </li>
                    </ul>
                </li>

			</ul>
			<ul class="nav navbar-nav navbar-right">
                <li class="hidden-xs hidden-sm">
                    <form class="navbar-form navbar-left" style="min-width:220px;" role="search" action="/admin/articles/search" method="get">
                        <div style="max-width:200px;" class="row">
                            <div class="input-group input-group-sm">
                                <input type="search" name="q" class="form-control" value="<?php echo isset($search_term)?$search_term:'';?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><span class="radmin radmin-search"></span></button>
                                </span>
                            </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                    </form>
                </li>
                <li><?php echo anchor( base_url(), 'Site' );?></li>
                <li><?php echo anchor( base_url(), '<span class="glyphicon glyphicon-share-alt"></span>', 'target="_blank"' );?></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<?php echo "{$user_data['first_name']} {$user_data['last_name']}"?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo home_url('account');?>">My Account</a></li>
	                    <?php if($this->flexi_auth->is_privileged('Run Debug Mode')):?>
                            <li><a href="?toggle_debug_mode=1"><?php echo $this->pc->debug_status()?></a></li>
	                    <?php endif;?>
                        <li class="divider"></li>
                        <li><?php echo anchor( home_url('account/logout'), 'Logout');?></li>
                    </ul>
                </li>
			</ul>
		</div><!--/.nav-collapse -->
	</div><!--/.container-fluid -->
</nav>

<div class="col-xs-12">
    <form class="form form-horizontal hidden-md hidden-lg" role="search" action="/admin/articles/search" method="get">
        <div class="row">
            <div class="input-group input-group-lg">
                <input type="search" name="q" class="form-control" value="<?php echo isset($search_term)?$search_term:'';?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><span class="radmin radmin-search"></span></button>
                </span>
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
    </form>
</div>