<?php
	session_start();
	$sender = $_SESSION['sender'];

	$getOwnerId = $_POST['postAuthor'];
	$getPostId = $_POST['postId'];

	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, LIKES FROM stack$getOwnerId WHERE ID=$getPostId";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getLikes = $row["LIKES"];
		}
	}

	$convertLikers = explode(",", $getLikes);
	if (count($convertLikers) == 1 && $convertLikers[0] == "") $convertLikers = array();

	if (!empty($convertLikers)) {
		if (in_array($sender, $convertLikers)) {
			$liked = 1;
			$tmp_container = array();
			foreach ($convertLikers as $liker) {
				if ($liker != $sender) {
					array_push($tmp_container, $liker);
				}
			}

			unset($convertLikers);
			$convertLikers = array();

			foreach ($tmp_container as $liker) {
				array_push($convertLikers, $liker);
			}
		} else {
			array_push($convertLikers, $sender);
		}
	} else {
		array_push($convertLikers, $sender);
	}

	$countLikes = count($convertLikers);

	if (!empty($convertLikers))
		$convertLikers = implode(",", $convertLikers);
	else
		$convertLikers = NULL;

	$sql = "UPDATE stack$getOwnerId SET LIKES='$convertLikers' WHERE ID='$getPostId'";
	$conn->query($sql);

	//Add notification if needed
	if (!isset($liked)) {
		$sql = "CREATE TABLE pushTable$getOwnerId (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			buildNotification($sender, $getOwnerId, $conn, $getPostId);
		} else {
			buildNotification($sender, $getOwnerId, $conn, $getPostId);				
		}
	}

	//Close the connection
	$conn->close();

	//Check if mail is needed
	if (!isset($liked)) {
		//Get and send mail
		$lineCount = 0;
		$getConfig = fopen("../../Authors/$getOwnerId/config.txt", "r") or die("Failed to read config.");
		while (!feof($getConfig)) {
			$line = trim(fgets($getConfig));

			if ($lineCount == 6) {
				$getMail = trim($line);
				break;
			}

			$lineCount++;
		}
		fclose($getConfig);

		$msg = "Hello there your friend just liked your story, come and see what's new in Blogy :-)\r\nCheck it from here: http://".$_SERVER['HTTP_HOST'];
		$subject = "Liked story";
		mail($getMail, $subject, $msg);
	}


	//Build notifications functions
	function buildNotification($sender, $followerID, $conn, $postId) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', '$postId#liked your story', '$date')";
		$conn->query($sql);
	}



	//Return a responce.
	echo $countLikes;
?>