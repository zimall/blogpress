
/*-----------------------------------------------------------
*	PERMISSIONS MANAGEMENT
*-----------------------------------------------------------*/
	
	$("#add_one").click( function(){
		var x = $("#av_permissions").val();
		if(x==null) alert('select an item from the available permissions list');
		else{
		var y = $("#av_permissions option:selected").text();
		var perms = document.getElementById('permissions').options;
		pass = true;
		for(i=0; i<perms.length; i++){
			if(x==perms[i].value)
				pass=false;
		}
		if(pass){
			var div = '<option value="'+x+'">'+y+'</option>';
			$("#permissions").prepend(div);
			$("#av_permissions option:selected").remove();
			$("#permissions option").each(function(i) {
				$(this).attr('selected', 'selected');
			});
		}
		else{ alert('selected item already exists'); $("#av_permissions option:selected").remove(); }
		}
	});
	
	
	$("#av_permissions").dblclick( function(){
		var x = $("#av_permissions").val();
		var y = $("#av_permissions option:selected").text();
		var perms = document.getElementById('permissions').options;
		pass = true;
		for(i=0; i<perms.length; i++){
			if(x==perms[i].value)
				pass=false;
		}
		if(pass){
			var div = '<option value="'+x+'" selected>'+y+'</option>';
			$("#permissions").prepend(div);
			$("#av_permissions option:selected").remove();
			$("#permissions option").each(function(i) {
				$(this).attr('selected', 'selected');
			});
		}
		else{ alert('selected item already exists'); $("#av_permissions option:selected").remove(); }
	});
	
	
	$("#remove_one").click( function(){
		var x = $("#permissions").val();
		if(x==null) alert('select an item from the permissions list');
		else{
		var y = $("#permissions option:selected").text();
		var perms = document.getElementById('av_permissions').options;
		pass = true;
		for(i=0; i<perms.length; i++){
			if(x==perms[i].value)
				pass=false;
		}
		if(pass){
			var div = '<option value="'+x+'">'+y+'</option>';
			$("#av_permissions").append(div);
			$("#permissions option:selected").remove();
		}
		else alert('item cannot be removed');
		}
	});
	
	$("#permissions").dblclick( function(){
		var x = $("#permissions").val();
		if(x==null) alert('select an item from the permissions list');
		else{
		var y = $("#permissions option:selected").text();
		var perms = document.getElementById('av_permissions').options;
		pass = true;
		for(i=0; i<perms.length; i++){
			if(x==perms[i].value)
				pass=false;
		}
		if(pass){
			var div = '<option value="'+x+'">'+y+'</option>';
			$("#av_permissions").append(div);
			$("#permissions option:selected").remove();
		}
		else alert('item cannot be removed');
		}
	});
	
	$("#remove_all").click( function(){
		$("#permissions option").each(function(i) {
				var div = '<option value="'+$(this).val()+'">'+$(this).text()+'</option>';
				$("#av_permissions").append(div);
			});
		
		$("#permissions option").remove();
	});
	
/*	
	$("#permissions").blur( function(){	
		$("#permissions option").each(function(i) {
			$(this).attr('selected', 'selected');
		});
	
	});
*/
	$('#select_all_options').click( function(){ 
		$("#permissions option").each(function(i) {
			$(this).attr('selected', 'selected');
		});
		//$('#now_submit').attr('type','submit');
		$('#now_submit').click();
	});

	$('#add_selected_options').click( function(){
		$("#av_options option").each(function(i) {
			$(this).attr('selected').val();
		});
	});

	$('#av_options').change( function()
	{
		var v = $(this).val();
		if(v==0) return;
		var data = JSON.parse(v);
		var id = 'attr_'+data['id'];
		var a = '<a class="close" data-dismiss="alert">&times;</a>';
		var b = '<a class="close" onclick="remove_option(this)" name="'+data['name']+'">&times;</a>';
		
		if(data['input_type']=='text')
		{
			var label = '<label class="control-label">'+data['name']+'</label>';
			var input = '<div class="control-group" id="'+id+'">'+label+b+'<div class="controls"><input class="span8" type="text" name="attr['+data['id']+']" value=""></div></div>';
		}
		else
		{
			var label = '<label class="control-label">'+data['name']+'</label>';
			var input = '<div class="control-group" id="'+id+'">'+label+b+'<div class="controls"><textarea class="span8" rows="3" name="attr['+data['id']+']"></textarea></div></div>';
		}
		
		$('#selected_options').prepend(input);
		$('#av_options option:selected').attr('disabled', 'disabled');
		$("#av_options option[value='0']").attr('selected',true);


	});

function remove_option(i){
//	if(confirm("This field will be removed!")){
		j=i.parentNode;
		j.parentNode.removeChild(j);
		$('#av_options option:contains("'+i.name+'")').removeAttr('disabled');
//	}
}
