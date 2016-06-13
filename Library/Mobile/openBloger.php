<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { header("Location: http://". $_SERVER["HTTP_HOST"]); }

	$sender_first_name = $_SESSION["senderFN"];
	$sender_last_name = $_SESSION["senderLN"];
	$sender_profile_picture = $_SESSION["senderImg"];

	$v_user_id = explode( "?", $_SERVER["REQUEST_URI"] )[1];
	if ( empty( $v_user_id ) || $sender == $v_user_id ) { header( "Location: logedin.php" ); }

	$server_path = "/home/blogycoo/public_html/";

	//Read visited user meta
	$parser_ = 0;
	$meta_handler = fopen( $server_path."Library/Authors/$v_user_id/config.txt", "r" );
	while ( !feof( $meta_handler ) ) {
		$meta_line = trim( fgets( $meta_handler ) );
		if ( $parser_ == 0 ) { $v_user_profile_pic = $meta_line; }
		elseif ( $parser_ == 1 ) { $v_user_social_profile = $meta_line; }
		elseif ( $parser_ == 3 ) { $v_user_first_name = $meta_line; }
		elseif ( $parser_ == 4 ) { $v_user_last_name = $meta_line; }
		$parser_ += 1;
	}
	fclose( $meta_handler );
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "header.php"; ?>
	<title><?php echo $v_user_first_name; ?>'s story</title>
	<script type="text/javascript">
		var storiesOffset = 0;
		var lockScroll = 0;

		//$( document ).ready(function(){ loadStories( storiesOffset ); });
		$( document ).scroll(function(){
			win = $( window );
			if ( $( document ).height() - win.height() <= win.scrollTop() + 200 && lockScroll == 0 ) {
				loadStories( storiesOffset );
				storiesOffset += 2;
				lockScroll = 1;
			}
		});
	</script>
</head>
<body>
	<?php include "primary-menu.php"; ?>
	<div id="body-container" class="full-body-container">
		<div id="author-container" class="profile-meta-container">
			<div class="author-meta" onclick="openUserControlBox('<?php echo $v_user_id ."%%". $v_user_first_name ."%%". $v_user_last_name ."%%". $v_user_profile_pic; ?>', 'visited-story');">
				<div id="profile-picture" class="profile-picture" style="background-image: url(<?php echo $v_user_profile_pic; ?>); background-size: cover; background-position: center;"></div>
				<h1><?php echo $v_user_first_name ." ". $v_user_last_name; ?></h1>
			</div>
			<button id="composer-controller" class="compose-button"><span>&#xf1d8;</span>Message</button>
		</div>
		<div id="stories-holder" class="stories-container"></div>
	</div>
</body>
</html>