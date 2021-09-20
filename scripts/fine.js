function createUploader() {
	var action_string = $('#action_string').val();
	$('#product_id').val();
	$messages = $('#messages');
	id = 'uploader';
	var csrf = $('.gallery_form input:hidden:first');
	var csrf_name = $(csrf).attr('name');
	var csrf_value = $(csrf).val();
	var uploader = new qq.FineUploaderBasic({
		button: document.getElementById('file-uploader'),
		debug: false,
		request: {
			inputName: 'uploadfile',
			endpoint: action_string,
			params: {[csrf_name] : csrf_value}
		},
		validation: {
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
			sizeLimit: 2000 * 1024 // 500 kB = 500 * 1024 bytes
		},
		callbacks: {
			onSubmit: function (id, name) {
				createProgressBar(id, name);
			},
			onProgress: function (id, name, done, total) {
				updateProgress(id, done, total)
			},
			onComplete: function (id, fileName, response) {
				processImage(id, fileName, response);
			},
			onError: function (id, name, message, xhr) {
				handleError(id, name, message, xhr)
			}
		},
		multiple: true
	});
}

window.onload = createUploader;

function createProgressBar(id, name) {
	const container = document.querySelector('#upload-progress')
	const row = document.createElement('li')
	row.id = 'img-' + id
	row.innerHTML = '<div class="mb-2"><div class="col-sm-6 upload-name ms-0 ps-0">' + name + ' [<span>0% uploaded</span>]</div><div class="col-sm-6 me-0 pe-0"><div class="progress mb-1"><div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"><span class="sr-only">0% Complete</span></div></div></div></div>';
	container.appendChild(row)
}

function updateProgress(id, done, total) {
	const pb = document.querySelector('#img-' + id + ' .progress-bar')
	const text = document.querySelector('#img-' + id + ' .upload-name span')
	if (pb && total > 0) {
		const p = ((done / total) * 100).toFixed(0);
		pb.setAttribute('aria-valuenow', p);
		pb.style = "width: " + p + "%";
		text.innerText = p + '% uploaded';
	}
}

function handleError(id, name, message, xhr){
	const row = document.querySelector('#img-' + id);
	const div = document.createElement('div');
	div.className = 'col-sm-12';
	div.innerHTML = '<div class="alert alert-danger py-0 px-2 mb-3">' + message + '</div>';
	if (row) row.appendChild(div); else window.alert(message)
}

function remove_item(i) {
	j = i.parentNode;
	j.removeChild(i);
}

function processImage(id, fileName, response) {
	const pb = document.querySelector('#img-' + id + ' .progress-bar')
	if (response.success) {
		if (pb) {
			pb.classList.remove('active', 'progress-bar-striped', 'progress-bar-info');
			pb.classList.add('progress-bar-success');
		}

		if (response.form === "profile") {
			$('#photo').val(response.photo);
			const phot = document.getElementById("profile_picture");
			phot.innerHTML = '<span><img src="/images/users/thumbs/' + response.photo + '"></span>';

			if (response.auto) $('#change_profile_pic').submit();
		} else if (response.form === "blog_article") {
			$('#images').val(response.image);
			curl = $('#current_url').val();
			burl = $('#base_url').val();
			//id = $('#listing_id').val();
			li = '<li id="' + response.image + '"><div class="thumbnail"><img src="/images/articles/sm/' + response.image + '"></div><p><button class="btn btn-xs btn-default product_img_fs" type="button" value="' + response.image + '" onclick="delete_piffs(this)">delete</button></p></li>';
			$('#upload_files').prepend(li);

			op = '<option value="' + response.image + '" selected>' + response.image + '</option>';
			$('#image_names').prepend(op);
			//update_list_images(id,response['image'],burl,curl);
		}
	} else {
		$('#messages').text(response.error);
		if (pb) {
			pb.classList.remove('active', 'progress-bar-striped', 'progress-bar-info');
			pb.classList.add('progress-bar-danger');
		}
	}
}

