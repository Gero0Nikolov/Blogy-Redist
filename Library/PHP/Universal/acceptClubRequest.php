<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$getMemberId = $_POST["memberId"];

	//Include functions library
	include "functions.php";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Members, Requesters FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubMembers = explode(",", $row["Members"]);
			$getClubRequesters = explode(",", $row["Requesters"]);
		}
	}

	//Check if the arrays are arrays :O
	if ( !is_array($getClubMembers) ) { $getClubMembers = array(); }
	if ( !is_array($getClubRequesters) ) { $getClubRequesters = array(); }

	//Append the new member
	array_push($getClubMembers, $getMemberId);

	//Revise club members
	$reviseClubMembers = array();
	foreach ($getClubMembers as $member) {
		if ( !empty($member) ) { array_push($reviseClubMembers, $member); }
	}
	if ( !empty($reviseClubMembers) ) { $reviseClubMembers = implode(",", $reviseClubMembers); }
	elseif ( empty($reviseClubMembers) ) { $reviseClubMembers = NULL; }

	//Update club members
	$sql = "UPDATE ".$getClubTable." SET Members='".$reviseClubMembers."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Revise club requesters
	$reviseClubRequesters = array();
	foreach ($getClubRequesters as $requester) {
		if ( $requester != $getMemberId ) { array_push($reviseClubRequesters, $requester); }
	}
	if ( !empty($reviseClubRequesters) ) { $reviseClubRequesters = implode(",", $reviseClubRequesters); }
	elseif ( empty($reviseClubRequesters) ) { $reviseClubRequesters = NULL; }

	//Update club requests
	$sql = "UPDATE ".$getClubTable." SET Requesters='".$reviseClubRequesters."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Add club to memberships to the requester
	$sql = "INSERT INTO ".$getMemberId."_Membershiped_Clubs (Club_Table, Club_Id) VALUES ('".$getClubTable."', '".$getClubId."')";
	$conn->query($sql);

	//Send notification to the user who was a requester
	send_club_notification($sender, $getClubTable, $getClubId, $getMemberId, $conn, "approved you to join the club");

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>