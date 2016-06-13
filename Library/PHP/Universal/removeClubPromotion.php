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

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Promoted FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubPromotedMembers = explode(",", $row["Promoted"]);
		}
	}

	if ( !is_array($getClubPromotedMembers) ) { $getClubPromotedMembers = array(); }

	//Revise club promotions
	$reviseClubPromotions = array();
	foreach ($getClubPromotedMembers as $promotion) {
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