<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	//Include bundle
	include "functions.php";

	$user_image = htmlentities( $_POST["profile_image"] );
	$user_image = str_replace( "'", "&#39;", $user_image);
	if ( empty( $user_image ) ) { $user_image = "default"; }

	$social_profile = secure_input( $_POST["social_profile"] );
	if ( empty( $social_profile ) ) { $social_profile = "NULL"; }

	$authentication_code = htmlentities( $_POST["authentication_code"] );
	$authentication_code = str_replace( "'", "&#39;", $authentication_code);

	$hobbies = secure_input( $_POST["hobbies"] );
	$birthdate = secure_input( $_POST["birth_date"] );

	$config_ = "../../Authors/$sender/config.txt";

	$line_counter = 0;
	$read_ = fopen( $config_, "r" );
	while( !feof( $read_ ) ) {
		$line_ = trim( fgets( $read_ ) );
		if ( $line_counter == 0 ) {
			$profile_picture = $line_;
		} elseif ( $line_counter == 1 ) {
			$user_social_profile = $line_;
		} elseif ( $line_counter == 3 ) {
			$user_first_name = $line_;
		} elseif ( $line_counter == 4 ) {
			$user_last_name = $line_;
		} elseif ( $line_counter == 5 ) {
			$user_pass = $line_;
		} elseif ( $line_counter == 6 ) {
			$user_email = $line_;
		} elseif ( $line_counter == 7 ) {
			$notify_user_on_post = $line_;
		} elseif ( $line_counter == 8 ) {
			$notify_user_on_message = $line_;
		}
		$line_counter += 1;
	}
	fclose( $read_ );

	if ( $user_image == "default" ) { $profile_picture = "https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-512.png"; }
	if ( $social_profile == "NULL" ) { $user_social_profile = "NULL"; }

	$write_ = fopen( $config_, "w" );
	fwrite( $write_, $profile_picture.PHP_EOL );
	fwrite( $write_, $user_social_profile.PHP_EOL );
	fwrite( $write_, $sender.PHP_EOL );
	fwrite( $write_, $user_first_name.PHP_EOL );
	fwrite( $write_, $user_last_name.PHP_EOL );
	fwrite( $write_, $user_pass.PHP_EOL );
	fwrite( $write_, $user_email.PHP_EOL );
	fwrite( $write_, $notify_user_on_post.PHP_EOL );
	fwrite( $write_, $notify_user_on_message );
	fclose( $write_ );

	//Convert Hobbies into Hobby Tags
	$hobbies_ = explode( ",", $hobbies );
	$hobbies = array();
	foreach ( $hobbies_ as $hobby ) {
		$hobby = strtolower( trim( $hobby ) );
		if ( !empty( $hobby ) ) {
			array_push( $hobbies, $hobby );
		}
	}
	$hobbies = implode( ",", $hobbies );

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "UPDATE WorldBloggers SET Save_Code='$authentication_code', Hobbies='$hobbies', Birthdate='$birthdate' WHERE Author_UID='$sender'";
	$conn->query( $sql );

	//Close the connection
	$conn->close();

	//Reset Session variables
	$_SESSION['senderImg'] = $profile_picture;
	$_SESSION['senderHref'] = $user_social_profile;
	$_SESSION['senderFN'] = $user_first_name;
	$_SESSION['senderLN'] = $user_last_name;

	echo "READY";
?>