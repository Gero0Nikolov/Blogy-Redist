<?php
	$authorId = $_POST['authorId'];

	//Connect to the Database
	include "../PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE WorldBloggers SET BAN=0 WHERE Author_UID='$authorId'";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>