<?php 
	include "../Universal/functions.php";
	include "loadStories.php";

	if (strpos($_SERVER[REQUEST_URI], "?")) $get_post_argument = end(explode("?", $_SERVER[REQUEST_URI]));
	else $get_post_argument = NULL;

	if ( !isset($get_post_argument) && empty($get_post_argument) ) {
		blockAccess();
	} else {
		$get_post_argument = str_replace("%40", "@", $get_post_argument);
		$get_post_argument = str_replace("%3D", "=", $get_post_argument);

		$get_post_author = explode("=", explode("@", $get_post_argument)[0])[1];
		$get_post_id = explode("=", explode("@", $get_post_argument)[1])[1];
	}

	//Get story//
 
	//Connect to the Database
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT STORYTITLE, STORYLINK, STORYCONTENT FROM stack".$get_post_author." WHERE ID=".$get_post_id;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getTitle = $row["STORYTITLE"];
			$getLink = $row["STORYLINK"];
			$getContent = $row["STORYCONTENT"];
			$getLikes = $row["LIKES"];

			$isFinded = 1;
		}
	}

	//Close the connection
	$conn->close();

	//Parse author
	$line_counter = 0;
	$parseOwner = fopen("../../Authors/".$get_post_author."/config.txt", "r") or die("Fatal: Unable to start parsing.");
	while ( !feof($parseOwner) ) {
		$line = trim(fgets($parseOwner));
		if ($line_counter == 0) { $get_author_img = $line; }
		elseif ($line_counter == 3) { $get_author_fn = $line; }
		elseif ($line_counter == 4) { $get_author_ln = $line; break;}
		$line_counter++;
	}

	$convertTitle = str_replace("6996", " ", $getTitle);
?>

<html>
	<META http-equiv='content-type' content='text/html; charset=utf-8'>
	<head>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<title><?php echo $convertTitle; ?></title>
		<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>

		<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
		<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
		<script src='../../../LightBox/js/lightbox.min.js'></script>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>

		<script type='text/javascript' src='../../../java.js'></script>

		<style type="text/css">
			#poster { opacity: 1 !important; transform: translate(0) !important; }
		</style>
	</head>
	<body>
		<div id='menu'>
			<a href='../../../index.html' class='logo-button'><img src='../../images/Blogy-ICO.png' /></a>
		</div>

		<?php
			if ( isset($isFinded) ) {
		?>
			<div id='blogger-info-container'>
				<a href="http://<?php echo $_SERVER["HTTP_HOST"]."?".$get_post_author; ?>">
					<div class='img' style='background-image: url(<?php echo $get_author_img; ?>); background-size: cover; background-position: 50%;'></div>
					<?php echo $get_author_fn." ".$get_author_ln; ?>
				</a>
			</div>

			<div id='body'>
				<table id='main-table'>
				<?php
				//Print story build
				echo parseContent($getTitle, $getLink, $getContent, $getLikes, 0, "3", $getId, $get_post_author);
				?>
				</table>
			</div>
		<?php
			} else {
		?>
			<h2 id='error-message'>Oops, it seems that story is missing.</h2>
		<?php
			}
		?>

	</body>
</html>