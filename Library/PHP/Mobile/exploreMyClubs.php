<?php
	include "header.php";
?>
	
	<!-- ADD ADDITIONAL HEADER & TITLE INCLUDES IF NEEDED -->
	<title>My Clubs</title>
</head>
<body onload="loadMyRegisteredClubs(1, -1); loadMyMembershipedClubs(1, -1);">
	<?php // Include Menu and Sidebars 
		include "loadMenu.php";
	?>
	<div class="body-container">
		<div id='sub-menu-global'>
			<div id='current'>
				<a href='exploreMyClubs.php'>My clubs</a>
			</div>
			<div id='other' class='right'>
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