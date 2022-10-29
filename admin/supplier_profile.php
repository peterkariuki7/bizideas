<?php

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'supplier')
{
    header("location:".$object->base_url."");
}

$object->query = "
    SELECT * FROM supplier_table
    WHERE supplier_id = '".$_SESSION["admin_id"]."'
    ";

$result = $object->get_result();

include('header.php');

?>

                 
                    <h1 class="h3 mb-4 text-gray-800">Profile</h1>                   
                    <form method="post" id="profile_form" enctype="multipart/form-data">
                        <div class="row"><div class="col-md-10"><span id="message"></span><div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-danger">Profile</h6>
                                    </div>
                                    <div clas="col" align="right">
                                        <input type="hidden" name="action" value="supplier_profile" />
                                        <input type="hidden" name="hidden_id" id="hidden_id" />
                                        <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                        <span id="form_message"></span>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>supplier Email Address <span class="text-danger">*</span></label>
                                                    <input type="text" name="supplier_email_address" id="supplier_email_address" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>supplier Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="supplier_password" id="supplier_password" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>supplier Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="supplier_name" id="supplier_name" class="form-control" required data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>supplier Phone No. <span class="text-danger">*</span></label>
                                                    <input type="text" name="supplier_phone_no" id="supplier_phone_no" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>supplier Address </label>
                                                    <input type="text" name="supplier_address" id="supplier_address" class="form-control" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>supplier Date of Birth </label>
                                                    <input type="text" name="supplier_date_of_birth" id="supplier_date_of_birth" readonly class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> Company Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="company_name" id="company_name" class="form-control" required data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>supplier Speciality <span class="text-danger">*</span></label>
                                                    <input type="text" name="supplier_expert_in" id="supplier_expert_in" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>supplier Image <span class="text-danger">*</span></label>
                                            <br />
                                            <input type="file" name="supplier_profile_image" id="supplier_profile_image" />
                                            <div id="uploaded_image"></div>
                                            <input type="hidden" name="hidden_supplier_profile_image" id="hidden_supplier_profile_image" />
                                        </div>
                            
                            </div>
                        </div></div></div>
                    </form>
                <?php
                include('footer.php');
                ?>

<script>
$(document).ready(function(){

    $('#supplier_date_of_birth').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });

    <?php
    foreach($result as $row)
    {
    ?>
    $('#hidden_id').val("<?php echo $row['supplier_id']; ?>");
    $('#supplier_email_address').val("<?php echo $row['supplier_email_address']; ?>");
    $('#supplier_password').val("<?php echo $row['supplier_password']; ?>");
    $('#supplier_name').val("<?php echo $row['supplier_name']; ?>");
    $('#supplier_phone_no').val("<?php echo $row['supplier_phone_no']; ?>");
    $('#supplier_address').val("<?php echo $row['supplier_address']; ?>");
    $('#supplier_date_of_birth').val("<?php echo $row['supplier_date_of_birth']; ?>");
    $('#company_name').val("<?php echo $row['company_name']; ?>");
    $('#supplier_expert_in').val("<?php echo $row['supplier_expert_in']; ?>");
    
    $('#uploaded_image').html('<img src="<?php echo $row["supplier_profile_image"]; ?>" class="img-thumbnail" width="100" /><input type="hidden" name="hidden_supplier_profile_image" value="<?php echo $row["supplier_profile_image"]; ?>" />');

    $('#hidden_supplier_profile_image').val("<?php echo $row['supplier_profile_image']; ?>");
    <?php
    }
    ?>

    $('#supplier_profile_image').change(function(){
        var extension = $('#supplier_profile_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['png','jpg']) == -1)
            {
                alert("Invalid Image File");
                $('#supplier_profile_image').val('');
                return false;
            }
        }
    });

    $('#profile_form').parsley();

	$('#profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#profile_form').parsley().isValid())
		{		
			$.ajax({
				url:"profile_action.php",
				method:"POST",
				data:new FormData(this),
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#edit_button').attr('disabled', 'disabled');
					$('#edit_button').html('wait...');
				},
				success:function(data)
				{
					$('#edit_button').attr('disabled', false);
                    $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                    $('#supplier_email_address').val(data.supplier_email_address);
                    $('#supplier_password').val(data.supplier_password);
                    $('#supplier_name').val(data.supplier_name);
                    $('#supplier_phone_no').val(data.supplier_phone_no);
                    $('#supplier_address').text(data.supplier_address);
                    $('#supplier_date_of_birth').text(data.supplier_date_of_birth);
                    $('#company_name').text(data.company_name);
                    $('#supplier_expert_in').text(data.supplier_expert_in);
                    if(data.supplier_profile_image != '')
                    {
                        $('#uploaded_image').html('<img src="'+data.supplier_profile_image+'" class="img-thumbnail" width="100" />');

                        $('#user_profile_image').attr('src', data.supplier_profile_image);
                    }

                    $('#hidden_supplier_profile_image').val(data.supplier_profile_image);
						
                    $('#message').html(data.success);

					setTimeout(function(){

				        $('#message').html('');

				    }, 5000);
				}
			})
		}
	});

});
</script>