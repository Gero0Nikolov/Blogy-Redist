<?php
	include "header.php"
?>
	<title>Plugins Dashboard</title>
</head>
<body>
	<?php 
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div class="three-columns">
		<a href="openPluginDevelopmentDialog.php" class="develop">
			<div class="column">
				<img src="<?php echo $url_set; ?>/Library/images/social-contributor.png" alt="Broken image!" />
				<h1>Develop!</h1>
			</div>
		</a>
		<a href="openPluginManager.php" class="manage">
			<div class="column">
				<img src="<?php echo $url_set; ?>/Library/images/my-plugins.png" alt="Broken image!" />
				<h1>Attached Plugins</h1>
			</div>
		</a>
		<a href="pluginStore.php" class="store">
			<div class="column">
				<img src="<?php echo $url_set; ?>/Library/images/plugins-store.png" alt="Broken image!" />
				<h1>Plugin Store</h1>
			</div>
		</a>
	</div>
</body>