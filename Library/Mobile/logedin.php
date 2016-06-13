<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { header("Location: http://". $_SERVER["HTTP_HOST"]); }

	$sender_first_name = $_SESSION["senderFN"];
	$sender_last_name = $_SESSION["senderLN"];
	$sender_profile_picture = $_SESSION["senderImg"];
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "header.php"; ?>
	<title><?php echo $sender_first_name; ?>'s story</title>
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
			<div class="author-meta" onclick="openUserControlBox('<?php echo $sender ."%%". $sender_first_name ."%%". $sender_last_name ."%%". $sender_profile_picture; ?>', 'logged-in-author');">
				<div id="profile-picture" class="profile-picture" style="background-image: url(<?php echo $sender_profile_picture; ?>); background-size: cover; background-position: center;"></div>
				<h1><?php echo $sender_first_name ." ". $sender_last_name; ?></h1>
			</div>
			<button id="composer-controller" class="compose-button" onclick="openStoryComposer(-1);"><span>&#xf0f4;</span>Compose</button>
		</div>
		<div id="stories-holder" class="stories-container"></div>
	</div>
</body>
</html>