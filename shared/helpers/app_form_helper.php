<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function year_of_study()
{
	$options = array();
	for( $i=date('Y');$i<=( date('Y')+2 );$i++ )
	{
		$options["$i - February"] = "$i - February";
		$options["$i - September"] = "$i - September";
	}
	return $options;
}

function app_courses()
{
	$ci = &get_instance();
	$courses = $ci->data['courses'];
	$data = array('Select');
	foreach( $courses as $c ) $data[$c['at_title']] = $c['at_title'];
	return $data;
}

function app_specialisations()
{
	$ci = &get_instance();
	$courses = $ci->data['specialisations'];
	$data = array('Select');
	foreach( $courses as $c ) $data[$c['at_title']] = $c['at_title'];
	return $data;
}

function app_form_days()
{
	$s = array('Day');
	return $days = array_merge( $s, range( 1,31 ) );
}

function app_form_years( $start=1900, $end=2100 )
{
	$years = array('Year');
	$r = range( $start, $end );
	foreach( $r as $v ) $years[$v] = $v;
	return $years;
}

function app_gender()
{
	return array( 0=>"Select", 'Male'=>'Male', 'Female'=>'Female' );
}

function app_yesno()
{
	return array( 0=>"Select", 'Yes'=>'Yes', 'No'=>'No' );
}

function app_form_fields($formdata)
{
	$ci = &get_instance();
	$form_types = $ci->config->item('form_input_type');
	foreach( $formdata as $name=>$input ):
		$att = $form_types[$input['type']]['att'];
		echo '<div class="form-group"><div class="row"><div class="col-xs-12 col-sm-4"><div class="label">';
			echo $input['label'];
				if( $input['required'] ) echo ' <span class="error">*</span>';
		echo '</div></div><div class="col-xs-12 col-sm-8"><div class="text-field">';
		
		if( $input['type']=='text'||$input['type']=='email'||$input['type']=='tel' ):
			if($input['required']) $att['required']=TRUE;
			$args = array( 'name'=>$name, 'value'=>$ci->input->post($name) );
			echo form_input( array_merge($args, $att) );
		
		elseif( $input['type']=='select' ):
			if( isset($input['options']) ) $options = $input['options'](); else $options=array('Select');
			echo form_dropdown( $name, $options, $ci->input->post($name), $att );
		
		elseif( $input['type']=='date' ):
			echo '<div class="row"><div class="col-xs-6 col-sm-4">';
			echo form_dropdown($name.'_month',$ci->config->item('months'),$ci->input->post($name.'_month'),$att );
			echo '</div><div class="col-xs-6 col-sm-4">';
			echo form_dropdown( $name.'_day', app_form_days(), $ci->input->post($name.'_day'), $att );
			echo '</div><div class="col-xs-6 col-sm-4">';
			echo form_dropdown($name.'_year',app_form_years($input['start'],$input['end'] ),$ci->input->post($name.'_year'),$att);
			echo '</div></div>';
		endif;

		echo '</div></div></div></div>';
	endforeach;
}
