<?php
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Visits FROM ".$getClubTable." WHERE  ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getVisits = $row["Visits"];
		}
	}

	//Add the new visit
	$getVisits += 1;

	//Update the visits
	$sql = "UPDATE ".$getClubTable." SET Visits=".$getVisits." WHERE ID=".$getClubId;
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Set responce
	$responce = "READY";

	//Return responce
	echo $responce;
?>