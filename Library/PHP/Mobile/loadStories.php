<?php
	function parseContent($title, $link, $content, $likes, $author_array, $arg, $id, $author) {
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		$url = NULL;

		if ($content != "") {
			//Split ot extern if lenght is too big
			if ( $arg != "3" ) { $content = splitToExcerpt($content, $id); }

			$content = nl2br($content);

			//Parse shortcodes
			$content = parseShortcodes($content, $id);
			$content = str_replace( "`", "'", str_replace( "&35;", "#", $content ) );
		}
		
		if ($link != "") {
			$isVideo = 0;

			$get_responce = build_url( $link );
			$cmd = $get_responce[0];
			$isVideo = $get_responce[1];

			if ($arg == 0) {$postBuild = parseForUser($id, 1, $title, $cmd, $content, $link, $isVideo, $likes, $author);}
			if ($arg == 1) {$postBuild = parseForViewer($id, 1, $title, $cmd, $content, $link, $isVideo, $likes, $author);}
			if ($arg == 2) {$postBuild = parseForStories($id, 1, $title, $cmd, $author_array, $content, $link, $isVideo);}
			if ($arg == 3) {$postBuild = parseForPagePreview($id, 1, $title, $cmd, $content, $link, $isVideo);}
			if ($arg == 5) {$postBuild = parseForClubs($id, 1, $title, $cmd, $author_array, $content, $link, $isVideo, $author, $likes);}
		}
		else
		if ($postLink == "") {
			if ($arg == 0) {$postBuild = parseForUser($id, 0, $title, 0, $content, "", 0, $likes, $author);}
			if ($arg == 1) {$postBuild = parseForViewer($id, 0, $title, 0, $content, "", 0, $likes, $author);}
			if ($arg == 2) {$postBuild = parseForStories($id, 0, $title, 0, $author_array, $content, $link, $isVideo);}
			if ($arg == 3) {$postBuild = parseForPagePreview($id, 0, $title, 0, $content, $link, $isVideo);}
			if ($arg == 5) {$postBuild = parseForClubs($id, 0, $title, 0, $author_array, $content, $link, $isVideo, $author, $likes);}
		}

		return $postBuild;
	}

	function parseForUser($getId, $arg, $title, $cmd, $content, $image_link, $isVideo, $likes, $author) {
		if ($likes == "" || $likes == "NULL") { $countLikes = 0; }
		else {
			$countLikes = count(explode(",", $likes));
		}

		$overlay_header = "<h1 id='getLikes' class='likes' onclick='previewLikes(\"$getId\", 1);'><span>&#xf004;</span>$countLikes</h1>";
		$repost_button = "<button type='button' class='repost-button' onclick='repostPost(\"$getId\", \"\");'><span>&#xf064;</span>Repost</button>";

		if ($isVideo == 0) {
			$cmd = "<a href='$image_link' data-lightbox='roadtrip'>$cmd</a>";
		}

		/*
		if ($isVideo == 0) { $preview_button = "<a href='$image_link' data-lightbox='roadtrip'>Preview picture</a>"; }
		else
		if ($isVideo == 1) { $preview_button = "<a href='#!' class='play-button' onclick='playVideo(\"$getId\", \"youtube\");'>Play video</a>"; }
		else
		if ($isVideo == 2) { $preview_button = "<a href='#!' class='play-button' onclick='playVideo(\"$getId\", \"vimeo\");'>Play video</a>"; }
		*/

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td id='poster'>
							<div id='quickMenu'>
								<a href='#!' onclick=\"editPost('$title', '$getId')\" class='left'>Edit<a>
								<a href='#!' onclick=\"deletePost('$title', '$getId')\" class='right'>Delete</a><br>
			";

			$title = str_replace("6996", " ", $title);
			$title = str_replace("-id-", "", $title);
			$title = str_replace("`", "'", $title);

			$build .= "
							<h1 onclick='shareGlobalPost(\"".$author."\", ".$getId.", 1);' class='share-title'>$title</h1>
							</div>
							$cmd
							<p>
								$content
							</p>
							<div id='overlay-options'>
								$overlay_header
							</div>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<br>
						</td>
					</tr>
				</tbody>
			";			
		} else 
			if ($arg == 0) {
				$build = "
					<tbody class='$getId'>
						<tr>
							<td>
							</td>
							<td id='poster'>
								<div id='quickMenu'>
									<a href='#!' onclick=\"editPost('$title', '$getId')\" class='left'>Edit<a>
									<a href='#!' onclick=\"deletePost('$title', '$getId')\" class='right'>Delete</a><br>
				";

				$title = str_replace("6996", " ", $title);
				$title = str_replace("-id-", "", $title);
				$title = str_replace("`", "'", $title);

				$build .= "
								<h1 onclick='shareGlobalPost(\"".$author."\", ".$getId.", 1);' class='share-title'>$title</h1>
								</div>
								<p>
									$content
								</p>
								<div id='overlay-options'>
									$overlay_header
								</div>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
								<br>
							</td>
						</tr>
					</tbody>
				";
			}

			/*$build .= "
				<script>
					scaleContainers(1, '#poster,#overlay-options,$getId');
				</script>
			";*/

		return $build;
	}

	function parseForViewer($getId, $arg, $title, $cmd, $content, $image_link, $isVideo, $likes, $author) {
		$title = str_replace("6996", " ", $title);
		$title = str_replace("-id-", "", $title);
		$title = str_replace("`", "'", $title);

		$getOwner = $_COOKIE['blogSender'];

		session_start();
		$sender = $_SESSION['sender'];

		if ($likes == "" || $likes == "NULL") { 
			$countLikes = 0; 
			$addLikeButtonClass = "not-liked";
		}
		else {
			$convertLikers = explode(",", $likes);
			$countLikes = count($convertLikers);
			
			if (in_array($sender, $convertLikers) || $convertLikers == $sender) { $addLikeButtonClass = "liked"; }
			else { $addLikeButtonClass = "not-liked"; }
		}

		if ($isVideo == 0) {
			$cmd = "<a href='$image_link' data-lightbox='roadtrip'>$cmd</a>";
		}

		$overlay_header = "<h1 class='likes $addLikeButtonClass' onclick='likeUnlikePost(\"$getId\", \"$getOwner\", 1);'><span>&#xf004;</span>$countLikes</h1>";
		$repost_button = "<button type='button' class='repost-button' onclick='repostPost(\"$getId\", \"$getOwner\", 1);'><span>&#xf064;</span>Repost</button>";
		$splitter = "<span class='bullet'>&bull;</span>";

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td id='poster'>
							<h1 onclick='shareGlobalPost(\"".$author."\", ".$getId.", 1);' class='share-title'>$title</h1>
							$cmd
							<p>
								$content
							</p>
							<div id='overlay-options'>
								$overlay_header
								$splitter
								$repost_button
							</div>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<br>
						</td>
					</tr>
				</tbody>
			";			
		} else 
			if ($arg == 0) {
				$build = "
					<tbody class='$getId'>
						<tr>
							<td>
							</td>
							<td id='poster'>
								<h1 onclick='shareGlobalPost(\"".$author."\", ".$getId.", 1);' class='share-title'>$title</h1>
								<p>
									$content
								</p>
								<div id='overlay-options'>
									$overlay_header
									$splitter
									$repost_button
								</div>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
								<br>
							</td>
						</tr>
					</tbody>
				";
			}

		return $build;
	}

	function parseForStories($getId, $arg, $title, $cmd, $author, $content, $image_link, $isVideo) {
		$title = str_replace("6996", " ", $title);
		$title = str_replace("-id-", "", $title);
		$title = str_replace("`", "'", $title);

		if ($isVideo == 0) {
			$cmd = "<a href='$image_link' data-lightbox='roadtrip'>$cmd</a>";
		}

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td class='poster-header'>
							<div id='history'>
								<a href='openBloger.php?".$author[0]."'>
									<div class='img' style='background-image: url($author[1]); background-size: cover; background-position: 50%;'></div>
								</a>
							</div>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td id='poster' class='arrow-container'>
							<div id='history-right'>
								<h1>$title</h1>
							</div>
							$cmd
							<p>
								$content
							</p>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<br>
						</td>
					</tr>
				</tbody>
			";
		} else
			if ($arg == 0) {
				$build = "
					<tbody class='$getId'>
						<tr>
							<td>
							</td>
							<td class='poster-header'>
								<div id='history'>
									<a href='openBloger.php?".$author[0]."'>
										<div class='img' style='background-image: url($author[1]); background-size: cover; background-position: 50%;'></div>
									</a>
								</div>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
							</td>
							<td id='poster' class='arrow-container'>
								<div id='history-right'>
									<h1>$title</h1>
								</div>
								<p>
									$content
								</p>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
								<br>
							</td>
						</tr>
					</tbody>
				";
			}

		return $build;
	}

	function parseForPagePreview($getId, $arg, $title, $cmd, $content, $image_link, $isVideo) {
		$title = str_replace("6996", " ", $title);
		$title = str_replace("-id-", "", $title);
		$title = str_replace("`", "'", $title);

		if ($likes == "" || $likes == "NULL") { $countLikes = 0; }
		else {
			$countLikes = count(explode(",", $likes));
		}

		if ($isVideo == 0) {
			$cmd = "<a href='$image_link' data-lightbox='roadtrip'>$cmd</a>";
		}

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td id='poster' onmouseover='scaleContainers(1, \"#poster,#overlay-options,$getId\", 0);'>
							<h1>$title</h1>
							$cmd
							<p>
								$content
							</p>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<br>
						</td>
					</tr>
				</tbody>
			";			
		} 
		else 
		if ($arg == 0) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td id='poster' onmouseover='scaleContainers(1, \"#poster,#overlay-options,$getId\", 0);'>
							<h1>$title</h1>
							</div>
							<p>
								$content
							</p>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<br>
						</td>
					</tr>
				</tbody>
			";
		}

		return $build;
	}

	function parseForClubs($getId, $arg, $title, $cmd, $author, $content, $image_link, $isVideo, $sender, $likes) {
		if ($isVideo == 0) {
			$cmd = "<a href='$image_link' data-lightbox='roadtrip' class='featured-image-activate'>$cmd</a>";
		}

		if ($likes == "" || $likes == "NULL") { 
			$countLikes = 0; 
			$addLikeButtonClass = "not-liked";
		}
		else {
			$convertLikers = explode(",", $likes);
			$countLikes = count($convertLikers);
			
			if (in_array($sender, $convertLikers) || $convertLikers == $sender) { $addLikeButtonClass = "liked"; }
			else { $addLikeButtonClass = "not-liked"; }
		}

		session_start();

		if ( isset($_SESSION["club_likes"]) && $_SESSION["club_likes"] == 1 ) {
			$overlay_header = "<h1 class='likes $addLikeButtonClass' onclick='likeUnlikeClubPost(\"$getId\", 1);'><span>&#xf004;</span>$countLikes</h1>";
		} else {
			$overlay_header = "";
		}

		if ( isset($_SESSION["club_comments"]) && $_SESSION["club_comments"] == 1 ) {
			$repost_button = "<button type='button' class='repost-button' onclick='openComments(\"$getId\", 1);'><span>&#xf0f4;</span>Comments</button>";
		}

		if ( isset($_SESSION["club_likes"])
		&& isset($_SESSION["club_likes"]) 
		&& $_SESSION["club_likes"] == 1
		&& $_SESSION["club_comments"] == 1 ) {
			$splitter = "<span class='bullet'>&bull;</span>";
		} else {
			$splitter = "";
		}

		if ( $sender == $author[0] || in_array($sender, $_SESSION['club_administrators']) ) {
			$buildHeader = "
				<div id='quickMenu'>
					<a href='#!' onclick=\"editClubPost('$title', '$getId', 1)\" class='left'>Edit<a>
					<a href='#!' onclick=\"deleteClubPost('$title', '$getId', 1)\" class='right'>Delete</a><br>
			";

			$title = str_replace("6996", " ", $title);
			$title = str_replace("-id-", "", $title);
			$title = str_replace("`", "'", $title);
		
			$buildHeader .= "
					<h1 class='hover-none'>$title</h1>
				</div>
			";
		} else {
			$title = str_replace("6996", " ", $title);
			$title = str_replace("-id-", "", $title);
			$title = str_replace("`", "'", $title);

			$buildHeader = "<h1>$title</h1>";
		}

		if ($arg == 1) {
			$build = "
				<tbody class='$getId'>
					<tr>
						<td>
						</td>
						<td class='poster-header'>
							<div id='history'>
								<a href='openBloger.php?".$author[0]."'>
									<div class='img' style='background-image: url($author[1]); background-size: cover; background-position: 50%;'></div>
								</a>
							</div>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td id='poster' class='arrow-container'>
							<div id='history-right'>
								$buildHeader
							</div>
							$cmd
							<p>
								$content
							</p>
							<div id='overlay-options' style='padding: 5px 7.5px;'>
								$overlay_header
								$splitter
								$repost_button
							</div>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<br>
						</td>
					</tr>
				</tbody>
			";
		} else
			if ($arg == 0) {
				$build = "
					<tbody class='$getId'>
						<tr>
							<td>
							</td>
							<td class='poster-header'>
								<div id='history'>
									<a href='openBloger.php?".$author[0]."'>
										<div class='img' style='background-image: url($author[1]); background-size: cover; background-position: 50%;'></div>
									</a>
								</div>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
							</td>
							<td id='poster' class='arrow-container'>
								<div id='history-right'>
									$buildHeader
								</div>
								<p>
									$content
								</p>
								<div id='overlay-options' style='padding: 5px 7.5px;'>
									$overlay_header
									$splitter
									$repost_button
								</div>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
								<br>
							</td>
						</tr>
					</tbody>
				";
			}

		return $build;
	}
?>
