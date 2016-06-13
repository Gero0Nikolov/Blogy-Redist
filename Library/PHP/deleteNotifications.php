<?php
	session_start();
	$sender = $_SESSION['sender'];
	
	//Connect to data base
	include "Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "DROP TABLE pushTable$sender";
		$conn->query($sql);
		
		$pageId = $_COOKIE['pageId'];
		header("Location: $pageId");
	}
?>