jQuery(function($){

$('.gallery_form').on('click', '.img_privacy', function(e)
{
	var img_id = $(this).data('img_id');
	var privacy = $(this).data('privacy');
	var file = $(this).data('file');
	var id = $(this).data('article_id');
	var td = $('#'+img_id+' a');
	base_url = $("#base_url").val();
	x = "id="+id+'&privacy='+privacy+'&img_id='+img_id+'&file='+file;
	if(id>0)
	{
		$.ajax({
			type: "GET",
			url: base_url+"admin/ajax/image_privacy",
			data: x,
			dataType: 'json',
			timeout: 60000,
			beforeSend: function()
			{
				td.addClass('disabled');
				td.html('please wait');
			},
			success: function(re)
			{
				td.removeClass('disabled');
				td.html(re['text']);
				td.data('privacy',re['gi_private']);
			}
		});
	}
	else
	{
		console.log('id missing');
	}
});


$('.gallery_form').on('click', '.delete_img', function(e)
{
	var base_url = $('#base_url').val();
	var file = $(this).data('file');
	var img_id = $(this).data('img_id');
	var li = document.getElementById('li_'+img_id);
	console.log(img_id);
	if(img_id>0)
	{
		var x = 'file='+file+'&img_id='+img_id;
		$.ajax(
		{
			type: "GET",
			url: base_url+"admin/ajax/delete_gallery_img",
			data: x,
			dataType: 'json',
			timeout: 60000,
			success: function(re)
			{
				if(re['error']===false)
				{
					var ul = li.parentNode;
					ul.removeChild(li);
				}
				else
				{
					$('#status').html('<div class="alert alert-warning">'+re['error_msg']+'</div>');
				}
			},
			error: function(a,b,re)
			{
				if( b=='timeout' )
					$('#status').html('<div class="alert alert-warning">The request timed out</div>');
				else
				{
					$('#status').html('<div class="alert alert-warning">'+b+'</div>');
				}
			}
		});
	}
});


















// end jquery
});
