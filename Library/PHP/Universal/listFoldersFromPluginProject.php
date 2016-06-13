<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getSlug = $_POST["pluginSlug"];
	$getType = $_POST["type"];
	$getTarget = "../../Authors/$sender/Plugins/".$_POST["breadcrumbs"];
	$getTargetName = $_POST["breadcrumbs"];
	$mobile = $_POST["mobile"];

	$getFoldersArray = array();
	array_push($getFoldersArray, "../../Authors/$sender/Plugins/$getSlug"); // Add the main folder
	listFolders( "../../Authors/$sender/Plugins/$getSlug" );
	
	$response = "";

	$folder_counter = 1;
	foreach ( $getFoldersArray as $folder ) {
		if ( $folder != $getTarget && !strpos($folder, $getTargetName) ) {
			$folder_name = end( explode("/", $folder) );
			$response .= "<button id='folder-$folder_counter' class='folder-holder' onclick='selectFolder(\"$folder\", \"#folder-$folder_counter\", \"$getTarget\", \"$getType\", $mobile);'><span class='iconic'>&#xf07b;</span>$folder_name</button>";
			$folder_counter += 1;
		}
	}

	//Return response
	echo $response;

	//Functions

	//List directory & subdirectories
	function listFolders($dir){
	    $ffs = scandir($dir);
	    
	    foreach( $ffs as $ff ) {
	        if( $ff != '.' && $ff != '..' ) {
	            if( is_dir($dir.'/'.$ff) ) {
	            	array_push($GLOBALS["getFoldersArray"], $dir.'/'.$ff);
	            	listFolders($dir.'/'.$ff);
	            }
	        }
	    }
	}
?>