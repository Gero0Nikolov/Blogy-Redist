<?php
	include "../Universal/dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$getUsers = fopen("../../Authors/Info.csv", "r") or die("Failed to open users container.");

	while (!feof($getUsers)) {
		$userInfo = fgetcsv($getUsers);
		$tableName = "stack".$userInfo[1];

		//Create and execute the SQL selector
		$sql = "ALTER TABLE $tableName ADD LIKES LONGTEXT";
		$conn->query($sql);
	}

	fclose($getUsers);

	//End connections
	$conn->close();
?>