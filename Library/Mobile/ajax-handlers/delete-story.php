<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	$response = "READY";

	$post_id = $_POST["storyID"];
	if ( empty( $post_id ) ) { $response = "-1"; }

	$server_path = "/home/blogycoo/public_html/";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "DELETE FROM stack$sender WHERE ID=$post_id";
	$conn->query($sql);
	$sql = "DELETE FROM worldStories WHERE AuthorTitle LIKE '%$sender$$post_id$%'";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>