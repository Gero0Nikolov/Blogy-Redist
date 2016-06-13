<?php
	include "header.php";

	$getPluginSlug = $_SERVER["QUERY_STRING"];
	if ( empty($getPluginSlug) ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }
	if ( !file_exists("../Authors/$sender/Plugins/$getPluginSlug") ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }

	$_SESSION["current_dev_plugin_slug"] = $getPluginSlug;

	$buildPluginName = "";
	$parsePluginSlug = explode("_", $getPluginSlug);
	foreach ($parsePluginSlug as $name_part) {
		$buildPluginName .= ucfirst($name_part) ." ";
	}
	$buildPluginName = trim($buildPluginName);
?>
	<title>File Explorer</title>
	<link href='../Styles/plugin-explorer/plugin-file-explorer.css' rel='stylesheet' type='text/css' media='screen'>
</head>
<body onload='relistListedFolder("<?php echo $getPluginSlug."/"; ?>", 0);'>
	<?php
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div id="file-explorer">
		<div class="project-header">
			<h1><?php echo $buildPluginName; ?> - Project</h1>
		</div>
		<div class="project-sidebar">
			<button onclick="compilePluginPatch(0);"><img id="compile-image" class="button-icon" src="<?php echo $url_set; ?>/Library/images/compile.png" alt="Broken image!">Compile!</button>
			<button onclick="window.location='preparePluginUpdate.php?<?php echo $getPluginSlug; ?>'"><img class="button-icon" src="<?php echo $url_set; ?>/Library/images/upload.png" alt="Broken image!">Upload update</button>
			<button onclick="downloadPluginProject(0);"><img id="download-image" class="button-icon" src="<?php echo $url_set; ?>/Library/images/download.png" alt="Broken image!">Download project</button>
			<button onclick="openNewFileDialog(0);"><img class="button-icon" src="<?php echo $url_set; ?>/Library/images/new-file.png" alt="Broken image!">New file</button>
			<button onclick="openNewFolderDialog(0);"><img class="button-icon" src="<?php echo $url_set; ?>/Library/images/new-folder.png" alt="Broken image!">New folder</button>
			<button onclick="sendPluginForReview(0);"><img class='button-icon' src="<?php echo $url_set; ?>/Library/images/attach.png">Attach to Store</button>
			<button onclick="openRemovePluginDialog(0);"><img class="button-icon" src="<?php echo $url_set; ?>/Library/images/remove-project.png" alt="Broken image!">Remove project</button>
			<div id="<?php echo $getPluginSlug."/"; ?>" class="breadcrumbs"><?php echo $getPluginSlug."/"; ?></div>
		</div>
		<div class="directory-holder">
		</div>
	</div>
</body>