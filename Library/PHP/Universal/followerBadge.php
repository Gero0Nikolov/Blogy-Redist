<?php
	session_start();
	$sender = $_SESSION['sender'];
	if ( !isset( $sender ) ) { die(); }

	$start_path = $_POST['startPath'];

	$user_id = $_POST['userID'];
	if ( $user_id == "sender" ) { $user_id = $sender; }

	//Collect followers
	$following_ = -1;
	$path_ = "../../Authors/". $user_id ."/Following.txt";
	if ( file_exists( $path_ ) ) {
		$open_ = fopen( $path_, "r" );
		while ( !feof( $open_ ) ) {
			fgets( $open_ );
			$following_ += 1;
		}
		fclose( $open_ );
	} else {
		$following_ = 0;
	}

	$build_flag = false;
	$stars_ = "";
	$badge_ = "&#xf1ae;";
	$badge_title = "Follower";

	if ( $following_ >= 5 && $following_ < 15 ) { // Basic
		$build_flag = true;
	} 

	if ( $following_ >= 15 && $following_ < 45 ) { // Advanced
  		$stars_ = "<span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $following_ >= 45 && $following_ < 100 ) { // Master
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $following_ >= 100 ) { // Chief
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $build_flag == true ) {
	  	$badge_type = "fr";
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