<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$postTitle = htmlentities($_POST['postTitle']);
	$postId = $_POST["postId"];
	
	//Connect to data base
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT STORYTITLE, STORYLINK, STORYCONTENT FROM stack$sender WHERE ID=$postId";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getTitle = html_entity_decode($row["STORYTITLE"]);
				$getLink = $row["STORYLINK"];
				$getContent = html_entity_decode($row["STORYCONTENT"]);
				$getContent = str_replace("<br />", "\r\n", $getContent);
			}
		}
	}
	$conn->close();
	
	$getBuild = "$getTitle~||~$getLink~||~$getContent";
	echo $getBuild;
?>