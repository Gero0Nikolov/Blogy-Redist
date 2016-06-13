<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { header("Location: http://".$_SERVER["HTTP_HOST"]); die(); }

	//Collect the variables
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "DELETE FROM ".$sender."_Membershiped_Clubs WHERE Club_Table='".$getClubTable."' AND Club_Id=".$getClubId;
	$conn->query($sql);

	$sql = "SELECT Administrators, Members FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$clubAdministrators = explode(",", $row["Administrators"]);
			$clubMembers = explode(",", $row["Members"]);
		}
	}

	//Check if the objects are arrays
	if ( !is_array($clubAdministrators) ) { $clubAdministrators = array(); }
	if ( !is_array($clubMembers) ) { $clubMembers = array(); }

	//Revise Administrators
	$reviseAdministrators = array();
	foreach ($clubAdministrators as $administrator) {
		if ( $administrator != $sender ) {
			array_push($reviseAdministrators, $administrator);
		}
	}
	if ( !empty($reviseAdministrators) ) { $reviseAdministrators = implode(",", $reviseAdministrators); }
	elseif ( empty($reviseAdministrators) ) { $reviseAdministrators = NULL; }

	//Revise Members
	$reviseMembers = array();
	foreach ($clubMembers as $member) {
		if ( $member != $sender ) {
			array_push($reviseMembers, $member);
		}
	}
	if ( !empty($reviseMembers) ) { $reviseMembers = implode(",", $reviseMembers); }
	elseif ( empty($reviseMembers) ) { $reviseMembers = NULL; }

	//Update Administrators & Members
	$sql = "UPDATE ".$getClubTable." SET Administrators='".$reviseAdministrators."', Members='".$reviseMembers."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Set responce
	$responce = "READY";

	//Return responce
	echo $responce;
?>