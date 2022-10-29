<?php

//login.php

include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Register</div>
				<div class="card-body">
					<form method="post" id="client_register_form">
						<div class="form-group">
							<label>client Email Address<span class="text-danger">*</span></label>
							<input type="text" name="client_email_address" id="client_email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
						</div>
						<div class="form-group">
							<label>client Password<span class="text-danger">*</span></label>
							<input type="password" name="client_password" id="client_password" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<labelclient> First Name<span class="text-danger">*</span></label>
									<input type="text" name="client_first_name" id="client_first_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>client Last Name<span class="text-danger">*</span></label>
									<input type="text" name="client_last_name" id="client_last_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>client Contact No.<span class="text-danger">*</span></label>
									<input type="text" name="client_phone_no" id="client_phone_no" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<label>client Address<span class="text-danger">*</span></label>
							<textarea name="client_address" id="client_address" class="form-control" required data-parsley-trigger="keyup"></textarea>
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="client_register" />
							<input type="submit" name="client_register_button" id="client_register_button" class="btn btn-primary" value="Register" />
						</div>

						<div class="form-group text-center">
							<p><a href="login.php">Login</a></p>
						</div>
					</form>
				</div>
			</div>
			<br />
			<br />
		</div>
	</div>
</div>

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){


	$('#clientt_register_form').parsley();

	$('#client_register_form').on('submit', function(event){

		event.preventDefault();

		if($('#client_register_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#client_register_button').attr('disabled', false);
				},
				success:function(data)
				{
					$('#client_register_button').attr('disabled', false);
					$('#client_register_form')[0].reset();
					if(data.error !== '')
					{
						$('#message').html(data.error);
					}
					if(data.success != '')
					{
						$('#message').html(data.success);
						
					}
				}
			});
		}

	});

});

</script>