var loader_ = "<img src='http://"+window.location.hostname+"/Library/images/loadSign.GIF' alt='Broken loader.' class='loader' />";

//Notifications
var notificationsInterval = setInterval(function(){
	pullNotifications();
}, 2000);
var stopPullingNotifications = 0;

$( document ).on("scrollstart", function(){
});

$( document ).on("scrollstop", function(){
});

//Append Callbacks
$( document ).ready(function(){
	$( "#primary-menu-activator" ).on("tap",function(){
		showHidePrimaryMenu();
	});
	$( "#primary-menu-wrapper" ).on("swipeleft",function( e ){
		if( e.target == this ) { hidePrimaryMenu(); }
	});
	$( "#primary-menu-wrapper" ).on("tap",function( e ){
		if( e.target == this ) { hidePrimaryMenu(); }
	});

	$( "#sidebar-activator .sidebar-button" ).on("tap",function(){
		showHideSidebarMenu();
	});

	$( "#close-sidebar-button" ).on("tap",function(){
		hideSidebarMenu();
	});

	$( "#suggestions-controller" ).on("tap",function(){
		showHideSuggestions();
	});
	$( "#online-friends-controller" ).on("tap",function(){
		showHideOnlineFriends();
	});
	$( "#ohana-controller" ).on("tap",function(){
		showHideOhana();
	});

	$( "#notifications-activator" ).on("tap", function(){
		showHideNotifications();
	});
});

//Functions
function showHidePrimaryMenu() { 
	if ( $( "#primary-menu-wrapper" ).hasClass( "slide-in" ) ) {
		hidePrimaryMenu();
	} else {
		$( "#primary-menu-wrapper" ).addClass( "slide-in" );
	}
}

function hidePrimaryMenu() { $( "#primary-menu-wrapper" ).removeClass( "slide-in" ); }

/** SIDEBAR FUNCTIONS */
function showHideSidebarMenu() {
	if ( $( "#sidebar-menu" ).hasClass( "scale-normal" ) ) {
		hideSidebarMenu();
	} else {
		$( "#sidebar-menu" ).addClass( "scale-normal" );
	}
}

function hideSidebarMenu() { 
	$( "#sidebar-menu" ).removeClass( "scale-normal" ); 

	hideSuggestions();
	hideOnlineFriends();
	hideOhana();
}

function showHideSuggestions() {
	if ( $( "#suggestions-controller" ).hasClass( "reveal-sidebar-section-header" ) ) {
		hideSuggestions();
	} else {
		showSuggestions();
	}
}

function showSuggestions() {
	$( "#suggestions-controller" ).addClass( "reveal-sidebar-section-header" );
	
	$( "#suggestions-container" ).append( loader_ );

	args = {};
	getSuggestions = make_ajax( "ajax-handlers/collect-suggestions.php", "POST", "text", args );
	getSuggestions.onreadystatechange = function() {
        if ( getSuggestions.readyState == 4 && getSuggestions.status == 200 ) {
        	$( "#suggestions-container .loader" ).remove();
        	if ( $( "#suggestions-controller" ).hasClass( "reveal-sidebar-section-header" ) ) { $( "#suggestions-container" ).append( getSuggestions.responseText ); }
        }
    }
}

function hideSuggestions() {
	$( "#suggestions-controller" ).removeClass( "reveal-sidebar-section-header" );
	$( "#suggestions-container" ).empty();
}

function showHideOnlineFriends() {
	if ( $( "#online-friends-controller" ).hasClass( "reveal-sidebar-section-header" ) ) {
		hideOnlineFriends();
	} else {
		showOnlineFriends();
	}
}

function showOnlineFriends() {
	$( "#online-friends-controller" ).addClass( "reveal-sidebar-section-header" );
	
	$( "#online-friends-container" ).append( loader_ );

	args = {};
	getFriends = make_ajax( "ajax-handlers/collect-online-friends.php", "POST", "text", args );
	getFriends.onreadystatechange = function() {
        if ( getFriends.readyState == 4 && getFriends.status == 200 ) {
        	$( "#online-friends-container .loader" ).remove();
        	if ( $( "#online-friends-controller" ).hasClass( "reveal-sidebar-section-header" ) ) { $( "#online-friends-container" ).append( getFriends.responseText ); }
        }
    }
}

function hideOnlineFriends() {
	$( "#online-friends-controller" ).removeClass( "reveal-sidebar-section-header" );
	$( "#online-friends-container" ).empty();
}

