<?php

include('../class/Appointment.php');

$object = new Appointment;

if($_POST["action"] == 'supplier_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$supplier_profile_image = '';

	$data = array(
		':supplier_email_address'	=>	$_POST["supplier_email_address"],
		':supplier_id'			=>	$_POST['hidden_id']
	);

	$object->query = "
	SELECT * FROM supplier_table 
	WHERE supplier_email_address = :supplier_email_address 
	AND supplier_id != :supplier_id
	";

	$object->execute($data);

	if($object->row_count() > 0)
	{
		$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
	}
	else
	{
		$supplier_profile_image = $_POST["hidden_supplier_profile_image"];

		if($_FILES['supplier_profile_image']['name'] != '')
		{
			$allowed_file_format = array("jpg", "png");

	    	$file_extension = pathinfo($_FILES["supplier_profile_image"]["name"], PATHINFO_EXTENSION);

	    	if(!in_array($file_extension, $allowed_file_format))
		    {
		        $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
		    }
		    else if (($_FILES["supplier_profile_image"]["size"] > 2000000))
		    {
		       $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
		    }
		    else
		    {
		    	$new_name = rand() . '.' . $file_extension;

				$destination = '../images/' . $new_name;

				move_uploaded_file($_FILES['supplier_profile_image']['tmp_name'], $destination);

				$supplier_profile_image = $destination;
		    }
		}

		if($error == '')
		{
			$data = array(
				':supplier_email_address'			=>	$object->clean_input($_POST["supplier_email_address"]),
				':supplier_password'				=>	$_POST["supplier_password"],
				':supplier_name'					=>	$object->clean_input($_POST["supplier_name"]),
				':supplier_profile_image'			=>	$supplier_profile_image,
				':supplier_phone_no'				=>	$object->clean_input($_POST["supplier_phone_no"]),
				':supplier_address'				=>	$object->clean_input($_POST["supplier_address"]),
				':supplier_date_of_birth'			=>	$object->clean_input($_POST["supplier_date_of_birth"]),
				':company_name'				=>	$object->clean_input($_POST["company_name"]),
				':supplier_expert_in'				=>	$object->clean_input($_POST["supplier_expert_in"])
			);

			$object->query = "
			UPDATE supplier_table  
			SET supplier_email_address = :supplier_email_address, 
			supplier_password = :supplier_password, 
			supplier_name = :supplier_name, 
			supplier_profile_image = :supplier_profile_image, 
			supplier_phone_no = :supplier_phone_no, 
			supplier_address = :supplier_address, 
			supplier_date_of_birth = :supplier_date_of_birth, 
			company_name = :company_name,  
			supplier_expert_in = :supplier_expert_in 
			WHERE supplier_id = '".$_POST['hidden_id']."'
			";
			$object->execute($data);

			$success = '<div class="alert alert-success">supplier Data Updated</div>';
		}			
	}

	$output = array(
		'error'					=>	$error,
		'success'				=>	$success,
		'supplier_email_address'	=>	$_POST["supplier_email_address"],
		'supplier_password'		=>	$_POST["supplier_password"],
		'supplier_name'			=>	$_POST["supplier_name"],
		'supplier_profile_image'	=>	$supplier_profile_image,
		'supplier_phone_no'		=>	$_POST["supplier_phone_no"],
		'supplier_address'		=>	$_POST["supplier_address"],
		'supplier_date_of_birth'	=>	$_POST["supplier_date_of_birth"],
		'company_name'			=>	$_POST["company_name"],
		'supplier_expert_in'		=>	$_POST["supplier_expert_in"],
	);

	echo json_encode($output);
}

if($_POST["action"] == 'admin_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$website_logo = $_POST['hidden_website_logo'];

	if($_FILES['website_logo']['name'] != '')
	{
		$allowed_file_format = array("jpg", "png");

	    $file_extension = pathinfo($_FILES["website_logo"]["name"], PATHINFO_EXTENSION);

	    if(!in_array($file_extension, $allowed_file_format))
		{
		    $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
		}
		else if (($_FILES["website_logo"]["size"] > 2000000))
		{
		   $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
	    }
		else
		{
		    $new_name = rand() . '.' . $file_extension;

			$destination = '../images/' . $new_name;

			move_uploaded_file($_FILES['website_logo']['tmp_name'], $destination);

			$website_logo = $destination;
		}
	}

	if($error == '')
	{
		$data = array(
			':admin_email_address'			=>	$object->clean_input($_POST["admin_email_address"]),
			':admin_password'				=>	$_POST["admin_password"],
			':admin_name'					=>	$object->clean_input($_POST["admin_name"]),
			':website_name'				=>	$object->clean_input($_POST["website_name"]),
			':website_address'				=>	$object->clean_input($_POST["website_address"]),
			':website_contact_no'			=>	$object->clean_input($_POST["website_contact_no"]),
			':website_logo'				=>	$website_logo
		);

		$object->query = "
		UPDATE admin_table  
		SET admin_email_address = :admin_email_address, 
		admin_password = :admin_password, 
		admin_name = :admin_name, 
		website_name = :website_name, 
		website_address = :website_address, 
		website_contact_no = :website_contact_no, 
		website_logo = :website_logo 
		WHERE admin_id = '".$_SESSION["admin_id"]."'
		";
		$object->execute($data);

		$success = '<div class="alert alert-success">Admin Data Updated</div>';

		$output = array(
			'error'					=>	$error,
			'success'				=>	$success,
			'admin_email_address'	=>	$_POST["admin_email_address"],
			'admin_password'		=>	$_POST["admin_password"],
			'admin_name'			=>	$_POST["admin_name"], 
			'website_name'			=>	$_POST["website_name"],
			'website_address'		=>	$_POST["website_address"],
			'website_contact_no'	=>	$_POST["website_contact_no"],
			'website_logo'			=>	$website_logo
		);

		echo json_encode($output);
	}
	else
	{
		$output = array(
			'error'					=>	$error,
			'success'				=>	$success
		);
		echo json_encode($output);
	}
}

?>