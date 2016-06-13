<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$plugin_id = $_POST["pluginID"];
	$plugin_name = $_POST["pluginName"];
	$plugin_slug = $_POST["pluginSlug"];
	$plugin_path = $_POST["pluginPath"];
	$plugin_state = $_POST["pluginState"];
	$plugin_author = $_POST["pluginAuthor"];
	$plugin_store_id = $_POST["pluginStoreID"];
	$mobile = $_POST["mobile"];

	$plugin_path = str_replace("http://blogy.co/", "/home/blogycoo/public_html/", $plugin_path);
	$plugin_meta = $plugin_path."/meta-description.txt";
	$controller_ = "";

	if ( file_exists($plugin_meta) && filesize($plugin_meta) > 0 ) {
		$open_target = fopen($plugin_meta, "r");
		$get_meta = fread( $open_target, filesize($plugin_meta) );
		fclose($open_target);

		$get_meta_icon = trim( explode( ";", explode( "~icon:", $get_meta )[1] )[0] );
		$get_meta_description = nl2br( trim( explode( ";", explode( "~description:", $get_meta )[1] )[0] ) );
		$get_meta_license = ucwords( trim( explode( ";", explode("~license:", $get_meta )[1] )[0] ) );

		if ( $plugin_state ==  1 ) {
			$activate_deactive_button = "<button id='power-button' class='deactivate-button' onclick='activateDeactivatePlugin($plugin_id, $mobile)'><span class='iconic'>&#xf127;</span>Deactivate</button>";
		} elseif ( $plugin_state == 0 ) {
			$activate_deactive_button = "<button id='power-button' class='activate-button' onclick='activateDeactivatePlugin($plugin_id, $mobile);'><span class='iconic'>&#xf0c1;</span>Activate</button>";
		}

		if ( $sender != $plugin_author ) { $uninstall_button = "<span class='bullet'>•</span><button class='uninstall-button' onclick='uninstallPlugin($plugin_store_id, $mobile);'><span class='iconic'>&#xf12d;</span>Uninstall</button>"; }

		$controller_ = "
			<div class='plugin-controller'>
				<div id='plugn-$plugin_id' class='plugin-icon' style='background-image: url($get_meta_icon); background-size: cover; background-position: 50%;' ></div>
				<div class='description-container'>
					<h1 class='plugin-name'>$plugin_name</h1>
					<h2 class='plugin-license'>License: $get_meta_license</h2>
					<p class='plugin-description'>$get_meta_description</p>
					<h3 class='plugin-author'>By: <a href='openBloger.php?$plugin_author'>$plugin_author</a></h3>
				</div>
				<div class='plugin-options'>
					$activate_deactive_button
					$uninstall_button
				</div>
			</div>
		";	
	}

	//Return response
	echo $controller_;
?>