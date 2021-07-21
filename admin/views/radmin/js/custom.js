switcher_div = $('#color-switcher');
switcher_control = $('#color-switcher-control');
switcher_is_transitioning = false;

switcher_div_style = {
	'width': switcher_control.children('a:first').width(),
	'z-index': 2,
	'top': '+=78px',
	'left': '-=5px'
};

switcher_control_style = {
	'z-index': 3,
	'position': 'relative'
};




// link fancybox plugin on product detail
function productFancyBox() {
	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
}


function get_flot_colors() {
	if(radmin_current_theme === 'pink') {
		return ['#E63E5D', '#97AF22', '#9D3844', '#533436', '#082D35'];
	} else if(radmin_current_theme === 'green') {
		return ['#42826C', '#FFC861', '#A5C77F', '#6d9f00', '#002F32'];
	} else {
		return ['#49AFCD', '#FF6347', '#38849A', '#BF4A35', '#999', '#555'];
	}

	return ['#49AFCD', '#FF6347', '#38849A', '#BF4A35', '#999', '#555'];
}

function get_sparkline_colors(){
	if(radmin_current_theme === 'pink') {
		return ['#E63E5D', '#97AF22'];
	} else if(radmin_current_theme === 'green') {
		return ['#42826C', '#FFC861'];
	} else {
		return ['#49AFCD', '#FF6347'];
	}

	return ['#49AFCD', '#FF6347'];
}

var sparkline_colors = get_sparkline_colors();


//Mouse over function
function suggestOver(div_value) {
	div_value.className = 'suggest_link_over';
}
//Mouse out function
function suggestOut(div_value) {
	div_value.className = 'suggest_link';
}

//Click function
function setSearch(value, id)
{
	document.getElementById('product_instant').value = value;
	document.getElementById('search_suggest').innerHTML = '';
	$("#search_suggest").css('display','none');
	get_product_data(id);
}

// Input lost focus
function clear_suggest()
{
	document.getElementById('search_suggest').innerHTML = '';
	$("#search_suggest").css('display','none');
}

// fetch product data
function get_product_data(id)
{
	site_url = $("#site_url").val();
	x = "id="+id;
	if(id > 0)
	{
		$.ajax({
			type: "GET",
			url: site_url+"/ajax/get_product_data",
			data: x,
			dataType: 'json',
			timeout: 60000,
			success: function(re)
			{
				if(!re['error'])
				{
					n = re.length;
					form = document.getElementById('new_product_form');
					if( re['var1'] ) form.var1.value = re['var1'];
					if( re['author'] ) form.author.value = re['author'];
					if( re['var2'] ) form.var2.value = re['var2'];
					if( re['publisher'] ) form.publisher.value = re['publisher'];
					if( re['category'] ) form.category.value = re['category'];
					if( re['d_summary'] ) form.summary.value = re['d_summary'];
					if( re['d_keywords'] ) form.keywords.value = re['d_keywords'];
					if( re['d_description'] ) form.description.value = re['d_description'];
					//alert( jQuery.parseJSON(window.CKE) );
					CKEDITOR.instances.ckeditor1.setData( re['d_description'] );
					if( re['d_weight'] ) form.weight.value = re['d_weight'];
					if( re['d_weight_class'] ) form.weight_class.value = re['d_weight_class'];
					if( re['d_length_class'] ) form.length_class.value = re['d_length_class'];
					if( re['d_length'] ) form.length.value = re['d_length'];
					if( re['d_width'] ) form.width.value = re['d_width'];
					if( re['d_height'] ) form.height.value = re['d_height'];
				}
				else
				{

				}
			}
		});
	}
	else
	{
		ss.innerHTML = '';
		$("#search_suggest").css('display','none');
	}
}


function bdatepicker()
{
	$('.date').each( function(){
		var id = $(this).prop('id');
		if(id)
		{
			//console.log('format: '+$('#'+id).data('format'));
			$('#'+id).datetimepicker({
				format: $('#'+id).data('format'),
				defaultDate: $('#'+id).data('time')
			});
		}
	});
}

