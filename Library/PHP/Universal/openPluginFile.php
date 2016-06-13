<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getBreadcrumbs = $_POST["breadcrumbs"];

	$build_file = "";
	$openFile = fopen("../../Authors/$sender/Plugins/$getBreadcrumbs", "r");
	while ( !feof($openFile) ) {
		$build_file .= fgets($openFile);
	}
	fclose($openFile);

	//Return responce
	echo $build_file;
?>