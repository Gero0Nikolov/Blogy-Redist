<?php 
	$user_id = $_POST["userID"];

	if ( $user_id == "sender" ) {
		session_start();
		$user_id = $_SESSION["sender"];
	}

	$server_path = "/home/blogycoo/public_html/";

	$followers_ = array();
	$followers =  fopen( $server_path."Library/Authors/$user_id/FollowersID.html", "r") or die("Fatal: Could not get Followers.");
	while ( !feof( $followers ) ) {
		$line = trim( fgets( $followers ) );
		if ( !empty( $line ) ) { 
			$user_ = explode( "-", $line )[1];
			array_push( $followers_, $user_ ); 
		}
	}
	fclose( $followers );

	$response = implode( "%%", $followers_ );

	//Return response
	echo $response;
?>