<?php
	session_start();
	$user_id = $_POST["userID"];
	if ( empty( $user_id ) ) {
		$user_id = $_SESSION["sender"];
		if ( !isset( $user_id ) ) { die(); }
	} else { $sender = $_SESSION["sender"]; }
	$stories_offset = $_POST["offset"];
	$referrer = $_POST["referrer"];

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

	//Stories stack
	$container_ = "";

	if ( $user_id != "world-story" ) {
		$sql = "SELECT ID, STORYTITLE, STORYLINK, STORYCONTENT, LIKES FROM stack$user_id ORDER BY ID DESC LIMIT 2 OFFSET $stories_offset";
		$pick = $conn->query( $sql );

		if ( $pick->num_rows > 0 ) {
			while ( $row = $pick->fetch_assoc() ) {
				$story_id = $row["ID"];
				
				$story_title = $row["STORYTITLE"];
				$story_title = str_replace("6996", " ", $story_title);
				$story_title = str_replace("-id-", "", $story_title);
				$story_title = str_replace("`", "'", $story_title);

				$story_link = $row["STORYLINK"];
				$story_content = strip_words( $row["STORYCONTENT"], 25, "..." );

				$banner_container = "";
				if ( !empty( $story_link ) ) {
					$story_banner_build = build_url( $story_link );
					$story_banner = $story_banner_build[0];
					$banner_container = "
					<div class='story-banner-container'>
						$story_banner
					</div>
					";
					$edit_delete_containers_class = "smaller-containers";
				}

				$story_likes_handler = array();
				if ( !empty( $row["LIKES"] ) ) { 
					$story_likes_handler = explode( ",", $row["LIKES"] );
					$story_likes = count( $story_likes_handler );
				} else { $story_likes = 0; }

				if ( $referrer == "personal-story" ) {
					$container_ .= "
						<div id='story-$story_id' class='story-container'>
							<div id='edit-container' class='control-container'>
								<span>&#xf040;</span>
								<h1>Edit</h1>
							</div>
							<div id='story-open-controller' class='controll-box'></div>
							<h1 class='story-title'>$story_title</h1>
							$banner_container
							<div class='story-content'>$story_content</div>
							<div class='story-likes'><span class='liked'>&#xf004;</span>$story_likes</div>
							<div id='delete-container' class='control-container'>
								<span>&#xf014;</span>
								<h1>Delete</h1>
							</div>
						</div>
						<script>
						$( '#story-$story_id' ).on('swiperight', function(){
							$( '#story-$story_id #edit-container' ).addClass( 'slide-in' );
							setTimeout(function(){ 
								openStoryComposer( $story_id );
								setTimeout(function(){ $( '#story-$story_id #edit-container' ).removeClass( 'slide-in' ); }, 250); 
							}, 400);
						});
						$( '#story-$story_id' ).on('swipeleft', function(){
							$( '#story-$story_id #delete-container' ).addClass( 'slide-in' );
							setTimeout(function(){ deleteStory( $story_id ); }, 400);
						});
						$( '#story-$story_id #story-open-controller' ).on('tap', function(){
							openStoryReader( $story_id );
						});
						</script>
					";
				} elseif ( $referrer == "visited-story" ) {
					//Build the like / unlike container
					if ( empty( $story_likes_handler ) || !in_array( $sender, $story_likes_handler ) ) {
						$like_unlike_container = "
						<div id='like-container' class='control-container'>
							<span>&#xf004;</span>
							<h1>Like</h1>
						</div>
						";
					} else {
						$like_unlike_container = "
						<div id='like-container' class='control-container'>
							<span>&#xf088;</span>
							<h1>Dislike</h1>
						</div>
						";
					}

					$container_ .= "
						<div id='story-$story_id' class='story-container'>
							$like_unlike_container
							<div id='story-open-controller' class='controll-box'></div>
							<h1 class='story-title'>$story_title</h1>
							$banner_container
							<div class='story-content'>$story_content</div>
							<div class='story-likes'><span class='liked'>&#xf004;</span>$story_likes</div>
							<div id='repost-container' class='control-container'>
								<span>&#xf064;</span>
								<h1>Repost</h1>
							</div>
						</div>
						<script>
						$( '#story-$story_id' ).on('swiperight', function(){
							$( '#story-$story_id #like-container' ).addClass( 'slide-in' );
							setTimeout(function(){ 
								likeUnlikeStory( $story_id, '$user_id' );
								setTimeout(function(){ $( '#story-$story_id #like-container' ).removeClass( 'slide-in' ); }, 250); 
							}, 400);
						});
						$( '#story-$story_id' ).on('swipeleft', function(){
							$( '#story-$story_id #repost-container' ).addClass( 'slide-in' );
							setTimeout(function(){ 
								$( '#story-$story_id #repost-container span' ).addClass( 'pulse-colorful' );
								$( '#story-$story_id #repost-container h1' ).html( 'Reposting...' ).addClass( 'pulse-colorful' );
								repostStory( $story_id, '$user_id' );
							}, 250);
						});
						$( '#story-$story_id #story-open-controller' ).on('tap', function(){
							openStoryReader( $story_id );
						});
						</script>
					";
				}
			}
		} else { $container_ = "break-point"; }
	}

	//Close the connection
	$conn->close();

	echo $container_;
?>