function showHideOhana() {
	if ( $( "#ohana-controller" ).hasClass( "reveal-sidebar-section-header" ) ) {
		hideOhana();
	} else {
		showOhana();
	}
}

function showOhana() {
	$( "#ohana-controller" ).addClass( "reveal-sidebar-section-header" );
	
	$( "#ohana-container" ).append( loader_ );

	args = {};
	getOhana = make_ajax( "ajax-handlers/collect-ohana-members.php", "POST", "text", args );
	getOhana.onreadystatechange = function() {
        if ( getOhana.readyState == 4 && getOhana.status == 200 ) {
        	$( "#ohana-container .loader" ).remove();
        	if ( $( "#ohana-controller" ).hasClass( "reveal-sidebar-section-header" ) ) { $( "#ohana-container" ).append( getOhana.responseText ); }
        }
    }
}

function hideOhana() {
	$( "#ohana-controller" ).removeClass( "reveal-sidebar-section-header" );
	$( "#ohana-container" ).empty();
}


//Notifications functions
function showHideNotifications() {
	if ( $( "#notifications-container" ).hasClass( "slide-in" ) ) {
		hideNotifications();
	} else {
		showNotifications();
		
		args = {};
		update_notifications_handler = make_ajax( "ajax-handlers/update-notifications.php", "POST", "text", args );
		update_notifications_handler.onreadystatechange = function() {
			if ( update_notifications_handler.readyState == 400 && update_notifications_handler.status == 200 ) {
				if ( update_notifications_handler.responseText == "READY" ) {}
				else { console.log( update_notifications_handler.responseText ); }
			}
		}
	}
}

function showNotifications() { 
	stopPullingNotifications = 1;
	$( "#notifications-container" ).addClass( "slide-in" ); 
	$( "#notifications-activator" ).removeClass( "active-notification" );
}
function hideNotifications() { 
	$( "#notifications-container" ).removeClass( "slide-in" ); 
	stopPullingNotifications = 0;
}

//AJAX Caller
function make_ajax( path, type, resultType, variables ) {
	var requestType;	
	//Send request
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open(type, path, true);

	varCount = 0;

	buildVarStructure = "";
	for ( var key in variables ) {
		if ( variables.hasOwnProperty( key ) ) {
			varName = key;
			varValue = variables[key];

			buildVarStructure += varName +"="+ varValue +"&";
		}
	}

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send( buildVarStructure );

	return requestType;
}

//User Control box
function openUserControlBox( user, caller ) {
	container_ = "\
		<div id='user-control-box' class='full-page-container'>\
			<div id='user-inline-control-box' class='inline-box'>\
				<div class='upper-box'>\
				</div>\
				<div class='lower-box'>\
					<button id='close-user-controll-box-button' class='discard-button'>Close</button>\
				</div>\
			</div>\
		</div>\
	";
	$( "body" ).append( container_ );

	//Get user information
	userID = user.split( "%%" )[0];
	userFirstName = user.split( "%%" )[1];
	userLastname = user.split( "%%" )[2];
	userProfilePicture = user.split( "%%" )[3];

	//Build UI for the specific caller
	if ( caller == "sidebar" ) {
		container_ = "\
			<div id='user-meta'>\
				<div class='profile-picture' style='background-image: url("+ userProfilePicture +"); background-size: cover; background-position: 50%;'></div>\
				<h1>"+ userFirstName +" "+ userLastname +"</h1>\
			</div>\
			<div id='user-controls'>\
				<a href='openBloger.php?"+ userID +"' class='blue-button' data-ajax='false'>Meet up</a>\
				<button class='blue-button'>Send Message</button>\
			</div>\
		";
	}

	if ( caller == "logged-in-author" || caller == "visited-story" ) {
		container_ = "\
			<div id='user-meta-wide'>\
				<div class='user-profile'>\
					<div class='profile-picture' style='background-image: url("+ userProfilePicture +"); background-size: cover; background-position: 50%;'></div>\
					<h1>"+ userFirstName +" "+ userLastname +"</h1>\
				</div>\
				<div id='badges' class='user-badges'></div>\
				<div id='followers' class='user-followers'>"+ loader_ +"</div>\
				<div id='followers-list' class='users-followers-list'>"+ loader_ +"</div>\
			</div>\
		";
	}

	//Append the new built container
	$( "#user-control-box #user-inline-control-box .upper-box" ).append( container_ );

	//Call AJAXes if needed
	if ( caller == "logged-in-author" ) {
		loadBadges( 0 );
		calculateFollowers( 0, "#user-control-box #user-inline-control-box .upper-box #followers" );
		listFollowers( 0, "#user-control-box #user-inline-control-box .upper-box #followers-list" );
	}

	if ( caller == "visited-story" ) {
		loadBadges( 1 );
		calculateFollowers( 1, "#user-control-box #user-inline-control-box .upper-box #followers" );
		//listFollowers( 1, "#user-control-box #user-inline-control-box .upper-box #followers-list" );
		showFollowOrUnfollowButton( userID, "#user-control-box #user-inline-control-box .upper-box #followers-list" );
	}

	//Reveal the control box
	setTimeout(function(){
		$( "#user-control-box" ).addClass( "slide-in" );
		$( "#close-user-controll-box-button" ).on("tap", function(){ closeUserControlBox(); });
	}, 150);
}