function get_other_traffic_sources(start_date, end_date, target){
	let href = window.location.origin + '/admin/ajax/get_other_traffic_sources';
	let data = { start:start_date, end:end_date };
	$.ajax({
		type: "GET",
		url: href,
		data: data,
		dataType: 'json',
		timeout: 60000,
		success: function(re)
		{
			if( re && Array.isArray(re) ){
				let body = '';
				let total = re.reduce( (accumulator, currentValue) => accumulator + (currentValue.sessions*1), 0 );
				total = total?total:1;
				re.forEach( function (item){
					let p = (item.sessions/total)*100;
					p = p.toFixed(1);
					body += '<tr><th class="truncate-left">'+item.host+'</th><td>'+item.sessions+'</td><td>'+p+'%</td></tr>';
				});
				$('#'+target+' .modal-body table tbody').html(body);
			}
			else{
				msg = create_error('Unable to fetch data');
				let tr = '<tr><td colspan="3">'+msg+'</td>';
				$('#'+target+' .modal-body table tbody').html(tr);
			}
		},
		error: function (e){
			msg = create_error('Unable to fetch data', e);
			let tr = '<tr><td colspan="3">'+msg+'</td>';
			$('#'+target+' .modal-body table tbody').html(tr);
		}
	});
}

function create_error(msg,title=false){
	let div = '<div class="alert alert-warning">';
	if(title) div += '<h3>'+title+'</h3>';
	div += msg+'</div>';
	return div;
}


function get_category_fields(id)
{
	const site_url = $("#site_url").val();
	if(id && id > 0)
	{
		$.ajax({
			type: "GET",
			url: site_url+"/ajax/get_category_fields/"+id,
			dataType: 'json',
			timeout: 60000,
			success: function(response)
			{
				const form = document.querySelector('.article_form');
				if(form){
					for( let i=1; i<9; i++ ){
						const f = 'sc_f'+i;
						const v = 'sc_v'+i;
						const ff = form.querySelector("input[name='f"+i+"']")
						const fv = form.querySelector("input[name='v"+i+"']")
						if(!fv.value) {
							if (ff) ff.value = response[f] ? response[f] : '';
							if (fv) fv.setAttribute('placeholder', response[v] ? response[v] : '');
						}
					}
				}
			}
		});
	}
}

/**
 *  Jquery Load Event
 *
 */
