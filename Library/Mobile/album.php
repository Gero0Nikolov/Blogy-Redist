<?php 
	session_start();
	$sender = $_SESSION[ "sender" ];
	if ( !isset( $sender ) ) { header("Location: http://". $_SERVER["HTTP_HOST"]); }

	$sender_first_name = $_SESSION["senderFN"];
	$sender_last_name = $_SESSION["senderLN"];
	$sender_profile_picture = $_SESSION["senderImg"];

	//Server path
	$server_path = "/home/blogycoo/public_html/";

	//Album
	$album_src = $server_path ."Library/Authors/$sender/Album/";
	if ( !file_exists( $album_src ) ) {
		mkdir( $album_src, 0777 );
		$security_index = fopen( "$album_src/index.php", "w" ) or die( "Fatal: Unable to build security index." );
		fwrite( $security_index, "<?php header('Location: ". $_SERVER[ "HTTP_HOST" ] ."');?>" );
		fclose( $security_index );
	}

	$album_elements = 0;

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "CREATE TABLE albumOf$sender (ID int NOT NULL AUTO_INCREMENT, ALBUM LONGTEXT, SPACE LONG, PRIMARY KEY (ID))";
	if ($conn->query($sql) === TRUE) {
		$sql = "INSERT INTO albumOf$sender (ALBUM, SPACE) VALUES ('SPACE', '100000000')";
		$conn->query($sql);
		$getSizeInMB = 100.00;
	} else {
		$sql = "SELECT ID, ALBUM, SPACE FROM albumOf$sender ORDER BY ID ASC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ($row['ALBUM'] == "SPACE") {
					$getSizeInMB = (int)$row['SPACE'] * 0.000001;
					$getSizeInMB = number_format ($getSizeInMB, 2);
					break;
				}
			}

			$album_elements = $pick->num_rows - 1;
		}
	}

	//Close the connection
	$conn->close();

	$width_class = "width-50";
	if ( $getSizeInMB < 5.00 ) { $width_class = "width-100"; }
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "header.php"; ?>
	<title><?php echo $sender_first_name; ?>'s album</title>
	<script type="text/javascript">
		var lockScroll = 0;
		$( document ).scroll(function(){
			win = $( window );
			if ( $( document ).height() - win.height() <= win.scrollTop() + 200 && lockScroll == 0 ) {
				listAlbum();
				lockScroll = 1;
			}
		});

		var imagesOffset = 0;
		var totalElements = <?php echo $album_elements; ?>;

		function openFileChooseDialog() {
			$( "#fileToUpload" ).click();
		}
	</script>
</head>
<body>
	<?php include "primary-menu.php"; ?>
	<div id="body-container" class="full-body-container">
		<div id="album-statistics" class="header-container">
			<div class="left <?php echo $width_class; ?>">
				<h2>Free space: <?php echo $getSizeInMB; ?>mb</h2>
			</div>
			<?php if ( $getSizeInMB > 5.00 ) { ?>
			<div class="right <?php echo $width_class; ?>">
				<button id="album-upload" onclick="openFileChooseDialog();"><span class="iconic">&#xf0ee;</span>Upload</button>
			</div>
			<form id="toUpload" style="display: none;" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" id="fileToUpload" onchange="startToUpload()">
			</form>
			<?php } ?>
		</div>
		<div id="album-list">
		</div>
	</div>
</body>