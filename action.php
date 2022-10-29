<?php

//action.php

include('class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'check_login')
	{
		if(isset($_SESSION['client_id']))
		{
			echo 'dashboard.php';
		}
		else
		{
			echo 'login.php';
		}
	}

	if($_POST['action'] == 'client_register')
	{
		$error = '';

		$success = '';

		$data = array(
			':client_email_address'	=>	$_POST["client_email_address"]
		);

		$object->query = "
		SELECT * FROM client_table 
		WHERE client_email_address = :client_email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$client_verification_code = md5(uniqid());
			$data = array(
				':client_email_address'		=>	$object->clean_input($_POST["client_email_address"]),
				':client_password'				=>	$_POST["client_password"],
				':client_first_name'			=>	$object->clean_input($_POST["client_first_name"]),
				':client_last_name'			=>	$object->clean_input($_POST["client_last_name"]),
				':client_address'				=>	$object->clean_input($_POST["client_address"]),
				':client_phone_no'				=>	$object->clean_input($_POST["client_phone_no"]),
				':client_added_on'				=>	$object->now,
				':client_verification_code'	=>	$client_verification_code,
				':email_verify'					=>	'No'
			);

			$object->query = "
			INSERT INTO client_table 
			(client_email_address, client_password, client_first_name, client_last_name,client_address, client_phone_no,client_added_on, client_verification_code, email_verify) 
			VALUES (:client_email_address, :client_password, :client_first_name, :client_last_name, :client_address, :client_phone_no,:client_added_on, :client_verification_code, :email_verify)
			";

			$object->execute($data);

			
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}

	if($_POST['action'] == 'client_login')
	{
		$error = '';

		$data = array(
			':client_email_address'	=>	$_POST["client_email_address"]
		);

		$object->query = "
		SELECT * FROM client_table 
		WHERE client_email_address = :client_email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{

			$result = $object->statement_result();

			foreach($result as $row)
			{
				if($row["email_verify"] == 'No')
				{
					if($row["client_password"] == $_POST["client_password"])
					{
						$_SESSION['client_id'] = $row['client_id'];
						$_SESSION['client_name'] = $row['client_first_name'] . ' ' . $row['client_last_name'];
					}
					else
					{
						$error = '<div class="alert alert-danger">Wrong Password</div>';
					}
				}
				else
				{
					$error = '<div class="alert alert-danger">Please first verify your email address</div>';
				}
			}
		}
		else
		{
			$error = '<div class="alert alert-danger">Wrong Email Address</div>';
		}

		$output = array(
			'error'		=>	$error
		);

		echo json_encode($output);

	}

	if($_POST['action'] == 'fetch_schedule')
	{
		$output = array();

		$order_column = array('supplier_table.supplier_name', 'supplier_table.company_name', 'supplier_table.supplier_expert_in', 'supplier_schedule_table.supplier_schedule_date', 'supplier_schedule_table.supplier_schedule_day', 'supplier_schedule_table.supplier_schedule_start_time');
		
		$main_query = "
		SELECT * FROM supplier_schedule_table 
		INNER JOIN supplier_table 
		ON supplier_table.supplier_id = supplier_schedule_table.supplier_id 
		";

		$search_query = '
		WHERE supplier_schedule_table.supplier_schedule_date >= "'.date('Y-m-d').'" 
		AND supplier_schedule_table.supplier_schedule_status = "Active" 
		AND supplier_table.supplier_status = "Active" 
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( supplier_table.supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_table.company_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_table.supplier_expert_in LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_schedule_table.supplier_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_schedule_table.supplier_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_schedule_table.supplier_schedule_start_time LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY supplier_schedule_table.supplier_schedule_date ASC ';
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

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();


		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["supplier_name"];

			$sub_array[] = $row["company_name"];

			$sub_array[] = $row["supplier_expert_in"];

			$sub_array[] = $row["supplier_schedule_date"];

			$sub_array[] = $row["supplier_schedule_day"];

			$sub_array[] = $row["supplier_schedule_start_time"];

			$sub_array[] = '
			<div align="center">
			<button type="button" name="get_appointment" class="btn btn-primary btn-sm get_appointment" data-supplier_id="'.$row["supplier_id"].'" data-supplier_schedule_id="'.$row["supplier_schedule_id"].'">Get Appointment</button>
			</div>
			';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data,
		);
			
		echo json_encode($output);
	}

	if($_POST['action'] == 'edit_profile')
	{
		$data = array(
			':client_password'			=>	$_POST["client_password"],
			':client_first_name'		=>	$_POST["client_first_name"],
			':client_last_name'		=>	$_POST["client_last_name"],
		
			':client_address'			=>	$_POST["client_address"],
			':client_phone_no'			=>	$_POST["client_phone_no"],
			
		);

		$object->query = "
		UPDATE client_table  
		SET client_password = :client_password, 
		client_first_name = :client_first_name, 
		client_last_name = :client_last_name, 
		 
		client_address = :client_address, 
		client_phone_no = :client_phone_no, 
		
		WHERE client_id = '".$_SESSION['client_id']."'
		";

		$object->execute($data);

		$_SESSION['success_message'] = '<div class="alert alert-success">Profile Data Updated</div>';

		echo 'done';
	}

	if($_POST['action'] == 'make_appointment')
	{
		$object->query = "
		SELECT * FROM client_table 
		WHERE client_id = '".$_SESSION["client_id"]."'
		";

		$client_data = $object->get_result();

		$object->query = "
		SELECT * FROM supplier_schedule_table 
		INNER JOIN supplier_table 
		ON supplier_table.supplier_id = supplier_schedule_table.supplier_id 
		WHERE supplier_schedule_table.supplier_schedule_id = '".$_POST["supplier_schedule_id"]."'
		";

		$supplier_schedule_data = $object->get_result();

		$html = '
		<h4 class="text-center">client Details</h4>
		<table class="table">
		';

		foreach($client_data as $client_row)
		{
			$html .= '
			<tr>
				<th width="40%" class="text-right">client Name</th>
				<td>'.$client_row["client_first_name"].' '.$client_row["client_last_name"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Contact No.</th>
				<td>'.$client_row["client_phone_no"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Address</th>
				<td>'.$client_row["client_address"].'</td>
			</tr>
			';
		}

		$html .= '
		</table>
		<hr />
		<h4 class="text-center">Appointment Details</h4>
		<table class="table">
		';
		foreach($supplier_schedule_data as $supplier_schedule_row)
		{
			$html .= '
			<tr>
				<th width="40%" class="text-right">supplier Name</th>
				<td>'.$supplier_schedule_row["supplier_name"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Appointment Date</th>
				<td>'.$supplier_schedule_row["supplier_schedule_date"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Appointment Day</th>
				<td>'.$supplier_schedule_row["supplier_schedule_day"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Available Time</th>
				<td>'.$supplier_schedule_row["supplier_schedule_start_time"].' - '.$supplier_schedule_row["supplier_schedule_end_time"].'</td>
			</tr>
			';
		}

		$html .= '
		</table>';
		echo $html;
	}

	if($_POST['action'] == 'book_appointment')
	{
		$error = '';
		$data = array(
			':client_id'			=>	$_SESSION['client_id'],
			':supplier_schedule_id'	=>	$_POST['hidden_supplier_schedule_id']
		);

		$object->query = "
		SELECT * FROM appointment_table 
		WHERE client_id = :client_id 
		AND supplier_schedule_id = :supplier_schedule_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">You have already applied for appointment for this day, try for other day.</div>';
		}
		else
		{
			$object->query = "
			SELECT * FROM supplier_schedule_table 
			WHERE supplier_schedule_id = '".$_POST['hidden_supplier_schedule_id']."'
			";

			$schedule_data = $object->get_result();

			$object->query = "
			SELECT COUNT(appointment_id) AS total FROM appointment_table 
			WHERE supplier_schedule_id = '".$_POST['hidden_supplier_schedule_id']."' 
			";

			$appointment_data = $object->get_result();

			$total_supplier_available_minute = 0;
			$average_consulting_time = 0;
			$total_appointment = 0;

			foreach($schedule_data as $schedule_row)
			{
				$end_time = strtotime($schedule_row["supplier_schedule_end_time"] . ':00');

				$start_time = strtotime($schedule_row["supplier_schedule_start_time"] . ':00');

				$total_supplier_available_minute = ($end_time - $start_time) / 60;

				$average_consulting_time = $schedule_row["average_consulting_time"];
			}

			foreach($appointment_data as $appointment_row)
			{
				$total_appointment = $appointment_row["total"];
			}

			$total_appointment_minute_use = $total_appointment * $average_consulting_time;

			$appointment_time = date("H:i", strtotime('+'.$total_appointment_minute_use.' minutes', $start_time));

			$status = '';

			$appointment_number = $object->Generate_appointment_no();

			if(strtotime($end_time) > strtotime($appointment_time . ':00'))
			{
				$status = 'Booked';
			}
			else
			{
				$status = 'Waiting';
			}
			
			$data = array(
				':supplier_id'				=>	$_POST['hidden_supplier_id'],
				':client_id'				=>	$_SESSION['client_id'],
				':supplier_schedule_id'		=>	$_POST['hidden_supplier_schedule_id'],
				':appointment_number'		=>	$appointment_number,
				':reason_for_appointment'	=>	$_POST['reason_for_appointment'],
				':appointment_time'			=>	$appointment_time,
				':status'					=>	'Booked'
			);

			$object->query = "
			INSERT INTO appointment_table 
			(supplier_id, client_id, supplier_schedule_id, appointment_number, reason_for_appointment, appointment_time, status) 
			VALUES (:supplier_id, :client_id, :supplier_schedule_id, :appointment_number, :reason_for_appointment, :appointment_time, :status)
			";

			$object->execute($data);

			$_SESSION['appointment_message'] = '<div class="alert alert-success">Your Appointment has been <b>'.$status.'</b> with Appointment No. <b>'.$appointment_number.'</b></div>';
		}
		echo json_encode(['error' => $error]);
		
	}

	if($_POST['action'] == 'fetch_appointment')
	{
		$output = array();

		$order_column = array('appointment_table.appointment_number','supplier_table.supplier_name', 'supplier_schedule_table.supplier_schedule_date', 'appointment_table.appointment_time', 'supplier_schedule_table.supplier_schedule_day', 'appointment_table.status');
		
		$main_query = "
		SELECT * FROM appointment_table  
		INNER JOIN supplier_table 
		ON supplier_table.supplier_id = appointment_table.supplier_id 
		INNER JOIN supplier_schedule_table 
		ON supplier_schedule_table.supplier_schedule_id = appointment_table.supplier_schedule_id 
		
		";

		$search_query = '
		WHERE appointment_table.client_id = "'.$_SESSION["client_id"].'" 
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( appointment_table.appointment_number LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_table.supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_schedule_table.supplier_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointment_table.appointment_time LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR supplier_schedule_table.supplier_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointment_table.status LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY appointment_table.appointment_id ASC ';
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

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["appointment_number"];

			$sub_array[] = $row["supplier_name"];

			$sub_array[] = $row["supplier_schedule_date"];			

			$sub_array[] = $row["appointment_time"];

			$sub_array[] = $row["supplier_schedule_day"];

			$status = '';

			if($row["status"] == 'Booked')
			{
				$status = '<span class="badge badge-warning">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'In Process')
			{
				$status = '<span class="badge badge-primary">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Completed')
			{
				$status = '<span class="badge badge-success">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Cancel')
			{
				$status = '<span class="badge badge-danger">' . $row["status"] . '</span>';
			}

			$sub_array[] = $status;

			$sub_array[] = '<a href="download.php?id='.$row["appointment_id"].'" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>';

			$sub_array[] = '<button type="button" name="cancel_appointment" class="btn btn-danger btn-sm cancel_appointment" data-id="'.$row["appointment_id"].'"><i class="fas fa-times"></i></button>';

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

	if($_POST['action'] == 'cancel_appointment')
	{
		$data = array(
			':status'			=>	'Cancel',
			':appointment_id'	=>	$_POST['appointment_id']
		);
		$object->query = "
		UPDATE appointment_table 
		SET status = :status 
		WHERE appointment_id = :appointment_id
		";
		$object->execute($data);
		echo '<div class="alert alert-success">Your Appointment has been Cancel</div>';
	}
}



?>