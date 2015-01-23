$(document).ready(function(){
	window.setTimeout(alert_hide(),3000);

	$('.delete-link').click(function(){
		return confirm('Are you sure want to delete this data?');
	});

	$('[data-toggle="tooltip"]').tooltip();

	/***RENT ADD***/
	function get_date_return(start_date,add){
		var date_return = '';
		$.ajax({
			type: 'POST',
			url: base_url + 'rent/get_date_return',
			dataType: 'json',
			data: {add:add, start_date:start_date},
			success:function(data){
				var newDate = data.newDate;
				var d = newDate.substring(8,10);
				var m = newDate.substring(5,7);
				var y = newDate.substring(0,4);

				date_return = m + '/' + d +'/' + y;
				$('#rent_date_return').val(date_return);
			}
		})
	}

	$('#rent_date').datetimepicker({
		pickTime: false,
		minDate: today
	});

	$('#rent_date').change(function(){
		get_date_return($('#rent_date').val(),$('#rent_days').val());
	});

	$('#rent_date').keyup(function(){
		get_date_return($('#rent_date').val(),$('#rent_days').val());
	});

	$('#rent_upload_internal').show();

	$('#rent_days').keyup(function(){
		//hitung total bayar
		var harga_per_hari = parseInt($('#rent_property_price').val());
		var lama_sewa = parseInt($('#rent_days').val());
		var total = harga_per_hari * lama_sewa;

		$('#rent_price').val(total);

		get_date_return($('#rent_date').val(),lama_sewa);
	})

	$('#rent_type').change(function(){
		if($(this).val()=='1'){
			$('#rent_upload_internal').show();

			$('#rent_price').val(0);
		}else{
			$('#rent_upload_internal').hide();

			//hitung total bayar
			var harga_per_hari = parseInt($('#rent_property_price').val());
			var lama_sewa = parseInt($('#rent_days').val());
			var total = harga_per_hari * lama_sewa;

			$('#rent_price').val(total);
		}
	});
	/**************/


	/*** RENT REPORT ***/
	$('#start_date').datetimepicker({
		pickTime: false
	});

	$('#end_date').datetimepicker({
		pickTime: false
	});

	$('#submit_report_all').click(function(){
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var start_day = start_date.substring(3,5);
		var start_month = start_date.substring(0,2);
		var start_year = start_date.substring(6,10);
		var end_day = end_date.substring(3,5);
		var end_month = end_date.substring(0,2);
		var end_year = end_date.substring(6,10);

		var start = start_year + '-' + start_month + '-' + start_day;
		var end = end_year + '-' + end_month + '-' + end_day;

		window.open(base_url+'report/generate_all/'+start+'/'+end,'_blank');
	});


	$('#submit_report_by_user').click(function(){
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var start_day = start_date.substring(3,5);
		var start_month = start_date.substring(0,2);
		var start_year = start_date.substring(6,10);
		var end_day = end_date.substring(3,5);
		var end_month = end_date.substring(0,2);
		var end_year = end_date.substring(6,10);

		var start = start_year + '-' + start_month + '-' + start_day;
		var end = end_year + '-' + end_month + '-' + end_day;

		var user_id = $('#user_id').val();

		window.open(base_url+'report/generate_by_user/'+start+'/'+end+'/'+user_id,'_blank');
	});


	$('#submit_report_type').click(function(){
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var start_day = start_date.substring(3,5);
		var start_month = start_date.substring(0,2);
		var start_year = start_date.substring(6,10);
		var end_day = end_date.substring(3,5);
		var end_month = end_date.substring(0,2);
		var end_year = end_date.substring(6,10);

		var start = start_year + '-' + start_month + '-' + start_day;
		var end = end_year + '-' + end_month + '-' + end_day;

		var rent_type = $('#rent_type').val();

		window.open(base_url+'report/generate_type/'+start+'/'+end+'/'+rent_type,'_blank');
	});


	$('#submit_report_status').click(function(){
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var start_day = start_date.substring(3,5);
		var start_month = start_date.substring(0,2);
		var start_year = start_date.substring(6,10);
		var end_day = end_date.substring(3,5);
		var end_month = end_date.substring(0,2);
		var end_year = end_date.substring(6,10);

		var start = start_year + '-' + start_month + '-' + start_day;
		var end = end_year + '-' + end_month + '-' + end_day;

		var rent_status = $('#rent_status').val();

		window.open(base_url+'report/generate_by_status/'+start+'/'+end+'/'+rent_status,'_blank');
	});

	$('#submit_report_income').click(function(){
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var start_day = start_date.substring(3,5);
		var start_month = start_date.substring(0,2);
		var start_year = start_date.substring(6,10);
		var end_day = end_date.substring(3,5);
		var end_month = end_date.substring(0,2);
		var end_year = end_date.substring(6,10);

		var start = start_year + '-' + start_month + '-' + start_day;
		var end = end_year + '-' + end_month + '-' + end_day;

		window.open(base_url+'report/generate_income/'+start+'/'+end,'_blank');
	});
	/*******************/
});

function alert_hide(){
	if($('.alert').length > 0){
		$('.alert').fadeOut(10000);
	}
}