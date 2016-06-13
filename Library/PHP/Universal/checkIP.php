<?php
	//Include functions.php
	include "functions.php";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$getIp = get_client_ip();
	$isBanned = "FALSE";

	$sql = "SELECT BLACK_IP FROM Blacklist WHERE BLACK_IP='".$getIp."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$isBanned = "TRUE";
		}
	}

	//Close the connection
	$conn->close();

	//Return responce
	echo $isBanned;
?>