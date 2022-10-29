<?php

//supplier_schedule_action.php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('supplier_table.supplier_name', 'supplier_schedule_table.supplier_schedule_date', 'supplier_schedule_table.supplier_schedule_day', 'supplier_schedule_table.supplier_schedule_start_time', 'supplier_schedule_table.supplier_schedule_end_time', 'supplier_schedule_table.average_consulting_time');
			$main_query = "
			SELECT * FROM supplier_schedule_table 
			INNER JOIN supplier_table 
			ON supplier_table.supplier_id = supplier_schedule_table.supplier_id 
			";

			$search_query = '';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'WHERE supplier_table.supplier_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_table.supplier_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_table.supplier_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_table.supplier_schedule_start_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_table.supplier_schedule_end_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_table.average_consulting_time LIKE "%'.$_POST["search"]["value"].'%" ';
			}
		}
		else
		{
			$order_column = array('supplier_schedule_date', 'supplier_schedule_day', 'supplier_schedule_start_time', 'supplier_schedule_end_time', 'average_consulting_time');
			$main_query = "
			SELECT * FROM supplier_schedule_table 
			";

			$search_query = '
			WHERE supplier_id = "'.$_SESSION["admin_id"].'" AND 
			';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= '(supplier_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_start_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR supplier_schedule_end_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR average_consulting_time LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY supplier_schedule_table.supplier_schedule_id DESC ';
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
			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = html_entity_decode($row["supplier_name"]);
			}
			$sub_array[] = $row["supplier_schedule_date"];

			$sub_array[] = $row["supplier_schedule_day"];

			$sub_array[] = $row["supplier_schedule_start_time"];

			$sub_array[] = $row["supplier_schedule_end_time"];

			$sub_array[] = $row["average_consulting_time"] . ' Minute';

			$status = '';
			if($row["supplier_schedule_status"] == 'Active')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["supplier_schedule_id"].'" data-status="'.$row["supplier_schedule_status"].'">Active</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["supplier_schedule_id"].'" data-status="'.$row["supplier_schedule_status"].'">Inactive</button>';
			}

			$sub_array[] = $status;

			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["supplier_schedule_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["supplier_schedule_id"].'"><i class="fas fa-times"></i></button>
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

		$supplier_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$supplier_id = $_POST["supplier_id"];
		}

		if($_SESSION['type'] == 'supplier')
		{
			$supplier_id = $_SESSION['admin_id'];
		}

		$data = array(
			':supplier_id'					=>	$supplier_id,
			':supplier_schedule_date'			=>	$_POST["supplier_schedule_date"],
			':supplier_schedule_day'			=>	date('l', strtotime($_POST["supplier_schedule_date"])),
			':supplier_schedule_start_time'	=>	$_POST["supplier_schedule_start_time"],
			':supplier_schedule_end_time'		=>	$_POST["supplier_schedule_end_time"],
			':average_consulting_time'		=>	$_POST["average_consulting_time"]
		);

		$object->query = "
		INSERT INTO supplier_schedule_table 
		(supplier_id, supplier_schedule_date, supplier_schedule_day, supplier_schedule_start_time, supplier_schedule_end_time, average_consulting_time) 
		VALUES (:supplier_id, :supplier_schedule_date, :supplier_schedule_day, :supplier_schedule_start_time, :supplier_schedule_end_time, :average_consulting_time)
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">supplier Schedule Added Successfully</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM supplier_schedule_table 
		WHERE supplier_schedule_id = '".$_POST["supplier_schedule_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['supplier_id'] = $row['supplier_id'];
			$data['supplier_schedule_date'] = $row['supplier_schedule_date'];
			$data['supplier_schedule_start_time'] = $row['supplier_schedule_start_time'];
			$data['supplier_schedule_end_time'] = $row['supplier_schedule_end_time'];
			$data['average_consulting_time'] = $row['average_consulting_time'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$supplier_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$supplier_id = $_POST["supplier_id"];
		}

		if($_SESSION['type'] == 'supplier')
		{
			$supplier_id = $_SESSION['admin_id'];
		}

		$data = array(
			':supplier_id'					=>	$supplier_id,
			':supplier_schedule_date'			=>	$_POST["supplier_schedule_date"],
			':supplier_schedule_start_time'	=>	$_POST["supplier_schedule_start_time"],
			':supplier_schedule_end_time'		=>	$_POST["supplier_schedule_end_time"],
			':average_consulting_time'		=>	$_POST["average_consulting_time"]
		);

		$object->query = "
		UPDATE supplier_schedule_table 
		SET supplier_id = :supplier_id, 
		supplier_schedule_date = :supplier_schedule_date, 
		supplier_schedule_start_time = :supplier_schedule_start_time, 
		supplier_schedule_end_time = :supplier_schedule_end_time, 
		average_consulting_time = :average_consulting_time    
		WHERE supplier_schedule_id = '".$_POST['hidden_id']."'
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">supplier Schedule Data Updated Successfully Updated</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':supplier_schedule_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE supplier_schedule_table 
		SET supplier_schedule_status = :supplier_schedule_status 
		WHERE supplier_schedule_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">supplier Schedule Status change to '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM supplier_schedule_table 
		WHERE supplier_schedule_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">supplier Schedule has been Deleted</div>';
	}
}

?>