function closeUserControlBox() {
	$( "#user-control-box" ).removeClass( "slide-in" );
	setTimeout(function(){ $( "#user-control-box" ).remove(); }, 250);
}

//Show follow / unfollow button
function showFollowOrUnfollowButton( userID, container ) {
	args = { userID: userID };
	button_handler = make_ajax( "ajax-handlers/follow-unfollow-button.php", "POST", "text", args );
	button_handler.onreadystatechange = function() {
		if ( button_handler.readyState == 4 && button_handler.status == 200 ) {
			$( container +" .loader" ).remove();
			$( container ).append( button_handler.responseText );
		}
	}
}

//Follow / Unfollow user
function followUnfollow( userID ) {
	args = { userID: userID };
	f_u_handler = make_ajax( "ajax-handlers/follow-unfollow.php", "POST", "text", args );
	f_u_handler.onreadystatechange = function() {
		if ( f_u_handler.readyState == 4 && f_u_handler.status == 200 ) {
			if ( f_u_handler.responseText == "READY" ) { window.location.reload( true ); }
			else { console.log( f_u_handler.responseText ); }
		}
	}
}

//Calculate user followers
function calculateFollowers( isVisited, container ) {
	userID = "";
	if ( isVisited == 0 ) { userID = "sender"; }
	else if ( isVisited == 1 ) { userID = window.location.href.split("?")[1]; }

	args = { userID: userID }
	followers = make_ajax( "ajax-handlers/calculate-followers.php", "POST", "text", args );
	followers.onreadystatechange = function() {
		if ( followers.readyState == 4 && followers.status == 200 ) {
			$( container +" .loader" ).remove();
			$( container ).append( followers.responseText );
		}
	}
}

//List followers
function listFollowers( isVisited, container ) {
	userID = "";
	if ( isVisited == 0 ) { userID = "sender"; }
	else if ( isVisited == 1 ) { userID = window.location.href.split("?")[1]; }

	args = { userID: userID }
	collect_followers = make_ajax( "ajax-handlers/collect-followers.php", "POST", "text", args );
	collect_followers.onreadystatechange = function() {
		if ( collect_followers.readyState == 4 && collect_followers.status == 200 ) {
			users_ = collect_followers.responseText.split( "%%" );
			buildUsersFromList( users_, container );
		}
	}
}

//Build users from list
function buildUsersFromList( list, container ) {
	user_ = list[ list.length - 1 ];
	list.pop();

	args = { userID: user_ };
	user_handler = make_ajax( "ajax-handlers/build-user.php", "POST", "text", args );
	user_handler.onreadystatechange = function() {
		if ( user_handler.readyState == 4 && user_handler.status == 200 ) {
			user_built = user_handler.responseText;
			$( container +" .loader" ).remove();
			$( container ).append( user_built );

			if ( list.length > 0 ) { buildUsersFromList( list, container ); }
		}
	}
}

