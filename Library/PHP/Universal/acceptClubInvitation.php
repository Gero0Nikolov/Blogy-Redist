<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
	}

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	$clubOwner = explode("_", $getClubTable)[0];

	$table_name = $sender."_Membershiped_Clubs";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Create Membershiped table clubs if not exsist
	$sql = "CREATE TABLE ".$table_name." (ID int NOT NULL AUTO_INCREMENT, Club_Table LONGTEXT, Club_Id INT, PRIMARY KEY (ID))";
	$conn->query($sql);

	//Add the new clubs into the membershiped clubs of the user
	$sql = "INSERT INTO ".$table_name." (Club_Table, Club_Id) VALUES ('".$getClubTable."', ".$getClubId.")";
	$conn->query($sql);

	//Move from Invited to Members into the club table
	$sql = "SELECT Members, Invited FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubMembers = explode(",", $row["Members"]);
			$getClubInvites = explode(",", $row["Invited"]);
		}
	}

	//Check if the containers are arrays
	if ( !is_array($getClubMembers) ) { $getClubMembers = array(); }
	if ( !is_array($getClubMembers) ) { $getClubInvites = array(); }

	//Add to members
	array_push($getClubMembers, $sender);
	$getClubMembers = implode(",", $getClubMembers);

	$sql = "UPDATE ".$getClubTable." SET Members='".$getClubMembers."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Revise club invites
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
	send_club_notification($sender, $getClubTable, $getClubId, $clubOwner, $conn, "accepted club invitation");

	//Close the connection
	$conn->close();

	//Return responc
	echo "READY";
?>