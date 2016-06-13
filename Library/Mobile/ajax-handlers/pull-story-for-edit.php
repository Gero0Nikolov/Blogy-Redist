<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	$response = "";

	$post_id = $_POST["storyID"];
	if ( empty( $post_id ) ) { $response = "-1"; }

	$server_path = "/home/blogycoo/public_html/";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, STORYTITLE, STORYLINK, STORYCONTENT, LIKES FROM stack$sender WHERE ID=$post_id";
	$pick = $conn->query( $sql );

	if ( $pick->num_rows > 0 ) {
		while ( $row = $pick->fetch_assoc() ) {	
			$story_title = $row["STORYTITLE"];
			$story_title = str_replace("6996", " ", $story_title);
			$story_title = str_replace("-id-", "", $story_title);
			$story_title = str_replace("`", "'", $story_title);
			$story_title = html_entity_decode( $story_title );

			$story_link = $row["STORYLINK"];

			$story_content = str_replace("<br />", "\r\n", html_entity_decode( $row["STORYCONTENT"] ) );
		
			$response = $story_title ."%%%". $story_link ."%%%". $story_content;
		}
	}

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>