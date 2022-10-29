<?php

//download.php

include('class/Appointment.php');

$object = new Appointment;

require_once('class/pdf.php');

if(isset($_GET["id"]))
{
	$html = '<table border="0" cellpadding="5" cellspacing="5" width="100%">';

	$object->query = "
	SELECT website_name, website_address, website_contact_no, website_logo 
	FROM admin_table
	";

	$website_data = $object->get_result();

	foreach($website_data as $website_row)
	{
		$html .= '<tr><td align="center">';
		if($website_row['website_logo'] != '')
		{
			$html .= '<img src="'.substr($website_row['website_logo'], 3).'" /><br />';
		}
		$html .= '<h2 align="center">'.$website_row['website_name'].'</h2>
		<p align="center">'.$website_row['website_address'].'</p>
		<p align="center"><b>Contact No. - </b>'.$website_row['website_contact_no'].'</p></td></tr>
		';
	}

	$html .= "
	<tr><td><hr /></td></tr>
	<tr><td>
	";

	$object->query = "
	SELECT * FROM appointment_table 
	WHERE appointment_id = '".$_GET["id"]."'
	";

	$appointment_data = $object->get_result();

	foreach($appointment_data as $appointment_row)
	{

		$object->query = "
		SELECT * FROM client_table 
		WHERE client_id = '".$appointment_row["client_id"]."'
		";

		$client_data = $object->get_result();

		$object->query = "
		SELECT * FROM supplier_schedule_table 
		INNER JOIN supplier_table 
		ON supplier_table.supplier_id = supplier_schedule_table.supplier_id 
		WHERE supplier_schedule_table.supplier_schedule_id = '".$appointment_row["supplier_schedule_id"]."'
		";

		$supplier_schedule_data = $object->get_result();
		
		$html .= '
		<h4 align="center">client Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">';

		foreach($client_data as $client_row)
		{
			$html .= '<tr><th width="50%" align="right">client Name</th><td>'.$client_row["client_first_name"].' '.$client_row["client_last_name"].'</td></tr>
			<tr><th width="50%" align="right">Contact No.</th><td>'.$client_row["client_phone_no"].'</td></tr>
			<tr><th width="50%" align="right">Address</th><td>'.$client_row["client_address"].'</td></tr>';
		}

		$html .= '</table><br /><hr />
		<h4 align="center">Appointment Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<th width="50%" align="right">Appointment No.</th>
				<td>'.$appointment_row["appointment_number"].'</td>
			</tr>
		';
		foreach($supplier_schedule_data as $supplier_schedule_row)
		{
			$html .= '
			<tr>
				<th width="50%" align="right">supplier Name</th>
				<td>'.$supplier_schedule_row["supplier_name"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Date</th>
				<td>'.$supplier_schedule_row["supplier_schedule_date"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Day</th>
				<td>'.$supplier_schedule_row["supplier_schedule_day"].'</td>
			</tr>
				
			';
		}

		$html .= '
			<tr>
				<th width="50%" align="right">Appointment Time</th>
				<td>'.$appointment_row["appointment_time"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Reason for Appointment</th>
				<td>'.$appointment_row["reason_for_appointment"].'</td>
			</tr>
		
			<tr>
				<th width="50%" align="right">supplier Comment</th>
				<td>'.$appointment_row["supplier_comment"].'</td>
			</tr>
		</table>
			';
	}

	$html .= '
			</td>
		</tr>
	</table>';

	echo $html;

	$pdf = new Pdf();

	$pdf->loadHtml($html, 'UTF-8');
	$pdf->render();
	ob_end_clean();
	//$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>1 ));
	$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>false ));
	exit(0);

}

?>