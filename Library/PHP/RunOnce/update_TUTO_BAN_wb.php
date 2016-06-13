<?php
	//Connect to the Database
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE WorldBloggers SET BAN=0, TUTO=1";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>