//Load badges
function loadBadges( isVisited ) {
	path = "http://"+ window.location.hostname +"/Library/PHP/Universal/";

	build_ = "<div id='badges-collection'><a href='badges-page.php' id='nb-link' class='link'>No badges yet</a></div>";

	$( "#user-control-box" ).find( "#badges" ).append( build_ );

	userID = "";
	if ( isVisited == 0 ) { userID = "sender"; }
	else if ( isVisited == 1 ) { userID = window.location.href.split("?")[1]; }

	//Story Teller
	args = { startPath: path, userID: userID };
	storyTeller = make_ajax( path+"storyTellerBadge.php", "POST", "text", args );
	storyTeller.onreadystatechange = function() {
        if ( storyTeller.readyState == 4 && storyTeller.status == 200 ) {
 			if ( storyTeller.responseText != "NB" ) {
 				$( "#user-control-box" ).find( "#badges #nb-link" ).remove();
 				$( "#user-control-box" ).find( "#badges-collection" ).append( storyTeller.responseText );
 			}
        }
    }

    //Messenger
    args = { startPath: path, userID: userID };
    messenger = make_ajax( path+"messengerBadge.php", "POST", "text", args );
	messenger.onreadystatechange = function() {
        if ( messenger.readyState == 4 && messenger.status == 200 ) {
 			if ( messenger.responseText != "NB" ) {
 				$( "#user-control-box" ).find( "#badges #nb-link" ).remove();
 				$( "#user-control-box" ).find( "#badges-collection" ).append( messenger.responseText );
 			}
        }
    }

    //Plugin Dev
    args = { startPath: path, userID: userID };
    pluginDev = make_ajax( path+"pluginDevBadge.php", "POST", "text", args );
	pluginDev.onreadystatechange = function() {
        if ( pluginDev.readyState == 4 && pluginDev.status == 200 ) {
 			if ( pluginDev.responseText != "NB" ) {
 				$( "#user-control-box" ).find( "#badges #nb-link" ).remove();
 				$( "#user-control-box" ).find( "#badges-collection" ).append( pluginDev.responseText );
 			}
        }
    }

    //Leader
    args = { startPath: path, userID: userID };
    leader = make_ajax( path+"leaderBadge.php", "POST", "text", args );
	leader.onreadystatechange = function() {
        if ( leader.readyState == 4 && leader.status == 200 ) {
 			if ( leader.responseText != "NB" ) {
 				$( "#user-control-box" ).find( "#badges #nb-link" ).remove();
 				$( "#user-control-box" ).find( "#badges-collection" ).append( leader.responseText );
 			}
        }
    }

    //Cluber
    args = { startPath: path, userID: userID };
    cluber = make_ajax( path+"cluberBadge.php", "POST", "text", args );
	cluber.onreadystatechange = function() {
        if ( cluber.readyState == 4 && cluber.status == 200 ) {
 			if ( cluber.responseText != "NB" ) {
 				$( "#user-control-box" ).find( "#badges #nb-link" ).remove();
 				$( "#user-control-box" ).find( "#badges-collection" ).append( cluber.responseText );
 			}
        }
    }

    //Follower
    args = { startPath: path, userID: userID };
    follower = make_ajax( path+"followerBadge.php", "POST", "text", args );
	follower.onreadystatechange = function() {
        if ( follower.readyState == 4 && follower.status == 200 ) {
 			if ( follower.responseText != "NB" ) {
 				$( "#user-control-box" ).find( "#badges #nb-link" ).remove();
 				$( "#user-control-box" ).find( "#badges-collection" ).append( follower.responseText );
 			}
        }
    }
}

