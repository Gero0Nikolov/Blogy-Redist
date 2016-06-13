<?php
	session_start();
	$sender = $_SESSION['sender'];
	if ( !isset( $sender ) ) { die(); }

	$start_path = $_POST['startPath'];

	$user_id = $_POST['userID'];
	if ( $user_id == "sender" ) { $user_id = $sender; }

	//Collect followers
	$followers_ = -1;
	$path_ = "../../Authors/". $user_id ."/FollowersID.html";
	if ( file_exists( $path_ ) ) {
		$open_ = fopen( $path_, "r" );
		while ( !feof( $open_ ) ) {
			fgets( $open_ );
			$followers_ += 1;
		}
		fclose( $open_ );
	} else {
		$followers_ = 0;
	}

	$build_flag = false;
	$stars_ = "";
	$badge_ = "&#xf21d;";
	$badge_title = "Leader";

	if ( $followers_ >= 5 && $followers_ < 15 ) { // Basic
		$build_flag = true;
	} 

	if ( $followers_ >= 15 && $followers_ < 45 ) { // Advanced
  		$stars_ = "<span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $followers_ >= 45 && $followers_ < 100 ) { // Master
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $followers_ >= 100 ) { // Chief
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $build_flag == true ) {
	  	$badge_type = "ld";
		$build_ = "
			<div class='badge-container'>
				<a href='badgesPage.php?b=". $badge_type ."' class='badge ". $badge_type ."' title='". $badge_title ."'>". $badge_ ."
				<div class='badge-stars'>". $stars_ ."</div>
				</a>
			</div>
		";
	} else {
		$build_ = "NB";
	}

	echo $build_;
?>