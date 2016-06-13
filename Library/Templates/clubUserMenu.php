<!-- DROP DOWN HEADER MENUS -->
<div id='club-header-menu' class='full-width-container'>
	<div id='club-header-settings-mainmenu' class='menu-container right-aligned white-menu right-margin-25'>
		<button class='club-header-button' onclick='showHideClubSubMenu("#club-header-settings-submenu");'><span>&#xf013;</span>Options</button>
		<div id='club-header-settings-submenu' class='submenu'>
			<button class='club-header-button' onclick='leaveClub(<?php echo $isMobile; ?>);'><span>&#xf00d;</span>Leave club</button>
		</div>
	</div>
</div>