//Open Story Composer
function openStoryComposer( storyID ) {
	composer_ = "\
		<div id='story-composer' class='composer'>\
			<div id='inline-fields' class='inline-composer'>\
				<div id='shortcodes-container' class='aside-container'>\
					<button id='bold' class='shortcode' onclick='addShortcode(\"bold\");'><span>&#xf032;</span> - Bold</button>\
					<button id='italic' class='shortcode' onclick='addShortcode(\"italic\");'><span>&#xf033;</span> - Italic</button>\
					<button id='underline' class='shortcode' onclick='addShortcode(\"underline\");'><span>&#xf0cd;</span> - Underline</button>\
					<button id='overline' class='shortcode' onclick='addShortcode(\"overline\");'>[o] ... [/o] - Overline</button>\
					<button id='scratch' class='shortcode' onclick='addShortcode(\"scratch\");'><span>&#xf0cc;</span> - Scratch</button>\
					<button id='color' class='shortcode' onclick='addShortcode(\"color\");'>[color=\"blue\"] ... [/color] - Custom Text Color</button>\
					<button id='marker' class='shortcode' onclick='addShortcode(\"marker\");'>[mark=\"yellow\"] ... [/mark] - Mark Text</button>\
					<button id='media' class='shortcode' onclick='addShortcode(\"media\");'>[media] http://... [/media] - Insert Media</button>\
					<button id='mascot' class='shortcode' onclick='addShortcode(\"mascot\");'>[mascot=\"wave / wat / tongue / love / confused / wave\"] - Insert Mascot</button>\
				</div>\
				<div class='upper-box'>\
					<input type='text' id='story-title' class='wide-fat' placeholder='Give it title ...'>\
					<input type='text' id='story-link' class='wide-fat' placeholder='Place a link to a ...' onClick='this.select();'>\
					<textarea id='story-content' class='big-jack' placeholder='Hey Chrisie ...'></textarea>\
				</div>\
				<div class='lower-box'>\
					<button id='compose-button' class='compose-button'>Compose</button>\
					<button id='close-button' class='discard-button'>Discard</button>\
				</div>\
			</div>\
		</div>\
	";

	//Append to the body container
	$( "body" ).append( composer_ );

	//Get the post information if it comes from the Edit function
	if ( storyID !== undefined && storyID != "" && storyID != -1 ) {
		args = { storyID: storyID };
		editor_handler = make_ajax( "ajax-handlers/pull-story-for-edit.php", "POST", "text", args );
		editor_handler.onreadystatechange = function() {
			if ( editor_handler.readyState == 4 && editor_handler.status == 200 ) {
				if ( editor_handler.responseText != "-1" ) {
					storyTitle = editor_handler.responseText.split( "%%%" )[0];
					storyLink = editor_handler.responseText.split( "%%%" )[1];
					storyContent = editor_handler.responseText.split( "%%%" )[2];
					
					$( "#story-composer #story-title" ).val( storyTitle );
					$( "#story-composer #story-link" ).val( storyLink );
					$( "#story-composer #story-content" ).val( storyContent );
				} else {
					console.log( editor_handler.responseText );
				}
			}
		}
	}

	//Slide the composer IN
	setTimeout(function(){ $( "#story-composer" ).addClass( "slide-in" ); }, 150);

	//Add open shortcodes ability
	$( "#story-composer #story-content" ).on("taphold", function(){ openShortcodes(); });

	//Add close shortcode ability
	$( "#story-composer #inline-fields" ).on("swipeleft", function(){ closeShorcodes(); });

	//Add close function to the close button
	$( "#story-composer #close-button" ).on("tap", function(){ closeStoryComposer(); });

	//Add composer ability to the Compose button
	$( "#story-composer #compose-button" ).on("tap", function(){ composeStory( storyID ); });
}

//Open / Close Shortcodes
function openShortcodes() {
	$( "#story-composer #story-content" ).attr( "disabled", true );
	$( "#story-composer #shortcodes-container" ).addClass( "slide-in" );
}
function closeShorcodes() {
	$( "#story-composer #story-content" ).removeAttr( "disabled" );
	$( "#story-composer #shortcodes-container" ).removeClass( "slide-in" ); 
}

//Add shortcode
function addShortcode( shortcodeID ) {
	currentStoryContent = $( "#story-composer #story-content" ).val();

	if ( shortcodeID == "bold" ) {
		currentStoryContent += "[b][/b]";
	} else if ( shortcodeID == "italic" ) {
		currentStoryContent += "[i][/i]";
	} else if ( shortcodeID == "underline" ) {
		currentStoryContent += "[u][/u]";
	} else if ( shortcodeID == "overline" ) {
		currentStoryContent += "[o][/o]";
	} else if ( shortcodeID == "scratch" ) {
		currentStoryContent += "[scratch][/scratch]";
	} else if ( shortcodeID == "color" ) {
		currentStoryContent += "[color=\"\"][/color]";
	} else if ( shortcodeID == "marker" ) {
		currentStoryContent += "[marker=\"\"][/marker]";
	} else if ( shortcodeID == "media" ) {
		currentStoryContent += "[media][/media]";
	} else if ( shortcodeID == "mascot" ) {
		currentStoryContent += "[mascot=\"\"]";
	}

	$( "#story-composer #story-content" ).val( currentStoryContent );
}

