<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { header("../../../index.php"); die(); }

	$getClubInformation = $_POST["clubInformation"];
	$clubTable = explode("=", $getClubInformation)[0];
	$clubId = explode("=", $getClubInformation)[1];

	//Include functions bundle
	include "functions.php";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Club_Slug, Owner, Members FROM ".$clubTable." WHERE ID=".$clubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$clubSlug = $row["Club_Slug"];
			$clubOwner = $row["Owner"];
			$clubMembers = explode(",", $row["Members"]);
		}
	}

	//Check if array
	if ( !is_array($clubMembers) ) { $clubMembers = array(); }

	//Check if the requester is club owner
	if ( $sender == $clubOwner ) {
		//Remove club from the World Clubs
		$sql = "DELETE FROM worldClubs WHERE Club_Slug='".$clubSlug."' AND Owner_ID='".$clubOwner."'";
		$conn->query($sql);

		//Remove club from owner clubs
		$sql = "DELETE FROM ".$clubTable." WHERE ID=".$clubId;
		$conn->query($sql);

		//Remove the club from members memberships
		foreach ($clubMembers as $member) {
			$sql = "DELETE FROM ".$member."_Membershiped_Clubs WHERE Club_Table='".$clubTable."' AND Club_Id='".$clubId."'";
			$conn->query($sql);
		}

		//Delete the club story
		$sql = "DROP TABLE ".$clubTable."_Story_".$clubId;
		$conn->query($sql);

		//Delete club comments
		$sql = "DROP TABLE ".$clubTable."_Story_Comments_".$clubId;
		$conn->query($sql);

		$responce = "READY";
	} elseif ( $sender != $clubOwner ) {
		send_club_notification($sender, $clubTable, $clubId, $clubOwner, $conn, "delete club request");	
		$responce = "DELRS";
	}

	//Close the connection
	$conn->close();

	echo $responce;
?>