<?php
	session_start();
	$sender = $_SESSION["sender"];
	$senderFirst = $_SESSION['senderFN'];
	$senderLast = $_SESSION['senderLN'];
	if ( !isset($sender) ) { header("Location: ../../../index.php"); die(); }

	//Include bundle
	include "functions.php";

	//Get core variables
	$getStoryType = $_POST["story_type"];
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];

	$getTitle = secure_input($_POST["storyTitle"], false);
	$getLink = strip_tags($_POST["storyLink"]);
	$getContent = secure_input($_POST["storyContent"], false);

	$tableName = $getClubTable."_Story_".$getClubId;

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ( $getStoryType == "club" ) {
		$sql = "CREATE TABLE $tableName (
			ID int NOT NULL AUTO_INCREMENT,
			Story_Title LONGTEXT, 
			Story_Link LONGTEXT, 
			Story_Content LONGTEXT, 
			Likers LONGTEXT,
			Author LONGTEXT,
			PRIMARY KEY(ID))";
		$conn->query($sql);

		$sql = "INSERT INTO $tableName (
			Story_Title,
			Story_Link,
			Story_Content,
			Likers,
			Author) VALUES (
			'".$getTitle."',
			'".$getLink."',
			'".$getContent."',
			NULL,
			'".$sender."'
			)
			";
		$conn->query($sql);
	}

	//Get all members
	$sql = "SELECT Members FROM ".$getClubTable." WHERE ID=".$getClubId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getMembers = explode(",", $row["Members"]);	
		}
	}

	//Close the connection
	$conn->close();

	//Build message
	$message = "just published a story in the [link=!previewClub.php?".$getClubTable."=".$getClubId."!]club[/link]";

	//Send message
	if ( is_array($getMembers) ) {
		foreach ($getMembers as $member) {
			if ( $member != $sender) { 
				send_notification($sender, $member, $message); 

				$content = "Hello there!<br><a href='http://blogy.co?$sender' target='_blank'>@$senderFirst$senderLast</a> just published a story in your <b>Blogy</b> club.<br>Come and check it now! :-)";
				send_custom_mail($sender, $member, "New club story", "New story in your club", $content);
			}
		}
	}

	$responce = "POSTED";

	//Return responce
	echo $responce;
?>