//Compose a Story
function composeStory( storyID ) {
	storyTitle = $( "#story-composer #story-title" ).val().trim();
	storyLink = $( "#story-composer #story-link" ).val().trim();
	storyContent = $( "#story-composer #story-content" ).val().trim();

	stopFlag = 0;

	if ( storyTitle === undefined || storyTitle == "" ) {
		stopFlag = 1;
		alert("Give title to your story first!");
	} else {
		if ( ( storyLink === undefined || storyLink == "" ) && ( storyContent === undefined || storyContent == "" ) ) {
			stopFlag = 1;
			alert("Put a link to a video or image or at least tell something for that story!");
		}
	}

	if ( stopFlag == 0 ) {
		//Compose story callback
		args = { 
			storyID: storyID,
			storyTitle: storyTitle,
			storyLink: storyLink,
			storyContent: storyContent
		};
		composer_handler = make_ajax( "ajax-handlers/compose-story.php", "POST", "text", args );
		composer_handler.onreadystatechange = function() {
			if ( composer_handler.readyState == 4 && composer_handler.status == 200 ) {
				if ( composer_handler.responseText == "READY" ) {
					//Append ready pointer to the composer
					ready_pointer = "<div id='ready-pointer' class='popup-message green-popup-message'><span>&#xf058;</span>Composed</div>";
					$( "#story-composer" ).append( ready_pointer );

					//If story was edited
					if ( storyID !== undefined && storyID != "" && storyID != -1 ) {
						args = { 
							storyID: storyID,
							storyBuild: "normal"
						};
						edited_handler = make_ajax( "ajax-handlers/build-single-story.php", "POST", "text", args );
						edited_handler.onreadystatechange = function() {
							if ( edited_handler.readyState == 4 && edited_handler.status == 200 ) {
								if ( edited_handler.responseText != "-1" ) {
									$( "#story-"+storyID ).replaceWith( edited_handler.responseText );
								}
							}
						}
					}

					setTimeout(function(){ 
						$( "#story-composer #ready-pointer" ).addClass( "normal-opacity" );
						if ( storyID !== undefined && storyID != "" && storyID != -1 ) { // If story was edited
							setTimeout(function(){ 
								closeStoryComposer(); 
								$( "#story-"+storyID ).addClass( "fade-in" );
							}, 1500);
						} else { setTimeout(function(){ window.location.reload( true ); }, 1500); }
					}, 150);
				} else { console.log( composer_handler.responseText ); }
			}
		}
	}
}

//Close Story Composer
function closeStoryComposer() {
	$( "#story-composer" ).removeClass( "slide-in" );
	setTimeout(function(){ $( "#story-composer" ).remove(); }, 150);
}

//Delete story
function deleteStory( storyID ) {
	if ( storyID !== undefined && storyID != "" && storyID != -1 ) {
		args = { storyID: storyID };
		delete_story_handler = make_ajax( "ajax-handlers/delete-story.php", "POST", "text", args );
		delete_story_handler.onreadystatechange = function () {
			if ( delete_story_handler.readyState == 4 && delete_story_handler.status == 200 ) {
				if ( delete_story_handler.responseText == "READY" ) {
					$( "#story-"+storyID ).removeClass( "fly-in" );
					setTimeout(function(){ $( "#story-"+storyID ).remove(); }, 400);
				} else {
					console.log( delete_story_handler.responseText );
				}
			}
		}
	}
}

//Open Story Reader
function openStoryReader( storyID ) {
	reader_ = "\
	<div id='story-control-box' class='full-page-container'>\
		<div id='story-inline-control-box' class='inline-box'>\
			<div id='story-holder' class='story-holder'>\
				"+loader_+"\
			</div>\
			<div class='lower-box'>\
				<button id='close-story-reader-button' class='discard-button' onclick='closeStoryReader();'>Close</button>\
			</div>\
		</div>\
	</div>\
	";
	$( "body" ).append( reader_ );

	//Show reader
	setTimeout(function(){ $( "#story-control-box" ).addClass( "slide-in" ); }, 150);

	userID = "";
	if ( window.location.href.indexOf( "?" ) > -1 ) { userID = window.location.href.split( "?" )[1]; }
	else if ( window.location.href.indexOf( "world-story" ) > -1 ) { userID = "world-story"; }

	//Get the story
	args = {
		storyID: storyID,
		userID: userID,
		storyBuild: "story-reader"
	};
	story_handler = make_ajax( "ajax-handlers/build-single-story.php", "POST", "text", args );
	story_handler.onreadystatechange = function() {
		if ( story_handler.readyState == 4 && story_handler.status == 200 ) {
			$( "#story-control-box #story-holder .loader" ).remove();
			$( "#story-control-box #story-holder" ).append( story_handler.responseText );

			//Add opening function to the story banner
			$( "#story-control-box #story-holder .featured-image" ).on("tap", function(){
				if ( ! $( "#story-control-box #story-holder .featured-image" ).hasClass( "opened-banner" ) ) {
					$( "#story-control-box #story-holder .story-banner-container" ).addClass( "opened-banner-container" );
					$( "#story-control-box #story-holder .featured-image" ).addClass( "opened-banner" );
				}
			});

			//List story likers
			visited_story = 0;
			if ( userID !== undefined && userID != "" ) { visited_story = 1; }
			listStoryLikers( visited_story, storyID, "#story-control-box #story-holder #likes-list" );
		}
	}
}

