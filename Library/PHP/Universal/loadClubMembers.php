<?php
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Owner, Members, Invited FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubOwner = $row["Owner"];
			$getClubMembers = $row["Members"];
			$getClubInvites = $row["Invited"];
		}
	}

	//Close the connection
	$conn->close();

	//Build responce
	$responce = "";

	if ( !empty($getClubMembers) && isset($getClubMembers) ) {
		foreach (explode(",", $getClubMembers) as $memberId) {
			if ($memberId != $getClubOwner) { $responce .= $memberId."~"."MEMBER,"; }
		}
	}

	if ( !empty($getClubInvites) && isset($getClubInvites) ) {
		foreach (explode(",", $getClubInvites) as $invitedId) {
			$responce .= $invitedId."~"."INVITED,";
		}
	}

	//Return responce
	echo $responce;
?>