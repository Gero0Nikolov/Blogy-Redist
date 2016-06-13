<?php
	//Connect to the Database
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "ALTER TABLE WorldBloggers ADD Birthdate LONGTEXT";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>