//Close Story Reader
function closeStoryReader() {
	$( "#story-control-box" ).removeClass( "slide-in" );
	setTimeout(function(){ $( "#story-control-box" ).remove(); }, 250);
}

//Load Stories
function loadStories( storiesOffset ) {
	$( "#stories-holder" ).append( loader_ );

	userID = "";
	referrer = "personal-story";
	if ( window.location.href.indexOf( "?" ) > -1 ) { userID = window.location.href.split( "?" )[1]; referrer = "visited-story"; }
	else if ( window.location.href.indexOf( "world-story" ) > -1 ) { userID = "world-story"; referrer = "world-story" }

	args = { 
		offset: storiesOffset,
		userID: userID,
		referrer: referrer
	};
	stories_handler = make_ajax( "ajax-handlers/collect-stories.php", "POST", "text", args );
	stories_handler.onreadystatechange = function() {
		if ( stories_handler.readyState == 4 && stories_handler.status == 200 ) {
			if ( stories_handler.responseText != "break-point" ) {
				$( "#stories-holder .loader" ).remove();
				$( "#stories-holder" ).append( stories_handler.responseText );
				lockScroll = 0; //Unlock scroll event	
				//Show posts
				setTimeout(function(){ $( ".story-container" ).addClass( "fly-in" ); }, 150);
			} else {
				$( "#stories-holder .loader" ).remove();
			}
		}
	}
}

//List story likers
function listStoryLikers( isVisited, storyID, container ) {
	userID = "";

	if ( isVisited == 0 ) { userID = "sender"; }
	else if ( isVisited == 1 ) { userID = window.location.href.split("?")[1]; }

	args = { 
		userID: userID, 
		storyID: storyID 
	};

	collect_likers = make_ajax( "ajax-handlers/collect-story-likers.php", "POST", "text", args );
	collect_likers.onreadystatechange = function() {
		if ( collect_likers.readyState == 4 && collect_likers.status == 200 ) {
			users_ = collect_likers.responseText.split( "&" );
			buildUsersFromList( users_, container );
		}
	}
}

//Like / Unlike story
function likeUnlikeStory( storyID, userID ) {
	args = {
		userID: userID,
		storyID: storyID
	};
	story_l_u_handler = make_ajax( "ajax-handlers/like-unlike-story.php", "POST", "text", args );
	story_l_u_handler.onreadystatechange = function() {
		if ( story_l_u_handler.readyState == 4 && story_l_u_handler.status == 200 ) {
			setTimeout(function() {
				obj_ = $( "#story-"+storyID+" #like-container" );
				status_ = story_l_u_handler.responseText.split( "%%" )[0];
				likes_ = story_l_u_handler.responseText.split( "%%" )[1];
				if ( status_ == "liked" ) {
					obj_.children( "span" ).html( "&#xf088;" );
					obj_.children( "h1" ).html( "Dislike" );
					//Add new like
					obj_.parent( "#story-"+storyID ).children( ".story-likes" ).html( "<span class='liked'>&#xf004;</span>"+likes_ );
				} else if ( status_ == "disliked" ) {
					obj_.children( "span" ).html( "&#xf004;" );
					obj_.children( "h1" ).html( "Like" );
					//Remove the like
					obj_.parent( "#story-"+storyID ).children( ".story-likes" ).html( "<span class='liked'>&#xf004;</span>"+likes_ );
				} else { console.log( status_ ); }
			}, 250);
		}
	}
}

//Repost story
function repostStory( storyID, userID ) {
	args = {
		userID: userID,
		storyID: storyID
	};
	repost_story_handler = make_ajax( "ajax-handlers/repost-story.php", "POST", "text", args );
	repost_story_handler.onreadystatechange = function() {
		if ( repost_story_handler.readyState == 4 && repost_story_handler.status == 200 ) {
			status_ = repost_story_handler.responseText;
			if ( status_ == "reposted" ) {
				obj_ = $( "#story-"+storyID+" #repost-container" );
				obj_.children( "span" ).removeClass( "pulse-colorful" ).html( "&#xf118;" );
				obj_.children( "h1" ).removeClass( "pulse-colorful" ).html( "Reposted!" );

				//Hide the container
				setTimeout(function(){
					obj_.removeClass( "slide-in" );
					setTimeout(function(){
						obj_.children( "span" ).html( "&#xf064;" );
						obj_.children( "h1" ).html( "Repost" );
					}, 300);
				}, 350);
			} else { console.log( status_ ); }
		}
	}
}

