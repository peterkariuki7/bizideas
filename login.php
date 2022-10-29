<?php

//login.php

include('header.php');

include('class/Appointment.php');

$object = new Appointment;

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-4">
			<?php
			if(isset($_SESSION["success_message"]))
			{
				echo $_SESSION["success_message"];
				unset($_SESSION["success_message"]);
			}
			?>
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Login</div>
				<div class="card-body">
					<form method="post" id="client_login_form">
						<div class="form-group">
							<label>client Email Address</label>
							<input type="text" name="client_email_address" id="client_email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" placeholder="Email Address" />
						</div>
						<div class="form-group">
							<label>client Password</label>
							<input type="password" name="client_password" id="client_password" class="form-control" required  data-parsley-trigger="keyup" placeholder="Password" />
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="client_login" />
							<input type="submit" name="client_login_button" id="client_login_button" class="btn btn-primary" value="Login" />
						</div>

						<div class="form-group text-center">
							<p><a href="register.php">Register</a></p>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

include('footer.php');

?>


<script>

$(document).ready(function(){

	$('#client_login_form').parsley();

	$('#client_login_form').on('submit', function(event){

		event.preventDefault();

		if($('#client_login_form').parsley().isValid())
		{
			$.ajax({

				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function()
				{
					$('#client_login_button').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#client_login_button').attr('disabled', false);

					if(data.error != '')
					{
						$('#message').html(data.error);
					}
					else
					{
						window.location.href="dashboard.php";
					}
				}
			});
		}

	});

});



</script>