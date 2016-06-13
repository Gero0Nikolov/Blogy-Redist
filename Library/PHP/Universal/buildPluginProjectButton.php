<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getPluginSlug = $_POST["pluginSlug"];

	$buildPluginName = "";
	$parsePluginSlug = explode("_", $getPluginSlug);
	foreach ($parsePluginSlug as $name_part) {
		$buildPluginName .= ucfirst($name_part) ." ";
	}
	$buildPluginName = trim($buildPluginName);

	//Parse plugin description
	$store_description = "";
	$parse_description = fopen("../../Authors/$sender/Plugins/$getPluginSlug/meta-description.txt", "r") or die("Fatal.");
	while ( !feof($parse_description) ) {
		$parse_line = trim( fgets($parse_description) );
		$store_description .= $parse_line;
	}
	fclose($parse_description);

	$get_plugin_icon = explode("~icon: ", $store_description)[1];
	$get_plugin_icon = explode(";", $get_plugin_icon)[0];

	$build_button = "
	<a href='openPluginMenu.php?".$getPluginSlug."'>
		<div class='img' style='background-image: url(".$get_plugin_icon."); background-size: cover; background-position: 50%;'></div>
		$buildPluginName
	</a>
	";

	echo $build_button;
?>