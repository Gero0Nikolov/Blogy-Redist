<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	
	$postTitle = htmlentities($_POST["postTitle"]);
	$postId = $_POST["postId"];
	
	//Connect to data base
	include "../Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "DELETE FROM stack$sender WHERE ID='$postId'";
		$conn->query($sql);
		$sql = "DELETE FROM worldStories WHERE AuthorTitle LIKE '%$sender$$postId$%'";
		$conn->query($sql);
	}
	$conn->close();

	echo "READY";
?>