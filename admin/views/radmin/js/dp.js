jQuery(function($){
			// format date inputs
			
	$('#users').popover({
			placement: 'right',
			trigger: 'hover',
			content: function()
			{
				var users = $('#users').val();
				var price = $('#price').val();
				if( users )
				{
					var entry = price/users;
					var bid = entry / 5;
					var data=  'Entry Fee: $'+entry.toFixed(2)+', Bid Price: $'+bid.toFixed(2);
					return data;
				}
				else return 'Enter number of users expected to participate in auction';
			}
		});
			
			$('#start-date').datetimepicker(
			{
				format:'d-m-Y H:i',
				step:30
			});
			$('#end-date').datetimepicker(
			{
				format:'d-m-Y H:i',
				step:30
			});
			
			//$('#dp1').datepicker();
			//$('#dp2').datepicker();
	});
