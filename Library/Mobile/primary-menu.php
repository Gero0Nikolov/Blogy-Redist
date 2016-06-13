<div class="top-menu">
	<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { header("Location: http://blogy.co"); }

	$sender_first_name = $_SESSION["senderFN"];
	$sender_last_name = $_SESSION["senderLN"];
	$sender_profile_picture = $_SESSION["senderImg"];
	?>
	<button class="left-side">
		<div id="primary-menu-activator" class="menu-avatar" style="background-image: url(<?php echo $sender_profile_picture; ?>); background-size: cover; background-position: 50%;"></div>
	</button>
	<button id="notifications-activator" class="middle-side"><span class="sidebar-button">&#xf0f3;</span></button>
	<button id="sidebar-activator" class="right-side"><span class="sidebar-button">&#xf111;</span></button>
</div>
<div id="primary-menu-wrapper" class="full-page-wrapper">
	<div id="primary-menu" class="left-sidebar-menu">
		<div class="upper-box">
			<div class="name-contianer"><?php echo $sender_first_name ." ". $sender_last_name; ?></div>
			<div class="menu-container">
				<a href="logedin.php" class="menu-item" data-ajax="false"><span>&#xf0f4;</span>Home</a>
				<a href="album.php" class="menu-item" data-ajax="false"><span>&#xf083;</span>Album</a>
				<a href="#" class="menu-item"><span>&#xf1ea;</span>Stories</a>
				<a href="#" class="menu-item"><span>&#xf075;</span>Messages</a>
				<a href="#" class="menu-item"><span>&#xf0c0;</span>Bloggers</a>
				<a href="#" class="menu-item"><span>&#xf0ad;</span>Settings</a>
				<a href="#" class="menu-item"><span>&#xf279;</span>Places</a>
				<a href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/Library/PHP/LogOut.php" class="menu-item" data-ajax="false"><span>&#xf08b;</span>Exit</a>
			</div>
		</div>
		<div class="lower-box">
			<input type="text" class="wide-fat" placeholder="Search..."></input>
		</div>
	</div>
</div>
<div id="notifications-container"></div>
<!-- <a href="#" class="menu-item"><span>&#xf005;</span>Clubs</a> -->
<div id="sidebar-menu" class="right-sidebar-menu">
	<div class="upper-box">
		<button class="section-header" id="suggestions-controller">People you may know</button>
		<div id="suggestions-container" class="sidebar-container"></div>
		<button class="section-header" id="online-friends-controller">Online Friends</button>
		<div id="online-friends-container" class="sidebar-container"></div>
		<button class="section-header" id="ohana-controller">Ohana</button>
		<div id="ohana-container" class="sidebar-container"></div>
		<button class="section-header" id="clubs-controller">Clubs</button>
		<button class="section-header" id="plugins-controller">Plugins</button>
	</div>
	<div class="lower-box">
		<button id="close-sidebar-button" class="discard-button">Close</button>
	</div>
</div>

<?php /* INLINE MENU SCRIPTS */ ?>
<script type="text/javascript">
	$( document ).ready(function(){
		refreshNotifications = setInterval(function(){ pullNotifications(); }, 8000);
	});
</script>