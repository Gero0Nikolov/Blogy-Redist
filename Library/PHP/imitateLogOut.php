<?php
	session_start();
	$sender = $_SESSION["sender"];

	//Connect to data base
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Remove from TABLE
		$sql = "DELETE FROM logedUsers WHERE USERID='$sender'";
		$conn->query($sql);
	}
	$conn->close();
?>