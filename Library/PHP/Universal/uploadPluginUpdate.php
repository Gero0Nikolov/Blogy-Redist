<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$pluginSlug = $_SESSION["plugin_for_patch"];
	$target_loc = "../../Authors/$sender/Plugins/$pluginSlug/";
	$tmp_target_ = $_FILES["updateFile"]["tmp_name"];
	$target_ = $target_loc . "PATCH_$pluginSlug.zip";
	$file_type = strtolower( pathinfo($target_, PATHINFO_EXTENSION) );
	$patch_size = $_FILES["updateFile"]["size"];

	$result = "";
	if ( $file_type = "zip" ) {

		if ( $patch_size <= 10000000 ) {
			if ( move_uploaded_file($tmp_target_, $target_) ) {
				$result = "READY";
			} else {
				$result = "Something went wrong.. Try again.";
			}
		} else {
			$result = "Patch size must be smaller then 10MB!";
		}
	
	} else {
		$result = "Your file is not an update file!\nChoose another .ZIP file.";
	}

	if ( $result == "READY" ) { header("Location: ../openPluginFileExplorer.php?$pluginSlug"); }
	else { echo $result; }
?>