function delete_piffs(obj) {
	var btn = $(obj);
	var img = btn.val();
	btn.text('deleting...');
	var base_url = $('#base_url').val();
	if (img) {
		var x = 'file=' + img + '&fs=1';
		$.ajax({
			type: "GET",
			url: base_url + "admin/ajax/delete_article_image",
			data: x,
			dataType: 'json',
			timeout: 60000,
			success: function (re) {
				if (re['error']) {
					btn.text('image deleted');
					$('#image_names option:contains("' + img + '")').removeAttr('selected');
					$('#image_names option:contains("' + img + '")').attr('disabled', 'disabled');
					remove_item(document.getElementById(img));
				} else {
					btn.text('could not delete image');
				}
			},
			error: function (a, b, re) {
				if (b === 'timeout') btn.text('The request timed out'); else {
					btn.text('an error occurred');
				}

				//setTimeout( 'remove_by_id("'+aid+'")', 5000 );
			}
		});
	}
}

$(function () {

	$('.delete_prod_img').click(function () {
		var id = $(this).attr('img_id');
		var file = $(this).attr('file');

		var x = 'id=' + id + '&file=' + file;
		var base_url = $('#base_url').val();

		if (id > 0) {
			$.ajax({
				type: "GET",
				url: base_url + "admin/ajax/delete_product_image",
				data: x,
				dataType: 'json',
				timeout: 60000,
				beforeSend: function () {
					aid = new Date().getTime();
					var span = '<td class="loading" id="' + aid + '">deleting, please wait...</td>';
					$('#ajax_feedback tr').append(span);
				},
				success: function (re) {
					if (re['error'] != 'fail') {
						$('#' + aid).removeClass('.loading');
						$('#' + aid).addClass('ajax_success');
						$('#' + aid).text(re['error_msg']);
						$('#li_' + id).remove();
						$("#image_name option[value='" + file + "']").remove();
					} else {
						$('#' + aid).removeClass('.loading');
						$('#' + aid).addClass('ajax_error');
						$('#' + aid).text(re['error_msg']);
					}
					setTimeout('remove_by_id("' + aid + '")', 5000);
				},
				error: function (a, b, re) {
					$('#' + aid).removeClass('.loading');
					$('#' + aid).addClass('ajax_error');
					if (b == 'timeout') $('#' + aid).text('The request timed out'); else {
						$('#' + aid).text('an error occurred');
						open_error_box(re);
					}

					setTimeout('remove_by_id("' + aid + '")', 5000);
				}
			});
		}
	});

	$('.make_prod_img_main').click(function () {
		var pd = $(this).attr('prod_id');
		var id = $(this).attr('img_id');
		var file = $(this).attr('file');

		var x = 'id=' + pd + '&file=' + file;
		var base_url = $('#base_url').val();

		if (pd > 0) {
			$.ajax({
				type: "GET",
				url: base_url + "admin/ajax/make_prod_img_main",
				data: x,
				dataType: 'json',
				timeout: 60000,
				beforeSend: function () {
					aid = new Date().getTime();
					var span = '<td class="loading" id="' + aid + '">working, please wait...</td>';
					$('#ajax_feedback tr').append(span);
				},
				success: function (re) {
					if (re['error'] != 'fail') {
						$('#' + aid).removeClass('.loading');
						$('#' + aid).addClass('ajax_success');
						$('#' + aid).text(re['error_msg']);
						$('#main_img_make').text('make main');
						$('#main_img_make').removeAttr('id');
						$('#main_img_delete').text('delete');
						$('#main_img_delete').removeAttr('id');

						$('#li_' + id + ' .make_prod_img_main').text('');
						$('#li_' + id + ' .make_prod_img_main').attr('id', 'main_img_make')
						$('#li_' + id + ' .delete_prod_img').text('');
						$('#li_' + id + ' .delete_prod_img').attr('id', 'main_img_delete')
						$('#main_image').val(file);
					} else {
						$('#' + aid).removeClass('.loading');
						$('#' + aid).addClass('ajax_error');
						$('#' + aid).text(re['error_msg']);
					}
					setTimeout('remove_by_id("' + aid + '")', 5000);
				},
				error: function (a, b, re) {
					$('#' + aid).removeClass('.loading');
					$('#' + aid).addClass('ajax_error');
					if (b == 'timeout') $('#' + aid).text('The request timed out'); else {
						$('#' + aid).text('an error occurred');
						open_error_box(re);
					}

					setTimeout('remove_by_id("' + aid + '")', 5000);
				}
			});
		}
	});

});
