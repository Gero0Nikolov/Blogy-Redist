<?php
	session_start();
	$sender = $_SESSION['sender'];

	if (isset($_COOKIE['sideBar'])) {
		$cmd = "style='display: none;'";
	} else {
		$cmd = "";
	}
	
echo "
	<div id='sideBar' $cmd>
			<h1>People you may know</h1>
			<div id='suggestions'>
				<!-- SUGGESTIONS -->
			</div>
			<div id='friendsContainers'>
				<div class='sub-menu'>
					<button type='button' id='left' class='left-button current' onclick='switchFriendsContainers(\"#onlineFriends\", \".left-button\"); loadOnlineFriends(0);'>Friends online</button>
					<button type='button' id='right' class='right-button ohana-button' onclick='switchFriendsContainers(\"#ohanaContainer\", \".right-button\"); loadOhana(0);'>
						Ohana
					</button>
				</div>
				<div id='onlineFriends' class='visible'>
					<!-- ONLINE Friends -->
				</div>
			
				<div id='ohanaContainer'>
				 	<!-- OHANA -->
				</div>
			</div>
	</div>
	<!--<div id='ohana-meaning' class='sidebar-popup'>
		<p>
			Ohana means family, and family means nobody gets left behind.
			<br> 
			Or forgotten.
		</p>
	</div>-->
	<div id='quickMessageBox' style='display: none;'>
		<div id='title'>
			<h1 id='receiver'></h1>
			<button type='button' class='hideButton' onclick='hideMessageBox()'></button>
		</div>
		<form id='sendArea'>
			<textarea id='messageArea' name='messageArea' placeholder=\"What's up ?\" onkeydown='checkKey(event)'></textarea>
			<button type='button' class='sendButton' onclick='sendMessageBox()'>Send</button>
			<div style='display: none;'>
				<input type='text' id='receiverId' name='receiverId'>
			</div>
		</form>
	</div>
";

echo "
	<div id='rightSideBar' $cmd>
		<div class='clubs-container'>
			<div class='row blue-left-border'>
				<a href='exploreMyClubs.php'>
					<span>&#xf005;</span>
					Clubs
				</a>
				<button type='button' title='Explore clubs' id='show-hide-clubs' class='show-hide-arrow-button' onclick='showMyClubs(0);'>&#xf107;</button>
			</div>
			<div class='clubs-list'>
			</div>
		</div>
		<div class='plugins-container'>
			<div class='row dark-left-border'>
				<a href='openPluginsDashboard.php' style='width: 100%;'>
					<span>&#xf127;</span>
					Plugins
				</a>
				<!--<button type='button' title='Explore clubs' id='show-hide-clubs' class='show-hide-arrow-button'>&#xf107;</button>-->
			</div>
		</div>
		<div class='plugins-list'>
		</div>
	</div>
";
?>