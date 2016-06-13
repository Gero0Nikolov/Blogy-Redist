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

	$sql = "SELECT LIKES FROM stack$user_id WHERE ID=$post_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$post_likes = explode( ",", $row["LIKES"] );
		}
	}

	if ( empty( $post_likes ) || !in_array( $sender, $post_likes ) ) {
		//Like the story
		if ( empty( $post_likes ) ) { $post_likes = $sender; }
		if ( !in_array( $sender, $post_likes ) ) { 
			//Trim likes
			$trim_likes = array();
			foreach ( $post_likes as $like ) { if ( !empty( $like ) ) { array_push( $trim_likes, $like ); } }
			array_push( $trim_likes, $sender );
		 	$post_likes = implode( ",", $trim_likes ); 
		}
		
		//Update the database
		$sql = "UPDATE stack$user_id SET LIKES='$post_likes' WHERE ID=$post_id";
		$conn->query( $sql );
		
		//Set response
		$likes_ = count( $trim_likes );
		$response = "liked%%".$likes_;

		//Send notification
		send_notification( $sender, $user_id, "$post_id#liked your story" );
	} elseif ( !empty( $post_likes ) || in_array( $sender, $post_likes ) ) {
		if ( in_array( $sender, $post_likes ) ) { 
			//Trim likes
			$trim_likes = array();
			foreach ( $post_likes as $like ) { if ( $like != $sender && !empty( $like ) ) { array_push( $trim_likes, $like ); } }
			$post_likes = implode( ",", $trim_likes );
			
			//Update the database
			$sql = "UPDATE stack$user_id SET LIKES='$post_likes' WHERE ID=$post_id";
			$conn->query( $sql );
			
			//Set response
			$likes_ = count( $trim_likes );
			$response = "disliked%%".$likes_;
		}
	}

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>