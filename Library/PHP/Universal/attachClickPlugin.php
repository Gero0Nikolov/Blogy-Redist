<?php 
	$plugin_path = $_POST[ "pluginPath" ];

	$build_plugin = call_plugin( $plugin_path );

	echo $build_plugin;

//Functions:
	//Call plugins
	function call_plugin($target_) {
		ob_start();  // start buffer
		include( $target_ );  // read in buffer
		$get_content = ob_get_contents();  // get buffer content
		ob_end_clean();
		return $get_content;
	}
?>