function createUploader()
{
	var action_string = $('#action_string').val();
	var pid = $('#product_id').val();
	$messages = $('#messages');
	id = 'uploader';
	var uploader = new qq.FineUploader(
	{
		element: document.getElementById('file-uploader'),
		request:
		{
			inputName: 'uploadfile',
			endpoint: action_string
		},
		validation:
		{
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
			sizeLimit: 2000*1024 // 500 kB = 500 * 1024 bytes
		},
		callbacks:
		{
			onComplete: function(id, fileName, response)
			{
				if ( response.success )
				{
					if(response['form']==="profile")
					{
						$('#photo').val(response['photo']);
						var phot = document.getElementById("profile_picture");
						phot.innerHTML = '<span><img src="'+base_url+'images/users/thumbs/'+response['photo']+'"></span>';
						
						if( response['auto'] )
							$('#change_profile_pic').submit();
					}
					else if(response['form']==="blog_article")
					{
						$('#images').val(response['image']);
						curl = $('#current_url').val();
						burl = $('#base_url').val();
						//id = $('#listing_id').val();
						li = '<li id="'+response['image']+'"><div class="thumbnail"><img src="'+burl+'images/articles/250x180/'+response['image']+'"></div><p><button class="btn btn-xs btn-default product_img_fs" type="button" value="'+response['image']+'" onclick="delete_piffs(this)">delete</button></p></li>';
						$('#upload_files').prepend(li);
						
						op = '<option value="'+response['image']+'" selected>'+response['image']+'</option>';
						$('#image_names').prepend(op);
						//update_list_images(id,response['image'],burl,curl);
					}
				}
				else
				{
					$('#messages').text(response['error']);
				}
			}
		},
		multiple: true
	});
}
window.onload = createUploader;

function delete_piffs( obj ){
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
				url: base_url+"admin/ajax/delete_article_image",
				data: x,
				dataType: 'json',
				timeout: 60000,
				success: function(re)
				{
					if(re['error'])
					{
						btn.text( 'image deleted' );
						$('#image_names option:contains("'+img+'")').removeAttr('selected');
						$('#image_names option:contains("'+img+'")').attr('disabled', 'disabled');
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

	function remove_item(i)
	{
		j=i.parentNode;
		j.removeChild(i);
	}


$( function(){


	


	$('.delete_prod_img').click( function(){ 
		var id = $(this).attr('img_id');
		var file = $(this).attr('file');
		
		var x = 'id='+id+'&file='+file;
		var base_url = $('#base_url').val();
		
		if(id>0){
			$.ajax({
				type: "GET",
				url: base_url+"admin/ajax/delete_product_image",
				data: x,
				dataType: 'json',
				timeout: 60000,
				beforeSend: function()
				{
					aid = new Date().getTime();
					var span = '<td class="loading" id="'+aid+'">deleting, please wait...</td>';
					$('#ajax_feedback tr').append(span);
				},
				success: function(re)
				{
					if(re['error']!='fail'){
						$('#'+aid).removeClass('.loading');
						$('#'+aid).addClass('ajax_success');
						$('#'+aid).text(re['error_msg']);
						$('#li_'+id).remove();
						$("#image_name option[value='"+file+"']").remove();
					}
					else
					{
						$('#'+aid).removeClass('.loading');
						$('#'+aid).addClass('ajax_error');
						$('#'+aid).text(re['error_msg']);
					}
					setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				},
				error: function(a,b,re)
				{
					$('#'+aid).removeClass('.loading');
					$('#'+aid).addClass('ajax_error');
					if( b=='timeout' )
						$('#'+aid).text('The request timed out');
					else
					{
						$('#'+aid).text('an error occurred');
						open_error_box(re);
					}
					 
					setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				}
			});
		}
	});
	
	$('.make_prod_img_main').click( function(){ 
		var pd = $(this).attr('prod_id');
		var id = $(this).attr('img_id');
		var file = $(this).attr('file');
		
		var x = 'id='+pd+'&file='+file;
		var base_url = $('#base_url').val();
		
		if(pd>0)
		{
			$.ajax({
				type: "GET",
				url: base_url+"admin/ajax/make_prod_img_main",
				data: x,
				dataType: 'json',
				timeout: 60000,
				beforeSend: function()
				{
					aid = new Date().getTime();
					var span = '<td class="loading" id="'+aid+'">working, please wait...</td>';
					$('#ajax_feedback tr').append(span);
				},
				success: function(re)
				{
					if(re['error']!='fail')
					{
						$('#'+aid).removeClass('.loading');
						$('#'+aid).addClass('ajax_success');
						$('#'+aid).text(re['error_msg']);
						$('#main_img_make').text('make main');
						$('#main_img_make').removeAttr('id');
						$('#main_img_delete').text('delete');
						$('#main_img_delete').removeAttr('id');
						
						$('#li_'+id+' .make_prod_img_main').text('');
						$('#li_'+id+' .make_prod_img_main').attr('id', 'main_img_make')
						$('#li_'+id+' .delete_prod_img').text('');
						$('#li_'+id+' .delete_prod_img').attr('id', 'main_img_delete')
						$('#main_image').val(file);
					}
					else
					{
						$('#'+aid).removeClass('.loading');
						$('#'+aid).addClass('ajax_error');
						$('#'+aid).text(re['error_msg']);
					}
					setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				},
				error: function(a,b,re)
				{
					$('#'+aid).removeClass('.loading');
					$('#'+aid).addClass('ajax_error');
					if( b=='timeout' )
						$('#'+aid).text('The request timed out');
					else
					{
						$('#'+aid).text('an error occurred');
						open_error_box(re);
					}
					 
					setTimeout( 'remove_by_id("'+aid+'")', 5000 );
				}
			});
		}
	});
	
});
