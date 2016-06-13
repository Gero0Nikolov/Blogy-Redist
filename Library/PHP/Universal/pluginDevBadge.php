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

	$table_name = $user_id."_Plugins_Relations";
	$sql = "SELECT ID FROM $table_name WHERE Plugin_Author='$user_id'";
	$query_ = $conn->query( $sql );

	$plugins_ = $query_->num_rows;

	//Close the connection
	$conn->close();

	$build_flag = false;
	$stars_ = "";
	$badge_ = "&#xf1e6;";
	$badge_title = "Plugin Developer";

	if ( $plugins_ >= 1 && $plugins_ < 3 ) { // Basic
		$build_flag = true;
	} 

	if ( $plugins_ >= 3 && $plugins_ < 9 ) { // Advanced
  		$stars_ = "<span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $plugins_ >= 9 && $plugins_ < 27 ) { // Master
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $plugins_ >= 27 ) { // Chief
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $build_flag == true ) {
	  	$badge_type = "pd";
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