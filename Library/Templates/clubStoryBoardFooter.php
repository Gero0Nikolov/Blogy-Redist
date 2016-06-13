<script>
	var loops = 1;
	var lastId = -1;

	$(document).ready(function(){
		loadStoriesClubs(-1, "5", <?php echo $isMobile; ?>, "<?php echo $getClubAdministrators; ?>");
		var flag = 0;
	
		//Attach functions
		$(document).scroll(function(){ checkPos(); });

		<?php //Add club visit if the visitor is not an admin
			if ( !in_array($sender, $_SESSION["club_administrators"]) ) :
		?>
			addClubVisit(<?php echo $isMobile; ?>);
		<?php endif; ?>
	});

	function callBack() { //Loads stories for explorer
		loops = 1;
		loadStoriesClubs(lastId, "5", <?php echo $isMobile; ?>, "<?php echo $getClubAdministrators; ?>");
	}
</script>