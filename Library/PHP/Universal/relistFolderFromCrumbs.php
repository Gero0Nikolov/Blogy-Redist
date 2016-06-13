<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getPluginSlug = $_SESSION["current_dev_plugin_slug"];
	$getBreadCrumbs = $_POST["breadcrumbs"];

	$buildListedFolder = "";
	$pluginDir = "/home/blogycoo/public_html/Library/Authors/$sender/Plugins/$getBreadCrumbs";
	$pluginDirShort = "../Authors/$sender/Plugins/$getBreadCrumbs";

	$getDirectoryFiles = scandir( $pluginDir );
	sort($getDirectoryFiles);
	foreach ( $getDirectoryFiles as $inline_ ) {
		if ( $inline_ != "." && $inline_ != ".." && $inline_ != "index.php" && !empty($inline_) ) {

			$buildListedFolder .= "<div class='row'>";
			
			if ( is_dir("../../Authors/$sender/Plugins/$getBreadCrumbs/$inline_") ) :
			
				$buildListedFolder .= "
					<button class='directory-opener placeholder' title='List directory' onclick='openFolder(\"$getBreadCrumbs$inline_/\", 0);'><span class='iconic'>&#xf07b;</span>$inline_</button>
					<div class='side-options'>
						<button class='move iconic' title='Move folder' onclick='openMoveDialog(\"$getBreadCrumbs$inline_\", \"folder\", 0);'>&#xf07c;</button>
						<button class='remove iconic' title='Remove folder' onclick='openRemoveDialog(\"$getBreadCrumbs$inline_/\", \"folder\", 0);'>&#xf014;</button>
					</div>
				";
			
			else :
				
				if ( !strpos($inline_, ".zip") ) {
					$buildListedFolder .= "
						<button class='file-opener placeholder' title='Read file' onclick='openFile(\"$getBreadCrumbs$inline_\", 0);'><span class='iconic'>&#xf15b;</span>$inline_</button>
						<div class='side-options'>
							<a href='http://blogy.co/Library/PHP/openBoard.php?$getPluginSlug%$pluginDirShort/$inline_' class='edit iconic' title='Edit file' target='_blank'>&#xf040;</a>
							<button class='move iconic' title='Move file' onclick='openMoveDialog(\"$getBreadCrumbs$inline_\", \"file\", 0);'>&#xf07c;</button>
							<button class='remove iconic' title='Remove file' onclick='openRemoveDialog(\"$getBreadCrumbs$inline_\", \"file\", 0);'>&#xf014;</button>
						</div>
					";
				} else {
					$buildListedFolder .= "
						<button class='patch-file' title='Patch file'><span class='iconic'>&#xf0e7;</span>$inline_</button>
						<div class='side-options'>
							<button class='remove iconic' title='Remove file' onclick='openRemoveDialog(\"$getBreadCrumbs$inline_\", \"file\", 0);'>&#xf014;</button>
						</div>
					";
				}

			endif;			

			$buildListedFolder .= "</div>";
		}
	}

	echo $buildListedFolder;

/*EDIT BUTTON:
	FILE: <button class='edit iconic' title='Edit file - Coming Soon'>&#xf040;</button>
*/
?>