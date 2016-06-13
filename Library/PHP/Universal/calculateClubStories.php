<?php
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	$clubOwner = explode("_", $_POST["clubTable"])[0];

	$collectStories = 0; //Count stories

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, Club_Slug FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getSlug = $row["Club_Slug"];
		}
	}

	$table_name = $getClubTable."_Story_".$getClubId;

	//Check if this is first club of the user
	$sql = "SELECT ID FROM ".$table_name;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$collectStories++;
		}
	} else {
		$collectStories = 0;
	}

	//Close the connetion
	$conn->close();

	//Calculate members
	$responce = $collectStories;

	if ($responce > 1 || $responce < 1) { $attachment = "stories"; }
	else
	if ($responce == 1) { $attachment = "story"; }

	//Return responce
	echo $responce." ".$attachment;
?>