<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$postId = $_POST["postId"];

	$getComment = trim($_POST["commentContent"]);
	$getComment = htmlentities($getComment);
	$getComment = str_replace("'", "`", $getComment);

	$tableName = $getClubTable."_Story_Comments_".$getClubId;

	//Include bundle
	include "functions.php";
	include "dataBase.php";

	//Connect to the Database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "INSERT INTO $tableName (Post_Id, Post_Author, Post_Content) VALUES ('$postId', '$sender', '$getComment')";
	$conn->query($sql);

	//Get story author
	$tableName = $getClubTable."_Story_".$getClubId;
	$sql = "SELECT Author FROM $tableName WHERE ID=$postId";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$post_author = $row["Author"];
		}
	}

	//Close the connection
	$conn->close();

	//Send notification to the post author
	if ( $sender != $post_author ) {
		$message = "just commented your [link=!previewClub.php?".$getClubTable."=".$getClubId."&open_s=".$postId."!]story[/link] in the [link=!previewClub.php?".$getClubTable."=".$getClubId."!]club[/link]";
		send_notification( $sender, $post_author, $message );
	}

	//Set responce
	$responce = "READY";

	//Return responce
	echo $responce;
?>