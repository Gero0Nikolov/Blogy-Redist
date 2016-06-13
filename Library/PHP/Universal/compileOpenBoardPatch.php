<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die( "Log in first!" ); }

	//Include bundle
	include "functions.php";

	$plugin_slug = $_POST["pluginSlug"];
	$plugin_patch = str_replace("#11", "+", $_POST["patch"]);
	$plugin_patch = str_replace("#33", "&", $plugin_patch);
	$plugin_file = $_POST["file"];

	$plugin_patch = strip_functions($plugin_patch);

	//Update file
	$open_file = fopen("../$plugin_file", "w");
	fwrite($open_file, $plugin_patch);
	fclose($open_file);

	//Return response
	echo "READY";
?>