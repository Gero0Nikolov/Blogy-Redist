<?php
	include "header.php";

	$getPluginSlug = $_SERVER["QUERY_STRING"];

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
<body>
	<?php
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div id="file-explorer">
		<div class="project-header">
			<h1><?php echo $buildPluginName; ?> - Project</h1>
		</div>
		<div class="project-sidebar">
			<button onclick="openNewFileDialog(0);"><img class="button-icon" src="<?php echo $url_set; ?>/Library/images/new-file.png" alt="Broken image!">New file</button>
			<button onclick="openNewFolderDialog(0);"><img class="button-icon" src="<?php echo $url_set; ?>/Library/images/new-folder.png" alt="Broken image!">New folder</button>
			<button onclick="openRemovePluginDialog(0);"><img class="button-icon" src="<?php echo $url_set; ?>/Library/images/remove-project.png" alt="Broken image!">Remove project</button>
		</div>
		<div class="directory-holder">
			<?php //Directory first listing
				$getDirectoryFiles = scandir("../Authors/$sender/Plugins/$getPluginSlug");
				foreach ( $getDirectoryFiles as $inline_ ) {
					if ( $inline_ != "." && $inline_ != ".." && $inline_ != "index.php" && !empty($inline_) ) {
						?>

						<div class="row">
						
						<?php
						if ( is_dir("../Authors/$sender/Plugins/$getPluginSlug/$inline_") ) :
						?>
							<button class="directory-opener placeholder" title="List directory"><span class="iconic">&#xf07b;</span><?php echo $inline_; ?></button>
							<div class="side-options">
								<button class="move iconic" title="Move folder">&#xf07c;</button>
								<button class="remove iconic" title="Remove folder">&#xf00d;</button>
							</div>
						<?php
						else :
						?>
							<button class="file-opener placeholder" title="Read file"><span class="iconic">&#xf15b;</span><?php echo $inline_; ?></button>
							<div class="side-options">
								<button class="edit iconic" title="Edit file">&#xf040;</button>
								<button class="move iconic" title="Move file">&#xf07c;</button>
								<button class="remove iconic" title="Remove file">&#xf00d;</button>
							</div>
						<?php
						endif;
						?>

						</div>

						<?php
					}
				}
			?>
		</div>
	</div>
</body>