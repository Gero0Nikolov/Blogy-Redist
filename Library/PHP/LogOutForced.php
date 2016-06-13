<?php
	session_start();
	$sender = $_POST['sender'];

	//Connect to data base
	include "Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Delate old TABLE
		$sql = "DROP TABLE $sender";
		$conn->query($sql);
	}
	$conn->close();
?>