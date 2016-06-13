<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die( "Fatal: No logged in user." ); }

	$user_id = $_POST["userID"];
	if ( empty( $user_id ) ) { die( "Fatal: No user ID." ); }

	$post_id = $_POST["storyID"];
	if ( empty( $post_id ) ) { die( "Fatal: No story ID." ); }

	$server_path = "/home/blogycoo/public_html/";

	//Include functions.php
	include $server_path."Library/PHP/Universal/functions.php";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get the story from the visited user
	$sql = "SELECT STORYTITLE, STORYLINK, STORYCONTENT FROM stack$user_id WHERE ID=$post_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$post_title = $row["STORYTITLE"];
			$post_link = $row["STORYLINK"];
			$post_content = $row["STORYCONTENT"];
		}
	}

	//Build the new story
	if ( !strpos( $post_content, "reposted]" ) ) { $post_content .= "[reposted]".$user_id."[/reposted]"; }
	$dateTime = date("d.m.Y-H:i:s");

	//Add to the personal story
	$sql = "INSERT INTO stack$sender (DATETIME, STORYTITLE, STORYLINK, STORYCONTENT) VALUES ('$dateTime', '$post_title', '$post_link', '$post_content')";
	$conn->query($sql);

	//Get the ID of the newly posted story
	$store_post_id = $conn->insert_id;

	//Add to the world stories
	$sql = "INSERT INTO worldStories (AuthorTitle, LINK, POST) VALUES ('$sender$$store_post_id$$post_title', '$post_link', '$post_content')";
	$conn->query($sql);

	//Send notification
	send_notification( $sender, $user_id, "$post_id#reposted your story" );

	//Close the connection
	$conn->close();

	//Return response
	echo "reposted";
?>