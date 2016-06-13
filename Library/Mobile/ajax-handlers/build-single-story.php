<?php
	$user_id = $_POST["userID"];

	if ( empty( $user_id ) ) {
		session_start();
		$user_id = $_SESSION["sender"];
		if ( !isset( $user_id ) ) { die(); }
	}

	$response = "";

	$post_id = $_POST["storyID"];
	if ( empty( $post_id ) ) { $response = "-1"; }

	$post_build_type = $_POST["storyBuild"];

	$server_path = "/home/blogycoo/public_html/";

	//Include functions.php
	include $server_path."Library/PHP/Universal/functions.php";
	//Include mobile functions.php
	include $server_path."/Library/Mobile/assets/functions.php";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, STORYTITLE, STORYLINK, STORYCONTENT, LIKES FROM stack$user_id WHERE ID=$post_id";
	$pick = $conn->query( $sql );

	if ( $pick->num_rows > 0 ) {
		while ( $row = $pick->fetch_assoc() ) {
			$story_id = $row["ID"];
			
			$story_title = $row["STORYTITLE"];
			$story_title = str_replace("6996", " ", $story_title);
			$story_title = str_replace("-id-", "", $story_title);
			$story_title = str_replace("`", "'", $story_title);

			$story_link = $row["STORYLINK"];

			if ( $post_build_type == "normal" ) { $story_content = strip_words( $row["STORYCONTENT"], 25, "..." ); }
			elseif ( $post_build_type == "story-reader" ) {
				$story_content = parse_story_content( $row["STORYCONTENT"], $post_id );
			}

			$banner_container = "";
			if ( !empty( $story_link ) ) {
				$story_banner_build = build_url( $story_link );
				$story_banner = $story_banner_build[0];
				$banner_container = "
				<div class='story-banner-container'>
					$story_banner
				</div>
				";
			}

			if ( !empty( $row["LIKES"] ) ) { $story_likes = count( explode( ",", $row["LIKES"] ) ); }
			else { $story_likes = 0; }

			if ( $post_build_type == "normal" ) {
				$response = "
					<div id='story-$story_id' class='story-container'>
						<div class='edit-container'>
							<span>&#xf040;</span>
							<h1>Edit</h1>
						</div>
						<div id='story-open-controller' class='controll-box'></div>
						<h1 class='story-title'>$story_title</h1>
						$banner_container
						<div class='story-content'>$story_content</div>
						<div class='story-likes'><span class='liked'>&#xf004;</span>$story_likes</div>
						<div class='delete-container'>
							<span>&#xf014;</span>
							<h1>Delete</h1>
						</div>
					</div>
					<script>
					$( '#story-$story_id' ).on('swiperight', function(){
						$( '#story-$story_id .edit-container' ).addClass( 'slide-in' );
						setTimeout(function(){ 
							openStoryComposer( $story_id );
							setTimeout(function(){ $( '#story-$story_id .edit-container' ).removeClass( 'slide-in' ); }, 250); 
						}, 400);
					});
					$( '#story-$story_id' ).on('swipeleft', function(){
						$( '#story-$story_id .delete-container' ).addClass( 'slide-in' );
						setTimeout(function(){ deleteStory( $story_id ); }, 400);
					});
					$( '#story-$story_id #story-open-controller' ).on('tap', function(){
						openStoryReader( $story_id );
					});
					</script>
				";
			} elseif ( $post_build_type == "story-reader" ) {
				$response = "
					<h1 class='story-title'>$story_title</h1>
					$banner_container
					<div class='story-content'>$story_content</div>
					<div class='share-container'>
						<h1>Share in</h1>
						<div class='share-methods'>
							<a href='http://www.facebook.com/share.php?u=http://".$_SERVER["HTTP_HOST"]."?author=$user_id@p_id=$post_id' target='_blank' class='facebook'>&#xf09a;</a>
							<a href='http://twitter.com/home?status=Follow+my+story+http://".$_SERVER["HTTP_HOST"]."?author=$user_id@p_id=$post_id' target='_blank' class='twitter'>&#xf099;</a>
							<a href='https://plus.google.com/share?url=http://".$_SERVER["HTTP_HOST"]."?author=$user_id@p_id=$post_id' target='_blank' class='google-plus'>&#xf0d5;</a>
							<input type='text' value='http://".$_SERVER["HTTP_HOST"]."?author=$user_id@p_id=$post_id' onClick='this.select();'>
						</div>
					</div>
					<div class='likes-container'>
						<h1><span class='liked'>&#xf004;</span>$story_likes</h1>
						<div id='likes-list' class='users-followers-list'></div>
					</div>
				";
			}
		}
	}

	//Close the connection
	$conn->close();

	//Return response
	echo $response;
?>