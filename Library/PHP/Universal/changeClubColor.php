<?php
	$getColor = $_POST["color"];
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	$isMobile = $_POST["mobilie"];

	//Connect to the Database
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE ".$getClubTable." SET Club_Color='".$getColor."' WHERE ID=".$getClubId;
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>