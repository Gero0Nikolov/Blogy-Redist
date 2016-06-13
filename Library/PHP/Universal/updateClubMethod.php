<?php
	session_start();
	$sender = $_SESSION["sender"];

	//Include bundle
	include "functions.php";

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	$postTitle = secure_input($_POST["postTitle"], false);

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
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		if (is_numeric($postTitle)) $postTitle = "-id-".$postTitle;

		$tableName = $getClubTable."_Story_".$getClubId;
		$sql = "UPDATE $tableName SET Story_Title='$postTitle',Story_Link='$postImg', Story_Content='$postContent' WHERE ID=".$postId;
		$conn->query($sql);
	}
	$conn->close();

	echo "READY";
?>