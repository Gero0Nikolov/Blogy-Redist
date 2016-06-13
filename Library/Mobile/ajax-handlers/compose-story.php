<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	$sender_first_name = $_SESSION["senderFN"];
	$sender_last_name = $_SESSION["senderLN"];

	$story_id = $_POST["storyID"];
	$story_title = $_POST["storyTitle"];
	$story_link = $_POST["storyLink"];
	$story_content = $_POST["storyContent"];

	//Clear the input
	$story_title = str_replace( "'", "`", htmlentities( trim( $story_title ) ) );	
	$story_link = str_replace( "'", "`", htmlentities( trim( $story_link ) ) );
	$story_content = str_replace( "'", "`", htmlentities( trim( $story_content ) ) );

	$server_path = "/home/blogycoo/public_html/";

	//Include functions bundle
	include $server_path."Library/PHP/Universal/functions.php";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ( empty( $story_id ) || $story_id == -1 ) {
		$date_ = date("d.m.Y-H:i:s");

		//Push to author stories
		$sql = "INSERT INTO stack$sender (DATETIME, STORYTITLE, STORYLINK, STORYCONTENT) VALUES ('$date_', '$story_title', '$story_link', '$story_content')";
		$conn->query($sql);

		//Get the ID from author stories
		$store_post_id = $conn->insert_id;

		//Push to world stories
		$sql = "INSERT INTO worldStories (AuthorTitle, LINK, POST) VALUES ('$sender$$store_post_id$$story_title', '$story_link', '$story_content')";
		$conn->query($sql);
	} else {
		$sql = "UPDATE stack$sender SET STORYTITLE='$story_title', STORYLINK='$story_link', STORYCONTENT='$story_content' WHERE ID='$story_id'";
		$conn->query($sql);

		$sql = "UPDATE worldStories SET AuthorTitle='$sender$$story_id$$story_title', LINK='$story_link', POST='$story_content' WHERE AuthorTitle LIKE '%$sender$$story_title$%'";
		$conn->query($sql);
	}

	//Close the connection
	$conn->close();

	//Send notification to the followers if this is a new story
	if ( empty( $story_id ) || $story_id == -1 ) {
		$followers_handler = fopen( $server_path."Library/Authors/$sender/FollowersID.html", "r" );
		while( !feof( $followers_handler ) ) {
			$follower = trim( explode( "-", trim( fgets( $followers_handler ) ) )[1] );
			if ( !empty( $follower ) ) {
				send_notification( $sender, $follower, "just shared a story" );

				$subject = "New story published in Blogy";
				$content = "Hello there, your friend $sender_first_name $sender_last_name just published a new story!<br> Come and check it in Blogy :-)";
				send_custom_mail( $sender, $follower, "new_story", $subject, $content );
			}
		}
		fclose( $followers_handler );
	}

	echo "READY";
?>