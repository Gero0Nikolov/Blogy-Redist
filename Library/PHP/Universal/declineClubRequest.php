<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$getMemberId = $_POST["memberId"];

	$clubOwner = explode("_", $getClubTable)[0];

	//Include function library
	include "functions.php";

	if ( $getMemberId == $clubOwner ) {
		$responce = "CDO";
	} else {
		//Connect to the database
		include "dataBase.php";

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT Requesters FROM ".$getClubTable." WHERE ID=".$getClubId;
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getClubRequesters = $row["Requesters"];
			}
		}

		//Check is array
		if ( !is_array($getClubRequesters) ) { $getClubRequesters = array(); }

		//Revise the club requestsl
		$reviseClubRequests = array();
		foreach ($getClubRequesters as $request) {
			if ( $request != $getMemberId && !empty($request) ) {
				array_push($reviseClubRequests, $request);
			}
		}
		if ( !empty($reviseClubRequests) ) { $reviseClubRequests = implode(",", $reviseClubRequests); }
		elseif ( empty($reviseClubRequests) ) { $reviseClubRequests = NULL; }

		//Update club requests
		$sql = "UPDATE ".$getClubTable." SET Requesters='".$reviseClubRequests."' WHERE ID=".$getClubId;
		$conn->query($sql);

		//Send notification to the user who was a requester
		send_club_notification($sender, $getClubTable, $getClubId, $getMemberId, $conn, "declined you to join the club");

		//Close the connection
		$conn->close();

		$responce = "READY";
	}

	//Return responce
	echo $responce;
?>