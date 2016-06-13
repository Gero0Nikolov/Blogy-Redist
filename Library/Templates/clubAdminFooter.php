<script>
	var getSearches;

	$(document).ready(function(){
		//Calculate members
		calculateClubMembers("<?php echo $clubOwner.'_Clubs'; ?>", "<?php echo $catchClubId; ?>", <?php echo $isMobile; ?>);
		//Calculate visits
		calculateClubVisits("<?php echo $clubOwner.'_Clubs'; ?>", "<?php echo $catchClubId; ?>", <?php echo $isMobile; ?>);
		//Calculate stories
		calculateClubStories("<?php echo $clubOwner.'_Clubs'; ?>", "<?php echo $catchClubId; ?>", <?php echo $isMobile; ?>);
		//Load administrators
		loadClubAdministrators("<?php echo $clubOwner.'_Clubs'; ?>", "<?php echo $catchClubId; ?>", <?php echo $isMobile; ?>);
		//Load club requests
		loadClubRequests("<?php echo $clubOwner.'_Clubs'; ?>", "<?php echo $catchClubId; ?>", <?php echo $isMobile; ?>);
		//Load members
		loadClubMembers_Admin("<?php echo $clubOwner.'_Clubs'; ?>", "<?php echo $catchClubId; ?>", <?php echo $isMobile; ?>);
	});
</script>