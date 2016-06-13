<?php
	$getIndex = explode(",", $_COOKIE["addIndex"]);

	//Connect to data base
	include "../PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		if (!is_array($getIndex)) {
			$sql = "UPDATE worldPlaces SET APPROVED=1 WHERE ID=$getIndex";
			$conn->query($sql);
		} else {
			foreach ($getIndex as $index) {
				$sql = "UPDATE worldPlaces SET APPROVED=1 WHERE ID=$index";
				$conn->query($sql);
			}
		}
	}

	//Close the connection
	$conn->close();

	echo "READY";
?>