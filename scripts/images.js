function init_uploader() {
	var base_url = $('#base_url').val();
	var resize_image = $('input[name="resize_image"]:checked').is(':checked');
	var rs = resize_image?1:0;
	var csrf = $('.article_form input:hidden:first');
	var csrf_name = $(csrf).attr('name');
	var csrf_value = $(csrf).val();
	var action_string = $('#action_string').val()
	var p = {
		test: 'test-data',
		resize_image: rs,
		filename: 'uploadfile'
	}
	p[csrf_name] = csrf_value;
	console.log(p);
	var btnUpload = $('#upload');
	var status = $('#status');
	new AjaxUpload(btnUpload, {
		debug: true,
		action: action_string,
		name: 'uploadfile',
		data: p,
		onSubmit: function (file, ext) {
			if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
				// extension is not allowed
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Uploading...Please wait until upload is finished');
		},
		onComplete: function (file, response) {
			//On completion clear the status
			console.log(response)
			status.text('');
			//Add uploaded file to list
			if (response['error_msg'] === "success") {

				if (response['form'] === "profile") {
					$('#photo').val(response['photo']);
					var phot = document.getElementById("profile_picture");
					phot.innerHTML = '<span><img src="' + base_url + 'images/users/250px/' + response['photo'] + '"></span>';

					if (response['auto'])
						$('#change_profile_pic').submit();
				} else if (response['form'] === "blog_article") {
					im = document.blog_post.image;
					if (im) delete_img(im.value);
					//$('#image_name').val(response['image']);
					curl = $('#current_url').val();
					burl = $('#base_url').val();
					li = '<li id="' + response['image'] + '"><div class="thumbnail"><img src="' + burl + 'images/articles/sm/' + response['image'] + '"></div><p><button class="btn btn-sm btn-mini btn-default product_img_fs" type="button" value="' + response['image'] + '" at_id="0" onclick="delete_piffs(this)">delete</button></p> <input type="hidden" name="image" value="' + response['image'] + '"> </li>';
					$('#upload_files').prepend(li);
					//document.getElementById('image_name').value = response['image'];
				} else if (response['form'] === "site_logo") {
					im = document.site_theme.image;
					if (im) delete_img(im.value);
					//$('#image_name').val(response['image']);
					curl = $('#current_url').val();
					burl = $('#base_url').val();
					folder = response['folder'];
					li = '<li id="' + response['image'] + '"><div class="thumbnail"><img src="' + burl + folder + response['image'] + '"></div><p><button class="btn btn-sm btn-mini btn-default product_img_fs" type="button" value="' + response['image'] + '" onclick="delete_logo(this)">delete</button></p> <input type="hidden" name="logo" value="' + response['image'] + '"> </li>';
					$('#upload_files').prepend(li);
					//document.getElementById('image_name').value = response['image'];
				} else if (response['form'] === "slider") {
					im = document.slider.image;
					if (im) delete_img(im.value);
					//$('#image_name').val(response['image']);
					curl = $('#current_url').val();
					burl = $('#base_url').val();
					folder = 'images/slider/';
					const li = '<li id="slider_' + response['image'] + '"><div class="thumbnail"><img src="' + burl + folder + response['image'] + '"></div><p><button class="btn btn-sm btn-mini btn-default product_img_fs" type="button" value="' + response['image'] + '" onclick="delete_slider(this)">delete</button></p> <input type="hidden" name="image" value="' + response['image'] + '"> </li>';
					$('#upload_files').prepend(li);
					//document.getElementById('image_name').value = response['image'];
				}
			} else {
				//alert( response['error'] );
				$('<p></p>').prependTo('#status').text(response['error']).addClass('alert alert-warning alert-error');
			}
		}
	});
}

$(function()
{
	init_uploader();

	function update_list_images(id, image, burl, curl){
		var x = 'id='+id+'&image='+image;

		if(id>0){
			$.ajax({
				type: "GET",
				url: burl+"/admin/ajax/update_list_image",
				data: x,
				dataType: 'json',
				success: function(re){
					if(re['error']){
						$('.success').text(re['error_msg']);
					}
				}
			});}
		else{
			alert('unknown error');
		}
	}



});


function delete_piffs( obj )
{
	var btn = $(obj);
	var img = btn.val();
	var id = btn.attr('at_id');
	btn.text( 'deleting...' );
	var base_url = $('#base_url').val();
	if( img )
	{
		var x = 'file='+img+'&fs=1&at_id='+id;
		$.ajax(
			{
				type: "GET",
				url: base_url+"admin/ajax/delete_article_image",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re['error'])
					{
						btn.text( 'image deleted' );
						$('#image_name').val('');
						remove_item( document.getElementById(img) );
					}
					else
					{
						btn.text( 'could not delete image' );
					}
				},
				error: function(a,b,re)
				{
					if( b=='timeout' )
						btn.text('The request timed out');
					else
					{
						btn.text('an error occurred');
					}

					//setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				}
			});
	}
}

function delete_logo( obj ){
	var btn = $(obj);
	var img = btn.val();
	var folder = btn.data('folder');
	btn.text( 'deleting...' );
	var base_url = $('#base_url').val();
	if( img )
	{
		remove_item( document.getElementById('site_logo_'+img) );
		return;
		var x = 'file='+img+'&fs=1&folder='+folder;
		$.ajax(
			{
				type: "GET",
				url: base_url+"admin/ajax/delete_logo",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re['error'])
					{
						btn.text( 'image deleted' );
						$('#image_name').val('');
						remove_item( document.getElementById('site_logo_'+img) );
					}
					else
					{
						btn.text( 'could not delete image' );
					}
				},
				error: function(a,b,re)
				{
					if( b=='timeout' )
						btn.text('The request timed out');
					else
					{
						btn.text('an error occurred');
					}

					//setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				}
			});
	}
}

function delete_slider( obj ){
	var btn = $(obj);
	var img = btn.val();
	btn.text( 'deleting...' );
	var base_url = $('#base_url').val();
	if( img )
	{
		var x = 'file='+img+'&fs=1';
		$.ajax(
			{
				type: "GET",
				url: base_url+"admin/ajax/delete_slider",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re['error'])
					{
						btn.text( 'image deleted' );
						$('#image_name').val('');
						remove_item( document.getElementById('slider_'+img) );
					}
					else
					{
						btn.text( 'could not delete image' );
					}
				},
				error: function(a,b,re)
				{
					if( b=='timeout' )
						btn.text('The request timed out');
					else
					{
						btn.text('an error occurred');
					}

					//setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				}
			});
	}
}

function remove_item(i)
{
	j=i.parentNode;
	j.removeChild(i);
}



function delete_img( img )
{
	var base_url = $('#base_url').val();
	if( img )
	{
		var btn = $( '#'+img+' button' );
		btn.text( 'deleting...' );
		var x = 'file='+img+'&fs=1';
		$.ajax(
			{
				type: "GET",
				url: base_url+"admin/ajax/delete_article_image",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re['error'])
					{
						btn.text( 'image deleted' );
						$('#image_name').val('');
						remove_item( document.getElementById(img) );
					}
					else
					{
						btn.text( 'could not delete image' );
					}
				},
				error: function(a,b,re)
				{
					if( b=='timeout' )
						btn.text('The request timed out');
					else
					{
						btn.text('an error occurred');
					}

					//setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				}
			});
	}
	else $('<p></p>').prependTo('#status').text(img+' not found').addClass('alert alert-warning alert-error');
}
