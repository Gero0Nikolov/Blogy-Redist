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

	$clubOwner = explode("_", $getClubTable)[0];

	if ( $getMemberId == $clubOwner ) {
		$responce = "CDO";
	} else {
		//Connect to the database
		include "dataBase.php";

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT Administrators, Members, Invited, Promoted FROM ".$getClubTable." WHERE ID=".$getClubId;
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getClubAdministrators = explode(",", $row["Administrators"]);
				$getClubMembers = explode(",", $row["Members"]);
				$getClubInvites = explode(",", $row["Invited"]);
				$getClubPromotions = explode(",", $row["Promoted"]);
			}
		}

		if ( !is_array($getClubAdministrators) ) { $getClubAdministrators = array(); }
		if ( !is_array($getClubMembers) ) { $getClubMembers = array(); }
		if ( !is_array($getClubInvites) ) { $getClubInvites = array(); }
		if ( !is_array($getClubPromotions) ) { $getClubPromotions = array(); }

		//Revise club administrators
		$reviseClubAdministrators = array();
		foreach ($getClubAdministrators as $administrator) {
			if ( $administrator != $getMemberId ) {
				array_push($reviseClubAdministrators, $administrator);
			}
		}
		if ( empty($reviseClubAdministrators) ) { $reviseClubAdministrators = NULL; }
		elseif ( !empty($reviseClubAdministrators) ) { $reviseClubAdministrators = implode(",", $reviseClubAdministrators); } 

		//Update club administrators
		$sql = "UPDATE ".$getClubTable." SET Administrators='".$reviseClubAdministrators."' WHERE ID=".$getClubId;
		$conn->query($sql);

		//Revise club members
		$reviseClubMembers = array();
		foreach ($getClubMembers as $member) {
			if ($member != $getMemberId) {
				array_push($reviseClubMembers, $member);
			}
 		}
 		if ( empty($reviseClubMembers) ) { $reviseClubMembers = NULL; }
 		elseif( !empty($reviseClubMembers) ) { $reviseClubMembers = implode(",", $reviseClubMembers); }

 		//Revise club invites
 		$reviseClubInvites = array();
 		foreach ($getClubInvites as $invited) {
 			if ($invited != $getMemberId) {
 				array_push($reviseClubInvites, $invited);
 			}
 		}
 		if ( empty($reviseClubInvites) ) { $reviseClubInvites = NULL; }
 		elseif ( !empty($reviseClubInvites) ) { $reviseClubInvites = implode(",", $reviseClubInvites); }

 		//Update club members and invited bloggers
 		$sql = "UPDATE ".$getClubTable." SET Members='".$reviseClubMembers."', Invited='".$reviseClubInvites."' WHERE ID=".$getClubId;
 		$conn->query($sql);

 		//Revise club promotions
 		$reviseClubPromotions = array();
 		foreach ($getClubPromotions as $promotion) {
 			if ( $promotion != $getMemberId ) {
 				array_push($reviseClubPromotions, $promotion);
 			}
 		}
 		if ( empty($reviseClubPromotions) ) { $reviseClubPromotions = NULL; }
 		elseif ( !empty($reviseClubPromotions) ) { $reviseClubPromotions = implode(",", $reviseClubPromotions); }

 		//Update club promotions
 		$sql = "UPDATE ".$getClubTable." SET Promoted='".$reviseClubPromotions."' WHERE ID=".$getClubId;
 		$conn->query($sql);

 		//Update the member Membershiped Clubs table
 		$table_name = $getMemberId."_Membershiped_Clubs";
 		$sql = "DELETE FROM ".$table_name." WHERE Club_Table='".$getClubTable."' AND Club_Id=".$getClubId;
 		$conn->query($sql);

		//Close the connection
		$conn->close();

		$responce = "READY";
	}

	//Return responce
	echo $responce;
?>