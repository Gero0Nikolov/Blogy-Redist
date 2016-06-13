<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$plugin_name = $_POST["pluginName"];
	$plugin_slug = $_POST["pluginSlug"];
	$plugin_author = $_POST["pluginAuthor"];

	$meta_dir = "../../Authors/$plugin_author/Plugins/$plugin_slug/meta-description.txt";
	$response = "";

	if ( file_exists($meta_dir) ) { 
		$line_count = 0;
		$open_meta = fopen($meta_dir, "r");
		while ( !feof($open_meta) ) {
			$get_meta = fgets( $open_meta );
			
			if ( $line_count == 1 ) {
				$plugin_icon = trim( explode(";", explode("~icon:", $get_meta)[1] )[0] );
				break;
			}

			$line_count += 1;
		}
		fclose($open_meta);
	
		$response = "
			<a id='$plugin_slug' href='pluginStore.php?s=$plugin_slug'>
				<div style='background: url(\"$plugin_icon\"); background-size: cover; background-position: 50%;' class='img'></div>
				$plugin_name
			</a>
		";
	}
	

	echo $response;
?>