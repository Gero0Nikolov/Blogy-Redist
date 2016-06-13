<?php
	include "header.php";
?>
	
	<!-- ADD ADDITIONAL HEADER & TITLE INCLUDES IF NEEDED -->
	<title>All Clubs</title>
</head>
<body onload="loadWorldRegisteredClubs(1, -1);">
	<?php // Include Menu and Sidebars 
		include "loadMenu.php"; 
	?>
	<div class="body-container">
		<div id='sub-menu-global'>
			<div id='other' class='left'>
				<a href='exploreMyClubs.php'>My clubs</a>
			</div>
			<div id='current'>
				<a href='exploreWorldClubs.php'>All Clubs</a>
			</div>
		</div>
		<div id='world-clubs' class='mt-30'>
		</div>
	</div>
</body>
</html>