<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$store_buttons = "";
	$collected_ = 1;

	$collect_projects = scandir("../../Authors/$sender/Plugins/");
	foreach ($collect_projects as $plugin_slug) {
		if ( $plugin_slug != "." && 
			$plugin_slug != ".." && 
			$plugin_slug != "index.php" && 
			!empty($plugin_slug)  && 
			!strpos($plugin_slug, ".zip") &&
			$plugin_slug != "Plugins_Files" ) {
			$buildPluginName = "";
			$parsePluginSlug = explode("_", $plugin_slug);
			foreach ($parsePluginSlug as $name_part) {
				$buildPluginName .= ucfirst($name_part) ." ";
			}
			$buildPluginName = trim($buildPluginName);

			//Parse plugin description
			$store_description = "";
			$parse_description = fopen("../../Authors/$sender/Plugins/$plugin_slug/meta-description.txt", "r") or die("Fatal.");
			while ( !feof($parse_description) ) {
				$parse_line = trim( fgets($parse_description) );
				$store_description .= $parse_line;
			}
			fclose($parse_description);

			$get_plugin_icon = explode("~icon: ", $store_description)[1];
			$get_plugin_icon = explode(";", $get_plugin_icon)[0];

			$build_button = "
			<a href='openPluginMenu.php?".$plugin_slug."'>
				<div class='img' style='background-image: url(".$get_plugin_icon."); background-size: cover; background-position: 50%;'></div>
				$buildPluginName
			</a>
			";

			$store_buttons .= $build_button;
		}

		$collected_ += 1;
	}

	if ( $collected_ == 3 ) { $store_buttons = "<h2 class='mt-15'>You don't have any projects :-(</h2>"; } 

	echo $store_buttons;
?>