//Pull notifications
function pullNotifications() {
	if ( stopPullingNotifications == 0 ) {
		args = {};
		notification_handler = make_ajax( "ajax-handlers/pull-notifications.php", "POST", "text", args );
		notification_handler.onreadystatechange = function() {
			if ( notification_handler.readyState == 4 && notification_handler.status == 200 ) {
				notifications_ = notification_handler.responseText.split( "%%" )[0];
				notifications_list = notification_handler.responseText.split( "%%" )[1];
				if ( notifications_ > 0 && !$( "#notifications-container" ).hasClass( "slide-in" ) ) {
					$( "#notifications-activator" ).addClass( "active-notification" );
					$( "#notifications-container" ).html( notifications_list );
				} else {
					$( "#notifications-container" ).html( notifications_list );
				}
			}
		}
	}
}

//Album
function listAlbum() {
	$( "#album-list" ).append( loader_ );

	args = { imagesOffset: imagesOffset };
	album_list_handler = make_ajax( "ajax-handlers/list-album.php", "POST", "text", args );
	album_list_handler.onreadystatechange = function() {
		if ( album_list_handler.readyState == 4 && album_list_handler.status == 200 ) {
			$( "#album-list .loader" ).remove();
			$( "#album-list" ).append( album_list_handler.responseText );
			setTimeout(function(){ $( ".album-element" ).addClass( "fade-in" ); }, 150);
			imagesOffset += 3;
			if ( imagesOffset < totalElements ) { lockScroll = 0; }
		}
	}
}

//Upload image
function startToUpload() {
	if (document.getElementById('fileToUpload').value != "") {
		document.getElementById('toUpload').action = 'ajax-handlers/upload-to-album.php';
		document.forms['toUpload'].submit();
	}
}

//Open album controller
function openAlbumController( element_id ) {
	target_ = $( "#"+element_id );

	build_ = "\
	<div id='popup-container'>\
		<button class='close iconic' onclick='hidePopup();'>&#xf00d;</button>\
		<div id='popup-inner'>\
			<div id='album-menu'>\
				<button id='view' class='blue-button'>View</button>\
				<button id='make-story' class='blue-button'>Make a story</button>\
				<button id='send-to-friend' class='blue-button'>Send to a friend</button>\
				<button id='set-as-profile-pic' class='blue-button'>Set as profile pic.</button>\
				<button id='set-as-club-logo' class='blue-button'>Set as club logo</button>\
				<button id='delete' class='discard-button'>Delete</button>\
			</div>\
		</div>\
	</div>\
	";
	$( "body" ).append( build_ );
	setTimeout(function(){
		$( "#popup-container" ).addClass( "fade-in" );
		setTimeout(function(){ $( "#popup-inner" ).addClass( "fly-in" ); }, 250);
	}, 250);

	//Configure menu
	background_ = target_.css( "background-image" );
	image_url = background_.replace( 'url(', '' ).replace( ')', '');
	
	//View button
	$( "#view" ).on("click", function(){ previewImage( $image_url ); });
	//Compose button
	$( "#make-story" ).on("click", function(){});
	//Send to a friend button
	$( "#send-to-friend" ).on("click", function(){});
	//Set as profile picture
	$( "#set-as-profile-pic" ).on("click", function(){});
	//Set as club logo
	$( "#set-as-club-logo" ).on("click", function(){});
	//Delete button
	$( "#delete" ).on("click", function(){});

	$( "#popup-container" ).on("tap",function( e ){
		if( e.target == this ) { hidePopup(); }
	});
}

//Hide popup
function hidePopup() {
	$( "#popup-container" ).removeClass( "fade-in" );
	setTimeout(function(){ $( "#popup-container" ).remove(); }, 250);
}


//Preview image
function previewImage(  imageURL ) {
	build_ = "\
	<div id='image-preview-popup'>\
		<div id='image-holder' style='background-image: url("+imageURL+"); background-size: cover; background-position: center;'>\
		</div>\
	</div>\
	";
}