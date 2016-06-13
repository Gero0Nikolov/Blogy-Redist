<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
		die();
	}

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

	//Get needed information
	$sql = "SELECT Administrators, Promoted FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubAdministrators = explode(",", $row["Administrators"]);
			$getClubPromotedMembers = explode(",", $row["Promoted"]);
		}
	}

	//Check if the arrays are arrays :O
	if ( !is_array($getClubAdministrators) ) { $getClubAdministrators = array(); }
	if ( !is_array($getClubPromotedMembers) ) { $getClubPromotedMembers = array(); }

	//Add the member to the administrators
	array_push($getClubAdministrators, $getMemberId);
	$getClubAdministrators = implode(",", $getClubAdministrators);

	$sql = "UPDATE ".$getClubTable." SET Administrators='".$getClubAdministrators."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Send notification to the user who was promoted
	send_club_notification($sender, $getClubTable, $getClubId, $getMemberId, $conn, "club admin promotion");

	//Revise the club promotions
	$reviseClubPromotions = array();
	foreach ($reviseClubPromotions as $promotion) {
		if ( $promotion != $getMemberId ) {
			array_push($reviseClubPromotions, $promotion);
		}
	}
	if ( empty($reviseClubPromotions) ) { $reviseClubPromotions = NULL; }
	elseif ( !empty($reviseClubPromotions) ) { $reviseClubPromotions = implode(",", $reviseClubPromotions); }

	//Update club promotions
	$sql = "UPDATE ".$getClubTable." SET Promoted='".$reviseClubPromotions."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>