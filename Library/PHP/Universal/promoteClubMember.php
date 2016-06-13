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
	$sql = "SELECT Owner, Administrators, Members, Promoted FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubOwner = $row["Owner"];
			$getClubAdministrators = explode(",", $row["Administrators"]);
			$getClubMembers = explode(",", $row["Members"]);
			$getClubPromotedMembers = explode(",", $row["Promoted"]);
		}
	}

	//Check if the arrays are arrays :O
	if ( !is_array($getClubAdministrators) ) { $getClubAdministrators = array(); }
	if ( !is_array($getClubMembers) ) { $getClubMembers = array(); }
	if ( !is_array($getClubPromotedMembers) ) { $getClubPromotedMembers = array(); }

	//Check if the requester is the owner of the club
	if ( $sender == $getClubOwner ) {
		if ( in_array($getMemberId, $getClubMembers) ) {
			if ( !in_array($getMemberId, $getClubAdministrators) && !in_array($getMemberId, $getClubPromotedMembers) ) {
				array_push($getClubAdministrators, $getMemberId);
				$getClubAdministrators = implode(",", $getClubAdministrators);

				$sql = "UPDATE ".$getClubTable." SET Administrators='".$getClubAdministrators."' WHERE ID=".$getClubId;
				$conn->query($sql);

				//Send notification to the user who was promoted
				send_club_notification($sender, $getClubTable, $getClubId, $getMemberId, $conn, "club admin promotion");
			
				//Set responce
				$responce = "READY";
			} elseif ( in_array($getMemberId, $getClubPromotedMembers) ) {
				$responce = "This user was already promoted !";
			} else {
				$responce = "This user is already an admin !";
			}
		}
	} else { //Send CR - Confirmation Request
		if ( !in_array($getMemberId, $getClubAdministrators) ) {
			//Check if already promoted
			if ( !in_array($getMemberId, $getClubPromotedMembers) ) {
				array_push($getClubPromotedMembers, $getMemberId);

				//Revice array
				$reviceClubPromotedMembers = array();
				foreach ($getClubPromotedMembers as $promotion) {
					if ( !empty($promotion) ) {
						array_push($reviceClubPromotedMembers, $promotion);
					}	
				}

				$getClubPromotedMembers = implode(",", $reviceClubPromotedMembers);

				//Update club promotions
				$sql = "UPDATE ".$getClubTable." SET Promoted='".$getClubPromotedMembers."' WHERE ID=".$getClubId;
				$conn->query($sql);

				//Send notification and email to the owner of the club
				send_club_notification($sender, $getClubTable, $getClubId, $getClubOwner, $conn, "promoted as admin");
				send_custom_mail($sender, $getMemberId, "club_promotion", "", "");

				//Set responce
				$responce = "READY";
			} else {
				$responce = "This user was already promoted !";
			}

		} else {
			$responce = "This user is already an admin !";
		}
	}

	//Close the connection
	$conn->close();

	//Return responce
	echo $responce;
?>