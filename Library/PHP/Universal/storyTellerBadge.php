<?php
	session_start();
	$sender = $_SESSION['sender'];
	if ( !isset( $sender ) ) { die(); }

	$start_path = $_POST['startPath'];

	$user_id = $_POST['userID'];
	if ( $user_id == "sender" ) { $user_id = $sender; }

	//Connect to database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$table_name = "stack".$user_id;
	$sql = "SELECT ID FROM $table_name";
	$query_ = $conn->query( $sql );

	$stories_ = $query_->num_rows;

	//Close the connection
	$conn->close();

	$build_flag = false;
	$stars_ = "";
	$badge_ = "&#xf0f4;";
	$badge_title = "Story Teller";

	if ( $stories_ >= 10 && $stories_ < 30 ) { // Basic
		$build_flag = true;
	} 

	if ( $stories_ >= 30 && $stories_ < 90 ) { // Advanced
  		$stars_ = "<span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $stories_ >= 90 && $stories_ < 150 ) { // Master
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $stories_ >= 150 ) { // Chief
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $build_flag == true ) {
	  	$badge_type = "st";
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