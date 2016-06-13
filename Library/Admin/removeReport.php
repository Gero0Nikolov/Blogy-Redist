<?php
	$getIndex = $_POST["deleteIndex"];

	//Connect to data base
	include "../PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "DELETE FROM worldReports WHERE ID=$getIndex";
		$conn->query($sql);
	}

	//Close the connection
	$conn->close();

	echo "READY";
?>