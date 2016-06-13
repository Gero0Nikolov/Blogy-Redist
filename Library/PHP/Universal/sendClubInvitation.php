<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
	}

	$clubTable = $_POST["clubTable"];
	$clubId = $_POST["clubId"];
	$bloggerId = $_POST["bloggerId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get current invitations
	$sql = "SELECT Invited FROM ".$clubTable." WHERE ID=".$clubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubInvites = explode(",", $row["Invited"]);
		}
	}
	if ( !is_array($getClubInvites) && empty($getClubInvites) ) { $getClubInvites = array(); }
	array_push($getClubInvites, $bloggerId);

	//Revise array
	$clubInvitesRevision = array();
	foreach ($getClubInvites as $invitation) {
		if ( !empty($invitation) && isset($invitation) ) { array_push($clubInvitesRevision, $invitation); }
	}

	$getClubInvites = implode(",", $clubInvitesRevision);

	//Add the bloger to the Invited column of the club
	$sql = "UPDATE ".$clubTable." SET Invited='".$getClubInvites."' WHERE ID=".$clubId;
	$conn->query($sql);

	//Send notification to the blogger
	include "functions.php";
	send_club_notification($sender, $clubTable, $clubId, $bloggerId, $conn, "club_invitation");
	send_custom_mail($sender, $bloggerId, "club_invitation", "", "");

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>