<?php
	session_start();
	$sender = $_SESSION["sender"];
	$postId = $_POST["postId"];

	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, LIKES FROM stack$sender WHERE ID=$postId";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getLikes = $row["LIKES"];
		}
	}

	//Close the connection
	$conn->close();

	echo $getLikes;
?>