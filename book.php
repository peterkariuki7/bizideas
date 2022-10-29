<?php

//index.php

include('class/Appointment.php');
include('header.php');

$object = new Appointment;



$object->query = "
SELECT * FROM supplier_schedule_table 
INNER JOIN supplier_table 
ON supplier_table.supplier_id = supplier_schedule_table.supplier_id
WHERE supplier_schedule_table.supplier_schedule_date >= '".date('Y-m-d')."' 
AND supplier_schedule_table.supplier_schedule_status = 'Active' 
AND supplier_table.supplier_status = 'Active' 
ORDER BY supplier_schedule_table.supplier_schedule_date ASC
";

$result = $object->get_result();



?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header text-danger"><h3><b>supplier Schedule List</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>supplier Name</th>
		      							<th>Company Name</th>
		      							<th>Speciality</th>
		      							<th>Appointment Date</th>
		      							<th>Appointment Day</th>
		      							<th>Available Time</th>
		      							<th>Action</th>
		      						</tr>
		      						<?php
		      						foreach($result as $row)
		      						{
		      							echo '
		      							<tr>
		      								<td>'.$row["supplier_name"].'</td>
		      								<td>'.$row["company_name"].'</td>
		      								<td>'.$row["supplier_expert_in"].'</td>
		      								<td>'.$row["supplier_schedule_date"].'</td>
		      								<td>'.$row["supplier_schedule_day"].'</td>
		      								<td>'.$row["supplier_schedule_start_time"].' - '.$row["supplier_schedule_end_time"].'</td>
		      								<td><button type="button" name="get_appointment" class="btn btn-dark btn-sm get_appointment" data-id="'.$row["supplier_schedule_id"].'">Get Appointment</button></td>
		      							</tr>
		      							';
		      						}
		      						?>
		      					</table>
		      				</div>
		      			</div>
		      		</form>
		      	</div>
		    

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){
	$(document).on('click', '.get_appointment', function(){
		var action = 'check_login';
		var supplier_schedule_id = $(this).data('id');
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:action, supplier_schedule_id:supplier_schedule_id},
			success:function(data)
			{
				window.location.href=data;
			}
		})
	});
});

</script>