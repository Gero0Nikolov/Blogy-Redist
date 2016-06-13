<?php
	$getId = $_POST["authorId"];

	//Connect to data base
	include "../PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "INSERT INTO grantedPermissions (ADMIN) VALUES ('$getId')";
		$conn->query($sql);
	}

	//Close the connection
	$conn->close();

	echo "READY";
?>