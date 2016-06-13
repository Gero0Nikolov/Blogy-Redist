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

	$recivers_ = array();

	$table_name = "Messages_".$user_id;
	$sql = "SELECT Receiver FROM $table_name WHERE Receiver != '". $user_id ."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push( $recivers_, $row['Receiver'] );
		}
	}

	$recivers_ = array_unique( $recivers_ );

	//Close the connection
	$conn->close();

	$recivers_ = count( $recivers_ );

	$build_flag = false;
	$stars_ = "";
	$badge_ = "&#xf086;";
	$badge_title = "Messenger";

	if ( $recivers_ >= 5 && $recivers_ < 15 ) { // Basic
		$build_flag = true;
	} 

	if ( $recivers_ >= 15 && $recivers_ < 60 ) { // Advanced
  		$stars_ = "<span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $recivers_ >= 60 && $recivers_ < 90 ) { // Master
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $recivers_ >= 90 ) { // Chief
  		$stars_ = "<span class='star'>&#xf005;</span><span class='star'>&#xf005;</span><span class='star'>&#xf005;</span>";
  		$build_flag = true;
  	}

  	if ( $build_flag == true ) {
	  	$badge_type = "ms";
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