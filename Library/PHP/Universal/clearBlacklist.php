<?php
	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "TRUNCATE TABLE Blacklist";
	$conn->query($sql);

	//Close the connection
	$conn->close();
?>