jQuery(function($){


	bdatepicker();
	$( "a[rel=popover]" ).popover();

	$('#user-popover').popover();
	$('[data-toggle="tooltip"]').tooltip();

	/**
	 * Sets active and expands menu items based on id of body tag of current page
	 * e.g. <body id="body-index-page"> will result in the menu item with the id="navigation-index-page" having the
	 * class 'active' added, subsequently it will look for a child div with a class of collapse and add the class 'in'
	 * and set the height to auto
	 */
	var body_id = $('body').attr('id');
	if(body_id != null){
		var nav_element = $('#navigation-' + body_id.replace('body-',''));
		nav_element.addClass('active');
		if(nav_element.has('div.collapse')){
			var child_nav = nav_element.find('div.collapse');
			child_nav.addClass('in');
			child_nav.css('height: auto;');

		}

	}

	//hide the top-stats when the arrow is clicked
	var item = $('.top-stats');
	var target = $('#hide-top-stats');
	if(item.length > 0 && target.length > 0){
		target.on('click', function() {
			item.css('position', 'relative');
			item.animate({
				left: '-=1200',
			}, 1000, function() {
				// Animation complete.
				item.hide('slow');
			});
		});
	}

	//display the color-switcher and change theme (plus anything with comments of //used in theming logic )
	// position_color_switcher(true);
	// switcher_div.show();

	// switcher_control.on('click', toggle_color_switcher);

	/*$(window).resize(function() {
        switcher_div.hide();
    });

    $('.color-switcher-color').bind('click', set_theme_url);*/

	$(".theme_color").change( function(e){
		$("#theme_color").css( 'background-color', $(this).val() );
	});

	$(".background_image").change( function(e){
		var st = $('#site_theme').val();
		var base = $('#base_url').val();
		var url = 'url('+base+'site/views/'+st+'/images/patterns/'+$(this).val()+')';
		$("#background_image").css( 'background-image', url );
	});

	$("#product_instant").blur( function(e){
		setTimeout( 'clear_suggest()', 200 );
	});

	$("#product_instant").keyup( function(e){
		q = $("#product_instant").val();
		base_url = $("#base_url").val();
		x = "name="+q;
		var ss = document.getElementById('search_suggest');
		if(q.length > 2)
		{
			$.ajax({
				type: "GET",
				url: base_url+"admin/ajax/get_product_list",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(!re['error'])
					{
						n = re.length;

						ss.innerHTML = '';
						for(i=0;i<n;i++)
						{
							//Build our element string.  This is cleaner using the DOM, but
							//IE doesn't support dynamically added attributes.
							var img = '<span class="suggest_image"><img src="'+base_url+'images/products/100x75/'+re[i]['image']+'"></span>'
							var suggest = '<li onmouseover="javascript:suggestOver(this);" ';
							suggest += 'onmouseout="javascript:suggestOut(this);" ';
							suggest += 'onclick="javascript:setSearch(\''+re[i]['name']+'\', '+re[i]['id']+' );" ';
							suggest += 'class="suggest_link">'+img + re[i]['name'] + ' - '+re[i]['category_name']+'</li>';
							ss.innerHTML += suggest;
							$("#search_suggest").css('display','block');
						}
					}
					else
					{
						ss.innerHTML = '';
						$("#search_suggest").css('display','none');
					}
				}
			});
		}
		else
		{
			ss.innerHTML = '';
			$("#search_suggest").css('display','none');
		}
	});



	$(".approve_article, .approve_pr").click( function()
	{
		var id = $(this).val();
		var f = $(this).data('f');
		base_url = $("#base_url").val();
		x = "id="+id+'&f='+f;
		if(id>0)
		{
			$.ajax({
				type: "GET",
				url: base_url+"admin/ajax/approve_article",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re)
					{
						$('#disable'+id).removeClass('hidden');
						$('#approve'+id).addClass('hidden');
					}
					else
					{
						console.log('could not update blog');
					}
				}
			});
		}
		else
		{
			console.log('id missing');
		}
	});

	$(".disable_article, .disable_pr").click( function()
	{
		var id = $(this).val();
		var f = $(this).data('f');
		base_url = $("#base_url").val();
		x = "id="+id+'&f='+f;
		if(id>0)
		{
			$.ajax({
				type: "GET",
				url: base_url+"admin/ajax/disable_article",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re)
					{
						$('#approve'+id).removeClass('hidden');
						$('#disable'+id).addClass('hidden');
					}
					else
					{
						console.log('could not update blog');
					}
				}
			});
		}
		else
		{
			console.log('id missing');
		}
	});


	$(".official").change( function()
	{
		var id = $(this).val();
		var f = $(this).data('f');
		if( $(this).attr('checked') )
			var state = 1;
		else var state = 0;
		base_url = $("#base_url").val();
		x = "id="+id+'&f='+f+'&state='+state;
		if(id>0)
		{
			$.ajax({
				type: "GET",
				url: base_url+"admin/ajax/toggle_official_review",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re==1)
					{
						$('#off'+id).attr( 'checked', 'checked' );
						$('#off'+id).addClass('btn-warning')
					}
					else
					{
						$('#off'+id).attr( 'checked', false );
						$('#off'+id).removeClass('btn-warning')
					}
				}
			});
		}
		else
		{
			console.log('id missing');
		}
	});


	$(".update_price").click( function()
	{
		var id = $(this).data('id');
		var price = $('#tp_'+id).val();
		base_url = $("#base_url").val();
		x = "id="+id+'&price='+price;
		if(id>0)
		{
			$.ajax({
				type: "GET",
				url: base_url+"shopadm/music/update_price",
				data: x,
				dataType: 'json',
				timeout: 60000,
				beforeSend: function()
				{
					$('#tp_'+id).addClass('disabled');
					$("#update_tp"+id).attr('disabled',true);
				},
				success: function(re)
				{
					$('#tp_'+id).removeClass('disabled');
					$("#update_tp"+id).attr('disabled',false);
					if(re['error']==false)
					{
						$("#update_tp"+id).addClass('disabled');
					}
				}
			});
		}
		else
		{
			console.log('id missing');
		}
	});

	$('.track_price').change(function(){
		id = $(this).data('id');
		$('#update_tp'+id).removeClass('disabled');
	});


	$('.read').click( function(){
		var id = $(this).val();
		$('.article-content').addClass('hidden');
		$('.hide_article').addClass('hidden');
		$('.read').removeClass('hidden');
		$('#article'+id).removeClass('hidden');
		$(this).addClass('hidden');
		$('#hide'+id).removeClass('hidden');
	});

	$('.hide_article').click( function(){
		var id = $(this).val();
		$('.article-content').addClass('hidden');
		$('.hide_article').addClass('hidden');
		$('.read').removeClass('hidden');
	});


	$('#copy_from_db').click(function(){
		db = document.db_tags;
		files = document.file_tags;
		files.title.value = db.title.value;
		files.artist.value = db.artist.value;
		files.album.value = db.album.value;
		files.year.value = db.year.value;
		files.filesize.value = db.filesize.value;
		files.comment.value = db.comment.value;
		files.genre.value = db.genre.value;
		files.track_number.value = db.track_number.value;
		files.playtime_string.value = db.playtime_string.value;
	});


	$('#copy_from_file').click(function(){
		db = document.file_tags;
		files = document.db_tags;
		files.title.value = db.title.value;
		files.artist.value = db.artist.value;
		files.album.value = db.album.value;
		files.year.value = db.year.value;
		files.filesize.value = db.filesize.value;
		files.comment.value = db.comment.value;
		files.genre.value = db.genre.value;
		files.track_number.value = db.track_number.value;
		files.playtime_string.value = db.playtime_string.value;
	});

	$('#ana_other_traffic_sources').on('show.bs.modal', function (event) {
		let button = $(event.relatedTarget) // Button that triggered the modal
		let s = button.data('start') // Extract info from data-* attributes
		let e = button.data('end') // Extract info from data-* attributes
		get_other_traffic_sources(s,e,'ana_other_traffic_sources');
	});

	$('.change_per_page a').click(function (e){
		e.preventDefault();
		let a = $(e.target);
		let v = a.text();
		$('#change_per_page').val(v);
		$('#change_per_page_form').submit();
	});

	$('.change_page_sort a').click(function (e){
		e.preventDefault();
		let a = $(e.target);
		let v = a.data('id');
		console.log(v);
		$('#change_page_sort').val(v);
		$('#change_page_sort_form').submit();
	});

	let start_date = $('#ana_start').val();
	let end_date = $('#ana_end').val();
	start_date = start_date ? start_date : moment().startOf('day').subtract( 7, 'day' );
	end_date = end_date ? end_date : moment().endOf('day');

	$('.daterange').daterangepicker({
		timePicker: true,
		timePicker24Hour: true,
		timePickerSeconds: true,
		startDate: start_date,
		endDate: end_date,
		locale: {
			format: 'DD MMM YYYY, HH:mm:ss'
		},
		alwaysShowCalendars: true,
		ranges: {
			'Today': [moment().startOf('day'), moment()],
			'Yesterday': [moment().startOf('day').subtract(1, 'days'), moment().endOf('day').subtract(1, 'days')],
			'Last 7 Days': [ moment().startOf('day').subtract(6, 'days'), moment().endOf('day') ],
			'Last 14 Days': [ moment().startOf('day').subtract(13, 'days'), moment().endOf('day') ],
			'Last 30 Days': [ moment().startOf('day').subtract(29, 'days'), moment().endOf('day') ],
			'This Month': [moment().startOf('month'), moment()],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
	});
	$('.daterange').on('change', function(ev) {
		$('#dateform').submit();
	});

});
