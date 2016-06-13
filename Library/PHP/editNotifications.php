<?php
	session_start();
	$sender = $_SESSION["sender"];

	//Connect to Database
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {}

	$sql = "UPDATE pushTable$sender SET CHECKED='TRUE' WHERE CHECKED IS NULL";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	echo "READY";
?>