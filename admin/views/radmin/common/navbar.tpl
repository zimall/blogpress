<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="sidebar-nav hidden-xs hidden-sm">
	<ul class="nav nav-stacked left-menu" id="navigation">
		<li id="navigation-index">
			<?php echo anchor( 'home',
				'<span class="box"> <i class="radmin-icon radmin-home"></i> </span>'.
				'<span class="hidden-tablet hidden-phone">Dashboard</span>'
			);?>
		</li>
		<li class="accordion" id="navigation-articles">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#navigation-articles" href="#articles">
				<span class="box">
					<i style="color:brown;" class="radmin-icon radmin-clipboard-2"></i>
				</span>
				<span class="hidden-tablet hidden-phone">Site Pages</span>
				<span class="badge pull-right hidden-tablet hidden-phone">2</span>
			</a>
			<div id="articles" class="accordion-body collapse">
				<ul class="nav nav-stacked submenu">
					<li><br></li>
					<li>
						<?php echo anchor( 'pages',
							'<span class="box"> <i class="radmin-icon radmin-book"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Page Manager</span>'
						);?>
					</li>
					<li class="submenu-last">
						<?php echo anchor( 'articles',
							'<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">All Articles</span>'
						);?>
					</li>
				<!--
					<li>
						<?php echo anchor( 'articles/news',
							'<span class="box"> <i class="radmin-icon radmin-newspaper"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">News</span>'
						);?>
					</li>
					<li>
						<?php echo anchor( 'articles/services',
							'<span class="box"> <i class="radmin-icon radmin-briefcase"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Services</span>'
						);?>
					</li>
					<li class="submenu-last">
						<?php echo anchor( 'articles/projects',
							'<span class="box"> <i class="radmin-icon radmin-briefcase"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Projects</span>'
						);?>
					</li>
				-->
				</ul>
			</div>
		</li>
		<li id="navigation-about" class="accordion">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#navigation-about"
				href="#collapse-about">
				<span class="box">
					<i style="color:brown;" class="radmin-icon radmin-cabinet"></i>
				</span>
				<span class="hidden-tablet hidden-phone">About</span>
				<span class="badge pull-right hidden-tablet hidden-phone">1</span>
			</a>
			
			<div id="collapse-about" class="accordion-body collapse">
				<br clear="all">
				<ul class="nav nav-stacked submenu">
					<li><br></li>
					<!-- <li>
						<?php echo anchor( 'about/index',
							'<span class="box"> <i class="radmin-icon radmin-box"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">General Information</span>'
						);?>
					</li> 
					<li>
						<?php echo anchor( 'about/leadership',
							'<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Management</span>'
						);?>
					</li>
					
					<li>
						<?php echo anchor( 'about/other',
							'<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Vision, Mission...</span>'
						);?>
					</li>
					
					<li>
						<?php echo anchor( 'about/approach',
							'<span class="box"> <i class="radmin-icon radmin-target-2"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Our Approach</span>'
						);?>
					</li>
					<li>
						<?php echo anchor( 'about/philosophy',
							'<span class="box"> <i class="radmin-icon radmin-lab"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Our Philosophy</span>'
						);?>
					</li>
					 -->
					<li class="submenu-last">
						<?php echo anchor( 'about/index',
							'<span class="box"> <i class="radmin-icon radmin-briefcase"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">About Us</span>'
						);?>
					</li>
				</ul>
			</div>
		</li>
		
		<!-- <li id="navigation-articles">
			<?php echo anchor( 'articles',
				'<span class="box"> <i style="color:blue" class="radmin-icon radmin-clipboard-2"></i> </span>'.
				'<span class="hidden-tablet hidden-phone">Articles</span>'
			);?>
		</li> -->
		
		<li class="accordion" id="navigation-settings">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#navigation-settings" href="#collapse-settings">
				<span class="box">
					<i style="color:brown;" class="radmin-icon radmin-cog"></i>
				</span>
				<span class="hidden-tablet hidden-phone">Settings</span>
				<span class="badge pull-right hidden-tablet hidden-phone">1</span>
			</a>
			<div id="collapse-settings" class="accordion-body collapse">
				<ul class="nav nav-stacked submenu">
					<li><br></li>
					<li>
						<?php echo anchor( 'settings/index',
							'<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">General</span>'
						);?>
					</li>
					<li class="submenu-last">
						<?php echo anchor( 'settings/slider',
							'<span class="box"> <i class="radmin-icon radmin-picture"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">Banner Slider</span>'
						);?>
					</li>
				</ul>
			</div>
		</li>
		<li class="accordion" id="navigation-users">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#navigation-users" href="#collapse-users">
				<span class="box">
					<i style="color:brown;" class="radmin-icon radmin-cog"></i>
				</span>
				<span class="hidden-tablet hidden-phone">Manage Users</span>
				<span class="badge pull-right hidden-tablet hidden-phone">3</span>
			</a>
			<div id="collapse-users" class="accordion-body collapse">
				<ul class="nav nav-stacked submenu">
					<li><br></li>
					<li>
						<?php echo anchor( 'users/index',
							'<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">User Accounts</span>'
						);?>
					</li>
					<li>
						<?php echo anchor( 'users/groups',
							'<span class="box"> <i class="radmin-icon radmin-user"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">User Groups</span>'
						);?>
					</li>
					<li class="submenu-last">
						<?php echo anchor( 'users/privileges',
							'<span class="box"> <i class="radmin-icon radmin-cog"></i> </span>'.
							'<span class="hidden-tablet hidden-phone">User Privileges</span>'
						);?>
					</li>
				</ul>
			</div>
		</li>
	</ul>
</div> <!-- end of sidebar-nav-->
