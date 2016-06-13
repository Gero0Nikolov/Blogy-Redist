<?php
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, Visits FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getVisits = $row["Visits"];
		}
	}

	//Close the connetion
	$conn->close();

	//Calculate members
	$responce = $getVisits;

	if ($responce > 1 || $responce < 1) { $attachment = "visits"; }
	else
	if ($responce == 1) { $attachment = "visit"; }

	//Return responce
	echo $responce." ".$attachment;
?>