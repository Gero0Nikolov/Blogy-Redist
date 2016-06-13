<?php 
	$user_id = $_POST["userID"];
	if ( $user_id == "sender" ) {
		session_start();
		$user_id = $_SESSION["sender"];
		if ( !isset( $user_id ) ) { die(); }
	}

	$post_id = $_POST["storyID"];

	$server_path = "/home/blogycoo/public_html/";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT LIKES FROM stack$user_id WHERE ID=$post_id";
	$pick = $conn->query( $sql );

	if ( $pick->num_rows > 0 ) {
		while ( $row = $pick->fetch_assoc() ) {
			$likers = explode( ",", $row["LIKES"] );
			$revise_likers = array();
			foreach ( $likers as $liker ) {
				if ( !empty( trim( $liker ) ) ) {
					array_push( $revise_likers, $liker );
				}
			}
			$response = implode( "&", $revise_likers );
		}
	}

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>