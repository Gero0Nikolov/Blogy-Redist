<?php include "header.php"; ?>
	<title>Plugin Development</title>
</head>
<body>
	<?php
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div id="body">
		<div class="dialog-container mt-30">

		<?php // Check if there are existing plugins
			if ( file_exists("../Authors/$sender/Plugins") ) {
				$build_open_project_button = "<button class='dialog-button' onclick='openChoosePluginProjectDialog(0);'><img src='$url_set/Library/images/open-project.png' alt='Broken image!' />Open plugin project</button>";
			}
		?>

		<button class="dialog-button" onclick="openCreatePluginDialog(0);"><img src="<?php echo $url_set; ?>/Library/images/go-dev.png" alt="Broken image!" />Create new plugin project</button>
		<?php echo $build_open_project_button; ?>
		</div>
	</div>
</body>