<?php
	include "header.php";
?>
	<title>Plugin Menu</title>
</head>
<body>
	<?php
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div class='two-columns'>
		<div class='column'>
			<img src='<?php echo $url_set; ?>/Library/images/folder-icon.png' alt='Broken image!' />
			<h1>File Explorer</h1>
			<a href="#" class="explore">Explore</a>	
		</div>
		<div class='column'>
			<img src='<?php echo $url_set; ?>/Library/images/oBoard.png' alt='Broken image!' />
			<h1>oBoard!</h1>
			<a href="#" class="oboard">Develop</a>
		</div>
	</div>
</body>