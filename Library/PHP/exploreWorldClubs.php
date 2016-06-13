<?php
	include "header.php";
?>
	
	<!-- ADD ADDITIONAL HEADER & TITLE INCLUDES IF NEEDED -->
	<title>All Clubs</title>
</head>
<body onload="loadWorldRegisteredClubs(0, -1);">
	<?php // Include Menu and Sidebars 
		include "loadMenu.php"; 
		include "loadSuggestedBlogers.php"; 
	?>
	<div class="body-container">
		<div id='sub-menu'>
			<div id='otherOption' class='left'>
				<a href='exploreMyClubs.php'>My clubs</a>
			</div>
			<div id='currentRight'>
				<a href='exploreWorldClubs.php'>All Clubs</a>
			</div>
		</div>
		<div id='world-clubs' class='mt-30'>
		</div>
	</div>
</body>
</html>