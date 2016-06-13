<?php 
	$user_id = $_POST["userID"];

	if ( $user_id == "sender" ) {
		session_start();
		$user_id = $_SESSION["sender"];
	}

	$server_path = "/home/blogycoo/public_html/";

	$followers_count = -1;
	$followers =  fopen( $server_path."Library/Authors/$user_id/FollowersID.html", "r") or die("Fatal: Could not get Followers.");
	while ( !feof( $followers ) ) {
		$followers_count += 1;
		$line = trim( fgets( $followers ) );
	}
	fclose( $followers );

	$response = "";
	if ( $followers_count == 1 ) {
		$response = "<h2 class='followers'>$followers_count follower</h2>";
	} else {
		$response = "<h2 class='followers'>$followers_count followers</h2>";
	}

	echo $response;
?>