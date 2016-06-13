<?php 
	//Include functions bundle
	include "Universal/functions.php";
	//Include header
	include "header.php";

	$star = "&#xf005;";
?>

<title>Badges Story</title>

<script type="text/javascript">
	$( document ).ready(function(){
		id = window.location.href.split( "b=" )[1];
		$( 'body' ).animate({
       		scrollTop: $( "#"+ id ).offset().top},
        1000);
	});
</script>
</head>
<body class="badges-page">
	<?php
	//Load menus
	include "loadMenu.php";
	include "loadSuggestedBlogers.php";
	?>
	<div class="badges-container">
		<?php //Story Teller
		$badge = "&#xf0f4;";
		?>
		<div id="st" class="badge-container">
			<div class="left-col">
				<div class="badge-icon st">
					<?php echo $badge; ?>
					<div class="overlay-text">
						<h1 class="text">Basic</h1>
					</div>
				</div>
				<div class="badge-icon st">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Advanced</h1>
					</div>
				</div>
				<div class="badge-icon st">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Master</h1>
					</div>
				</div>
				<div class="badge-icon st">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Chief</h1>
					</div>
				</div>
			</div>
			<div class="right-col badge-text">
				<h1>Story Teller</h1>
				<p>This badge is the badge of the <strong>Story Tellers</strong>.</p> 
				<p>It can be achieved after posting your first <strong>10 stories</strong>.</p>
				<p>The <strong>Story Teller</strong> badge is a sign of <span class="highlight">love</span> and <span class="highlight">creativity</span>.</p>
				<p>It shows that you are participating actively in the growing process of Blogy and we <span class="highlight">strongly appreciate</span> this!</p>
			</div>
		</div>

		<?php //Messenger
		$badge = "&#xf086;";
		?>
		<div id="ms" class="badge-container">
			<div class="left-col">
				<div class="badge-icon ms">
					<?php echo $badge; ?>
					<div class="overlay-text">
						<h1 class="text">Basic</h1>
					</div>
				</div>
				<div class="badge-icon ms">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Advanced</h1>
					</div>
				</div>
				<div class="badge-icon ms">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Master</h1>
					</div>
				</div>
				<div class="badge-icon ms">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Chief</h1>
					</div>
				</div>
			</div>
			<div class="right-col badge-text">
				<h1>Messenger</h1>
				<p>This badge is the badge of the <strong>Messenger</strong>.</p> 
				<p>It can be achieved when you hit chats with at least <strong>5 different Bloggers</strong>.</p>
				<p>The <strong>Messenger</strong> badge is a sign of <span class="highlight">social personality</span> which is <span class="highlight">amazing</span>.</p>
			</div>
		</div>

		<?php //Plugin Dev
		$badge = "&#xf1e6;";
		?>
		<div id="pd" class="badge-container">
			<div class="left-col">
				<div class="badge-icon pd">
					<?php echo $badge; ?>
					<div class="overlay-text">
						<h1 class="text">Basic</h1>
					</div>
				</div>
				<div class="badge-icon pd">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Advanced</h1>
					</div>
				</div>
				<div class="badge-icon pd">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Master</h1>
					</div>
				</div>
				<div class="badge-icon pd">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Chief</h1>
					</div>
				</div>
			</div>
			<div class="right-col badge-text">
				<h1>Plugin Developer</h1>
				<p>This badge is the badge of the <strong>Blogy Developers</strong>.</p> 
				<p>It can be achieved when you create your first <strong>Blogy Plugin</strong>.</p>
				<p>The <strong>Plugin Developer</strong> badge is a sign of <span class="highlight">trust</span> and <span class="highlight">love</span>.</p>
				<p>One BIG thank you! To the all of the <span class="hightlight">Believers</span>!</p>
			</div>
		</div>

		<?php //Leader
		$badge = "&#xf21d;";
		?>
		<div id="ld" class="badge-container">
			<div class="left-col">
				<div class="badge-icon ld">
					<?php echo $badge; ?>
					<div class="overlay-text">
						<h1 class="text">Basic</h1>
					</div>
				</div>
				<div class="badge-icon ld">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Advanced</h1>
					</div>
				</div>
				<div class="badge-icon ld">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Master</h1>
					</div>
				</div>
				<div class="badge-icon ld">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Chief</h1>
					</div>
				</div>
			</div>
			<div class="right-col badge-text">
				<h1>Leader</h1>
				<p>This badge is the badge of the <strong>Leaders</strong>.</p> 
				<p>You'll achieve it once you have been followed by <strong>5 different bloggers</strong>.</p>
				<p>The <strong>Leader</strong> badge is a sign of <span class="highlight">popularity</span> and <span class="highlight">original thinking</span>.</p>
			</div>
		</div>

		<?php //Follower
		$badge = "&#xf1ae;";
		?>
		<div id="fr" class="badge-container">
			<div class="left-col">
				<div class="badge-icon fr">
					<?php echo $badge; ?>
					<div class="overlay-text">
						<h1 class="text">Basic</h1>
					</div>
				</div>
				<div class="badge-icon fr">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Advanced</h1>
					</div>
				</div>
				<div class="badge-icon fr">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Master</h1>
					</div>
				</div>
				<div class="badge-icon fr">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Chief</h1>
					</div>
				</div>
			</div>
			<div class="right-col badge-text">
				<h1>Follower</h1>
				<p>This badge is the badge of the <strong>Followers</strong>.</p> 
				<p>You'll become a loyal follower once you've followed <strong>5 different bloggers</strong>.</p>
				<p>The <strong>Follower</strong> badge is a sign of <span class="highlight">trust</span> and <span class="highlight">respect</span>.</p>
			</div>
		</div>

		<?php //Cluber
		$badge = "&#xf005;";
		?>
		<div id="cb" class="badge-container">
			<div class="left-col">
				<div class="badge-icon cb">
					<?php echo $badge; ?>
					<div class="overlay-text">
						<h1 class="text">Basic</h1>
					</div>
				</div>
				<div class="badge-icon cb">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Advanced</h1>
					</div>
				</div>
				<div class="badge-icon cb">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Master</h1>
					</div>
				</div>
				<div class="badge-icon cb">
					<?php echo $badge; ?>
					<div class="star-container">
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
						<span class="star"><?php echo $star; ?></span>
					</div>
					<div class="overlay-text">
						<h1 class="text">Chief</h1>
					</div>
				</div>
			</div>
			<div class="right-col badge-text">
				<h1>Cluber</h1>
				<p>This badge is the badge of the <strong>Club owners</strong>.</p> 
				<p>You'll receive it once, you've create your <strong>personal club</strong>.</p>
				<p>The <strong>Cluber</strong> badge is a sign of <span class="highlight">unity</span> and <span class="highlight">leadership</span>.</p>
			</div>
		</div>
	</div>
</body>