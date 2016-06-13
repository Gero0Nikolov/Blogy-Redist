<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$postTitle = htmlentities($_POST["postTitle"]);
	$postId = $_POST["postId"];
	
	//Connect to data base
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$tableName = $getClubTable."_Story_".$getClubId;
		$sql = "SELECT Story_Title, Story_Link, Story_Content FROM $tableName WHERE ID=".$postId;
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getTitle = html_entity_decode($row["Story_Title"]);
				$getLink = $row["Story_Link"];
				$getContent = html_entity_decode($row["Story_Content"]);
				$getContent = str_replace("<br />", "\r\n", $getContent);
			}
		}
	}
	$conn->close();
	
	$getBuild = "$getTitle$$getLink$$getContent";
	echo $getBuild;
?>