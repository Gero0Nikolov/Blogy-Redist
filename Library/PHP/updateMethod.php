<?php
	session_start();
	$sender = $_SESSION["sender"];

	$postTitle = trim($_POST["postTitle"]);
	$postTitle = str_replace(";", "", $postTitle);
	$postTitle = str_replace("\"", "``", $postTitle);
	$postTitle = str_replace("'", "`", $postTitle);
	$postTitle = str_replace(" ", "6996", $postTitle);
	if (is_numeric($postTitle)) {
		$postTitle = "-id-$postTitle";
	}
	$postTitle = htmlentities($postTitle);

	$postId = trim($_POST["postId"]);

	$postImg = $_POST["postLink"];
	$postImg = strip_tags($postImg);

	$postContent = trim($_POST["postContent"]);
	if ($postContent != "") {
		$postContent = str_replace("<br />", "\r\n", $postContent);
		$postContent = str_replace("'", "`", $postContent);
		$postContent = htmlentities($postContent);
	} else {
		$postContent = NULL;
	}

	//Connect to data base
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "UPDATE stack$sender SET STORYTITLE='$postTitle',STORYLINK='$postImg', STORYCONTENT='$postContent' WHERE ID='$postId'";
		$conn->query($sql);
		$sql = "UPDATE worldStories SET AuthorTitle='$sender$$postId$$postTitle', LINK='$postImg', POST='$postContent' WHERE AuthorTitle LIKE '%$sender$$postId$%'";
		$conn->query($sql);
	}
	$conn->close();

	echo "READY";
?>