<?php
	$getIndex = $_POST["authorId"];

	//Connect to data base
	include "../PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "DELETE FROM grantedPermissions WHERE ID=$getIndex";
		$conn->query($sql);
	}

	//Close the connection
	$conn->close();

	echo "READY";
?>