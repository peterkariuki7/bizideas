<?php

//main front page

include('class/Appointment.php');



include('header.php');

?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header text-danger"><h3><b>Available Business Ideas:</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>T-Shirt Printing</th>
		      							<th>Cleaning Services</th>
		      							<th>Podcast Business</th>
		      						</tr>
                                      <tr>
		      							<td>
                                          <img src="img/tshirt.png" width="400" />
                                          <div class="col-md-7 text-right">
                                            <a href="tshirt.php
                                           " class="btn btn-primary btn-bg">Get Started-></a>
                                            </div>
                                          </div>
                                        </td>
		      							<td><img src="img/clean.png"  width="400" />
                                          <div class="col-md-7 text-right">
                                            <a href="cleaning.php
                                           " class="btn btn-primary btn-bg">Get Started-></a>
                                            </div>
                                    </td>
		      							<td><img src="img/pod.png" width="400" />
                                          <div class="col-md-7 text-right">
                                            <a href="podcast.php
                                           " class="btn btn-primary btn-bg">Get Started-></a>
                                            </div>
                                        </td>
		      						</tr>

                                    
		      						
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