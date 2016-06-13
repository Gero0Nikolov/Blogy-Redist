<?php
	$getIndex = explode(",", $_COOKIE["deleteIndex"]);

	//Connect to data base
	include "../PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		if (!is_array($getIndex)) {
			$sql = "DELETE FROM worldPlaces WHERE ID=$getIndex";
			$conn->query($sql);
		} else {
			foreach ($getIndex as $index) {
				$sql = "DELETE FROM worldPlaces WHERE ID=$index";
			$conn->query($sql);
			}
		}
	}

	//Close the connection
	$conn->close();

	echo "READY";
?>