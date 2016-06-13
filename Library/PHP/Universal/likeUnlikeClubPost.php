<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { header("../../../index.php"); die(); }

	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$postId = $_POST["postId"];

	//Include bundle
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//Get current likes
	$tableName = $getClubTable."_Story_".$getClubId;
	$sql = "SELECT Likers FROM $tableName WHERE ID=".$postId;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getLikes = $row["Likers"];
		}
	}

	if ( strpos($getLikes, ",") ) { $getLikes = explode(",", $getLikes); }

	//Set likes counter
	$countLikes = 0;

	//Check if it is an array
	if ( is_array($getLikes) ) {
		$is_array = 1;
		//Check if current blogger has already liked it
		if ( in_array($sender, $getLikes) ) {
			
			$reviseLikes = array();
			foreach ( $getLikes as $liker ) {
				if ( $liker != $sender ) {
					array_push($reviseLikes, $liker);
				}
			}
			if ( !empty($reviseLikes) ) { 
				$countLikes = count($reviseLikes);
				$reviseLikes = implode(",", $reviseLikes); 
			}
			elseif ( empty($reviseLikes) ) { 
				$countLikes = 0;
				$reviseLikes = NULL; 
			}

		} else { //Add the current user as liker
			
			array_push($getLikes, $sender);
			$reviseLikes = array();
			foreach ( $getLikes as $liker ) {
				if ( isset($liker) && !empty($liker) ) {
					array_push($reviseLikes, $liker);
				}
			}
			if ( !empty($reviseLikes) ) { 
				$countLikes = count($reviseLikes);
				$reviseLikes = implode(",", $reviseLikes); 
			}
			elseif ( empty($reviseLikes) ) { 
				$countLikes = 0;
				$reviseLikes = NULL; 
			}

		}
	} elseif ( !is_array($getLikes) ) {
		$reviseLikes = array();
		if ( $getLikes == $sender ) {
			$countLikes = 0;
			$reviseLikes = NULL;
		} else {
			if ( !empty($getLikes) ) {
				array_push($reviseLikes, $getLikes);
				array_push($reviseLikes, $sender);
				$countLikes = count($reviseLikes);
				$reviseLikes = implode(",", $reviseLikes);
			} else {
				$reviseLikes = $sender;
				$countLikes = 1;
			}
		}
	}

	$getLikes = $reviseLikes;

	//Update the likes
	$sql = "UPDATE ".$tableName." SET Likers='".$getLikes."' WHERE ID=".$postId;
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Set responce
	$responce = $countLikes;

	//Return responce
	echo $countLikes;
?>