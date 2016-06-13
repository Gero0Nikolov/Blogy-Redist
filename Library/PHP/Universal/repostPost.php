<?php
	session_start();
	$sender = $_SESSION["sender"];

	$getPostId = $_POST["postId"];
	$getAuthorId = $_POST["authorId"];

	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Pull the post from the blogger
	$sql = "SELECT ID, STORYTITLE, STORYLINK, STORYCONTENT FROM stack$getAuthorId WHERE ID=$getPostId";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getTitle = $row["STORYTITLE"];
			$getLink = $row["STORYLINK"];
			$getContent = $row["STORYCONTENT"];
			
			$found = 1;
		}
	}

	//Push the post in the own database
	if (isset($found)) {
		if ( !strpos($getContent, "reposted]") ){ $getContent .= "[reposted]".$getAuthorId."[/reposted]"; }

		$dateTime = date("d.m.Y-H:i:s");

		//Commit to own blog
		$sql = "INSERT INTO stack$sender (DATETIME, STORYTITLE, STORYLINK, STORYCONTENT) VALUES ('$dateTime', '$getTitle', '$getLink', '$getContent')";
		$conn->query($sql);

		//Get the ID from author stories
		$store_post_id = $conn->insert_id;
		
		//Push to the world stories
		$sql = "INSERT INTO worldStories (AuthorTitle, LINK, POST) VALUES ('$sender$$store_post_id$$getTitle', '$getLink', '$getContent')";
		$conn->query($sql);

		//Create and send notification to the author
		$sql = "CREATE TABLE pushTable$getAuthorId (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			buildNotification($sender, $getAuthorId, $conn, $getPostId);
		} else {
			buildNotification($sender, $getAuthorId, $conn, $getPostId);				
		}

		//Get and send mail
		$lineCount = 0;
		$getConfig = fopen("../../Authors/$getAuthorId/config.txt", "r") or die("Failed to read config.");
		while (!feof($getConfig)) {
			$line = trim(fgets($getConfig));

			if ($lineCount == 6) {
				$getMail = trim($line);
				break;
			}

			$lineCount++;
		}
		fclose($getConfig);

		$msg = "Hello there a blogger just repost your story, come and see what's new in Blogy :-)\r\nCheck it from here: http://".$_SERVER['HTTP_HOST'];
		$subject = "Reposted story";
		mail($getMail, $subject, $msg);

		$response = "READY";
	} else { $response = "BAD"; }

	//Close the connection
	$conn->close();

	//Build notifications functions
	function buildNotification($sender, $followerID, $conn, $postId) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', '$postId#reposted your story', '$date')";
		$conn->query($sql);
	}

	//Return response
	echo $response;
?>