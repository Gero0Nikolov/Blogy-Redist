<!-- DROPDOWN HEADER MENUS -->
<div id='club-header-menu' class='full-width-container'>
	<div id='club-header-settings-storiesmenu' class='menu-container right-aligned white-menu right-margin-25'>
		<button class='club-header-button' onclick='showHideClubSubMenu("#club-header-stories-submenu");'><span>&#xf0ac;</span>Stories</button>
		<div id='club-header-stories-submenu' class='submenu'>
			<a href='previewClub.php?<?php echo explode("&", $catchParameters)[0]; ?>' class='club-header-button'><span>&#xf013;</span>Dashboard</a>
		</div>
	</div>
</div>