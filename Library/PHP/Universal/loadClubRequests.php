<?php
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Owner, Requesters FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubOwner = $row["Owner"];
			$getClubRequesters = $row["Requesters"];
		}
	}

	//Close the connection
	$conn->close();

	//Build responce
	$responce = "";

	if ( !empty($getClubRequesters) && isset($getClubRequesters) ) {
		foreach (explode(",", $getClubRequesters) as $requesterId) {
			if ($memberId != $getClubOwner) { $responce .= $requesterId.","; }
		}
	}

	//Return responce
	echo $responce;
?>