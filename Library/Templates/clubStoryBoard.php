<div id="story-board">
	<?php 
		if ( ( ( $getClubAuthors == 0 && isset($isAdmin) ) || $getClubAuthors == 1 ) && in_array($sender, $getClubMembers) ) : 
	?>
		<div id="top-options-container">
			<button type="button" class="standart-blue-button display-block align-center mt-15" onclick="openStoryEditor('compose', <?php echo $isMobile; ?>);"><span class='iconic' style='margin-right: 5px;'>&#xf0f4;</span>Compose a story</button>
		</div>
	<?php endif; ?>
	<table id='main-table' class='mt-30'>
	</table>
</div>