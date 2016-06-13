<?php 
	session_start();
	$sender = $_SESSION[ "sender" ];
	if ( !isset( $sender ) ) { die(); }

	$user_id = $_POST[ "userID" ];
	if ( !isset( $sender ) || empty( $user_id ) ) { die(); }

	//Server path
	$server_path = "/home/blogycoo/public_html/";

	// Followed flag
	$followed_flag = 0;

	// Read followers
	$src_ = $server_path ."Library/Authors/$user_id/FollowersID.html";
	
	if ( file_exists( $src_ ) ) {
		$followers_handler = fopen( $src_, "r" ) or die();
		while ( !feof( $followers_handler ) ) {
			$follower_ = trim( fgets( $followers_handler ) );
			if ( !empty( $follower_ ) ) { $follower_id = explode( "-", $follower_ )[1]; }
			if ( $follower_id == $sender ) { $followed_flag = 1; break; }
		}
		fclose( $followers_handler );
	}

	// Build the button
	if ( $followed_flag == 0 ) {
		$response = "<button class='follow-button' onclick='followUnfollow(\"$user_id\");'>Follow</button>";
	} elseif ( $followed_flag == 1 ) {
		$response = "<button class='unfollow-button' onclick='followUnfollow(\"$user_id\");'>Unfollow</button>";
	}

	// Return response
	echo $response;
?>