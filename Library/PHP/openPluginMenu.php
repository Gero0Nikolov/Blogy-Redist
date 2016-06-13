<?php
	include "header.php";

	$getPluginSlug = $_SERVER["QUERY_STRING"];

	if ( empty($getPluginSlug) ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }
	if ( !file_exists("../Authors/$sender/Plugins/$getPluginSlug") ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }
?>
	<title>Plugin Menu</title>
</head>
<body>
	<?php
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div class='two-columns'>
		<a href="openPluginFileExplorer.php?<?php echo $getPluginSlug; ?>" class="explore">
			<div class='column'>
				<img src='<?php echo $url_set; ?>/Library/images/folder-icon.png' alt='Broken image!' />
				<h1>File Explorer</h1>	
			</div>
		</a>
		<a href="openBoard.php?<?php echo $getPluginSlug; ?>" target="_blank" class="oboard">
			<div class='column' id='coming-soon'>
				<img src='<?php echo $url_set; ?>/Library/images/oBoard.png' alt='Broken image!' />
				<h1>oBoard!</h1>
			</div>
		</a>
	</div>
</body>