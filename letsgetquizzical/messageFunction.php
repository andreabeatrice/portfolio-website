<?php 
	include 'conn.php';

	$result = array();
	$message = isset($_POST['message']) ? $_POST['message'] : null;
	$from = isset($_POST['from']) ? $_POST['from'] : null;
	$to = isset($_POST['to']) ? $_POST['to'] : null;

	if($from > $to){
		$cc = $to.'a'.$from;
	}
	else {
		$cc = $from.'a'.$to;
	}

	if (!empty($message) && !empty($from)) {
		$SQL = "INSERT INTO `tbchat` (`message`, `sent_by`, `sent_to`, `conversation_code`) VALUES ('".$message."','".$from."','".$to."','".$cc."')";

		$query = $mysqli->query($SQL);

		if (! $query){
			$result['result'] = mysql_error();
		}
		

	}

	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$result['start'] = $start;
	$items = $mysqli->query("SELECT * FROM `tbchat` WHERE id > ".$start);
	while ($row = $items->fetch_assoc()) {
		$result['items'][] = $row;
	}

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');


	echo json_encode($result);
?>

