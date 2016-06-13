<?php
	include "header.php";

	$getPluginSlug = $_SERVER["QUERY_STRING"];
	if ( empty($getPluginSlug) ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }
	if ( !file_exists("../Authors/$sender/Plugins/$getPluginSlug") ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }

	$_SESSION["plugin_for_patch"] = $getPluginSlug;
?>
	<title>Upload update</title>
</head>
<body>
	<?php
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div id="body">
		<h1>Choose update file</h1>
		<form id="update-container" class="fancy-form" method="POST" enctype='multipart/form-data'>
			<input type="file" id='update-file' name='updateFile' onchange='checkSelectedUpdate();'>
		</form>
		<button id="upload-update" class="submit-button" style="margin: auto auto; display: block;" onclick="uploadPluginUpdate(0);">Upload</button>
	</div>
</body>