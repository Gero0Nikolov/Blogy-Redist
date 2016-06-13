<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	//Include bundle
	include "Universal/functions.php";

	$getParameters = $_SERVER["QUERY_STRING"];

	$getPluginSlug = explode("%", $getParameters)[0];

	if ( strpos($getPluginSlug, "_") ) {
		$getPluginName = "";
		$parseSlug = explode("_", $getPluginSlug);
		foreach ($parseSlug as $word) {
			$getPluginName .= ucfirst($word) ." ";
		}
	} else {
		$getPluginName = ucfirst($getPluginSlug) ." ";
	}

	$plugin_path = "../Authors/$sender/Plugins/$getPluginSlug";

	if ( empty($getPluginSlug) ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }
	if ( !file_exists( $plugin_path ) ) { echo "<script>window.location='openPluginsDashboard.php';</script>"; die(); }

	$openedFile = explode("%", $getParameters)[1];

	if ( empty($openedFile) ) { echo "<script>window.location='openBoard.php?$getPluginSlug%../Authors/$sender/Plugins/$getPluginSlug/$getPluginSlug.php'</script>"; }

	$openedFile = str_replace("~", "/", $openedFile);
	$openedFile = str_replace("+", " ", $openedFile);

	$file_name = end( explode("/", $openedFile) );

	//Open file
	$open_file = fopen($openedFile, "r");
	$read_file = trim( fread($open_file, filesize($openedFile)) );
	fclose($open_file);

	$read_body = htmlentities( $read_file );

	$get_file_type = strtolower( end( explode(".", $openedFile) ) );
	if ( $get_file_type == "js" ) { $mode = "javascript"; }
	elseif ( $get_file_type == "php" ) { $mode = "php"; }
	elseif ( $get_file_type == "html" ) { $mode = "html"; }
	elseif ( $get_file_type == "css" ) { $mode = "css"; }
	else { $mode = "text"; }
?>
	<link rel='shortcut icon' href='../images/oBoard.png' type='image/x-icon'>
	<link rel='icon' href='../images/oBoard.png' type='image/x-icon'>
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="../images/oBoard.png" />

	<title><?php echo $getPluginName .'- '. $file_name; ?></title>
	<link href='../Styles/open-board/open-board.css' rel='stylesheet' type='text/css' media='screen'>
	<link href='../../../fonts.css' rel='stylesheet' type='text/css' media='screen'>

	<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
	<script type='text/javascript' src='../Styles/open-board/java.js'></script>
</head>
<body>
	<div id="dev-view">
		<div id="control-board">
			<h1>Dev-View</h1>
			<button id="close-dev-view" class="iconic">&#xf00d;</button>
		</div>
		<iframe id="dev-view-window" src="http://<?php echo $_SERVER[ "HTTP_HOST" ]; ?>"></iframe>
	</div>
	<div id="folder-explorer">
		<div id="sub-menu">
			<button id="compile-button">Compile</button>
			<span class="bullet">&bull;</span>
			<button onclick="openPopUp();">Themes</button>
		</div>
		<h1>Explorer :</h1>
		<?php
			list_folder_tree($plugin_path, $getPluginSlug);
		?>
		<div id="dev-view-holder">
			<button id="iframe-opener" class="iconic" title="View plugin in Dev-View">&#xf1e6;</button>
		</div>
	</div>
	<div id="editor"><?php echo $read_body; ?></div>

	<script src="../Ace-Editor/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
	<script>
		editorTheme = localStorage.editorTheme;

	    var editor = ace.edit("editor");
	    if ( editorTheme !== undefined ) {
	    	if ( editorTheme == "pod" ) {
	    		editorTheme = "pastel_on_dark";
	    	}
	    	editor.setTheme("ace/theme/"+ editorTheme);
	    } else {
	    	editorTheme = "monokai";
	    	editor.setTheme("ace/theme/"+ editorTheme);
	    }
	    editor.getSession().setMode("ace/mode/<?php echo $mode; ?>");
		$( "body" ).addClass( editorTheme );
	</script>
</body>
</html>