<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view("$theme/common/header.tpl"); ?>

<div class="container-fluid main-container">
		<div class="col-lg-12">

			<?php $this->load->view("$theme/common/navbar.tpl"); ?>
			
			<div class="container-fluid content-wrapper">

					<div class="col-sm-12">
						<h2 class="welcome">
							Welcome to 
							<span class="text-info"><?php echo $this->config->item('site-name');?></span>
						</h2>
					</div>
			
					<div class="col-sm-5">
						<h4 class="title">
                            <form class="form-horizontal" method="get" id="dateform">
                                <!--Recent Activity&nbsp;-->
                                <!--- Last <a href="#ana_days" data-toggle="modal" title="Set number of days to view">-->
                                <!--    <i class="radmin radmin-calendar"></i> --><?php //echo $ana_days==1?'24 Hours':"{$ana_days} Days";?>
                                <!--</a>&nbsp;&nbsp;&nbsp;-->
                                <!--<input type="text" name="datetimes" class="form-control input-xs daterange" />-->
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">Recent Activity </span>
                                    <input type="text" class="form-control daterange" name="daterange" aria-describedby="sizing-addon3">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Go</button>
                                    </span>
                                </div>
                            </form>
                            <form class="hidden">
                                <input type="hidden" name="ana_start" id="ana_start" value="<?php echo $ana_start?>">
                                <input type="hidden" name="ana_end" id="ana_end" value="<?php echo $ana_end?>">
                            </form>
                        </h4>
					    <div class="squiggly-border"></div>
						<table class="table table-index">
							<thead>
								<tr>
									<th>Description</th>
									<th>Number</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text">Total Visitors</td><td class="numbers"><?php echo $ana_summary['visitors'];?></td>
								</tr>
								<tr>
									<td class="text">New Visitors</td>
                                    <td class="numbers"><?php echo $ana_summary['new_visitors'];?></td>
								</tr>
                                <tr>
                                    <td class="text">Returning Visitors</td><td class="numbers"><?php echo $ana_summary['visitors']-$ana_summary['new_visitors'];?></td>
                                </tr>
								<tr>
									<td class="text">Page Views</td><td class="numbers"><?php echo $ana_summary['pageviews']['total'];?></td>
								</tr>
                                <tr>
                                    <td class="text">Average Page Load Time</td>
                                    <td class="numbers"><?php echo pretty_time($ana_summary['pageviews']['average_load_duration']*1, 'human' );?></td>
                                </tr>
                                <tr>
                                    <td class="text">Total Sessions</td><td class="numbers"><?php echo $ana_summary['sessions']['total'];?></td>
                                </tr>
                                <tr>
                                    <td class="text">Average Session Duration</td>
                                    <td class="numbers"><?php echo pretty_time($ana_summary['sessions']['average_duration']*1, 'human');?></td>
                                </tr>
                                <tr>
                                    <td class="text">Bounce Rate</td><td class="numbers"><?php echo round($ana_summary['sessions']['bounce_rate']*100,1),'%';?></td>
                                </tr>
                                <tr>
                                    <td class="text">Page views per session</td><td class="numbers"><?php echo $ana_summary['sessions']['pageviews'];?></td>
                                </tr>
                                <?php
                                    $classes=['success','danger','info','warning'];
                                    $i=$n=0;
                                    $btns = [ 'desktop'=>'screen', 'mobile'=>'mobile', 'robot'=>'android', 'other'=>'question' ];
                                    ?>
                                <tr>
                                    <td class="text">Devices (%)
                                        <div class="btn-group btn-group-xs" role="group">
                                            <?php foreach($ana_summary['devices'] as $name=>$d):
                                                $i = $n++%4;
                                                $c = isset( $btns[$name] ) ? $btns[$name] : $btns['other'];
                                            ?>
                                                <button type="button" class="btn btn-<?php echo $classes[$i];?>" data-toggle="tooltip" data-placement="top" data-trigger="hover focus click" title="<?php echo ucwords($name);?>">
                                                    &nbsp;<span class="radmin radmin-<?php echo $c;?>"></span>&nbsp;
                                                </button>
                                            <?php endforeach; $n=0;?>
                                        </div>
                                    </td>
                                    <td class="text">
                                        <div class="progress hidden-xs hidden-sm">
                                            <?php foreach($ana_summary['devices'] as $name=>$d):
                                                $i = $n%4;
	                                            $s = $n%2 ? 'progress-bar-striped':'';
	                                            $n++;
                                                $p = $ana_summary['visitors'] ? round(($d*100)/$ana_summary['visitors'],1) : 0;?>
                                                <div class="progress-bar progress-bar-<?php echo $classes[$i],' ',$s;?>" style="width: <?=$p;?>%"
                                                    data-toggle="tooltip" data-placement="top" data-trigger="hover focus click" title="<?php echo ucwords($name),' ',$p;?>%">
                                                    <span class="sr-only"><?php echo ucwords($name).' '; echo $p>14?'':$p;?></span>
                                                    <span><?php echo $p>14?$p:'';?></span>
                                                </div>
                                            <?php endforeach; $n=0;?>
                                        </div>
                                        <div class="hidden-md hidden-lg">
		                                    <?php foreach($ana_summary['devices'] as $name=>$d):
			                                    $i = $n++%4;
			                                    $p = $ana_summary['visitors'] ? round(($d*100)/$ana_summary['visitors'],1) : 0;
			                                    ?>
                                                <button type="button" class="btn btn-<?php echo $classes[$i];?> btn-xs" data-toggle="tooltip" data-placement="top" data-trigger="hover focus click" title="<?php echo ucwords($name);?>">
	                                                <?php echo $p;?>%
                                                </button>
		                                    <?php endforeach; $n=0;?>
                                        </div>
                                    </td>
                                </tr>
							</tbody>
						</table>
					</div>
			
					<div class="col-sm-7">
						<h4 class="title">Top 10 Pages : <?php echo $ana_days;?>
                        </h4>
						<div class="squiggly-border"></div>
                        <table class="table table-index">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Views</th>
                                <th>Ave. load time</th>
                                <th>Ave. time on page</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $ana_summary['top_pages'] as $k=>$v ):?>
                            <tr>
                                <td class="text"><?php echo $k+1;?></td>
                                <td class="text"><?php echo anchor( $v['pv_url'], $v['pv_title'], ['target'=>'_blank','title'=>$v['pv_uri']] );?></td>
                                <td class="numbers"><?php echo $v['viewed'];?></td>
                                <td class="numbers"><?php echo pretty_time($v['average_load_duration']*1,'human');?></td>
                                <td class="numbers"><?php echo pretty_time($v['pv_duration']*1);?></td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
					</div>
			
					<div class="col-sm-12">&nbsp;</div>

                <div class="col-sm-5">
                    <h4 class="title">
                        Traffic Sources&nbsp;: <?php echo $ana_days;?>
                    </h4>
                    <div class="squiggly-border"></div>
                    <table class="table table-index">
                        <thead>
                        <tr>
                            <th>Source</th>
                            <th>Sessions</th>
                            <th>Percentage</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($ana_summary['sources'] as $source):
                            $t = $ana_summary['sessions']['total'] ? $ana_summary['sessions']['total'] : 1;
                            $p = round( ($source['sessions']/$t)*100, 1 );
                            ?>
                            <tr>
                                <td class="text">
                                    <?php echo $source['host'];
                                        if($source['host']=='other'):
                                    ?>
                                    <a href="#ana_other_traffic_sources" data-toggle="modal" data-target="#ana_other_traffic_sources"
                                        data-start="<?php echo $ana_start;?>" data-end="<?php echo $ana_end;?>">
                                        <span class="radmin radmin-new-tab"></span>
                                    </a>
                                    <?php endif;?>
                                </td>
                                <td class="numbers"><?php echo $source['sessions'];?></td>
                                <td class="numbers"><?php echo $p;?>%</td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
			
					<div class="col-sm-12">&nbsp;</div>
			
					<div class="col-sm-12">
						<h4 class="title">Recent Posts</h4>
						<div class="squiggly-border"></div>
					</div>

					<div class="col-sm-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><span class="radmin radmin-picture"></span></th>
                                <th class="hidden-xs hidden-sm">Title / Section</th>
                                <th class="hidden-xs">Summary</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
	                        <?php foreach($recent_articles as $k=>$v):?>
                                <tr>
                                    <td><?php echo $k+1;?></td>
                                    <td>
				                        <?php
					                        if( $v['at_permalink'] )
						                        $link = home_url($v['at_segment']);
					                        else
					                        {
						                        $sections = explode( '/', $v['sc_value'] );
						                        if( count($sections)>1 )
							                        $link = home_url( "{$v['sc_value']}/{$v['at_id']}/{$v['at_segment']}" );
						                        else
							                        $link = home_url( "{$v['sc_value']}/{$v['at_id']}/{$v['at_segment']}" );
					                        }
					                        $try = "images/articles/xs/{$v['at_image']}";
					                        if( strlen($v['at_image'])>3 && file_exists($try) ) $file = $try;
					                        else $file = 'images/noimage.svg';
					                        $img = array( 'src'=>$file, 'class'=>'thumbnail' );
					                        echo anchor( $link, img($img), 'target="_blank"' );
				                        ?>
                                        <strong class="hidden-md hidden-lg"><?php echo $v['at_title'];?></strong>
                                    </td>
                                    <td class="hidden-xs hidden-sm">
                                        <strong><?php echo $v['at_title'];?></strong>
                                        <br>
				                        <?php echo $v['sc_name'];?>
                                    </td>
                                    <td class="hidden-xs">
                        <span class="hidden-sm">
                            <?php echo $v['at_summary'];?>
                            <br>
                            <strong>Posted: </strong><?php echo date( 'd M Y, H:i', $v['at_date_posted'] );?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <strong>Updated: </strong><?php echo date( 'd M Y, H:i', $v['at_date_updated'] );?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                                        Read by: <strong><?php echo $v['at_hits'];?></strong>
                                    </td>
                                    <td>
				                        <?php echo anchor( current_url()."?action=edit_article&id={$v['at_id']}",
					                        '<span class="radmin radmin-pencil"></span>',
					                        'class="btn btn-xs btn-link" title="edit article"' );

					                        $e = $v['at_enabled']?'radmin-checkbox':'radmin-checkbox-unchecked';
					                        $et = $v['at_enabled']?'Disable':'Enable';

					                        $f = $v['at_featured']?'radmin-star-3':'radmin-star';
					                        $ft = $v['at_featured']?'Remove from featured':'Add to featured';

					                        $p = $v['at_private']?'radmin-locked':'radmin-unlocked';
					                        $pt = $v['at_private']?'Make Public':'Make Private';

					                        echo anchor( current_url()."?action=toggle_state&p=enabled&v={$v['at_enabled']}&id={$v['at_id']}",
						                        '<span class="radmin '.$e.'" style="font-size:13px;"></span>',
						                        'title="'.$et.'" class="btn btn-link btn-xs"' );
					                        echo anchor( current_url()."?action=toggle_state&p=featured&v={$v['at_featured']}&id={$v['at_id']}",
						                        '<span class="radmin '.$f.'" style="font-size:15px;"></span>',
						                        'title="'.$ft.'" class="btn btn-link btn-xs"' );
					                        echo anchor( current_url()."?action=toggle_state&p=private&v={$v['at_private']}&id={$v['at_id']}",
						                        '<span class="radmin '.$p.'" style="font-size:15px;"></span>',
						                        'title="'.$pt.'" class="btn btn-link btn-xs"' );
					                        echo anchor( current_url()."?action=delete_article&id={$v['at_id']}",
						                        '<span class="radmin radmin-remove-3" style="color:red"></span>',
						                        'title="Delete" class="btn btn-xs btn-link"' );
					                        if( $v['sc_id']==11 )
						                        echo '<br>'.anchor( "articles/gallery/{$v['at_id']}",
								                        '<span class="radmin radmin-picture"></span> Gallery Images', 'class="btn btn-link"' );
				                        ?>



                                    </td>
                                </tr>
	                        <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
			
					<div class="col-sm-12">&nbsp;</div>
			</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="ana_days" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Internal Analytics</h4>
            </div>
            <div class="modal-body">
                <form method="get" action="" class="form form-horizontal" id="ana_days_form">
                    <div class="form-group">
                        <div class="col-xs-12 col-md-10">
                            <label>How many days of reports would you like to see?</label>
                            <input type="number" max="400" name="days" class="form-control input-lg">
                        </div>
                    </div>
                    <div class="hidden">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('ana_days_form').submit()">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ana_other_traffic_sources" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Internal Analytics - Traffic Sources</h4>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Source</th><th>Sessions</th><th>Percentage</th></tr>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("$theme/common/footer.tpl"); ?>
