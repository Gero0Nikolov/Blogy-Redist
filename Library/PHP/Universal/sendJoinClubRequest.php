<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$clubOwner = explode("_", $getClubTable)[0];

	//Connect to the Database
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get current join requests
	$sql = "SELECT Requesters FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubJoinRequests = explode(",", $row["Requesters"]);
		}
	}

	//Check is array
	if ( !is_array($getClubJoinRequests) ) { $getClubJoinRequests = array(); }

	//Add the new requester
	array_push($getClubJoinRequests, $sender);

	//Revise the requests
	$reviseClubJoinRequests = array();
	foreach ($getClubJoinRequests as $request) {
		if ( !empty($request) && isset($request) ) {
			array_push($reviseClubJoinRequests, $request);
		}
	}
	if ( !empty($reviseClubJoinRequests) ) { $reviseClubJoinRequests = implode(",", $reviseClubJoinRequests); }
	elseif ( empty($reviseClubJoinRequests) ) { $reviseClubJoinRequests = NULL; }

	//Update the requests
	$sql = "UPDATE ".$getClubTable." SET Requesters='".$reviseClubJoinRequests."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Send notification the club owner
	include "functions.php";
	send_club_notification($sender, $getClubTable, $getClubId, $clubOwner, $conn, "wants to join the club");

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>