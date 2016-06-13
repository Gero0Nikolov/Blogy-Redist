<?php 
	session_start();
	$sender = $_SESSION[ "sender" ];
	if ( !isset( $sender ) ) { die(); }
	$senderFN = $_SESSION[ "senderFN" ];
	$senderLN = $_SESSION[ "senderLN" ];

	$user_id = $_POST[ "userID" ];
	if ( !isset( $user_id ) || empty( $user_id ) ) { die(); }

	//Server path
	$server_path = "/home/blogycoo/public_html/";

	//Include functions bundle
	include $server_path."Library/PHP/Universal/functions.php";

	//Parse the e-mail of the currently logged user
	$line_counter = 0;
	$sender_config_handler = fopen( $server_path ."Library/Authors/$sender/config.txt", "r" ) or die();
	while ( !feof( $sender_config_handler ) ) {
		$line = trim( fgets( $sender_config_handler ) );
		if ( $line_counter == 6 ) {
			$sender_email = $line;
			break;
		}
		$line_counter += 1;
	}
	fclose( $sender_config_handler );

	//Follow / Unfollow the user
	$user_followers = array();
	$followed_flag = 0;

	$src_ = $server_path ."Library/Authors/$user_id/FollowersID.html";
	if ( file_exists( $src_ ) ) {
		$user_followers_handler = fopen( $src_, "r" ) or die();
		while ( !feof( $user_followers_handler ) ) {
			$follower_ = trim( fgets( $user_followers_handler ) );
			if ( !empty( $follower_ ) ) {
				$follower_id = explode( "-", $follower_ )[1];
				if ( $follower_id != $sender ) { array_push( $user_followers, $follower_); }
				elseif ( $follower_id == $sender ) { $followed_flag = 1; break; }
			}
		}
		fclose( $user_followers_handler );
	}

	if ( $followed_flag == 0 ) { array_push( $user_followers, $sender_email ."-". $sender ); }

	//Update the user followers
	$followers_handler = fopen( $src_, "w" ) or die();
	foreach( $user_followers as $follower_ ) {
		fwrite( $followers_handler, $follower_.PHP_EOL );
	}
	fclose( $followers_handler );

	//Revise followed users of the currently logged in user
	$user_following = array();

	$src_ = $server_path ."Library/Authors/$sender/Following.txt";
	if ( file_exists( $src_ ) ) {
		$user_following_handler = fopen( $src_, "r" ) or die();
		while ( !feof( $user_following_handler ) ) {
			$following_ = trim( fgets( $user_following_handler ) );
			if ( !empty( $following_ ) ) { if ( $following_ != $user_id ) { array_push( $user_following, $following_ ); } }
		}
		fclose( $user_following_handler );
	}

	if ( $followed_flag == 0 ) { array_push( $user_following, $user_id ); }

	//Update the user followed bloggers
	$following_handler = fopen( $src_, "w" ) or die();
	foreach ( $user_following as $following_ ) {
		fwrite( $following_handler, $following_.PHP_EOL );
	}
	fclose( $following_handler );

	//Send notification to the target
	if ( $followed_flag == 0 ) {
		send_notification( $sender, $user_id, "started following you" );

		$subject = "New follower";
		$content = "Hello there. $senderFN $senderLN just started following you. ";
		send_custom_mail( $sender, $user_id,"", $subject, $content );
	}

	//Return response
	echo "READY";
?>