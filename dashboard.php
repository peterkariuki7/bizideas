<?php

//dashboard.php



include('class/Appointment.php');

$object = new Appointment;

include('header.php');

?>
<div class="col-md-7 text-right">
    <a href="ideas.php
" class="btn btn-primary btn-bg">View Business Ideas-></a>
</div>


<div class="container-fluid">
	<?php
	include('navbar.php');
	?>
	<br />
	<div class="card">
		<div class="card-header"><h4>Available Suppliers and Experts</h4></div>
			<div class="card-body">
				<div class="table-responsive">
		      		<table class="table table-striped table-bordered" id="appointment_list_table">
		      			<thead>
			      			<tr>
			      				<th>Name</th>
			      				<th>Business Name</th>
			      				<th>Speciality</th>
			      				<th>Appointment Date</th>
			      				<th>Appointment Day</th>
			      				<th>Available Time</th>
			      				<th>Action</th>
			      			</tr>
			      		</thead>
			      		<tbody></tbody>
			      	</table>
			    </div>
			</div>
		</div>
	</div>

</div>

<?php

include('footer.php');

?>

<div id="appointmentModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="appointment_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Make Appointment</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div id="appointment_detail"></div>
                    <div class="form-group">
                    	<label><b>Reasone for Appointment</b></label>
                    	<textarea name="reason_for_appointment" id="reason_for_appointment" class="form-control" required rows="5"></textarea>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_supplier_id" id="hidden_supplier_id" />
          			<input type="hidden" name="hidden_supplier_schedule_id" id="hidden_supplier_schedule_id" />
          			<input type="hidden" name="action" id="action" value="book_appointment" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Book" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>


<script>

$(document).ready(function(){

	var dataTable = $('#appointment_list_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"action.php",
			type:"POST",
			data:{action:'fetch_schedule'}
		},
		"columnDefs":[
			{
                "targets":[6],				
				"orderable":false,
			},
		],
	});

	$(document).on('click', '.get_appointment', function(){

		var supplier_schedule_id = $(this).data('supplier_schedule_id');
		var supplier_id = $(this).data('supplier_id');

		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:'make_appointment', supplier_schedule_id:supplier_schedule_id},
			success:function(data)
			{
				$('#appointmentModal').modal('show');
				$('#hidden_supplier_id').val(supplier_id);
				$('#hidden_supplier_schedule_id').val(supplier_schedule_id);
				$('#appointment_detail').html(data);
			}
		});

	});

	$('#appointment_form').parsley();

	$('#appointment_form').on('submit', function(event){

		event.preventDefault();

		if($('#appointment_form').parsley().isValid())
		{

			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					$('#submit_button').val('Book');
					if(data.error != '')
					{
						$('#form_message').html(data.error);
					}
					else
					{	
						window.location.href="appointment.php";
					}
				}
			})

		}

	})

});

</script>