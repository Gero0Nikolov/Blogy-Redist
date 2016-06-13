<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	//Include bundle
	include "functions.php";

	$getSlug = $_POST["pluginSlug"];

	if ( file_exists("../../Authors/$sender/Plugins/$getSlug/PATCH_$getSlug.zip") ) {
		extract_zip("../../Authors/$sender/Plugins/$getSlug/PATCH_$getSlug.zip", "../../Authors/$sender/Plugins/$getSlug/");
		remove_file("../../Authors/$sender/Plugins/$getSlug/PATCH_$getSlug.zip");
	}

	compile_project("../../Authors/$sender/Plugins/$getSlug/");

	echo "READY";

//Compile function
	function compile_project($project_ = "") {
		if ( !empty($project_) ) {
			$dir_ = scandir($project_);

			foreach ($dir_ as $inline_) {
				if ( $inline_ != "." && $inline_ != ".." && !empty($inline_) ) {
					if ( is_dir($inline_) ) {
						compile_project($project_ . $inline_ . "/");
					} else {
						rebuild_file($project_ . $inline_);
					}
				}
			}
		}
	}

	function rebuild_file($target_ = "") {
		if ( !empty($target_) ) {
			if ( file_exists($target_) && filesize($target_) > 0 ) {
				$open_target = fopen($target_, "r");
				$catch_target = fread( $open_target, filesize($target_) );
				fclose($open_target);

				$convert_target = strip_functions($catch_target);

				$open_target = fopen($target_, "w");
				fwrite($open_target, $convert_target);
				fclose($open_target);
			}
		}
	}
?>