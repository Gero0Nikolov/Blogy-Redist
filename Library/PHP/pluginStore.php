<?php 
	include "header.php";

	$tab_ = strtolower( explode( "=", $_SERVER["QUERY_STRING"] )[1] );
	if ( !isset($tab_) || empty($tab_) ) { echo"<script>window.location='pluginStore.php?tab=Featured'</script>"; }

	if ( $tab_ == "featured") {
		$activate_featured_tab = "active";
	} elseif ( $tab_ == "popular" ) {
		$activate_popular_tab = "active";
	} elseif ( $tab_ == "all" ) {
		$activate_all_tab = "active";
	}
?>
	<title>Plugin Store</title>
</head>
<body onload="preloadPlugins('<?php echo $tab_; ?>', 0);">
	<?php
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>

	<div id="plugin-store-body">
		<div id="links-holder">
			<div id="links">
				<a href="pluginStore.php?tab=Featured" class="link-block <?php echo "$activate_featured_tab"; ?>">Featured</a>
				<a href="pluginStore.php?tab=Popular" class="link-block seperate <?php echo "$activate_popular_tab"; ?>">Popular</a>
				<a href="pluginStore.php?tab=All" class="link-block <?php echo "$activate_all_tab"; ?>">All</a>
			</div>
			<div id="search">
				<input type="text" id="plugin-search" placeholder="Search for.." onkeydown="searchForPlugin(event);">
			</div>
		</div>
		<div id="plugins-list">
		</div>
	</div>
</body>