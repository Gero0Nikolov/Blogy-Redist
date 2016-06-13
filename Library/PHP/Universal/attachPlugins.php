<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$plugin_path = str_replace("http://blogy.co/", "/home/blogycoo/public_html/", $_POST["pluginPath"]) ."/";
	$plugin_slug = $_POST["pluginSlug"];
	$plugin_name = $_POST["pluginName"];
	$original_path = $_POST["pluginPath"] ."/";

	$build_plugin = "";

	$call_tag = $_SERVER["HTTP_REFERER"];

	//Check descriptions
	$open_meta = fopen($plugin_path ."meta-description.txt", "r");
	$read_meta = fread($open_meta, filesize($plugin_path ."meta-description.txt"));
	fclose($open_meta);

	//Check call event
	$call_event = trim( explode(";", explode("~call-on:", $read_meta)[1] )[0] );

	if ( $call_event == "onload" ) { 
		
		if ( !strpos( $call_tag, "openBloger" ) ) {
			$call_event = "onload";
			$build_plugin = call_plugin( $plugin_path . $plugin_slug .".php" );
		}

	}
	elseif ( $call_event == "onload_both" ) { 
		$call_event = "onload_both";
		$build_plugin = call_plugin( $plugin_path . $plugin_slug .".php" );
	}
	elseif ( $call_event == "onload_visitor" ) { 

		if ( strpos( $call_tag, "openBloger" ) ) {
			$call_event = "onload_visitor"; 
			$build_plugin = call_plugin( $plugin_path . $plugin_slug .".php" );
		}
	}
	elseif ( $call_event == "click" ) {

		if ( !strpos( $call_tag, "openBloger" ) ) {
			$call_event = "onclick";

			//Get plugin icon
			$plugin_icon = trim( explode(";", explode("~icon:", $read_meta)[1] )[0] );

			$build_plugin = "
				<button class='plugin-button' onclick='runOnClickHook(\"$plugin_path$plugin_slug.php\");'>
					<div class='plugin-logo' style='background-image: url($plugin_icon); background-size: cover; background-position: 50%;'></div>
					$plugin_name
				</button>
			";
		}
	}

	//Build response
	$response = $call_event ."~||~". $build_plugin;

	//Return response
	echo $response;

//Functions:
	//Call plugins
	function call_plugin($target_) {
		ob_start();  // start buffer
		include($target_);  // read in buffer
		$get_content = ob_get_contents();  // get buffer content
		ob_end_clean();
		return $get_content;
	}
?>