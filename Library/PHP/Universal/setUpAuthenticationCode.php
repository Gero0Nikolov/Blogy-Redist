<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getAuthenticationCode = htmlentities( $_POST["authCode"] );

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE WorldBloggers SET Save_Code='".$getAuthenticationCode."' WHERE Author_UID='".$sender."'";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>