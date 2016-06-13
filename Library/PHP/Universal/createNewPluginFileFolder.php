<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getFileName = $_POST["fileName"];
	$getPluginSlug = $_POST["pluginSlug"];
	$getType = $_POST["type"];
	$getBreadCrumbs = $_POST["breadcrumbs"];

	if ( !strpos($getFileName, ".") && $getType != "folder" ) { $getFileName .= ".php";  }

	if ( $getType == "file" ) {
		$file_builder = fopen("../../Authors/$sender/Plugins/$getBreadCrumbs/$getFileName", "w");
		fwrite($file_builder, "");
		fclose($file_builder);
	} elseif ( $getType == "folder" ) {
		mkdir("../../Authors/$sender/Plugins/$getBreadCrumbs/$getFileName", 0777);
	}

	echo "READY";
?>