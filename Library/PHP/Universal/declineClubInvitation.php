<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
	}

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	$clubOwner = explode("_", $getClubTable)[0];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Invited FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubInvites = explode(",", $row["Invited"]);
		}
	}

	if ( !is_array($getClubInvites) ) { $getClubInvites = array(); }

	$reviseClubInvites = array();
	foreach ($getClubInvites as $invited) {
		if ($invited != $sender) {
			array_push($reviseClubInvites, $invited);
		}
	}
	if ( empty($reviseClubInvites) ) { $reviseClubInvites = NULL; }
	elseif ( !empty($reviseClubInvites) ) { $reviseClubInvites = implode(",", $reviseClubInvites); }

	//Update club invites
	$sql = "UPDATE ".$getClubTable." SET Invited='".$reviseClubInvites."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Send notification the club owner
	include "functions.php";
	send_club_notification($sender, $getClubTable, $getClubId, $clubOwner, $conn, "declined club invitation");

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>