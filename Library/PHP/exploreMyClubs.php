<?php
	include "header.php";
?>
	
	<!-- ADD ADDITIONAL HEADER & TITLE INCLUDES IF NEEDED -->
	<title>My Clubs</title>
</head>
<body onload="loadMyRegisteredClubs(0, -1); loadMyMembershipedClubs(0, -1);">
	<?php // Include Menu and Sidebars 
		include "loadMenu.php"; 
		include "loadSuggestedBlogers.php"; 
	?>
	<div class="body-container">
		<div id='sub-menu'>
			<div id='currentLeft'>
				<a href='exploreMyClubs.php'>My clubs</a>
			</div>
			<div id='otherOption' class='right'>
				<a href='exploreWorldClubs.php'>All Clubs</a>
			</div>
		</div>
		<h1 class="section-header">My Clubs</h1>
		<div id="my-clubs">
			<!-- LOAD MY CLUBS -->
		</div>
		<h1 class="section-header">Memberships</h1>
		<div id="membershiped-clubs">
			<!-- LOAD MEMBERSHIPED CLUBS -->
		</div>
	</div>
</body>
</html>