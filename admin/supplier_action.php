<?php

//supplier_action.php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('supplier_name', 'supplier_status');

		$output = array();

		$main_query = "
		SELECT * FROM supplier_table ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE supplier_email_address LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_phone_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_date_of_birth LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR company_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_expert_in LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_status LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY supplier_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = '<img src="'.$row["supplier_profile_image"].'" class="img-thumbnail" width="75" />';
			$sub_array[] = $row["supplier_email_address"];
			$sub_array[] = $row["supplier_password"];
			$sub_array[] = $row["supplier_name"];
			$sub_array[] = $row["supplier_phone_no"];
			$sub_array[] = $row["supplier_expert_in"];
			$status = '';
			if($row["supplier_status"] == 'Active')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["supplier_id"].'" data-status="'.$row["supplier_status"].'">Active</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["supplier_id"].'" data-status="'.$row["supplier_status"].'">Inactive</button>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["supplier_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["supplier_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["supplier_id"].'"><i class="fas fa-times"></i></button>
			</div>
			';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}

	if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':supplier_email_address'	=>	$_POST["supplier_email_address"]
		);

		$object->query = "
		SELECT * FROM supplier_table 
		WHERE supplier_email_address = :supplier_email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$supplier_profile_image = '';
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
			else
			{
				$character = $_POST["supplier_name"][0];
				$path = "../images/". time() . ".png";
				$image = imagecreate(200, 200);
				$red = rand(0, 255);
				$green = rand(0, 255);
				$blue = rand(0, 255);
			    imagecolorallocate($image, 230, 230, 230);  
			    $textcolor = imagecolorallocate($image, $red, $green, $blue);
			    imagettftext($image, 100, 0, 55, 150, $textcolor, '../font/arial.ttf', $character);
			    imagepng($image, $path);
			    imagedestroy($image);
			    $supplier_profile_image = $path;
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
					':supplier_expert_in'				=>	$object->clean_input($_POST["supplier_expert_in"]),
					':supplier_status'				=>	'Active',
					':supplier_added_on'				=>	$object->now
				);

				$object->query = "
				INSERT INTO supplier_table 
				(supplier_email_address, supplier_password, supplier_name, supplier_profile_image, supplier_phone_no, supplier_address, supplier_date_of_birth, company_name, supplier_expert_in, supplier_status, supplier_added_on) 
				VALUES (:supplier_email_address, :supplier_password, :supplier_name, :supplier_profile_image, :supplier_phone_no, :supplier_address, :supplier_date_of_birth, :company_name, :supplier_expert_in, :supplier_status, :supplier_added_on)
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">supplier Added</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM supplier_table 
		WHERE supplier_id = '".$_POST["supplier_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['supplier_email_address'] = $row['supplier_email_address'];
			$data['supplier_password'] = $row['supplier_password'];
			$data['supplier_name'] = $row['supplier_name'];
			$data['supplier_profile_image'] = $row['supplier_profile_image'];
			$data['supplier_phone_no'] = $row['supplier_phone_no'];
			$data['supplier_address'] = $row['supplier_address'];
			$data['supplier_date_of_birth'] = $row['supplier_date_of_birth'];
			$data['company_name'] = $row['company_name'];
			$data['supplier_expert_in'] = $row['supplier_expert_in'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

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
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':supplier_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE supplier_table 
		SET supplier_status = :supplier_status 
		WHERE supplier_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Class Status change to '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM supplier_table 
		WHERE supplier_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">supplier Data Deleted</div>';
	}
}

?>