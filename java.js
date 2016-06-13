function bruteEncrypt(text_to_crypt) {
	generateRandom = Math.floor(Math.random() * 90 + 10);
	encryptText = generateRandom+"#";

	for (parser = 0; parser < text_to_crypt.length; parser++) {
		encryptText += (generateRandom + text_to_crypt.charCodeAt(parser)) * 69;
		if ( parser + 1 < text_to_crypt.length ) { encryptText += "#"; }
	}

	return encryptText;
}

function showImg(id) {			
	pic = new Image();
	pic.src = document.getElementById(id).src;
	
	var title = 'Blogy';

	document.write('<head><title>');
		document.write(title);
		document.write('</title>');
		document.write('<link href=\'style.css\' rel=\'stylesheet\' type=\'text/css\' media=\'screen\' />');
		document.write('<link href=\'../../../style.css\' rel=\'stylesheet\' type=\'text/css\' media=\'screen\' />');
	
		document.write('<style>body{background-color: black;}</style>');
	document.write('</head>');
	document.write('<div id=\'sub-gallery\' align=\'center\'>');
		document.write('<a href=\'#\' onclick=\'location.reload()\'><img src=\'');
		document.write(pic.src);
		document.write('\'></а>');
	document.write('</div>');
}

function downloadBlogy(blogyId) {
	document.cookie="objectId="+blogyId;
	window.open("Library/Downloads/"+blogyId+".rar");
	window.location="downloadBlogy.php";
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
};

function returnToHome() {
	document.getElementById('accountInfo').action = '../PHP/logedIn.php';
	document.forms['accountInfo'].submit();
}

function openSettings() {
	document.getElementById('accountInfo').action = '../PHP/openSettings.php';
	document.forms['accountInfo'].submit();
}

function openBloger(title) {
	var blogSender = document.getElementById(title).elements["blogSender"].value;
	var blogerFN = document.getElementById(title).elements["blogerFN"].value;
	var blogerLN = document.getElementById(title).elements["blogerLN"].value;
	var blogerImg = document.getElementById(title).elements["blogerImg"].value;
	var blogerHref = document.getElementById(title).elements["blogerHref"].value;

	document.cookie = "blogSender="+blogSender+";domain=."+window.location.host+";path=/";
	document.cookie = "blogerFN="+blogerFN;
	document.cookie = "blogerLN="+blogerLN;
	document.cookie = "blogerImg="+blogerImg;
	document.cookie = "blogerHref="+blogerHref;
}

function reportData() {
	var data = document.getElementById('reportedData').value;
	if (data.trim() == "") {
		alert("Well tell us what is wrong first.");
	} else {
		document.getElementById("reportData").action = "reportData.php";
		document.forms['reportData'].submit();
	}
}

function showSideBar() {
	var allCookies = document.cookie;
	if (allCookies.indexOf("sideBar=") != -1) {
		document.cookie = "sideBar=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	} else {
		document.cookie="sideBar=1";
	}
	
	//document.getElementById('downButton').style.visibility='hidden';
	document.getElementById('sideBar').style.visibility='visible'; 
	$('#sideBar').fadeToggle('fast');
	$('#rightSideBar').fadeToggle('fast');
}

function showQuickMenu(id) {
	document.getElementById('quickMenu'+id).style.visibility='visible'; 
	$('#quickMenu'+id).slideToggle('fast');
}
function showQuickMenuOhana(id) {
	document.getElementById('ohanaQuickMenu'+id).style.visibility='visible'; 
	$('#ohanaQuickMenu'+id).slideToggle('fast');
}

function hideMessageBox() {
	$('#quickMessageBox').fadeOut('fast');
}

function showMessageBox(receiver) {	
	document.getElementById('receiverId').value = receiver;
	document.getElementById('receiver').innerHTML = document.getElementById(receiver).elements["blogerFN"].value + " " + document.getElementById(receiver).elements["blogerLN"].value;
	document.getElementById('messageArea').focus();
	$('#quickMessageBox').fadeIn('fast');
}

function checkKey(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if(code == 27) { //Enter keycode
		$('#quickMessageBox').fadeOut('fast');
	}
}

function sendMessageBox() {
	var messageText = document.getElementById('messageArea').value;
	var receiver = document.getElementById('receiverId').value;

	if (messageText.trim() != ""  && receiver.trim() != "") {
		getMessage = messageText.replace(/(?:\r\n|\r|\n)/g, '<br />');
		getReceiver = receiver;

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		requestType.open("POST", "sendQuickMessage.php", true);
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("MESSAGE="+getMessage+"&RECEIVER="+getReceiver);

		requestType.onreadystatechange = function() {
	        if (requestType.readyState == 4 && requestType.status == 200) {
	     		var storeResponce = requestType.responseText;
	 		   
	 		 	if (storeResponce == "READY") {
		 		 	$("#quickMessageBox").fadeOut("fast");
		 		 	document.getElementById('messageArea').value =  "";
		 		 	document.getElementById('receiverId').value = "";
	 		 	}
	     	}
	    }
	} else {
		alert("Enter something in this message.");
	}
}

function showOptions() {
	$('#optionsMenu').fadeToggle('fast');
}

function showOhanaMeaning() {
	$('#ohanaMeaning').fadeToggle('fast');
}

function addToOhana(id) {
	document.getElementById(id).action = "addToOhana.php";
	document.forms[id].submit();
}
function removeFromOhana(id) {
	document.getElementById(id).action = "removeFromOhana.php";
	document.forms[id].submit();
}

function blockUser(id) {
	document.getElementById(id).action = "blockUser.php";
	document.forms[id].submit();
}
function unBlockUser(id) {
	openBloger(id);
	document.getElementById(id).action = "unBlockUser.php";
	document.forms[id].submit();
}

function showEmojiContainer() {
	$('#emojis').slideToggle('fast');
}

function showHideNotifications() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "editNotifications.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				$("#click-container").removeClass("active-notification");
				$("#click-container").addClass("no-notification");
				$('#notifications').toggle('slide');
			}
		}
	}
}
function closeNotifications() {
	document.cookie = "pageId="+window.location.href.toString().split('/').pop(-1);
	document.cookie = "scrollPos="+$(window).scrollTop();
	
	window.location = "deleteNotifications.php";
}

function addEmoji(id) {
	textArea = document.getElementById("messageTXT");
	if (id == "lol") {
		textArea.value += ":D";
	}
	else
	if (id == "smile") {
		textArea.value += ":)";
	}
	else
	if (id == "sad") {
		textArea.value += ":(";
	}
	else
	if (id == "ooh") {
		textArea.value += ":O";
	}
	else
	if (id == "inlove") {
		textArea.value += "{2369}";
	}
	else
	if (id == "kiss") {
		textArea.value += ":*";
	}
	else
	if (id == "scare") {
		textArea.value += "{666}";
	}
	else
	if (id == "cry") {
		textArea.value += ":'(";
	}
	else
	if (id == "tongue") {
		textArea.value += ":P";
	}
	else
	if (id == "wat") {
		textArea.value += "{49}";
	}
	else
	if (id == "wink") {
		textArea.value += ";)";
	}
	else
	if (id == "mybad") {
		textArea.value += "{118}";
	}
	else
	if (id == "meh") {
		textArea.value += "{999}";
	}
	else
	if (id == "lolo") {
		textArea.value += "{1010}";
	}
	else
	if (id == "muchCry") {
		textArea.value += "{7428}";
	}
	else
	if (id == "calm") {
		textArea.value += ":3";
	}
	else
	if (id == "sexy") {
		textArea.value += "{1619}";
	}
	else
	if (id == "angry") {
		textArea.value += ":@";
	}
	else
	if (id == "redH") {
		textArea.value += "{23}";
	}
	else
	if (id == "blueH") {
		textArea.value += "{45}";
	}
	else
	if (id == "greenH") {
		textArea.value += "{0103}";
	}
}

function showHideHomeMenu() {
	$("#dropDownMenu").fadeToggle("fast");
}

function openDialog() {
	document.getElementById("fileToUpload").click();
}
function startToUpload() {
	if (document.getElementById('fileToUpload').value != "") {
		document.getElementById('toUpload').action = 'Upload.php';
		document.forms['toUpload'].submit();
	}
}

function sendLocation() {
	document.getElementById("postImg").value = document.getElementById("dialogWindow").value;
	
	var getObject = document.getElementById("dialogWindow");
	if (getObject.value != null) {
		document.cookie = "uploadPicture="+document.getElementById("postImg").value;
	}
	else
	if (getObject.value == null) {
		document.cookie = "uploadPicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	}
}
function checkLocation() {
	var getObject = document.getElementById("postImg");
	if (getObject.value != document.getElementById("dialogWindow").value) {
		document.cookie = "uploadPicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	}
}


//Function to Log out
function deleteTimer() {
	clearTimeout(timeOut);
}
function setTimer() {
	timeOut = setTimeout(logMeOut(), 10000)
}

function logMeOut() {
	window.location = "LogOut.php";
}

//Read message from notification
function readMessage(id) {
	document.cookie = "receiverId="+id;
	window.location="readMessage.php";
}

//Album options
function setAsProfilePic(id) {
	document.cookie = "newProfilePicture="+id;
	window.location = 'setNewProfilePic.php';
}
function showContainerPost(id, sender) {
	document.getElementById("postImg").value = '../../../Library/Authors/'+sender+'/Album/'+id;
	
	$("#makePost").fadeIn('fast');
}
function hideContainerPost(id) {
	$("#makePost").fadeOut('fast');
}
function showContainerFriends(id) {
	$("#storeFriends").fadeIn("fast");
	document.cookie = "sharePicture="+id;
}
function hideContainerFriends() {
	$("#storeFriends").fadeOut("fast");
	document.cookie = "sharePicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}
function deleteObjectFromAlbum(id, containerId) {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "deleteAlbumObject.php", true);
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pictureId="+id);
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	$(".free-space").text("Free space: "+requestType.responseText+"mb"); 
    		$("."+containerId).fadeOut("fast");
    		setTimeout(function(){ $("."+containerId).remove(); }, 150);
        }
    }
}

//Notes
function showNoteBuilder() {
	$("#noteBuilder").fadeIn("fast");
}
function hideNoteBuilder() {
	$("#noteBuilder").fadeOut("fast");
}
function pinNote(org, getId, getDate) {
	var noteTitle = document.getElementById("noteTitle").value;
	var noteDate = document.getElementById("datepicker").value;
	var noteContent = document.getElementById("noteContent").value;
	
	var flag = 0;
	
	if (noteTitle.trim() == "") {
		alert("Give title to your note.");
		flag = 1;
	}
	else
	if (noteDate.trim() == "") {
		alert("Choose date for your note.");
		flag = 1;
	}
	else
	if (noteContent.trim() == "") {
		alert("Enter something in your note.");
		flag = 1;
	}
	
	if (flag == 0) {
		if (org == 0) {
			document.getElementById('noteForm').action = 'pinNote.php';
			document.forms['noteForm'].submit();
		}
		else
		if (org == 1) {
			document.cookie = "oldNoteId="+getId;
			document.cookie = "oldNoteDate="+getDate;
			document.getElementById('noteForm').action = 'updateNote.php';
			document.forms['noteForm'].submit();
		}
	}
}
function deleteNote(id) {
	document.cookie = "noteId="+id;
	window.location = 'deleteNote.php';
}
function previewNote(id, date) {
	document.cookie = "noteId="+id;
	document.cookie = "noteDate="+date;
	window.location = 'previewNote.php';
}

//Searches
function exploreBloger(blogerId) {
	openBloger(blogerId.toString());
	window.location = 'openBloger.php';
}
function searchFriends(pullFriends, searchEngine, objectId, cmdType, mobile) {
	var searchRes = document.getElementById(objectId);
	searchRes.style.display = 'none';
	while (searchRes.firstChild) {
	    searchRes.removeChild(searchRes.firstChild);
	}

	var searchSugestions = new Array();
	var getInput = document.getElementById(searchEngine).value;

	for (i = 0; i < pullFriends.length; i++) {
		var split = pullFriends[i].split("#");
		var search = split[0] + split[1];
		if (search.toLowerCase().indexOf(getInput.trim().toLowerCase().replace(/\s/g, "").replace(/\s+/g, '#')) == 0 && getInput.trim() != '') {
			searchSugestions.push(pullFriends[i]);
		}
	}

	if ( cmdType != 3 ) {
		if ( getInput.trim() != '' ) {
			searchForGlobalBloggers( getInput.trim().toLowerCase().replace(/\s/g, "").replace(/\s+/g, '#'), mobile );
			searchForGlobalClubs( getInput.trim().toLowerCase().replace(/\s+/g, '_'), mobile );

			if ( window.location.href.indexOf("/Mobile/") < 0 ) { searchForGlobalPlugins( getInput.trim().toLowerCase().replace(/\s+/g, '_'), mobile ); }
		}
	}

	if (searchSugestions.length == 0) {
		if ( getInput.toLowerCase() != "ohana" ) {
			/*searchRes.innerHTML += '<h1 class="center-text error-message">No matches found :(</h1>';
			if (cmdType != 3) {
				searchRes.innerHTML += "<button class='search-more' onclick='makeGlobalSearch("+mobile+");'>Search more ?</button>";
			}*/
		} else {
			searchRes.innerHTML += "\
				<p style='text-align: justify;'>\
					<span class='inline-header'>What is the meaning of \"Ohana\" ?</span>\
					The word \"Ohana\" comes from the Hawaiian culture and it means family.\
					Family means nobody gets left behind or forgotten.\
					Just like your family, the members of your Ohana are unique and special just for you.\
					They are these close friends which are going to help you even in your baddest days.\
					So choose them smart and wisely! #BeUnique\
				</p>\
			";
		}
	} 
	else 
	if (searchSugestions.length > 0) {
		if ( cmdType == 1 ) {
			searchRes.innerHTML += "<h1 class='section-header'><span class='iconic'>&#xf0c0;</span>Friends</h1>";
		}

		for (count = 0; count < searchSugestions.length; count++) {
			if ( searchSugestions[count] !== undefined) {
				var splitResult = searchSugestions[count].split('#');
				var fName = splitResult[0];
				var lName = splitResult[1];
				var idName = splitResult[2];
				var img = splitResult[3];
				var href = splitResult[4];

				if (cmdType == 1) {
					searchRes.innerHTML += "<a href='openBloger.php?"+idName+"' tabindex='"+(count + 1)+"'><div style='background-image: url("+img+"); background-size: cover; background-position: 50%; margin-right: 5px;' class='img'></div>"+fName+" "+lName+"</a>";
				}
				else
				if (cmdType == 2) {
					searchRes.innerHTML += "<button onclick='addBloger(\""+idName+"\", \""+img+"\", \""+fName+"\", \""+lName+"\")'><img src="+img+" />"+fName+" "+lName+"</button>";
				}
				else
				if (cmdType == 3) {
					searchRes.innerHTML += "<button onclick='inviteMember(\""+idName+"\", \""+searchSugestions[count]+"\", "+mobile+")' class='"+idName+"'><div style='background-image: url("+img+"); background-size: cover; background-position: 50%; margin-right: 5px;' class='img'></div>"+fName+" "+lName+"</button>";
				}
			}
		}

		/*if (cmdType != 3) {
			searchRes.innerHTML += "<button class='search-more' onclick='makeGlobalSearch("+mobile+");'>Search more ?</button>";
		}*/
	}

	if (getInput.trim() != '') searchRes.style.display = 'block';
}

//Search for global bloggers in single search
function searchForGlobalBloggers(bloggerID, mobile) {
	if ( mobile == undefined ) { mobile = 0; }

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if ( mobile == 0 ) { requestType.open("POST", "Universal/searchForGlobalBloggers.php", true); }
	else 
	if ( mobile == 1 ) { requestType.open("POST", "../Universal/searchForGlobalBloggers.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("bloggerID="+bloggerID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getSearches_globalBloggers = requestType.responseText.split(",");

        	if (getSearches_globalBloggers != "NF:(") {
        		$("#search-engine-container > #child-container > #subchild-container > #all-bloggers").remove();
        		build_ = "<div id='all-bloggers'><h1 class='section-header'><span class='iconic'>&#xf234;</span>Bloggers</h1></div>";
        		$("#search-engine-container > #child-container > #subchild-container").append(build_);
	        	//Send parsing request while there are suggestions
	        	for (count = 0; count < getSearches_globalBloggers.length; count++) {
	        		parseAndGivePersons(getSearches_globalBloggers[count], "#search-engine-container > #child-container > #subchild-container > #all-bloggers");
	        	}
	        }
		}
	}
}

//Search for global clubs in single search
function searchForGlobalClubs(clubID, mobile) {
	if ( mobile == undefined ) { mobile = 0; }

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if ( mobile == 0 ) { requestType.open("POST", "Universal/searchForGlobalClubs.php", true); }
	else 
	if ( mobile == 1 ) { requestType.open("POST", "../Universal/searchForGlobalClubs.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubID="+clubID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getSearches_globalClubs = requestType.responseText.split(",");

        	if (getSearches_globalClubs != "NF:(") {
        		$("#search-engine-container > #child-container > #subchild-container > #all-clubs").remove();
        		build_ = "<div id='all-clubs'><h1 class='section-header'><span class='iconic'>&#xf075;</span>Clubs</h1></div>";
        		$("#search-engine-container > #child-container > #subchild-container").append(build_);
	        	//Send parsing request while there are suggestions
	        	for (count = 0; count < getSearches_globalClubs.length; count++) {
	        		parseAndGiveClubs(getSearches_globalClubs[count], "#search-engine-container > #child-container > #subchild-container > #all-clubs");
	        	}
	        }
		}
	}
}

//Search for global plugins in single search
function searchForGlobalPlugins(pluginID, mobile) {
	if ( mobile == undefined ) { mobile = 0; }

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if ( mobile == 0 ) { requestType.open("POST", "Universal/searchForGlobalPlugins.php", true); }
	else 
	if ( mobile == 1 ) { requestType.open("POST", "../Universal/searchForGlobalPlugins.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getSearches_globalPlugins = requestType.responseText.split(",");

        	if (getSearches_globalPlugins != "NF:(") {
        		$("#search-engine-container > #child-container > #subchild-container > #all-plugins").remove();
        		build_ = "<div id='all-plugins'><h1 class='section-header'><span class='iconic'>&#xf127;</span>Plugins</h1></div>";
        		$("#search-engine-container > #child-container > #subchild-container").append(build_);
	        	//Send parsing request while there are suggestions
	        	for (count = 0; count < getSearches_globalPlugins.length; count++) {
	        		parseAndGivePlugins(getSearches_globalPlugins[count], "#search-engine-container > #child-container > #subchild-container > #all-plugins");
	        	}
	        }
		}
	}
}


//Remove Element from INNER HTML
function removeElement(elementId, containerId) {
	document.getElementById(containerId).removeChild(document.getElementById(elementId));
}

//Places - Get location
function hideMap() {
	$("#mapContainer").fadeOut("fast");
	$("#placeInfoInput").slideUp("fast");
	
	taggedFriends = [];
	document.getElementById("placeInfoInput").removeChild(document.getElementById("taggedFriends"));
	document.getElementById("searchInput").value = "";
	document.getElementById("placeTitle").value = "";
	document.getElementById("placeStory").value = "";

	document.cookie = "placeLocation=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

	$("#mapHolder").remove();
	document.getElementById("mapContainer").innerHTML += "<div id='mapHolder'></div>";
}
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
       alert("Geolocation is not supported by this browser :(");
    }
}
function showPosition(position) {
    lat = position.coords.latitude;
    lon = position.coords.longitude;
	document.cookie = "placeLocation="+lat+"#"+lon;
    latlon = new google.maps.LatLng(lat, lon);

    var myOptions = {
	    center:latlon,zoom:14,
	    mapTypeId:google.maps.MapTypeId.ROADMAP,
	    mapTypeControl:false,
	    navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
    }
    
    var map = new google.maps.Map(document.getElementById("mapHolder"), myOptions);
    var marker = new google.maps.Marker({position:latlon,map:map,title:"You are here!"});

    $("#mapContainer").fadeToggle("fast");
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("User denied the request for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
           	alert("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            alert("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error occurred.");
            break;
    }
}

//Places - Tag location
function chooseLocation() {
	$("#placeInfoInput").slideToggle("fast");
}

//Places - Tag friends
function addBloger(blogerId, img, fName, lName) {
	document.getElementById("searchInput").value = "";
	document.getElementById("searchResults").style.display = 'none';
	
	buildPush = blogerId+"#"+img+"#"+fName+"#"+lName;

	if (taggedFriends.indexOf(buildPush) == -1)	taggedFriends.push(buildPush);

	document.getElementById("taggedFriends").innerHTML = "\
		<button type='button' onclick='$(\"#friendsContainer\").fadeToggle(\"fast\")' class='taggedPeople' title='Tagged friends'><img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-people-512.png' /></button>\
		<div id='friendsContainer' class='container'>\
		</div>\
	";

	var friendsContainer = document.getElementById("friendsContainer");
	while (friendsContainer.firstChild) {
	    friendsContainer.removeChild(friendsContainer.firstChild);
	}

	for (i = 0; i < taggedFriends.length; i++) {
		friendsContainer.innerHTML += "<button id='"+taggedFriends[i].split("#")[0]+"' onclick='removeBlogger(\""+taggedFriends[i].split("#")[0]+"\")'><img src="+taggedFriends[i].split("#")[1]+" />"+taggedFriends[i].split("#")[2]+" "+taggedFriends[i].split("#")[3]+"</button>";
	}
}

function removeBlogger(blogerId) {
	var coppyArray = [];
	for (i = 0; i < taggedFriends.length; i++) {
		if (taggedFriends[i].split("#")[0] != blogerId) {
			coppyArray.push(taggedFriends[i]);
		}
	}

	taggedFriends = [];

	if (coppyArray.length > 0) {
		for (i = 0; i < coppyArray.length; i++) {
			taggedFriends.push(coppyArray[i]);
		}
		
		var friendsContainer = document.getElementById("friendsContainer");
		while (friendsContainer.firstChild) {
		    friendsContainer.removeChild(friendsContainer.firstChild);
		}

		for (i = 0; i < taggedFriends.length; i++) {
			friendsContainer.innerHTML += "<button id='"+taggedFriends[i].split("#")[0]+"' onclick='removeBlogger(\""+taggedFriends[i].split("#")[0]+"\")'><img src="+taggedFriends[i].split("#")[1]+" />"+taggedFriends[i].split("#")[2]+" "+taggedFriends[i].split("#")[3]+"</button>";
		}
	} else {
		while (document.getElementById("taggedFriends").firstChild) {
		    document.getElementById("taggedFriends").removeChild(document.getElementById("taggedFriends").firstChild);
		}
	}

}

//Places - Tag place FINAL
function tagPlace() {
	var title = document.getElementById('placeTitle');
	var story = document.getElementById('placeStory');
	var flag = 0;

	if (title.value == "" || title.value.trim() == "") {
		alert("Give title to that place.");
		flag = 1;
	}

	if (story.value == "" || story.value.trim() == "") {
		alert("What is the story of that place ?");
		flag = 1;
	}

	if (flag == 0) {
		document.cookie = "placeTitle="+title.value;

		var getIDs = [];
		if (taggedFriends.length > 0) {
			for (i = 0; i < taggedFriends.length; i++) {
				getIDs.push(taggedFriends[i].split("#")[0]);
			}
			document.cookie = "taggedFriends="+getIDs.toString();
		}

		document.getElementById("placeStoryForm").action = "tagCurrentLocation.php";
		document.forms['placeStoryForm'].submit();
	}
}

//Places - Preview place
function previewPlace(placeId) {
	document.cookie = "placeId="+placeId;
	window.location = "previewPlace.php";
}
function showOnMap(lat, lon, containerId) {
	$("#"+containerId).fadeToggle('fast');
    latlon = new google.maps.LatLng(lat, lon);

    var myOptions = {
	    center:latlon,zoom:14,
	    mapTypeId:google.maps.MapTypeId.ROADMAP,
	    mapTypeControl:false,
	    navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
    }
    
    var map = new google.maps.Map(document.getElementById(containerId), myOptions);
    var marker = new google.maps.Marker({position:latlon,map:map,title:"The place is here !"});
}

//Places - Delete place
function deletePlace(placeId) {
	document.cookie = "placeId="+placeId;
	window.location = "deletePlace.php";
}

//Place - Share place
function sharePlace(placeId) {
	document.cookie = "placeId="+placeId;
	window.location = "sharePlace.php";
}

//Place - World places
	//..PREVIEW..
function previewWorldPlace(placeId, sender, mobile) {
	document.cookie = "placeId="+placeId;

	var requestType;

	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("GET", "getPlaceInfo.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
            //document.getElementById("txtHint").innerHTML = requestType.responseText;
			var getRequest = requestType.responseText;            
			var count = 0;
			var slice;

			//Struct
			var placeId = "";
			var placeTitle = "";
			var placeCords = "";
			var placeStory = "";
			var taggedPeople = "";
			var likers = "";

			for (i = 0; i < getRequest.length; i++) {
				if (getRequest[i] == '~') {
					count++;
					if (count == 1) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeId += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 2) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeTitle += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 3) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeCords += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 4) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							placeStory += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 5) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							taggedPeople += getRequest[slice]; 
							slice++;
						}
					}
					else
					if (count == 6) {
						slice = i + 1;
						while (getRequest[slice] != '$') {
							likers += getRequest[slice]; 
							slice++;
						}
					}
				}
			}

			var pinnedTimes = 0;
			if (likers != "NONE") {
				var pinnedBy = likers.split(",");
				for (i = 0; i < pinnedBy.length; i++) {
					pinnedTimes++;
				}
			} else {
				pinnedTimes = 0;
			}

			//Build UI
			if (document.getElementById('previewPlaceWorld') != null) document.getElementById("body").removeChild(document.getElementById("previewPlaceWorld"));
			var body = document.getElementById('body');
			if (mobile == 0) {
				body.innerHTML += "\
					<div id='previewPlaceWorld'>\
						<div id='menuContainer'>\
							<div class='left'>\
								<button type='button' id='pinnButton' class='pinnButton' onclick='pinPlace(\""+placeId+"\")' title='Like this place'>\
									<img src='https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678087-heart-128.png' /> "+pinnedTimes+"\
								</button>\
							</div>\
							<div class='right'>\
								<button type='button' class='hideButton' onclick='removeElement(\"previewPlaceWorld\", \"body\");'></button>\
							</div>\
							<h1>"+placeTitle+"</h1>\
						</div>\
						<div id='storyContainer'>\
							<p>\
								"+placeStory+"\
							</p>\
							<div id='taggedContainer'>\
								<div id='optionsContainer'>\
								</div>\
							</div>\
							<div id='mapControler'>\
							</div>\
						</div>\
					</div>\
				";
			}
			else
			if (mobile == 1) {
				body.innerHTML += "\
					<div id='previewPlaceWorld-container' onclick='removeElement(\"previewPlaceWorld-container\", \"body\");'>\
						<div id='previewPlaceWorld' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>\
							<div id='optionsContainer'>\
								<button type='button' id='pinnButton' class='pinnButton' onclick='pinPlace(\""+placeId+"\");' title='Like this place'>\
									<img src='https://cdn0.iconfinder.com/data/icons/very-basic-android-l-lollipop-icon-pack/24/like-512.png' /> "+pinnedTimes+"\
								</button>\
							</div>\
							<div id='titleContainer'>\
								<h1>"+placeTitle+"</h1>\
							</div>\
							<div id='storyContainer'>\
								<p>\
									"+placeStory+"\
								</p>\
								<div id='mapControler'>\
								</div>\
							</div>\
						</div>\
					</div>\
				";
			}

			showOnMap(placeCords.split("#")[0], placeCords.split("#")[1], "mapControler");

			if (taggedPeople.indexOf(",") > -1) {
				if (mobile == 0) { cmd="$(\"#list\").slideToggle(\"fast\")"; addClass=""; }
				else 
				if (mobile == 1) { cmd="$(\"#list\").fadeToggle(\"fast\")"; addClass="class='right'"; }

				document.getElementById("optionsContainer").innerHTML += "\
					<button type='button' "+addClass+" title='Tagged people' onclick='"+cmd+"'>\
						<img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-people-512.png' />\
					</button>\
					<div id='list'>\
					</div>\
				";

				parseBlogger(taggedPeople, taggedPeople.split(",").length - 1, 0);
			} else {
				if (taggedPeople != sender) {
					if (mobile == 0) { cmd="$(\"#list\").slideToggle(\"fast\")"; addClass=""; }
					else 
					if (mobile == 1) { cmd="$(\"#list\").fadeToggle(\"fast\")"; addClass="right"; }

					document.getElementById("optionsContainer").innerHTML += "\
						<button type='button' "+addClass+" title='Tagged people' onclick='"+cmd+"'>\
							<img src='https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-people-512.png' />\
						</button>\
						<div id='list'>\
						</div>\
					";
					parseBlogger(taggedPeople, 0, -1);
				}
			}

			if (mobile == 1) {
				build = "\
					<button class='back-button' onclick='$(\"#list\").fadeToggle(\"fast\");'>\
						Close\
					</button>\
				";
				$("#list").prepend(build);
			}

			$("#mapControler").fadeIn("fast");
        }
    }
}

//Function - ParseBlogger
function parseBlogger(blogerId, callBacks, indexPointer) {
	var flag = 0;
	if (blogerId.indexOf(",") > -1) {
		blogerId = blogerId.split(",");
		document.cookie = "userId="+blogerId[indexPointer];
		flag = 1;
	} else {
		document.cookie = "userId="+blogerId;
	}

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "getUserInfo.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
     		result = requestType.responseText;
 		   	var parseResult = result.split("$");

 		   	var blogerIdArgument = "";
 		   	if (flag == 1) {
 		   		blogerIdArgument = blogerId[indexPointer];
 		   	}
 		   	else {
 		   		blogerIdArgument = blogerId;
 		   	}

 		   	var build = "\
 		   		<button type='button' onclick='window.location=\"openBloger.php?"+blogerIdArgument+"\";'>\
 		   			<div style='background-image: url("+parseResult[0]+"); background-size: cover; background-position: 50%;' class='img'></div>\
 		   			"+parseResult[2]+" "+parseResult[3]+"\
 		   		</button>\
 		   	";

			document.getElementById("list").innerHTML += build;
 		 	document.cookie = "userId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

 		 	if (callBacks > 0) {
 		 		parseBlogger(blogerId.toString(), callBacks - 1, indexPointer + 1);
 		 	}
     	}
    }
}

//Places - Resize map 
function resizeToggle(containerId) {
	if (flag == 0) {
		$("#"+containerId).animate({
            height: '+=30%',
            top: '-=30%'
        }, 500);

		document.getElementById("resizeButton").src = "https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-arrow-down-512.png";
		flag = 1;
	}
	else
	if (flag == 1) {
		$("#"+containerId).animate({
            height: '-=30%',
        	top: '+=30%'
        }, 500);

		document.getElementById("resizeButton").src = "https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-arrow-up-512.png";
		flag = 0;
	}
}

//Places - PinPlace 
function pinPlace(placeId) {
	document.cookie = "placeId="+placeId;

	var requestType;

	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("GET", "pinPlace.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
     		var getRequest = requestType.responseText;
     		document.getElementById('pinnButton').innerHTML = "<img src='https://cdn0.iconfinder.com/data/icons/very-basic-android-l-lollipop-icon-pack/24/like-512.png' /> "+getRequest;
        	document.cookie = "getId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
        }
    }
}

//Play video
function playVideo(videoSrc, videoType) {
	document.getElementById("fullPageContainer").innerHTML += "\
			<div id='videoContainer'>\
				<video controls>\
					<source src='"+videoSrc+"' type='video/"+videoType+"'>\
				</video>\
			</div>\
	";
	$("#fullPageContainer").fadeIn("fast");
}

function stopVideo() {
	$("#fullPageContainer").fadeOut("fast");
	document.getElementById("fullPageContainer").removeChild(document.getElementById("videoContainer"));
}

//Load stories - Author
function loadStories(id, cmd, from, sender) {
	if ( cmd == "1" ) { //Get sender if the user if previewing an author
		if ( window.location.href.indexOf("?") > -1 ) {
			sender = window.location.href.split("?")[1];
		}
	}

	flag = 1;

	var table = document.getElementById('main-table');
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	var build;
	if (from == 0) {
		if ($("#loadSign").size() == 0 && loops > 0) {
			build = "\
				<div id='loadSign'>\
					<img src='../images/loadSign.GIF' />\
				</div>\
			";
		}
		requestType.open("POST", "pullStories.php", true);
	} 
	else 
	if (from == 1) {
		if ($("#loadSign").size() == 0 && loops > 0) {
			build = "\
				<div id='loadSign'>\
					<img src='../../images/loadSign.GIF' />\
				</div>\
			";
		}
		requestType.open("POST", "../../PHP/pullStories.php", true);
	}
	else
	if (from == 2) {
		if ($("#loadSign").size() == 0 && loops > 0) {
			build = "\
				<div id='loadSign'>\
					<img src='../../images/loadSign.GIF' />\
				</div>\
			";
		}
		requestType.open("POST", "pullStories.php", true);
	}

	$("#body").append(build);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");

	requestType.send("sender="+sender+"&getId="+id+"&buildFor="+cmd);
	
	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			//console.log( requestType.responseText );

			getId = requestType.responseText.split("~")[0];
			getPost = requestType.responseText.split("~")[1];

			$("#body").children("#loadSign").remove();
			$("#main-table").append(getPost);
		 	setTimeout(function() { $("#main-table tbody").find("#poster").addClass("show-poster") }, 10 );
			
			if (loops == 10) { lastId = getId - 2; flag = 0; }

			if (getId > 0 && loops < 10) {
				loops++;
				loadStories(getId, cmd, from, sender);
			}
		}
	}
}

//Load stories - Explorer
function loadExplorerStories(id, cmd, from) {
	flag = 1;

	if ( $("#loadSign").size() == 0 ) {
		var build = "<div id='loadSign'>";
		if (from == 1) {
			build += "<img src='../../images/loadSign.GIF' />";
		} else {
			build += "<img src='../images/loadSign.GIF' />";
		}
		build += "</div>";
		$(build).appendTo("#body");
	}

	//if (cmd == 0) document.cookie = "authorInfo="+parsePosts[loops - 1].split("@")[1];
	if (cmd == 1) document.cookie = "buildWorldStories=1";

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "pullStories.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("getId="+id+"&buildFor=2");

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getId = requestType.responseText.split("~")[0];
			getPost = requestType.responseText.split("~")[1];

			//alert(requestType.responseText)

			$("#loadSign").remove();
			$("#main-table").append(getPost);
			setTimeout(function() { $("#main-table tbody").find("#poster").addClass("show-poster") }, 10 );

			// if (cmd == 0) document.cookie = "authorInfo=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			if (cmd == 1) document.cookie = "buildWorldStories=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

			if (loops == 10) { lastId = getId - 2; flag = 0; }

			if (getId > 0 && loops < 10) {
				loops++;
				loadExplorerStories(getId, cmd, from);
			}
		}
	}
}

//Load stories - Clubs
function loadStoriesClubs(id, cmd, from, administrators) {
	flag = 1;

	if ( $("#loadSign").size() == 0 ) {
		var build = "<div id='loadSign'>";
		if (from == 1) {
			build += "<img src='../../images/loadSign.GIF' />";
		} else {
			build += "<img src='../images/loadSign.GIF' />";
		}
		build += "</div>";
		$(build).appendTo("#body");
	}

	getURL = window.location.href;

	getClubTable = getURL.split("?")[1].split("=")[0];
	getClubId = getURL.split("?")[1].split("=")[1];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "pullStories.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("getId="+id+"&buildFor="+cmd+"&clubTable="+getClubTable+"&clubId="+getClubId+"&clubAdministrators="+administrators);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			/*alert(requestType.responseText);*/

			getId = requestType.responseText.split("~")[0];
			getPost = requestType.responseText.split("~")[1];

			$("#loadSign").remove();
			$("#main-table").append(getPost);
			setTimeout(function() { $("#main-table tbody").find("#poster").addClass("show-poster") }, 10 );

			if (loops == 10) { lastId = getId; flag = 0; console.log(lastId); }

			if (getId > 0 && loops < 10) {
				loops++;
				loadStoriesClubs(getId, cmd, from);
			}
		}
	}
}

//Page manipulations - Explore Stories / Explore F. Stories
function checkPos() { //Checks the scroll and loads more stories if true
	if ($(window).scrollTop() + document.body.clientHeight + 400 >= $(window).height()) {
		if (loops == 10 && flag == 0) { callBack(); }
	}
}


//Manipulate post
	//Delete post
function deletePost(postTitle, postId) {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "deleteMethod.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("postTitle="+postTitle+"&postId="+postId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
 			var storeResponce = requestType.responseText;

			if (storeResponce == "READY") {
				$("."+postId).remove();
				document.cookie = "postTitle=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			}
		}
	}
}	
	//Delete club post
function deleteClubPost(postTitle, postId, mobile) {
	getURL = window.location.href;
	getArguments = getURL.split("?")[1];

	if ( getArguments.indexOf("&") > -1 ) {
		getArguments = getArguments.split("&")[0];	
	}
	if ( getArguments.indexOf("#") > -1 ) {
		getArguments = getArguments.split("#")[0];
	}

	getClubTable = getArguments.split("=")[0];
	getClubId = getArguments.split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if ( mobile == 0 ) { requestType.open("POST", "Universal/deleteClubMethod.php", true); }
	else 
	if ( mobile == 1 ) { requestType.open("POST", "../Universal/deleteClubMethod.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&postTitle="+postTitle+"&postId="+postId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
 			var storeResponce = requestType.responseText;

			if (storeResponce == "READY") {
				$("."+postId).remove();
			}
		}
	}
}	

	//Edit post
function editPost(postTitle, postId) {
	POSTPOINTER = postId;

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "editMethod.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("postTitle="+postTitle+"&postId="+postId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
 			var storeResponce = requestType.responseText;
			storeResponce = storeResponce.split("~||~");

			document.getElementById("editorTitle").value = storeResponce[0].replace("-id-", "").replace(/6996/g, " ");
			document.getElementById("editorLink").value = storeResponce[1];
			document.getElementById("editorContent").value = storeResponce[2];

			document.getElementById("update-button").onclick = function() { 
		        updatePost(storeResponce[0], postId); 
		    };

			$("#editorContainer").fadeIn("fast");
		}
	}
}

	//Edit club post
function editClubPost(postTitle, postId, mobile) {
	getURL = window.location.href;
	getArguments = getURL.split("?")[1];

	if ( getArguments.indexOf("&") > -1 ) {
		getArguments = getArguments.split("&")[0];	
	}
	if ( getArguments.indexOf("#") > -1 ) {
		getArguments = getArguments.split("#")[0];
	}

	getClubTable = getArguments.split("=")[0];
	getClubId = getArguments.split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if ( mobile == 0 ) { requestType.open("POST", "Universal/clubEditMethod.php", true); }
	else
	if ( mobile == 1 ) { requestType.open("POST", "../Universal/clubEditMethod.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&postTitle="+postTitle+"&postId="+postId);

	if ( mobile == 0 ) { mt = "-134px"; }
	else
	if ( mobile == 1 ) { mt = "-487px" }

	//Build container
	build = "\
		<div id='full-page-container' class='hide-me'>\
			<div id='inline-fields' style='margin-top: "+mt+";'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<input type='text' placeholder='Title of the post..' id='editorTitle' name='editorTitle' class='mt-15'>\
				<input type='url' placeholder='Link to a video or to an image..' id='editorLink' name='editorLink'>\
				<textarea placeholder=\"What's up ?\" id='editorContent' name='editorContent'></textarea>\
				<button id='update-button'>Update</button>\
			</div>\
		</div>\
	";
	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
 			var storeResponce = requestType.responseText;
			storeResponce = storeResponce.split("$");

			document.getElementById("editorTitle").value = storeResponce[0].replace("-id-", "").replace(/6996/g, " ");
			document.getElementById("editorLink").value = storeResponce[1];
			document.getElementById("editorContent").value = storeResponce[2];

			document.getElementById("update-button").onclick = function() { 
		        updateClubPost(storeResponce[0], postId, mobile); 
		    };

			$("#full-page-container").fadeIn("fast");
		}
	}
}

	//Close editor
function clearEditorContainer() {
	$("#editorContainer").fadeOut("fast");

	document.cookie = "postPoint=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	document.getElementById("editorTitle").innerHTML = "Title";
	document.getElementById("editorLink").value = "";
	document.getElementById("editorContent").value = "";
}

	//Update post 
function updatePost(title, postId) {
	if (document.getElementById("editorTitle").value.trim() != "" && (
		document.getElementById("editorLink").value.trim() != "" || 
		document.getElementById("editorContent").value.trim() != "")
		) {

		flag = 0;

		if ( document.getElementById("editorTitle").value.indexOf("&") > -1 || document.getElementById("editorContent").value.indexOf("&") > -1
		 ) {
			alert('Ampersant is not alowed & use and instead.');
			flag = 1;
		}

		if ( flag == 0 ) {
			document.getElementById("update-button").onclick = '';

			getTitle = document.getElementById("editorTitle").value.trim();
			getLink = document.getElementById("editorLink").value.trim();
			getContent = document.getElementById("editorContent").value.trim().replace(/(?:\r\n|\r|\n)/g, '<br />');

			//alert(getTitle); -DEBUG MODE

			var requestType;
			if (window.XMLHttpRequest) {
				requestType = new XMLHttpRequest();
			} else {
				requestType = new ActiveXObject("Microsoft.XMLHTTP");
			}
			requestType.open("POST", "updateMethod.php", true);
			requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			requestType.send("postTitle="+getTitle+"&postLink="+getLink+"&postContent="+getContent+"&postId="+postId);

			requestType.onreadystatechange = function() {
				if (requestType.readyState == 4 && requestType.status == 200) {
		 			var storeResponce = requestType.responseText;
		 			//alert(storeResponce); //-DEBUG MODE

					if (storeResponce == "READY") {
						var requestType1;
						if (window.XMLHttpRequest) {
							requestType1 = new XMLHttpRequest();
						} else {
							requestType1 = new ActiveXObject("Microsoft.XMLHTTP");
						}
						requestType1.open("POST", "pullStories.php", true);
						requestType1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
						requestType1.send("getId="+POSTPOINTER+"&buildFor=0");

						requestType1.onreadystatechange = function() {
							if (requestType1.readyState == 4 && requestType1.status == 200) {
					 			var storePost = requestType1.responseText;
								
					 			$("."+POSTPOINTER).replaceWith(storePost);
					 			setTimeout(function(){ $("."+POSTPOINTER).find("#poster").addClass( "show-poster" ); }, 150);

								document.cookie = "postTitle=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
								document.cookie = "postId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
								document.cookie = "postLink=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
								document.cookie = "postContent=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
								clearEditorContainer();
							}
						}
					}
				}
			}
		}
	} else {
		if ( document.getElementById("editorTitle").value.trim() == "" ) { alert("Give title to your post first."); }
		else
		if ( document.getElementById("editorLink").value.trim() == "" || document.getElementById("editorContent").value.trim() == "" ) { alert("First add some image or text to your post."); }
	}
}

	//Update club post 
function updateClubPost(title, postId, mobile) {
	if (document.getElementById("editorTitle").value.trim() != "" && (
		document.getElementById("editorLink").value.trim() != "" || 
		document.getElementById("editorContent").value.trim() != "")
		) {

		document.getElementById("update-button").onclick = '';

		getTitle = document.getElementById("editorTitle").value.trim();
		getLink = document.getElementById("editorLink").value.trim();
		getContent = document.getElementById("editorContent").value.trim().replace(/(?:\r\n|\r|\n)/g, '<br />');

		getURL = window.location.href;
		getArguments = getURL.split("?")[1];

		if ( getArguments.indexOf("&") > -1 ) {
			getArguments = getArguments.split("&")[0];	
		}
		if ( getArguments.indexOf("#") > -1 ) {
			getArguments = getArguments.split("#")[0];
		}

		getClubTable = getArguments.split("=")[0];
		getClubId = getArguments.split("=")[1];

		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if ( mobile == 0 ) { requestType.open("POST", "Universal/updateClubMethod.php", true); }
		else
		if ( mobile == 1 ) { requestType.open("POST", "../Universal/updateClubMethod.php", true); }
		
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&postTitle="+getTitle+"&postLink="+getLink+"&postContent="+getContent+"&postId="+postId);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
	 			var storeResponce = requestType.responseText;
				if (storeResponce == "READY") {
					var requestType1;
					if (window.XMLHttpRequest) {
						requestType1 = new XMLHttpRequest();
					} else {
						requestType1 = new ActiveXObject("Microsoft.XMLHTTP");
					}
					requestType1.open("POST", "pullStories.php", true);
					requestType1.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					requestType1.send("clubTable="+getClubTable+"&clubId="+getClubId+"&getId="+postId+"&buildFor=5");

					requestType1.onreadystatechange = function() {
						if (requestType1.readyState == 4 && requestType1.status == 200) {			
				 			$("."+postId).replaceWith(requestType1.responseText);
							unloadFullPageContainer();
						}
					}
				}
			}
		}
	} else {
		if ( document.getElementById("editorTitle").value.trim() == "" ) { alert("Give title to your post first."); }
		else { alert("First add some image or text to your post."); }
	}
}

//Load bloggers
function loadBloggers(loops, stack, container) {
	if (stack != "") {
		parseStack = stack.split(",");

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		requestType.open("POST", "giveMePerson.php", true);
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("authorId="+parseStack[loops - 1]);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
	 			var storeResponce = requestType.responseText;
				
				document.getElementById(container).innerHTML += storeResponce;

				if (loops > 1) {
					loops--;
					loadBloggers(loops, stack, container);
				}
			}
		}
	}
}

//Notifications - Show notification date
function showNotificationDate(date) {
	$("#notifications").children("#title").children("h2").html(date);
}

//Notifications - Clear date container
function clearDateContainer() {
	$(".pushNotification").children("#title").children("h2").html("");
}

//User manipulation
	//Imitate logout
function imitateLogOut() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "imitateLogOut.php", true);
	requestType.send();
}
	//Imitate login
function imitateLogIn() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "imitateLogIn.php", true);
	requestType.send();
}

//Check if element is in the viewport
function isScrolledIntoView(elem) {
    var $elem = $(elem);
    var $window = $(window);

    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();

    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}


//Capitalize symbol
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

//Partners manipulations
//-Get partners
function loadPartnerships() {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "Library/PHP/Universal/getPartnerships.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			var storeResponce = requestType.responseText;

			if (storeResponce == "TBC") { /*Table is successfully created.*/ }
			else { //Parsed partners
				if (storeResponce.indexOf(",") > -1) { 
					storePartners = storeResponce.split(","); 

					for (i = 0; i <= storePartners.length; i++) {
						storePartnerInfo = storePartners[i].split("|");
						buildPartner(storePartnerInfo);
					}
				}
				else { 
					storePartners = storeResponce; 
					storePartnerInfo = storePartners.split("|");
					buildPartner(storePartnerInfo);
				}
			}
		}
	}
}

//-Build partner UI
function buildPartner(storePartnerInfo) {
	build = "\
		<div id='partner' onclick='openPartner(\""+storePartnerInfo[1]+"\")'>\
			<div id='partner-logo' style='background: url(\""+storePartnerInfo[2]+"\"); background-size: cover;'></div>\
			<h1>"+storePartnerInfo[0]+"</h1>\
		</div>\
	";
	$("#partners").append(build);
}

//-Open partner page
function openPartner(url) {
	window.open(url);
}

//-Clear partners-holder
function clearPartnersContainer() {
	$("#partnersList-main").children("#partners-container").empty();
}

//Build Report user - Container
function buildReportContainer(mobile, blogerId) {
	if (mobile == 0)
		addCloseButton = "<button class='hideButton' onclick='removeReportContainer();'></button>";
	else
	if (mobile == 1)
		addCloseButton = "";

	build = "\
		<div id='report-container' onclick='removeReportContainer();'>\
			<div id='text-container' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>\
				"+addCloseButton+"\
				<h1>What is the problem ?</h1>\
				<textarea id='user-report' placeholder='The problem is..'></textarea>\
				<button class='sendButton' onclick='sendUserReport("+mobile+", \""+blogerId+"\");'>Send</button>\
			</div>\
		</div>\
	";

	$(document.body).append(build);

	$("#report-container").fadeIn("fast");
}

//Remove Report user - Container
function removeReportContainer() {
	$("#report-container").fadeOut("fast");
	setTimeout(function() {
		$("#report-container").remove();
	}, 250);
}

//Send Report user - Send Report
function sendUserReport(mobile, blogerId) {
	storeReport = $("#report-container").children("#text-container").children("#user-report").val();
	if (storeReport.trim() == "") {
		alert("Enter something into the report first !");
	} else {
		document.cookie = "reportContainer="+storeReport+";domain=."+window.location.host+";path=/";
		document.cookie = "reportedId="+blogerId+";domain=."+window.location.host+";path=/";

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0)
			requestPath = "Universal/sendUserReport.php";
		else
		if (mobile == 1)
			requestPath = "../Universal/sendUserReport.php";

		requestType.open("GET", requestPath, true);
		requestType.send();

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				var storeResponce = requestType.responseText;

				if (storeResponce == "READY") {
					document.cookie = "reportContainer=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
					document.cookie = "blogerId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
					removeReportContainer(); 
				}
			}
		}
	}
}

//Show / Hide label
function showLabel(motherContainer, objectId, cmd) {
	if (cmd == 1) { $(motherContainer).children(".img").hide(); }
	$(motherContainer).children(objectId).show();
}
function hideLabel(motherContainer, objectId, cmd) {
	$(motherContainer).children(objectId).hide();
	if (cmd == 1) { $(motherContainer).children(".img").show(); }
}

//Messages
//-Check for new message
function checkForMessageUpdate(conversationId, senderImg, messangerImg) {
	setTimeout(function(){
		//alert(1);
		document.cookie = "conversationId="+conversationId+";domain=."+window.location.host+";path=/";
		document.cookie = "senderImg="+senderImg+";domain=."+window.location.host+";path=/";
		document.cookie = "messangerImg="+messangerImg+";domain=."+window.location.host+";path=/";

		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		requestPath = "rePullMessages.php";
		requestType.open("GET", requestPath, true);
		requestType.send();

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				var storeResponce = requestType.responseText;

				document.cookie = "conversationId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				//alert(storeResponce);
				if (storeResponce != "NULL") {
					$("#message-text").prepend(storeResponce);
				}
				
				checkForMessageUpdate(conversationId, senderImg, messangerImg);
			}
		}
	}, 1000);
}

//Scale containers
function scaleContainers(cmd, containers, mobile) {
	if (cmd == 1) {
		getFirstContainer = containers.split(",")[0];
		getSecondContainer = containers.split(",")[1];
		getContainerID = containers.split(",")[2];
			
		//$("."+getContainerID).find(getFirstContainer).css("background-color", "black");
		getMotherWidth = $("."+getContainerID).find(getFirstContainer).width();
		getMotherHeight = $("."+getContainerID).find(getFirstContainer).height();

		setChildrenHeight = 0;
		if (mobile == 0) {
			setChildrenHeight = getMotherHeight - 37;

			//$("."+getContainerID).find("#poster").removeAttr("onmouseover");
		}
		else
		if (mobile == 1) {
			setChildrenHeight = getMotherHeight - 73;

			$("."+getContainerID).find("#poster").removeAttr("onclick");
			$("."+getContainerID).find("#poster").on("click", function(){ hideOverlayOptions(getContainerID, 1); });
		}

		$("."+getContainerID).find(getSecondContainer).width(getMotherWidth);
		$("."+getContainerID).find(getSecondContainer).height(setChildrenHeight);

		getChildrenHeight = $("."+getContainerID).find(getSecondContainer).height() / 2;
		getHeaderHeight = $("."+getContainerID).find(getSecondContainer).children(".likes").height() / 2;

		//$("."+getContainerID).find(getSecondContainer).css("margin-top", "37");
		if (mobile == 0) {
			$("."+getContainerID).find(getSecondContainer).children(".likes").css("margin-top", (getChildrenHeight - getHeaderHeight));
		}
		else
		if (mobile == 1) {
			$("."+getContainerID).find(getSecondContainer).children(".likes").css("margin-top", (getChildrenHeight - getHeaderHeight - 34));
		}
		
		$("."+getContainerID).find(getSecondContainer).show();

		/*$("."+getContainerID).find(getSecondContainer).mouseover(function(e){ e.stopPropagation(); });
		$("."+getContainerID).find(getSecondContainer).mouseout(function(e){ e.stopPropagation(); });*/
	}
}

function showOverlayOptions(id, mobile) {
	if (mobile == 0) {
		$("."+id).find("#overlay-options").show();
	}
	else
	if (mobile == 1) {
		$("."+id).find("#overlay-options").show();
		
		$("."+id).find("#poster").removeAttr("onclick");
		$("."+id).find("#poster").on("click", function(){ hideOverlayOptions(id, 1); });
	}
}

function hideOverlayOptions(id, mobile) {
	if (mobile == 0) {
		$("."+id).find("#overlay-options").hide();
	}
	else
	if (mobile == 1) {
		$("."+id).find("#overlay-options").hide();

		$("."+id).find("#poster").removeAttr("onclick");
		$("."+id).find("#poster").on("click", function(){ showOverlayOptions(id, 1); });
	}
}

//Overlay controls
function playVideo(boxId, videoPlayer) {
	getSrc = $("."+boxId).find("iframe").attr("src");

	if (videoPlayer == "youtube") { 
		if (getSrc.indexOf("?autoplay=1") > -1) { 
			setSrc = getSrc.split("?")[0]; 
			$("."+boxId).find(".play-button").text("Play video");
		}
		else { 
			setSrc = getSrc + "?autoplay=1"; 
			$("."+boxId).find(".play-button").text("Stop video");
		} 
	}
	else
	if (videoPlayer == "vimeo") { 
		if (getSrc.indexOf("?autoplay=1") > -1) { 
			setSrc = getSrc.split("?")[0]; 
			$("."+boxId).find(".play-button").text("Play video");
		}
		else { 
			setSrc = getSrc + "?autoplay=1"; 
			$("."+boxId).find(".play-button").text("Stop video");
		} 
	}
	else { setSrc = ""; }

	$("."+boxId).find("iframe").attr("src", setSrc);
}

//Like - Unlike post
function likeUnlikePost(postId, ownerId, mobile) {
	postAuthor = window.location.href.split("?")[1];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/likeUnlikePost.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/likeUnlikePost.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("postId="+postId+"&postAuthor="+postAuthor);

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	storeResponce = requestType.responseText;
        	
        	$("."+postId).find(".likes").html("<span>&#xf004;</span>"+storeResponce);

        	if ($("."+postId).find(".likes").hasClass("liked")) {
        		$("."+postId).find(".likes").removeClass("liked");
        		$("."+postId).find(".likes").addClass("not-liked");
        	} 
        	else 
        	if ($("."+postId).find(".likes").hasClass("not-liked")) {
        		$("."+postId).find(".likes").removeClass("not-liked");
        		$("."+postId).find(".likes").addClass("liked");
        	}
        }
    }
}

//Like - Unlike post
function likeUnlikeClubPost(postId, mobile) {
	getURL = window.location.href;
	getArguments = getURL.split("?")[1];

	if ( getArguments.indexOf("&") > -1 ) {
		getArguments = getArguments.split("&")[0];	
	}
	if ( getArguments.indexOf("#") > -1 ) {
		getArguments = getArguments.split("#")[0];
	}

	getClubTable = getArguments.split("=")[0];
	getClubId = getArguments.split("=")[1];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/likeUnlikeClubPost.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/likeUnlikeClubPost.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&postId="+postId);

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	storeResponce = requestType.responseText;
        	
        	$("."+postId).find(".likes").html("<span>&#xf004;</span>"+storeResponce);

        	if ($("."+postId).find(".likes").hasClass("liked")) {
        		$("."+postId).find(".likes").removeClass("liked");
        		$("."+postId).find(".likes").addClass("not-liked");
        	} 
        	else 
        	if ($("."+postId).find(".likes").hasClass("not-liked")) {
        		$("."+postId).find(".likes").removeClass("not-liked");
        		$("."+postId).find(".likes").addClass("liked");
        	}
        }
    }
}

//Preview post likes
function previewLikes(postId, mobile) {
	document.getElementById("getLikes").onclick = "";

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/requestLikes.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/requestLikes.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("postId="+postId);

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	storeLikers = requestType.responseText;

        	if (storeLikers != "") {
        		//Build the UI for the container
				if (mobile == 0) { closeButton = "<button class='hideButton' onclick='removeContentContainer();'></button>"; }
				else
				if (mobile == 1) { closeButton = ""; }

				build  = "\
					<div id='content-container' onclick='removeContentContainer();'>\
						<div id='likers-list'>\
							<div id='header'>\
								<h1>Likes :</h1>\
								"+closeButton+"\
							</div>\
							<div id='list-users'>\
							</div>\
						</div>\
					</div>\
				";
				$("body").append(build);
				$("#content-container").fadeIn("fast");
				$("#content-container").children("#likers-list").slideToggle("medium");
	        	parseUser(storeLikers, storeLikers.split(',').length - 1, 1, "previewLikes");
        	} else {
        		build = "\
        			<div id='float-notification'>\
        				<h1>Nobody likes this :-(</h1>\
        			</div>\
        		";
        		$("body").append(build);
        		$("#float-notification").fadeIn("fast");
        		setTimeout(function(){ 
        			$("#float-notification").slideToggle("fast");  
        			setTimeout(function(){ 
        				$("#float-notification").remove(); 
        				document.getElementById("getLikes").onclick = function(){ previewLikes(postId, mobile); }
        			}, 350);
        		}, 2000);
        	}
        }
    }
}

//Remove content container
function removeContentContainer() {
	$("#content-container").children().slideToggle("medium");
	$("#content-container").fadeOut("fast");
	setTimeout(function(){ $("#content-container").remove(); }, 150);
}

//Request user & parse user
function parseUser(storage, loops, callBacks, append) {
	getId = storage.split(',')[loops];
	
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "giveMePerson.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+getId);

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	storeResponce = requestType.responseText;

        	if (append == "previewLikes") $("#content-container").find("#likers-list").children("#list-users").append(storeResponce);

			if (callBacks == 1 && loops > 0) {
				loops--;
				parseUser(storage, loops, callBacks, append);
			}
        }
    }
}

//Preview post page
function previewPost(postId) {
	document.cookie = "getId="+postId+";domain=."+window.location.host+";path=/";
	document.cookie = "postId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}

//Repost function
function repostPost(postId, author, mobile) {
	author = window.location.href.split("?")[1];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/repostPost.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/repostPost.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("postId="+postId+"&authorId="+author);
	

	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	storeResponce = requestType.responseText;

        	if (storeResponce == "READY") {
        		build = "\
        			<div id='float-notification'>\
        				<h1><span>&#xf118;</span>Reposted</h1>\
        			</div>\
        		";
        		$("body").append(build);
        		$("#float-notification").fadeIn("fast");
        		setTimeout(function(){ 
        			$("#float-notification").slideToggle("fast");  
        			setTimeout(function(){ $("#float-notification").remove(); }, 350);
        		}, 2000);
        	}
        }
    }
}

function loadAlbum(mobile, lastId) {
	if (mobile == 0) { src = "../images/loadSign.GIF"; }
	else
	if (mobile == 1) { src = "../../images/loadSign.GIF"; }

	load_sign = "\
		<div id='loadSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#albumImages").append(load_sign); }
	else
	if (mobile == 1) { $("body").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/getAlbum.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/getAlbum.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("lastId="+lastId+"&isMobile="+mobile);
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) { 	
        	storeResponce = requestType.responseText.split("~");

        	$("#loadSign").remove();
        	$("#albumImages").append(storeResponce[0]);
        
        	if (storeResponce[1] == 1) {
        		id = storeResponce[2];
        		loadAlbum(mobile, id);
        	}
        }
    }
}

function loadOlderNotifications(mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#notifications").children("#stack").empty().append(load_sign); }
	else
	if (mobile == 1) { $("#notifications-stack").empty().append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("GET", "Universal/getOlderNotifications.php", true); }
	else
	if (mobile == 1) { requestType.open("GET", "../Universal/getOlderNotifications.php", true); }

	requestType.send();
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	if (mobile == 0) { $("#notifications").children("#stack").children("#loadSign").fadeOut("fast"); }
        	else
        	if (mobile == 1) { $("#notifications-stack").children("#loadSign").fadeOut("fast"); }

        	setTimeout(function(){ 
        		if (mobile == 0) {
	        		$("#notifications").children("#stack").children("#loadSign").remove();
	        		$("#notifications").children("#stack").append(requestType.responseText);
	        	} 
	        	else
	        	if (mobile == 1) {
	        		$("#notifications-stack").children("#loadSign").remove();
	        		$("#notifications-stack").append(requestType.responseText);
	        		$("#notifications-stack").find(".timeStamp").remove();
	        	}
        	 }, 150);
        }
    }
}

function switchFriendsContainers(containerId, buttonId) {
	//Change active button
	$("#friendsContainers").find(".current").removeClass("current");
	$("#friendsContainers").find(buttonId).addClass("current");

	document.cookie = "previewBar=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	storeC_ID = containerId.split("#")[1];
	document.cookie = "previewBar="+storeC_ID+";domain=."+window.location.host+";path=/";

	//Switch containers
	$("#friendsContainers").find(".visible").fadeOut("fast");
	$("#friendsContainers").find(".visible").removeClass("visible");
	setTimeout(function(){ 
		$("#friendsContainers").find(containerId).addClass("visible");
		$("#friendsContainers").find(containerId).fadeIn("fast"); 4
	}, 250);
}


function loadSuggestions(mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign-suggestions'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#sideBar").children("#suggestions").append(load_sign); }
	else
	if (mobile == 1) { $("#sideBar").children("#suggestions-list").children("#suggestions").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("GET", "getSuggestions.php", true); }
	else
	if (mobile == 1) { requestType.open("GET", "loadSuggestedBlogers.php", true); }

	requestType.send();
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	if (mobile == 0) { $("#sideBar").children("#suggestions").children("#loadingSign-suggestions").fadeOut("fast"); }
        	else
        	if (mobile == 1) { $("#sideBar").children("#suggestions-list").find("#loadingSign-suggestions").fadeOut("fast"); }

        	setTimeout(function(){ 
        		if (mobile == 0) {
        			$("#sideBar").children("#suggestions").children("#loadingSign-suggestions").remove();
        			$("#sideBar").children("#suggestions").html(requestType.responseText);
        		} else 
        		if (mobile == 1) { 
        			$("#sideBar").children("#suggestions-list").find("#loadingSign-suggestions").remove();
        			$("#sideBar").children("#suggestions-list").children("#suggestions").html(requestType.responseText);
        		}
        	}, 150);
        }
    }
}

function loadOnlineFriends(mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#friendsContainers").children("#onlineFriends").append(load_sign); }
	else
	if (mobile == 1) { $("#sideBar").children("#online-list").children("#onlineFriends").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("GET", "loadOnlineFriends.php", true); }
	else
	if (mobile == 1) { requestType.open("GET", "loadOnlineFriends.php", true); }

	requestType.send();
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	//alert(requestType.responseText);

        	if (mobile == 0) { $("#friendsContainers").find("#loadingSign").fadeOut("fast"); }
        	else
        	if (mobile == 1) { $("#sideBar").children("#online-list").children("#onlineFriends").find("#loadingSign").fadeOut("fast"); }

        	setTimeout(function(){ 
        		if (mobile == 0) {
        			$("#friendsContainers").find("#loadingSign").remove();
        			$("#friendsContainers").children("#onlineFriends").empty();
        			$("#friendsContainers").children("#onlineFriends").html(requestType.responseText);
        		} else 
        		if (mobile == 1) { 
        			$("#sideBar").find("#onlineFriends").children("#loadingSign").remove();
        			$("#sideBar").find("#onlineFriends").html(requestType.responseText);
        		}
        	}, 150);
        }
    }
}

function loadOhana(mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#friendsContainers").children("#ohanaContainer").append(load_sign); }
	else
	if (mobile == 1) { $("#sideBar").children("#ohana-list").children("#ohana-members").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("GET", "loadOhana.php", true); }
	else
	if (mobile == 1) { requestType.open("GET", "loadOhana.php", true); }

	requestType.send();
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	if (mobile == 0) { $("#friendsContainers").children("#ohanaContainer").find("#loadingSign").fadeOut("fast"); }
        	else
        	if (mobile == 1) { $("#sideBar").children("#ohana-list").find("#loadingSign").fadeOut("fast"); }

        	setTimeout(function(){ 
        		if (mobile == 0) {
        			$("#friendsContainers").children("#ohanaContainer").find("#loadingSign").remove();
        			$("#friendsContainers").children("#ohanaContainer").html(requestType.responseText);
        		} else 
        		if (mobile == 1) { 
        			$("#sideBar").children("#ohana-list").find("#loadSign").remove();
        			$("#sideBar").children("#ohana-list").children("#ohana-members").html(requestType.responseText);
        		}
        	}, 150);
        }
    }
}

function loadMessages(mobile, id) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#message-text").append(load_sign); }
	else
	if (mobile == 1) { $("#message-text").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/giveMessage.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/giveMessage.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("mobile="+mobile+"&lastId="+id);
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	getId = requestType.responseText.split("~")[0];
        	getMessage = requestType.responseText.split("~")[1];

        	$("#message-text").find("#loadingSign").remove();
        	$("#message-text").append(getMessage);

        	if (lastMessageId == -1) { lastMessageId = parseInt(getId) + 1; }
        	if (getId > 0) { loadMessages(mobile, getId); }
        }
    }
}

//Request messages - Dinamic messaging
function requestNewMessages(sender, chatPartner, mobile) {
	//alert(chatPartner);

	setTimeout(function(){
		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/tryMessages.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/tryMessages.php", true); }

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("sender="+sender+"&chatPartner="+chatPartner+"&lastMessageId="+lastMessageId+"&mobile="+mobile);

		requestType.onreadystatechange = function() {
        	if (requestType.readyState == 4 && requestType.status == 200) {
        		
        		if ( requestType.responseText != "NNM" ) {
        			buildMessage = requestType.responseText.split("~")[0];
        			lastMessageId = requestType.responseText.split("~")[1];
        			$("#message-text").prepend(buildMessage);
        		}

        		requestNewMessages(sender, chatPartner, mobile);
        	}
        }
	}, 2500);
}

//Load places
function loadPlaces(id, mobile, type) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	$("#placesContainer").append(load_sign);

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (type == 0) {
		if (mobile == 0) { requestType.open("POST", "Universal/loadWorldPlaces.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/loadWorldPlaces.php", true); }
	}
	else
	if (type == 1) {  }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("mobile="+mobile+"&placeId="+id);
	
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	getId = requestType.responseText.split("~")[0];
        	getPlace = requestType.responseText.split("~")[1];

        	//alert(requestType.responseText);

        	$("#placesContainer").find("#loadingSign").remove();
    		$("#placesContainer").append(getPlace); 
        	
        	if (getId > 0) { loadPlaces(getId, mobile, type); }
        }
    }
}

//Load Notifications container
function loadNotifications() {
	if ( $("#notifications-container").length ) {
		$("#notifications-container").fadeOut("fast");
		setTimeout(function(){ $("#notifications-container").remove(); }, 150);
	} else {
		build = "\
			<div id='notifications-container'>\
				<div id='holder'>\
				</div>\
			</div>\
		";
		$("body").append(build);

		buffer = "\
			<div id='loadingSign'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
		$("#notifications-container #holder").append(buffer);
		$("#notifications-container").fadeIn("fast");
	
		//Send a collection request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		requestType.open("GET", "Universal/getNotifications.php", true);
		requestType.send();

		//Get responce
		requestType.onreadystatechange = function() {
	        if (requestType.readyState == 4 && requestType.status == 200) {
	        	$("#notifications-button span").remove();
	        	$("#notifications-container #holder #loadingSign").remove();
	        	$("#notifications-container #holder").append(requestType.responseText);
	        }
	    }
	}
}

//Search engine tools
function loadSearchEngine(mobile) {
	//Build and attach the main container
	buildContainer = "\
		<div id='search-engine-container'>\
		</div>\
	";
	$("body").append(buildContainer);

	//Build the buffer animation
	if (mobile == 1) {
		buildLoader = "\
			<div id='loadingSign'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loadingSign'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}
	$("#search-engine-container").append(buildLoader);

	//Send a collection request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 1) { requestType.open("GET", "../Universal/collectSearches.php", true); }
	else { requestType.open("GET", "Universal/collectSearches.php", true); }
	requestType.send();

	//Show the container
	$("#search-engine-container").fadeIn("fast");

	//Get responce
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	//Catch searches
        	getSearches = requestType.responseText.split(',');

        	//Add searches container and remove the loading animation
        	if (mobile == 1) {
        		childContainer = "\
	        		<div id='child-container'>\
	        			<button class='hide-button' onclick='unloadSearchEngine(1);'><span>&#xf104</span>Back</button>\
	        			<input type='text' id='search-input' placeholder='Search for...'>\
	        			<div id='subchild-container'>\
	        			</div>\
	        		</div>\
	        	";
        	} else {
	        	childContainer = "\
	        		<div id='child-container'>\
	        			<input type='text' id='search-input' placeholder='Search for...'>\
	        			<div id='subchild-container'>\
	        			</div>\
	        		</div>\
	        	";
	        }

        	$("#search-engine-container").children("#loadingSign").fadeOut();
        	$("#search-engine-container").append(childContainer);
        	$("#search-engine-container").children("#child-container").fadeIn("fast");
        	$("#search-engine-container").children("#loadingSign").remove();

        	//Attach events for searching
        	document.getElementById('search-input').onkeyup = function() { searchFriends(getSearches, 'search-input', 'subchild-container', 1, mobile); };
        }
    }

	//Change functions
	if (mobile != 1) {
		$("#search-button").removeAttr("onclick");
		document.getElementById("search-button").onclick = function() { unloadSearchEngine(); };
	}
}

function unloadSearchEngine(mobile) {
	$("#search-engine-container").fadeOut("fast");
	setTimeout(function(){
		$("#search-engine-container").remove();

		//Change functions
		if (mobile != 1) { 
			$("#search-button").removeAttr("onclick");
			document.getElementById("search-button").onclick = function() { loadSearchEngine(); } 
		}
	}, 150);
}

//Make global search
function makeGlobalSearch(mobile) {
	unloadSearchEngine(mobile);

	//Build the main-container
	if (mobile == 1) {
		buildFullContainer = "\
			<div id='full-search-container' onclick='unloadGlobalSearch();'>\
				<div id='search-engine-container'>\
					<div id='header'>\
						<button class='hide-button' onclick='unloadGlobalSearch();'><span>&#xf104</span>Back</button>\
						<h1>Search result :</h1>\
					</div>\
				</div>\
			</div>\
		";
	} else {
		buildFullContainer = "\
			<div id='full-search-container' onclick='unloadGlobalSearch();'>\
				<div id='search-engine-container'>\
					<div id='header'>\
						<button type='button' class='hideButton' onclick='unloadGlobalSearch();'></button>\
						<h1>Search result :</h1>\
					</div>\
				</div>\
			</div>\
		";
	}
	$("body").append(buildFullContainer);

	//Build the buffer animation
	if (mobile == 1) {
		buildLoader = "\
			<div id='loadingSign'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";		
	} else {
		buildLoader = "\
			<div id='loadingSign'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";		
	}
	$("#full-search-container").children("#search-engine-container").append(buildLoader);

	getSearch = $("#search-input").val();

	//Send a collection request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 1) { requestType.open("POST", "../Universal/collectWorldSearches.php", true); }
	else { requestType.open("POST", "Universal/collectWorldSearches.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("searchThis="+getSearch);

	//Attach and show
	$("#full-search-container").fadeIn("fast");
	$("#full-search-container").children("#search-engine-container").slideToggle("fast");
	$("#full-search-container").children("#search-engine-container").click(function() { event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation(); });

	//Get responce
	requestType.onreadystatechange = function() {
        if (requestType.readyState == 4 && requestType.status == 200) {
        	$("#full-search-container").children("#search-engine-container").children("#loadingSign").remove();
        	getSearches = requestType.responseText.split(",");

        	if (getSearches != "NF:(") {
	        	//Send parsing request while there are suggestions
	        	for (count = 0; count < getSearches.length; count++) {
	        		parseAndGivePersons(getSearches[count], "#full-search-container > #search-engine-container");
	        	}
	        } else {
	        	$("#full-search-container > #search-engine-container").append("<h1 class='error-message'>Nothing found :(</h1>");
	        }
        }
    }
}

function unloadGlobalSearch() {
	$("#full-search-container").fadeOut("fast");
	setTimeout(function(){ $("#full-search-container").remove(); }, 150);
}

//Parse and give Persons
function parseAndGivePersons(personId, container) {
	//Build the buffer animation
	buildLoader = "\
		<div id='loadingSign'>\
			<img src='http://blogy.co/Library/images/loadSign_white.gif'/>\
		</div>\
	";
	$(container).append(buildLoader);

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "giveMePerson.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+personId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#loadingSign").remove();
			$(container+" #"+personId).remove();
			$(container).append(requestType.responseText);
		}
	}
}

//Parse and give Clubs
function parseAndGiveClubs(clubMeta, container) {
	//Build the buffer animation
	buildLoader = "\
		<div id='loadingSign'>\
			<img src='http://blogy.co/Library/images/loadSign_white.gif'/>\
		</div>\
	";
	$(container).append(buildLoader);

	clubSlug = clubMeta.split("~")[0];
	clubOwner = clubMeta.split("~")[1];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "giveMeClub.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubSlug="+clubSlug+"&clubOwner="+clubOwner);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#loadingSign").remove();
			$(container+" #"+clubSlug).remove();
			$(container).append(requestType.responseText);
		}
	}
}

//Parse and give Plugins
function parseAndGivePlugins(pluginMeta, container) {
	//Build the buffer animation
	buildLoader = "\
		<div id='loadingSign'>\
			<img src='http://blogy.co/Library/images/loadSign_white.gif'/>\
		</div>\
	";
	$(container).append(buildLoader);

	pluginName = pluginMeta.split("~")[0];
	pluginSlug = pluginMeta.split("~")[1];
	pluginAuthor = pluginMeta.split("~")[2];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "giveMePlugin.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginName="+pluginName+"&pluginSlug="+pluginSlug+"&pluginAuthor="+pluginAuthor);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#loadingSign").remove();
			$(container+" #"+pluginSlug).remove();
			$(container).append(requestType.responseText);
		}
	}
}

//Load my registered clubs
function loadMyRegisteredClubs(mobile, clubId) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#my-clubs").append(load_sign); }
	else
	if (mobile == 1) { $("#my-clubs").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/loadMyRegisteredClubs.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/loadMyRegisteredClubs.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getId = requestType.responseText.split("~")[0];
			getBuild = requestType.responseText.split("~")[1];

			//alert(requestType.responseText);

			$("#my-clubs").children("#loadingSign").remove();

			if (getBuild != "" && getBuild != "NONE") { $("#my-clubs").append(getBuild); }
			if (getId > 0) { loadMyRegisteredClubs(mobile, getId); }
			
			//FREE
			if (getId <= 0) {
				buildAddNewClub = "\
					<div class='add-new-club club-container' onclick='loadAddClubEditor("+mobile+");'>\
						<span>&#xf055;</span>\
						<h1>Add new</h1>\
					</div>\
				";
				$("#my-clubs").prepend(buildAddNewClub);
			}


			/*PAYMENTAL
			if (getId <= 0 && ( getBuild == "" || getBuild == "NONE" ) ) {
				buildAddNewClub = "\
					<div class='add-new-club club-container' onclick='loadAddClubEditor("+mobile+");'>\
						<span>&#xf055;</span>\
						<h1>Add new</h1>\
					</div>\
				";
				$("#my-clubs").prepend(buildAddNewClub);
			}*/
		}
	}
}

//Load all registered clubs
function loadWorldRegisteredClubs(mobile, clubId) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign' class='mt-20'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#world-clubs").append(load_sign); }
	else
	if (mobile == 1) { $("#world-clubs").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/loadWorldRegisteredClubs.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/loadWorldRegisteredClubs.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getId = requestType.responseText.split("~")[0];
			getBuild = requestType.responseText.split("~")[1];

			//alert(requestType.responseText);

			$("#world-clubs").children("#loadingSign").remove();

			if (getBuild != "" && getBuild != "NONE") { $("#world-clubs").append(getBuild); }
			if (getId > 0) { loadWorldRegisteredClubs(mobile, getId); }
		}
	}
}

//Load my membershiped clubs
function loadMyMembershipedClubs(mobile, clubId) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loader-1' class='loading-buffer-small'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#membershiped-clubs").append(load_sign); }
	else
	if (mobile == 1) { $("#membershiped-clubs").append(load_sign); }

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/loadMyMembershipedClubs.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/loadMyMembershipedClubs.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getId = requestType.responseText.split("~")[0];
			getBuild = requestType.responseText.split("~")[1];

			$("#loader-1").remove();

			if (getBuild != "" && getBuild != "NONE") { $("#membershiped-clubs").append(getBuild); }
			if (getId > 0) { loadMyMembershipedClubs(mobile, getId); }
		}
	}
}

//Load Add Club Editor
function loadAddClubEditor(mobile) {
	build = "\
		<div id='editorContainer'>\
			<div id='editor-fields'>\
				<input type='text' id='club-name' placeholder='Name of your Club..' />\
				<div id='color-picker-container'>\
					<label>Color of your Club</label>\
					<span id='blue' class='color-box checked' onclick='changeClubColor(\"blue\");'></span>\
					<span id='green' class='color-box' onclick='changeClubColor(\"green\");'></span>\
					<span id='yellow' class='color-box' onclick='changeClubColor(\"yellow\");'></span>\
					<span id='red' class='color-box' onclick='changeClubColor(\"red\");'></span>\
					<span id='purple' class='color-box' onclick='changeClubColor(\"purple\");'></span>\
					<span id='dark' class='color-box' onclick='changeClubColor(\"dark\");'></span>\
				</div>\
				<button type='button' onclick='registerClub("+mobile+");' class='button'>Add club</button>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#editorContainer").fadeIn("fast");
	
	$("#editorContainer").on('click', function(e) { 
	   if( e.target == this ) unloadAddClubEditor(); 
	});
}

//Change Club color
function changeClubColor(color) {
	$("#color-picker-container").find(".checked").removeClass("checked");
	$("#color-picker-container").find("#"+color).addClass("checked");
}

//Register Club
function registerClub(mobile) {
	getName = $("#club-name").val().trim();
	getLogo = $("#club-logo").val();
	getColor = $(".checked").attr("id");

	flag = 0;

	if (getName == "") {
		flag = 1;
		alert("Your club needs a name !");
	}
	 
	if (getLogo == "") {
		flag = 1;
		alert("Your club needs an unique logo !");
	}

	if ( !/^[a-zA-Z0-9_ ]+$/.test(getName) ) {
		flag = 1;
		alert("Your club name can be Alpha-Numeric only !");
	}

	if (flag == 0) {
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/registerClub.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/registerClub.php", true); }
		
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("clubName="+getName+"&clubColor="+getColor);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				getResponce = requestType.responseText;

				//alert(getResponce);

				if (getResponce == "READY") {
					window.location = "newClubTutorial.php";
				} 
				else 
				if (getResponce == "Already exists") {
					$("#editor-fields").fadeOut();
					$("#editorContainer").append("<h1 class='message'>It seems that you already have registered this club :-)</h1>");
					$("#editor-fields").remove();
				}
			}
		}
	}
}

//Unload Add Club Editor
function unloadAddClubEditor() {
	$("#editorContainer").fadeOut("fast");
	setTimeout(function(){ $("#editorContainer").remove(); }, 150);
}

//Show my clubs in the sidebar
function showMyClubs(mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loadingSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $(".clubs-container").children(".clubs-list").append(load_sign); }
	else
	if (mobile == 1) {}

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/collectMyClubs.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/collectMyClubs.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			getBuild = requestType.responseText;

			$(".clubs-container #loadingSign").remove();
			$(".clubs-container").children(".clubs-list").append(getBuild);
			$(".clubs-container").find("#show-hide-clubs").html("&#xf106;");
			$(".clubs-container").find("#show-hide-clubs").removeAttr("onclick");
			document.getElementById("show-hide-clubs").onclick = function(){ hideMyClubs(mobile); };
		}
	}
}

function hideMyClubs(mobile) {
	$(".clubs-container").children(".clubs-list").empty().show();
	$(".clubs-container").find("#show-hide-clubs").html("&#xf107;");
	$(".clubs-container").find("#show-hide-clubs").removeAttr("onclick");
	document.getElementById("show-hide-clubs").onclick = function(){ showMyClubs(mobile); };
}

//Upload club logo
function uploadLogo(imageContainer) {
	getLogo = $(imageContainer).val();
	getClubOwner = $("#club-owner").val();
	getClubID = $("#club-id").val();

	isClub = $("#is-club").val();

	flag = 0;

	if (getLogo == "") {
		flag = 1;
		alert("Choose the logo first !");
	}

	if (getClubOwner == "" || 
		getClubOwner == undefined || 
		getClubID == "" || 
		getClubID == undefined || 
		getLogo == undefined ||
		isClub == "" ||
		isClub == undefined
		) {
		flag = 1;
		alert("Don't try to cheat ! Now you'll need to refresh the page :-)");
	} 

	if (flag == 0) {
		if ( $("#editor-fields").length ) {
			$("#editor-fields").attr("action", "Upload.php");
			$("#editor-fields").submit();
		} else {
			if ( $("#visualize-settings").length ) {
				$("#visualize-settings").attr("action", "Upload.php");
				$("#visualize-settings").submit();
			}
		}
	}
}

//Change club logo with picture from Album
function loadClubSelector(mobile, cmd, picture) {
	if (cmd == "change-logo") {
		build = "\
			<div id='editorContainer'>\
				<div id='editor-large-container'>\
					<div id='header'>\
						<button type='button' class='hideButton' onclick='unloadEditorContainer();'></button>\
						<h1>Clubs :</h1>\
					</div>\
				</div>\
			</div>\
		";

		$("body").append(build);
		$("#editorContainer").fadeIn("fast");

		$("#editorContainer").on('click', function(e) { 
		   if( e.target == this ) unloadEditorContainer(); 
		});

		//Attach loader
		if (mobile == 0) { src = "../images/loadSign_white.gif"; }
		else
		if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

		load_sign = "\
			<div id='loader-1' class='loading-buffer-large'>\
				<img src='"+src+"'/>\
			</div>\
		";

		if (mobile == 0) { $("#editor-large-container").append(load_sign); }
		else
		if (mobile == 1) { $("#editor-large-container").append(load_sign); }

		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/collectMyClubs-ClubSelector.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/collectMyClubs-ClubSelector.php", true); }

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("pictureId="+picture+"&mobile="+mobile);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				$("#loader-1").remove();
				$("#editor-large-container").append(requestType.responseText);
			}
		}
	}
}

//Set new club logo
function setNewClubLogo(mobile, table, clubId, pictureId) {
	//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/changeClubLogo-AlbumObject.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/changeClubLogo-AlbumObject.php", true); }

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("tableId="+table+"&clubId="+clubId+"&pictureId="+pictureId);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				if (requestType.responseText == "READY") {
					unloadEditorContainer();
				}
			}
		}
}

//Clubs menus
function showHideClubSubMenu(subMenu_id) {
	$(subMenu_id).toggle();
}

//Load color picker clubs
function loadColorPickerClubs(mobile) {
	build = "\
		<div id='editorContainer'>\
			<div id='editor-fields' class='min-height-115'>\
				<h1>Choose color for your club</h1>\
				<div id='color-picker-container' class='no-border'>\
					<span id='blue' class='color-box' onclick='changeClubColorCore(\"blue\", "+mobile+");'></span>\
					<span id='green' class='color-box' onclick='changeClubColorCore(\"green\", "+mobile+");'></span>\
					<span id='yellow' class='color-box' onclick='changeClubColorCore(\"yellow\", "+mobile+");'></span>\
					<span id='red' class='color-box' onclick='changeClubColorCore(\"red\", "+mobile+");'></span>\
					<span id='purple' class='color-box' onclick='changeClubColorCore(\"purple\", "+mobile+");'></span>\
					<span id='dark' class='color-box' onclick='changeClubColorCore(\"dark\", "+mobile+");'></span>\
				</div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	
	getCurrentColor = $("#club-header").attr("class");
	$("#"+getCurrentColor).addClass("checked");

	$("#editorContainer").fadeIn("fast");
	
	$("#editorContainer").on('click', function(e) { 
	   if( e.target == this ) unloadEditorContainer(); 
	});
}

//Change club color in the core and in the front
function changeClubColorCore(color, mobile) {
	if (mobile == 0) { src = "../images/buffer-loader.GIF"; }
	else
	if (mobile == 1) { src = "../../images/buffer-loader.GIF"; }

	load_sign = "\
		<div id='loadingSign'>\
			<img src='"+src+"'/>\
		</div>\
	";

	if (mobile == 0) { $("#color-picker-container").append(load_sign); }
	else
	if (mobile == 1) {}

	getURL = window.location.href;

	getClubTable = getURL.split("?")[1].split("=")[0];
	getClubId = getURL.split("?")[1].split("=")[1];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/changeClubColor.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/changeClubColor.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("color="+color+"&mobile="+mobile+"&clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				$("#color-picker-container").find("#loadingSign").remove();

				currentColor = $("#color-picker-container").find(".checked").attr("id");

				$("#color-picker-container").find(".checked").removeClass("checked");
				$("#color-picker-container").find("#"+color).addClass("checked");
				$("#club-header").removeClass(currentColor).addClass(color);
			}
		}
	}
}

//Unload color picker clubs
function unloadEditorContainer() {
	$("#editorContainer").fadeOut("fast");
	setTimeout(function(){ $("#editorContainer").remove(); }, 150);
}

//Update club meta
function updateClubMeta(mobile) {
	getName = $("#club-name").val();
	getSlug = $("#club-slug").val();
	getType = $("#club-type").find(":selected").text();

	flag = 0;

	if (getName == "" || getName === undefined) {
		flag = 1;
		alert("Your club needs name actually !");
	}

	if (getSlug == "" || getSlug === undefined) {
		flag = 1;
		alert("Your club needs slug !");
	}

	if (getType == "" || getType === undefined) {
		flag = 1;
		alert("Your club needs its unique type !");
	}

	if ( !/^[a-zA-Z0-9_ ]+$/.test(getName) ) {
		flag = 1;
		alert("Your club name can be only Alpha-Numeric !");
	}

	if (flag == 0) {
		if (mobile == 0) { $("#general-settings").attr("action", "Universal/updateClubMeta.php"); }
		else
		if (mobile == 1) { $("#general-settings").attr("action", "../Universal/updateClubMeta.php"); }

		$("#general-settings").submit();
	}
}

//Calculate club meta functions
function calculateClubMembers(table, clubId, mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	build_loader = "\
		<div id='loader-1' class='loading-buffer-small'>\
			<img src='"+src+"' />\
		</div>\
	";

	$("#members-calc-container").append(build_loader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/calculateClubMembers.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/calculateClubMembers.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+table+"&clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("#loader-1").remove();
			$("#members-calc-container").append(requestType.responseText);
		}
	}
}

function calculateClubVisits(table, clubId, mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	build_loader = "\
		<div id='loader-2' class='loading-buffer-small'>\
			<img src='"+src+"' />\
		</div>\
	";

	$("#visits-calc-container").append(build_loader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/calculateClubVisits.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/calculateClubVisits.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+table+"&clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("#loader-2").remove();
			$("#visits-calc-container").append(requestType.responseText);
		}
	}
}

function addClubVisit(mobile) {
	getURL = window.location.href;
	getClubTable = getURL.split("?")[1].split("=")[0];
	getClubId = getURL.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/addClubVisit.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/addClubVisit.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if ( requestType.responseText == "READY" ) { /*So a visit is added :O*/ }
 		}
	}
}

function calculateClubStories(table, clubId, mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	build_loader = "\
		<div id='loader-3' class='loading-buffer-small'>\
			<img src='"+src+"' />\
		</div>\
	";

	$("#stories-calc-container").append(build_loader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/calculateClubStories.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/calculateClubStories.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+table+"&clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("#loader-3").remove();
			$("#stories-calc-container").append(requestType.responseText);
		}
	}
}

//Load club administrators
function loadClubAdministrators(table, clubId, mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	build_loader = "\
		<div id='loader-4' class='loading-buffer-large'>\
			<img src='"+src+"' />\
		</div>\
	";

	$("#administrators-container").append(build_loader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/loadClubAdministrators.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/loadClubAdministrators.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+table+"&clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("#loader-4").remove();
			
			//Parse bloggers IDs
			getAdministratorIDs = requestType.responseText.split(",");
			for (count = 0; count < getAdministratorIDs.length; count++) {
				if (count + 1 < getAdministratorIDs.length) { addClass = "border-b-e5e5e5"; }
				else { addClass = ""; }

				buildRow = "\
					<div id='admin-"+count+"' class='inline-row "+addClass+"'>\
					</div>\
				";
				$("#administrators-container").append(buildRow);
				buildClubAdministratorContainer(getAdministratorIDs[count], "#admin-"+count, mobile);
			}
		}
	}
}

function buildClubAdministratorContainer(adminId, container, mobile) {
	//Build the buffer animation
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loader' class='loading-buffer-large'>\
			<img src='"+src+"'/>\
		</div>\
	";
	$(container).append(load_sign);

	getClubOwner = window.location.href.split("?")[1].split("=")[0].split("_")[0];

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if (mobile == 0) { requestType.open("POST", "Universal/buildClubAdministratorContainers.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/buildClubAdministratorContainers.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+adminId+"&container="+container+"&mobile="+mobile+"&clubOwner="+getClubOwner);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#loader").remove();
			$(container).append(requestType.responseText);
		}
	}
}

//Remove club administrator
function removeClubAdministrator(adminId, container, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/removeClubAdministrator.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/removeClubAdministrator.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&adminId="+adminId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location.reload(true);
			}
			else
			if (requestType.responseText == "CDUSO") {
				alert("You can't remove yourself, you are the owner !");
			}
			else
			if (requestType.responseText == "CDO") {
				alert("You can't remove the owner from his own club !");
			}
		}
	}
}

//REQUESTS
//Load requests
function loadClubRequests(table, clubId, mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	build_loader = "\
		<div id='loader-8' class='loading-buffer-large'>\
			<img src='"+src+"' />\
		</div>\
	";

	$("#requests-container").append(build_loader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/loadClubRequests.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/loadClubRequests.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+table+"&clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("#loader-8").remove();
			
			//Parse bloggers IDs
			getMembersIDs = requestType.responseText.split(",");

			//Remove empty elements
			getMembersIDs.splice(getMembersIDs.indexOf(""), 1);

			for (count = 0; count < getMembersIDs.length; count++) {
				memberId = getMembersIDs[count];

				if (count + 1 < getMembersIDs.length) { addClass = "border-b-e5e5e5"; }
				else { addClass = ""; }

				buildRow = "\
					<div id='request-"+count+"' class='inline-row "+addClass+" request'>\
					</div>\
				";
				$("#requests-container").append(buildRow);

				buildClubRequestsContainer(memberId, "#request-"+count, mobile);
			}
		}
	}
}

function buildClubRequestsContainer(memberId, container, mobile) {
	//Build the buffer animation
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loader-9' class='loading-buffer-large'>\
			<img src='"+src+"'/>\
		</div>\
	";
	$(container).append(load_sign);

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if (mobile == 0) { requestType.open("POST", "Universal/buildClubRequestContainer.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/buildClubRequestContainer.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+memberId+"&container="+container+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container+" #loader-9").remove();
			$(container).append(requestType.responseText);
		}
	}
}

//Accept club request
function acceptClubRequest(memberId, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/acceptClubRequest.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/acceptClubRequest.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&memberId="+memberId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location.reload(true);
			}
		}
	}
}

//Decline club request
function declineClubRequest(memberId, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/declineClubRequest.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/declineClubRequest.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&memberId="+memberId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location.reload(true);
			} 
			else
			if (request.responseText == "CDO") {
				alert("You can't remove the owner of the club !");
			}
		}
	}
}

//MEMBERS
//Load members
function loadClubMembers_Admin(table, clubId, mobile) {
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	build_loader = "\
		<div id='loader-6' class='loading-buffer-large'>\
			<img src='"+src+"' />\
		</div>\
	";

	$("#club-members-container").append(build_loader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/loadClubMembers.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/loadClubMembers.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+table+"&clubId="+clubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("#loader-6").remove();
			
			//Parse bloggers IDs
			getMembersIDs = requestType.responseText.split(",");

			//Remove empty elements
			getMembersIDs.splice(getMembersIDs.indexOf(""), 1);

			for (count = 0; count < getMembersIDs.length; count++) {
				memberId = getMembersIDs[count].split("~")[0];
				memberType = getMembersIDs[count].split("~")[1];

				buildRow = "\
					<div id='member-"+count+"' class='inline-row border-b-e5e5e5 member'>\
					</div>\
				";
				$("#club-members-container").append(buildRow);

				buildClubMembersContainer(memberId, "#member-"+count, mobile, memberType);
			}
		}
	}
}

function buildClubMembersContainer(memberId, container, mobile, memberType) {
	//Build the buffer animation
	if (mobile == 0) { src = "../images/loadSign_white.gif"; }
	else
	if (mobile == 1) { src = "../../images/loadSign_white.gif"; }

	load_sign = "\
		<div id='loader-7' class='loading-buffer-large'>\
			<img src='"+src+"'/>\
		</div>\
	";
	$(container).append(load_sign);

	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if (mobile == 0) { requestType.open("POST", "Universal/buildClubMemberContainer.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/buildClubMemberContainer.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+memberId+"&container="+container+"&mobile="+mobile+"&memberType="+memberType);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container+" #loader-7").remove();
			$(container).append(requestType.responseText);
		}
	}
}

//Load search and invite engine
function loadSearchAndInviteEngine(mobile) {
	closeButton = "";
	if ( mobile == 0 ) { closeButton = "<button type='button' class='hideButton' onclick='unloadSearchAndInviteEngine();'></button>" }
	else
	if ( mobile == 1 ) { closeButton = "<button class='hide-button' onclick='unloadSearchEngine(1);'><span>&#xf104</span>Back</button>"; }

	build = "\
	<div id='full-search-container'>\
		<div id='search-engine-container'>\
			<div id='header'>\
				"+closeButton+"\
				<h1>Invite friends :</h1>\
			</div>\
		</div>\
	</div>\
	";
	$("body").append(build);
	$("#full-search-container").fadeIn("fast");
	$("#search-engine-container").slideToggle("fast");
	$("#full-search-container").on('click', function(e) { 
	   if( e.target == this ) unloadSearchAndInviteEngine(); 
	});

	//Build the buffer animation
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}
	$("#search-engine-container").append(buildLoader);

	getURL = window.location.href;
	getClubTable = getURL.split("?")[1].split("=")[0];
	getClubId = getURL.split("?")[1].split("=")[1];

	//Send a collection request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 1) { requestType.open("POST", "../Universal/collectClubMembersSearches.php", true); }
	else { requestType.open("POST", "Universal/collectClubMembersSearches.php", true); }
	
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			//Catch searches
        	getSearches = requestType.responseText.split(',');

			$("#loader").remove();

			//Append the containers
			build = "\
				<input type='text' id='search-input' class='wide-fat open-sans-regular' placeholder='Search for...'>\
				<div id='list-users'>\
				</div>\
			";
			$("#search-engine-container").append(build);

			//Attach events for searching
        	document.getElementById('search-input').onkeyup = function() { searchFriends(getSearches, 'search-input', 'list-users', 3, mobile); };
		}
	}
}
//Unload search and invite engine
function unloadSearchAndInviteEngine() {
	$("#full-search-container").fadeOut("fast");
	setTimeout(function(){ $("#full-search-container").remove(); }, 150);
}

//Invite friend to join your club
function inviteMember(memberId, arrayElement, mobile) {
	getIndex = getSearches.indexOf(arrayElement);
	getSearches.splice(getIndex, 1); // Remove the added member from the container

	//Clear the input and the container
	$("."+memberId).remove();

	//Get club information
	getURL = window.location.href;
	getClubTable = getURL.split("?")[1].split("=")[0];
	getClubId = getURL.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/sendClubInvitation.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/sendClubInvitation.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("bloggerId="+memberId+"&clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				getLastMember = $(".member").last();

				if ( getLastMember.attr("id") === undefined ) {
					getLastMemberId = 0;
				} else {
					getLastMemberId = getLastMember.attr("id").split("-")[1];
				}

				getLastMemberId += 1;

				splitArrayElement = arrayElement.split('#');
				fName = splitArrayElement[0];
				lName = splitArrayElement[1];
				img = splitArrayElement[3];

				build = "\
					<div id='member-"+getLastMemberId+"' class='inline-row border-b-e5e5e5'>\
						<div id='placeholder' class='column w-65p vam'>\
							<a href='openBloger.php?"+memberId+"' onclick=\"openBloger('$authorId')\">\
								<div style='background: url(\""+img+"\"); background-size: cover; background-position: 50%;' class='img'></div>\
								"+fName+" "+lName+"\
							</a>\
						</div>\
						<div id='options-container' class='column w-30p vam'>\
							<span class='iconic roll-over' title='Pending for confirmation'>&#xf017;</span>\
							<button type='button' class='delete-button' onclick='removeClubMember(\""+memberId+"\", "+mobile+");'>Remove</button>\
						</div>\
					</div>\
				";

				$("#club-members-container").append(build);
			}
		}
	}
}

//Send join club request
function sendJoinClubRequest(location, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/sendJoinClubRequest.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/sendJoinClubRequest.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if ( requestType.responseText == "READY" ) {
				if ( location == "location.reload" ) { window.location.reload(true); }
				else
				if ( location == "location.myclubs" ) { window.location = "exploreMyClubs.php?"+getClubTable+"="+getClubId; }
			}
		}
	}
}

//Cancel join club request
function cancelJoinClubRequest(location, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/cancelJoinClubRequest.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/cancelJoinClubRequest.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if ( requestType.responseText == "READY" ) {
				if ( location == "location.reload" ) { window.location.reload(true); }
				else
				if ( location == "location.myclubs" ) { window.location = "exploreMyClubs.php?"+getClubTable+"="+getClubId; }
			}
		}
	}
}

//Leave club
function leaveClub(mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];
	
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}
	$("#story-board").empty().append(buildLoader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/leaveClub.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/leaveClub.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if ( requestType.responseText == "READY" ) {
				window.location = "exploreMyClubs.php";
			} else {
				alert(requestType.responseText);
			}
		}
	}
}


//Remove member
function promoteClubMember(memberId, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/promoteClubMember.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/promoteClubMember.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&memberId="+memberId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location.reload(true);
			}
			else
			if (requestType.responseText == "CS") {
				alert("Requset for confirmation had been send to the owner of the club.");
			}
			else {
				alert(requestType.responseText);
			}
		}
	}
}

function removeClubMember(memberId, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/removeClubMember.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/removeClubMember.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&memberId="+memberId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location.reload(true);
			}
		}
	}
}

//Club invites

function acceptClubInvitation(mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/acceptClubInvitation.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/acceptClubInvitation.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location = "exploreMyClubs.php";
			}
		}
	}
}

function declineClubInvitation(mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/declineClubInvitation.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/declineClubInvitation.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location = "exploreMyClubs.php";
			}
		}
	}
}

//Accept club promotion
function acceptClubPromotion(memberId, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/acceptClubPromotion.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/acceptClubPromotion.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&memberId="+memberId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location.reload(true);
			}
		}
	}
}

//Remove club promotion
function removeClubPromotion(memberId, mobile) {
	getClubTable = window.location.href.split("?")[1].split("=")[0];
	getClubId = window.location.href.split("?")[1].split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/removeClubPromotion.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/removeClubPromotion.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubTable="+getClubTable+"&clubId="+getClubId+"&memberId="+memberId);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				window.location.reload(true);
			}
		}
	}
}


//Load shortcodes help
function loadShortcodesHelp(mobile) {
	build = "\
		<div id='editorContainer' class='shortcodes-helper'>\
			<div id='editor-large-container'>\
				<div id='header'>\
					<button type='button' class='hideButton' onclick='unloadShortCodesHelp();'></button>\
					<h1>Shortcodes :</h1>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[b][/b]</span>\
					<span class='separator'>&bull;</span>\
					<span class='bold'>The sunset filled the entire sky..</span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[i][/i]</span>\
					<span class='separator'>&bull;</span>\
					<span class='italic'>The waves crashed and danced..</span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[u][/u]</span>\
					<span class='separator'>&bull;</span>\
					<span class='underline'>The painting was a field of..</span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[o][/o]</span>\
					<span class='separator'>&bull;</span>\
					<span class='overline'>His deep and soulful blue eyes..</span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[scratch][/scratch]</span>\
					<span class='separator'>&bull;</span>\
					<span class='scratch'>The old man was stooped and..</span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[color=\"blue\"][/color]<br>\
					(Use every color code here)\
					</span>\
					<span class='separator'>&bull;</span>\
					<span class='blue-text'>The soft fur of the dog felt like..</span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[mark=\"yellow\"][/mark]<br>\
					(Use every color code here)\
					</span>\
					<span class='separator'>&bull;</span>\
					<span class='yellow-background'>It was a cold grey day in late..</span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>[media]link to your conent[/media]<br>(It also works with YouTube and Vimoe videos)</span>\
					<span class='separator'>&bull;</span>\
					<span class='small-picture-example'><img src='https://scontent-fra3-1.xx.fbcdn.net/hphotos-xap1/t31.0-8/10914966_812627088773787_3619532195404352482_o.jpg' alt='Bad image'></span>\
				</div>\
				<div class='row'>\
					<span class='example-text'>\
					[mascot=\"wave\"]\
					<br>\
					<strong>Mascot Types :</strong><br>\
					- wat<br>\
					- tongue<br>\
					- love<br>\
					- confused<br>\
					- wave<br>\
					</span>\
					<span class='separator'>&bull;</span>\
					<span class='small-mascot-example'><img src='http://blogy.co/Library/images/Stickers/Bun/wave.png' alt='Bad image'></span>\
				</div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$(".shortcodes-helper").fadeIn("fast");
	$(".shortcodes-helper").on('click', function(e) { 
	   if( e.target == this ) unloadShortCodesHelp(); 
	});
}

//Unload shortcodes help
function unloadShortCodesHelp() {
	$(".shortcodes-helper").fadeOut("fast");
	setTimeout(function(){ $(".shortcodes-helper").remove(); }, 150);
}

//Slide to top
function slideToTop() {
	$('html,body').animate({scrollTop: 0}, 800);

	if (window.getSelection)
        window.getSelection().removeAllRanges();
    else if (document.selection)
        document.selection.empty();
}

//Submit save code
function submitSaveCode(mobile) {
	get_auth_code = $("#auth_code_container").val();

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/setUpAuthenticationCode.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/setUpAuthenticationCode.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authCode="+get_auth_code);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if ( requestType.responseText == "READY" ) {
				 document.cookie = "set_save_code=0;domain=."+window.location.host+";path=/";
				window.location.reload(true);
			}
		}
	}
}

//Open story extern
function openExcerpt(containerId, inline) {
	if ( inline == 1 ) {
		$("."+containerId).find("#extern-dots").remove();
		$("."+containerId).find("#open-extern").remove();
		$("#extern-"+containerId).slideDown("medium", function(){
			$("#extern-"+containerId).css("display", "inline");
		});
	} else {
		$(containerId).find("#extern-dots").remove();
		$(containerId).find("#open-extern").remove();
		$(containerId+" .extern").slideDown("medium", function(){
			$(containerId+" .extern").css("display", "inline");
		});
	}
}

//Share global post
function shareGlobalPost(author, postId, mobile) {
	blogy_url = "http://"+window.location.host+"/";

	if ( mobile == 0 ) {
		build = "\
			<div id='full-page-container' class='hide-me'>\
				<div id='inline-fields'>\
					<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
					<h1 class='border-b-e5e5e5'>Share in</h1>\
					<div class='social-icons-container'>\
						<a href='http://www.facebook.com/share.php?u="+blogy_url+"?author="+author+"@p_id="+postId+"&title=Check my story' target='_blank' title='Share in Facebook' class='iconic facebook'>&#xf09a;</a>\
						<a href='http://twitter.com/home?status=Follow+my+story+http://"+blogy_url+"?author="+author+"@p_id="+postId+"' target='_blank' title='Share in Twitter' class='iconic twitter'>&#xf099;</a>\
						<a href='https://plus.google.com/share?url=http://"+blogy_url+"?author="+author+"@p_id="+postId+"' target='_blank' title='Share in Google+' class='iconic google-plus'>&#xf1a0;</a>\
					</div>\
					<h1>Or copy this link</h1>\
					<input type='text' class='center-text' placeholder='Share post link..' onclick='this.select();' value='"+blogy_url+"?author="+author+"@p_id="+postId+"'>\
				</div>\
			</div>\
		";
	}
	else
	if ( mobile == 1 ) {
		build = "\
			<div id='full-page-container' class='hide-me'>\
				<div id='inline-fields'>\
					<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
					<h1 class='border-b-e5e5e5'>Share in</h1>\
					<div class='social-icons-container'>\
						<a href='http://www.facebook.com/share.php?u="+blogy_url+"?author="+author+"@p_id="+postId+"&title=Check my story' target='_blank' title='Share in Facebook' class='iconic facebook'>&#xf09a;</a>\
						<a href='http://twitter.com/home?status=Follow+my+story+http://"+blogy_url+"?author="+author+"@p_id="+postId+"' target='_blank' title='Share in Twitter' class='iconic twitter'>&#xf099;</a>\
						<a href='https://plus.google.com/share?url=http://"+blogy_url+"?author="+author+"@p_id="+postId+"' target='_blank' title='Share in Google+' class='iconic google-plus'>&#xf1a0;</a>\
					</div>\
					<h1>Or copy this link</h1>\
					<input type='text' class='center-text' placeholder='Share post link..' onclick='this.select();' value='"+blogy_url+"?author="+author+"@p_id="+postId+"'>\
				</div>\
			</div>\
		";
	}

	$("body").append(build);
	$("#full-page-container").fadeIn("fast");

	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Unload full page container
function unloadFullPageContainer() {
	$("#full-page-container").fadeOut("fast");
	setTimeout(function(){ $("#full-page-container").remove(); }, 150);
}

//Prompt delete club confirmation
function promptDeleteClubConfirmation(mobile) {
	build = "\
		<div id='full-page-container' class='hide-me'>\
			<div id='inline-fields'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1>You are about to delete your club.</h1>\
				<h2 class='border-b-e5e5e5'>Are you sure about that ?</h2>\
				<div class='buttons-container'>\
					<button type='button' class='just-button' onclick='deleteClub("+mobile+");'>Delete</button>\
					<button type='button' class='decline-button' onclick='unloadFullPageContainer();'>Decline</button>\
				</div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").fadeIn("fast");

	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Delete club
function deleteClub(mobile) {
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	messageText = "<h1>Deleting, please wait..</h1><br>";

	$("#full-page-container").unbind( "click" );
	$("#inline-fields").empty();
	$("#inline-fields").append(messageText+buildLoader);

	getClubInformation = window.location.href.split("?")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/sendDeleteClubRequest.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/sendDeleteClubRequest.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("clubInformation="+getClubInformation);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {

    		if ( requestType.responseText == "READY" ) {
    			window.location = "exploreMyClubs.php";
    		}
    		else
    		if ( requestType.responseText == "DELRS" ) {
    			$("#inline-fields").empty();
    			messageText = "\
    				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
    				<h1 class='mt-20'>Delete request has been sent.</h1>\
    				<h2>Now owner will decide how to proceed.</h2>\
    			";
    			$("#inline-fields").append(messageText);

    			$("#full-page-container").on('click', function(e) { 
					if( e.target == this ) unloadFullPageContainer(); 
				});
    		}
    	}
    }
}

//Load club admin dashboard
function loadClubDashBoard(mobile) {
	window.location.reload(true);
}

//Load club admin storyboard
function loadClubStoryBoard(mobile) {
	$("#club-dashboard").fadeOut("fast");
	$("#club-header-settings-mainmenu").hide();
	$("#club-header-settings-storiesmenu").show();

	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-60'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-60''>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	$("#club-container").append(buildLoader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "../Templates/clubStoryBoard.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../../Templates/clubStoryBoard.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send();

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
			$("#loader").remove();
			$("#club-container").append(requestType.responseText);  	
    	}
    }
}

//Open Story editor
function openStoryEditor(type, mobile) {
	if ( type == "compose" ) {
		if ( mobile == 0 ) { mt = "-134px"; }
		else
		if ( mobile == 1 ) { mt = "-487px" }

		build = "\
			<div id='full-page-container'>\
				<div id='inline-fields' style='margin-top: "+mt+";'>\
					<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
					<input type='text' placeholder='Choose your title..' id='storyTitle' name='storyTitle' class='mt-15'>\
					<input type='text' placeholder='Add link to an image or to a video..' id='storyLink' name='storyLink'>\
					<textarea id='storyContent' name='storyContent' placeholder=\"What's up ?\"></textarea>\
					<button class='compose-author-button publish-button' onclick='publishClubStory("+mobile+");'><span class='iconic'>&#xf0f4;</span>Compose</button>\
				</div>\
			</div>\
		";

		$("body").append(build);
		$("#full-page-container").on('click', function(e) { 
			if( e.target == this ) unloadFullPageContainer(); 
		});
	}
}

//Publish story
function publishClubStory(mobile) {
	getTitle = $("#storyTitle").val().trim();
	getLink = $("#storyLink").val().trim();
	getContent = $("#storyContent").val().trim();

	flag = 0;

	if ( getTitle == "" || getTitle === undefined ) {
		flag = 1;
		alert("Give title to your amazing story.");
	}

	if ( getContent == "" && getLink == "" ) {
		flag = 1;
		alert("Add unique image or video and tell your amazing story.");
	}

	if ( flag == 0 ) {
		$("#inline-fields").empty();

		if (mobile == 1) {
			buildLoader = "\
				<div id='loader' class='loading-buffer-large mt-30'>\
					<img src='../../images/loadSign_white.gif'/>\
				</div>\
			";
		} else {
			buildLoader = "\
				<div id='loader' class='loading-buffer-large mt-30''>\
					<img src='../images/loadSign_white.gif'/>\
				</div>\
			";
		}

		$("#inline-fields").append(buildLoader);

		//Build core variables
		getURL = window.location.href;
		getArguments = getURL.split("?")[1];

		if ( getArguments.indexOf("&") > -1 ) {
			getArguments = getArguments.split("&")[0];	
		}
		if ( getArguments.indexOf("#") > -1 ) {
			getArguments = getArguments.split("#")[0];
		}

		getClubTable = getArguments.split("=")[0];
		getClubId = getArguments.split("=")[1];

		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/publishStory.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/publishStory.php", true); }

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("story_type=club&clubTable="+getClubTable+"&clubId="+getClubId+"&storyTitle="+getTitle+"&storyLink="+getLink+"&storyContent="+getContent);

		requestType.onreadystatechange = function() {
	    	if (requestType.readyState == 4 && requestType.status == 200) {
	    		if ( requestType.responseText == "POSTED" ) {
	    			$("#loader").remove();
	    			build = "<h1 class='mt-30'>Successfully posted..</h1>";
	    			$("#inline-fields").append(build);

	    			setTimeout(function(){
	    				window.location.reload(true);
	    			}, 500);
	    		} else {
	    			alert(requestType.responseText);
	    		}
	    	}
		}
	}
}

//Open comments
function openComments(postId, mobile, buildStory) {
	getTitle = $("."+postId+" h1").html();
	
	getMediaObject = "";
	if ( $("."+postId+" iframe").length > 0 ) {
		getSrc = $("."+postId+" iframe").attr("src");
		getMediaObject = "<iframe src="+getSrc+" frameborder='0' allowfullscreen></iframe>";
	}
	if ( $("."+postId+" .featured-image").length > 0 ) {
		getMediaObject = $("."+postId+" .featured-image-activate").html();
	}

	setFullHeight = "";
	getContent = "";
	if ( $("."+postId+" p").length > 0 && $("."+postId+" p").text().trim() != "" ) {
		getContent = "<p>"+$("."+postId+" p").html()+"</p>";
	} else {
		setFullHeight = 1;
	}

 	if ( buildStory != 0 ) {
		if ( mobile == 0 ) {
			build = "\
				<div id='full-page-container'>\
					<div id='inline-fields-posts' style='margin-top: -250px;'>\
						<div id='inline-post'>\
							<h1>"+getTitle+"</h1>\
							"+getMediaObject.toString()+"\
							"+getContent+"\
						</div>\
						<div id='inline-comments-container'>\
							<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
							<h1>Comments :</h1>\
							<div id='comments-container'>\
							</div>\
							<div id='input-container'>\
								<textarea id='comment-entry' placeholder=\"What's up ?\"></textarea>\
								<button type='button' onclick='publishClubComment("+postId+", 0);'>Comment</button>\
							</div>\
						</div>\
					</div>\
				</div>\
			";
		}
		else
		if ( mobile == 1 ) {
			build = "\
				<div id='full-page-container'>\
					<div id='inline-comments-container'>\
						<h1>Comments :</h1>\
						<div id='comments-container'>\
						</div>\
						<div id='input-container'>\
							<textarea id='comment-entry' placeholder=\"What's up ?\"></textarea>\
							<button type='button' onclick='publishClubComment("+postId+", 1);'>Comment</button>\
						</div>\
					</div>\
				</div>\
			";
		}
	} else if ( buildStory == 0 ) {
		build = "\
			<div id='full-page-container'>\
				<div id='inline-fields-posts' style='margin-top: -250px;'>\
					<div id='inline-comments-container' style='display: block; margin: auto;'>\
						<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
						<h1>Comments :</h1>\
						<div id='comments-container'>\
						</div>\
						<div id='input-container'>\
							<textarea id='comment-entry' placeholder=\"What's up ?\"></textarea>\
							<button type='button' onclick='publishClubComment("+postId+", 0);'>Comment</button>\
						</div>\
					</div>\
				</div>\
			</div>\
		";
	}

	$("body").append(build);

	if ( setFullHeight == 1 ) {
		$("#inline-fields-posts iframe").css("height", "453px");
		$("#inline-fields-posts .featured-image").css("height", "453px");
	}

	//Clear content body
	$("#inline-fields-posts").find("#extern-dots").remove();
	$("#inline-fields-posts").find("#open-extern").remove();
	$("#inline-fields-posts #extern-"+postId).css("display", "inline");

	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});

	loadComments(postId, mobile);
}

//Load comments
function loadComments(postId, mobile) {
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-30'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-175'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	$("#comments-container").append(buildLoader);

	//Get club variables
	getURL = window.location.href;
	getArguments = getURL.split("?")[1];

	if ( getArguments.indexOf("&") > -1 ) {
		getArguments = getArguments.split("&")[0];	
	}
	if ( getArguments.indexOf("#") > -1 ) {
		getArguments = getArguments.split("#")[0];
	}

	getClubTable = getArguments.split("=")[0];
	getClubId = getArguments.split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/pullClubComments.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/pullClubComments.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("postId="+postId+"&clubTable="+getClubTable+"&clubId="+getClubId+"&isMobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$("#comments-container").empty();
    		$("#comments-container").append(requestType.responseText);
    	}
    }
}

//Load comment author
function loadCommentAuthor(postId, authorId, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/getUserInfo.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/getUserInfo.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+authorId);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		getUserInfo = requestType.responseText.split("~");
    		$("#"+postId+" #picture-holder").css("background-image", "url("+getUserInfo[0]+")");
    	}
    }
}

//Publish club comment
function publishClubComment(postId, mobile) {
	getComment = $("#comment-entry").val().trim();

	flag = 0;

	if ( getComment == "" || getComment == undefined ) {
		flag = 1;
		alert("Enter something in your comment first.");
	}

	//Get club variables
	getURL = window.location.href;
	getArguments = getURL.split("?")[1];

	if ( getArguments.indexOf("&") > -1 ) {
		getArguments = getArguments.split("&")[0];	
	}
	if ( getArguments.indexOf("#") > -1 ) {
		getArguments = getArguments.split("#")[0];
	}

	getClubTable = getArguments.split("=")[0];
	getClubId = getArguments.split("=")[1];

	if ( flag == 0 ) {
		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/publishClubComment.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/publishClubComment.php", true); }

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("postId="+postId+"&commentContent="+getComment+"&clubTable="+getClubTable+"&clubId="+getClubId);

		requestType.onreadystatechange = function() {
	    	if (requestType.readyState == 4 && requestType.status == 200) {
	    		if ( requestType.responseText == "READY" ) {
	    			$("#comment-entry").val("");
	    			loadComments(postId, mobile);
	    		} else {
	    			console.log( requestType.responseText );
	    		}
	    	}
	    }
	}
}

//Remove club comment
function removeClubComment(commentId, mobile) {
	//Get club variables
	getURL = window.location.href;
	getArguments = getURL.split("?")[1];

	if ( getArguments.indexOf("&") > -1 ) {
		getArguments = getArguments.split("&")[0];	
	}
	if ( getArguments.indexOf("#") > -1 ) {
		getArguments = getArguments.split("#")[0];
	}

	getClubTable = getArguments.split("=")[0];
	getClubId = getArguments.split("=")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/removeClubComment.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/removeClubComment.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("commentId="+commentId+"&clubTable="+getClubTable+"&clubId="+getClubId);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$("#"+commentId).remove();
    		}
    	}
    }
}

//Show hide club stories dropdown options
function showHideClubStoriesDropOptions(mobile, containerId) {
	if ( $("."+containerId+" #drop-downer").hasClass("closed") ) {
		$("."+containerId+" #drop-options").toggle();
		$("."+containerId+" #drop-downer").removeClass("closed").addClass("opened");
		$("."+containerId+" #drop-downer").html("&#xf106;");
	} else if ( $("."+containerId+" #drop-downer").hasClass("opened") ) {
		$("."+containerId+" #drop-options").toggle();
		$("."+containerId+" #drop-downer").removeClass("opened").addClass("closed");
		$("."+containerId+" #drop-downer").html("&#xf107;");
	}
}

//Imitate login --> Log out
window.onunload = window.onbeforeunload = function() {
	if ( $("#front-page").length <= 0 ) { imitateLogOut(); }
};
window.onload = function() {
	if ( $("#front-page").length <= 0 ) { imitateLogIn(); }
};


/* PLUGINS */

//Open create plugin dialog
function openCreatePluginDialog(mobile) {
	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1>Give name to your plugin</h1>\
				<input id='plugin-name' class='wide-fat mt-10' type='text' placeholder='Plugin name'>\
				<button onclick='registerPlugin("+mobile+")'>Develop!</button>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Register plugin
function registerPlugin(mobile) {
	getPluginName = $("#plugin-name").val().trim();

	if ( getPluginName != "" ) {
		//Check if plugin is already had been registered

		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/registerPlugin.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/registerPlugin.php", true); }

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("pluginName="+getPluginName);

		requestType.onreadystatechange = function() {
	    	if (requestType.readyState == 4 && requestType.status == 200) {
	    		if ( requestType.responseText == "READY" ) {
	    			getPluginSlug = getPluginName.replace(/ /g, "_").toLowerCase();
	    			navitageToUrl = "openPluginMenu.php?"+getPluginSlug;
	    			window.location = navitageToUrl;
	    		} 
	    		else
	    		if ( requestType.responseText == "PAE" ) {
	    			alert("You already had started to develop plugin with that name.\nPlease choose a new name and try again.");
	    		} else {
	    			alert(requestType.responseText);
	    		}
	    	}
	    }

	} else {
		alert("Give name to your plugin first!");
	}
}

//Open plugin project dialog
function openChoosePluginProjectDialog(mobile) {
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-30'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-30'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields' style='margin-top: -250px;'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				"+buildLoader+"\
				<div id='plugin-buttons-container'>\
				<h1>Projects :</h1>\
				<div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/collectPluginProjects.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/collectPluginProjects.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send();

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$("#loader").remove();
    		$("#plugin-buttons-container").append(requestType.responseText);
    	}
    }
}

//Open add new file dialog
function openNewFileDialog(mobile) {
	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1>Give name to your file</h1>\
				<input id='file-name' type='text' class='wide-fat mt-10' placeholder='File name'>\
				<button onclick='createNewPluginFileFolder("+mobile+", \"file\");'>Create</button>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Create new plugin file
function createNewPluginFileFolder(mobile, type) {
	getFileName = $("#file-name").val().trim();

	if ( getFileName == "" || getFileName == undefined ) { alert("Give name to your file first!"); }
	else {
		$("#inline-fields").empty();

		if (mobile == 1) {
			buildLoader = "\
				<div id='loader' class='loading-buffer-large mt-30'>\
					<img src='../../images/loadSign_white.gif'/>\
				</div>\
			";
		} else {
			buildLoader = "\
				<div id='loader' class='loading-buffer-large mt-30'>\
					<img src='../images/loadSign_white.gif'/>\
				</div>\
			";
		}

		$("#inline-fields").append(buildLoader);

		//Get plugin slug
		getSlug = window.location.href.split("?")[1];

		//Get breadcrumbs
		getBreadCrumbs = $(".breadcrumbs").html();

		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if (mobile == 0) { requestType.open("POST", "Universal/createNewPluginFileFolder.php", true); }
		else
		if (mobile == 1) { requestType.open("POST", "../Universal/createNewPluginFileFolder.php", true); }

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("fileName="+getFileName+"&pluginSlug="+getSlug+"&type="+type+"&breadcrumbs="+getBreadCrumbs);

		requestType.onreadystatechange = function() {
	    	if (requestType.readyState == 4 && requestType.status == 200) {
	    		if ( requestType.responseText == "READY" ) { 
	    			unloadFullPageContainer();
	    			relistListedFolder(getBreadCrumbs, mobile);
	    		}
	    		else { alert(requestType.responseText); }
	    	}
	    }
	}
}

//Relist listed folder
function relistListedFolder(breadcrumbs, mobile) {
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-30'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-30'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	//Clear the explorer
	$(".directory-holder").empty();
	$(".directory-holder").append(buildLoader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/relistFolderFromCrumbs.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/relistFolderFromCrumbs.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("breadcrumbs="+breadcrumbs);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$(".directory-holder").empty();
			$(".directory-holder").append(requestType.responseText);

			//Back button
			getBreadCrumbs = $(".breadcrumbs").html().split("/");
			length = getBreadCrumbs.length - 3;
			
			previousCrumbs = "";
			for ( count_crumb = 0; count_crumb <= length; count_crumb++ ) {
				previousCrumbs += getBreadCrumbs[count_crumb];
				if ( count_crumb < length ) { previousCrumbs += "/"; }
			}

			if ( previousCrumbs !== undefined && previousCrumbs != "" && previousCrumbs != "/" ) {
				backButton = "<button id='back' onclick='openFolder(\""+previousCrumbs+"/\", "+mobile+");'><span class='iconic'>&#xf104;</span>Back</button>";
				$(".directory-holder").prepend(backButton);
			}
    	}
    }
}

//Open add new folder dialog
function openNewFolderDialog(mobile) {
	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1>Give name to your folder</h1>\
				<input id='file-name' type='text' class='wide-fat mt-10' placeholder='Folder name'>\
				<button onclick='createNewPluginFileFolder("+mobile+", \"folder\");'>Create</button>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Send plugin for review for the store
function sendPluginForReview(mobile) {
	//Get plugin slug
	getSlug = window.location.href.split("?")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/sendPluginForReview.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/sendPluginForReview.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginSlug="+getSlug);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			build = "\
					<div id='full-page-container'>\
						<div id='inline-fields'>\
							<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
							<h1 class='mt-15'>Your plugin has been sent for a review!</h1>\
							<h2>Once the review is finished you\'ll receive a notification.</h2>\
						</div>\
					</div>\
				";

				$("body").append(build);
				$("#full-page-container").on('click', function(e) { 
					if( e.target == this ) unloadFullPageContainer(); 
				});
    		} else {
    			alert(requestType.responseText);
    		}
    	}
    }
}

//Remove plugin project
function removePluginProject(mobile) {
	$("#inline-fields").empty();

	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-30'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-30'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	$("#inline-fields").append(buildLoader);

	//Get plugin slug
	getSlug = window.location.href.split("?")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/removePluginProject.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/removePluginProject.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginSlug="+getSlug);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) { window.location = "openPluginsDashboard.php"; }
    		else { alert(requestType.responseText); }
    	}
    }
}

//Open remove plugin dialog
function openRemovePluginDialog(mobile) {
	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1>You are going to delete your project.</h1>\
				<h2 class='border-b-e5e5e5'>Are you sure for that ?</h2>\
				<div class='buttons-container'>\
					<button type='button' class='just-button' onclick='removePluginProject("+mobile+");'>Delete</button>\
					<button type='button' class='decline-button' onclick='unloadFullPageContainer();'>Decline</button>\
				</div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Open remove file / folder dialog
function openRemoveDialog(breadcrumbs, type, mobile) {
	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1>You are going to delete a "+type+".</h1>\
				<h2 class='border-b-e5e5e5'>Are you sure for that ?</h2>\
				<div class='buttons-container'>\
					<button type='button' class='just-button' onclick='removePluginFileFolder(\""+breadcrumbs+"\", \""+type+"\", "+mobile+");'>Delete</button>\
					<button type='button' class='decline-button' onclick='unloadFullPageContainer();'>Decline</button>\
				</div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Open move file / folder dialog
function openMoveDialog(breadcrumbs, type, mobile) {
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-15'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader' class='loading-buffer-large mt-15'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields' style='margin-top: -250px; height: 500px;'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<div id='plugin-buttons-container'>\
					<h1>Choose folder :</h1>\
					"+buildLoader+"\
					<div id='folders-list'></div>\
				</div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});

	//Get plugin slug
	getSlug = window.location.href.split("?")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/listFoldersFromPluginProject.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/listFoldersFromPluginProject.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginSlug="+getSlug+"&type="+type+"&breadcrumbs="+breadcrumbs+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$("#loader").remove();
    		$("#folders-list").append(requestType.responseText);
    		$("#plugin-buttons-container").append("<button id='move-to-button' class='inactive'>Move</button>");
    	}
    }

}

//Remove plugin file / folder
function removePluginFileFolder(breadcrumbs, type, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/removePluginFileFolder.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/removePluginFileFolder.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("breadcrumbs="+breadcrumbs+"&type="+type);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			unloadFullPageContainer();
    			relistListedFolder( $(".breadcrumbs").html(), mobile );
    		}
    		else { alert(requestType.responseText); }
    	}
    }
}

//Open file
function openFile(breadcrumbs, mobile) {
	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields' style='width: 60%; margin-left: -30%; height: 500px; margin-top: -250px;'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1 class='border-b-e5e5e5' style='padding-bottom: 5px;'>"+breadcrumbs+"</h1>\
				<textarea id='text-container' style='width: 100%; padding: 0; height: 90%; border: none; margin-top: 10px; background: #fff; text-align: left;'>\
				</textarea>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/openPluginFile.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/openPluginFile.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("breadcrumbs="+breadcrumbs);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$("#text-container").prop("disabled", true);
    		$("#text-container").val(requestType.responseText);
    	}
    }
}

//Open fodler
function openFolder(breadcrumbs, mobile) {
	relistListedFolder(breadcrumbs, mobile);
	$(".breadcrumbs").html(breadcrumbs);
}

//Select folder
function selectFolder(breadcrumbs, containerId, targetToMove, targetType, mobile) {
	$(".selected").removeClass("selected");
	$(containerId).addClass("selected");

	$("#move-to-button").removeClass("inactive");
	$("#move-to-button").addClass("active");
	document.getElementById("move-to-button").onclick = function(){ movePluginFileFolderTo(targetToMove, breadcrumbs, targetType, mobile); };
}

//Move file / folder to another location
function movePluginFileFolderTo(targetToMove, targetDestination, targetType, mobile) {
	message = "<h1 class='mt-35'>Moving, please wait..</h1>";
	$("#inline-fields").removeAttr("style").empty().append(message);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/movePluginFileFolderTo.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/movePluginFileFolderTo.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("targetToMove="+targetToMove+"&targetDestination="+targetDestination+"&targetType="+targetType);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			unloadFullPageContainer();
    			relistListedFolder( $(".breadcrumbs").html(), mobile );
    		} else {
    			alert(requestType.responseText);
    		}
    	}
    }
}

//Download plugin project
function downloadPluginProject(mobile) {
	if ( mobile == 0 ) {
		$("#download-image").attr("src", "../images/loadSign.GIF");
	} 
	else 
	if ( mobile == 1 ) {
		$("#download-image").attr("src", "../../images/loadSign.GIF");
	}

	//Get plugin slug
	getSlug = window.location.href.split("?")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/downloadPluginProject.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/downloadPluginProject.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginSlug="+getSlug);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		window.open(requestType.responseText);

    		if ( mobile == 0 ) {
				$("#download-image").attr("src", "../images/download.png");
			} 
			else 
			if ( mobile == 1 ) {
				$("#download-image").attr("src", "../../images/download.png");
			}
    	}
    }
}

//Open upload update dialog
function openUploadUpdateDialog(mobile) {
	build = "\
		<div id='full-page-container'>\
			<div id='inline-fields'>\
				<button class='hideButton' onclick='unloadFullPageContainer();'></button>\
				<h1>Choose file to upload</h1>\
				<h2 style='padding-bottom: 5px;'>Only .ZIP files!</h2>\
				<input type='file' id='update-file' name='update-file' class='wide-fat' onchange='checkSelectedUpdate();'></input>\
				<button onclick='uploadPluginUpdate("+mobile+");'>Upload</button>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});
}

//Check selected update
function checkSelectedUpdate() {
	getValue = $("#update-file").val();
	if ( getValue.indexOf(".zip") <= 0 ) { alert("Your file is not an update file!\nChoose another .ZIP file."); }
}

//Upload plugin update
function uploadPluginUpdate(mobile) {
	flag = 0;
	getUpdateFile = $("#update-file").val();
	if ( getUpdateFile.indexOf(".zip") <= 0 || getUpdateFile.trim() == "" ) { flag = 1; alert("Your file is not an update file!\nChoose another .ZIP file."); }

	if ( flag == 0 ) {
		document.getElementById("update-container").action = "Universal/uploadPluginUpdate.php";
		document.forms['update-container'].submit();
	}
}

//Compile and apply patches
function compilePluginPatch(mobile) {
	if ( mobile == 0 ) {
		$("#compile-image").attr("src", "../images/loadSign.GIF");
	} 
	else 
	if ( mobile == 1 ) {
		$("#compile-image").attr("src", "../../images/loadSign.GIF");
	}

	//Get plugin slug
	getSlug = window.location.href.split("?")[1];

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/compilePluginPatch.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/compilePluginPatch.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginSlug="+getSlug);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
	    		relistListedFolder( $(".breadcrumbs").html(), mobile );

	    		if ( mobile == 0 ) {
					$("#compile-image").attr("src", "../images/compile.png");
				} 
				else 
				if ( mobile == 1 ) {
					$("#compile-image").attr("src", "../../images/compile.png");
				}
			} else {
				alert(requestType.responseText);
			}
    	}
    }
}


//Plugin Manager
function loadPluginManager(mobile) {
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader-buffer' class='loading-buffer-large mt-60'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader-buffer' class='loading-buffer-large mt-60'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	$("#plugins-container").append(buildLoader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/collectAttachedPlugins.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/collectAttachedPlugins.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send();

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		getPluginsList = requestType.responseText.split(",");
    		$("#loader-buffer").remove();

    		for ( i = 0; i < getPluginsList.length; i++ ) {
    			pluginID = getPluginsList[i].split("~")[0];
    			pluginName = getPluginsList[i].split("~")[1];
    			pluginSlug = getPluginsList[i].split("~")[2];
    			pluginPath = getPluginsList[i].split("~")[3];
    			pluginState = getPluginsList[i].split("~")[4];
    			pluginAuthor = getPluginsList[i].split("~")[5];
    			pluginStoreID = getPluginsList[i].split("~")[6];
    			buildPluginControlContainer(pluginID, pluginName, pluginSlug, pluginPath, pluginState, pluginAuthor, pluginStoreID, mobile);
    		}
    	}
    }
}

//Build plugin control container
function buildPluginControlContainer(pluginID, pluginName, pluginSlug, pluginLocation, pluginState, pluginAuthor, pluginStoreID, mobile) {
	build_holder = "\
		<div id='plugin-"+pluginID+"' class='plugin-container'>\
		</div>\
	";

	$("#plugins-container").append(build_holder);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/buildPluginFromNameAndCrumb.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/buildPluginFromNameAndCrumb.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&pluginName="+pluginName+"&pluginSlug="+pluginSlug+"&pluginPath="+pluginLocation+"&pluginState="+pluginState+"&pluginAuthor="+pluginAuthor+"&pluginStoreID="+pluginStoreID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$("#plugin-"+pluginID).append(requestType.responseText);
    	}
    }
}

//Activate deactivate plugin
function activateDeactivatePlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/activateDeactivatePlugin.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/activateDeactivatePlugin.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		window.location.reload( true );

/*    		if ( requestType.responseText == "activated" ) {
	    		$("#plugin-"+pluginID+" #power-button").removeClass("activate-button").addClass("deactivate-button").html("<span class='iconic'>&#xf127;</span>Deactivate");
	    	} 
	    	else
    		if ( requestType.responseText == "deactivated" ) {
    			$("#plugin-"+pluginID+" #power-button").removeClass("deactivate-button").addClass("activate-button").html("<span class='iconic'>&#xf0c1;</span>Activate");
    		}
    		else {
    			alert(requestType.responseText);
    		}*/
    	}
    }
}


//Open video container
function playVideo(video, containerId, src, sender) {
	build = "\
		<div id='full-page-container'>\
			<div id='video-controller'>\
				<div id='video-inline-controller'>\
					<video src='"+src+"' controls autoplay></video>\
					<button class='close-button iconic' onclick='unloadFullPageContainer();' title='Close video'></button>\
					<div class='right-buttons'>\
						<button class='publish' onclick='showContainerPost(\""+video+"\", \""+sender+"\");' title='Publish!'>Make a story</button>\
						<button class='delete' onclick='deleteObjectFromAlbum(\""+video+"\", \""+containerId+"\"); unloadFullPageContainer();' title='Delete video'>Delete video</button>\
					</div>\
				<div>\
			</div>\
		</div>\
	";

	$("body").append(build);
	$("#video-inline-controller").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});	
	$("#full-page-container").on('click', function(e) { 
		if( e.target == this ) unloadFullPageContainer(); 
	});	
}

//Load plugins
function collectPlugins(visitor, mobile) {
	blogger_ = "";
	if ( visitor == 1 ) { blogger_ = window.location.href.split("?")[1]; }

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/collectActivatedPlugins.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/collectActivatedPlugins.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("visitor="+visitor+"&blogger="+blogger_);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		
    		pluginsList = requestType.responseText.split(",");
    		for ( i = 0; i < pluginsList.length; i++ ) {
	    		runOnloadHook( pluginsList[i].split("~"), visitor, mobile );
	    	}
    	
    	}
    }
}

function runOnloadHook(target_, visitor, mobile) {
	if ( target_ != "" && target_ != undefined ) {
		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if ( mobile == 0 ) {
			requestType.open("POST", "Universal/attachPlugins.php", true);
		}
		else
		if ( mobile == 1 ) {
			requestType.open("POST", "../Universal/attachPlugins.php", true);
		}

		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("pluginPath="+target_[3]+"&pluginSlug="+target_[2]+"&pluginName="+target_[1]);

		requestType.onreadystatechange = function() {
	    	if (requestType.readyState == 4 && requestType.status == 200) {

	    		attachTo = requestType.responseText.split("~||~")[0];
	    		pluginBuilt = requestType.responseText.split("~||~")[1];

	    		if ( attachTo == "onload" || attachTo == "onload_both" ) { 
	    			$("body").append(pluginBuilt);
	    		}
	    		if ( attachTo == "onclick" ) { 
	    			$(".plugins-list").append(pluginBuilt);
	    		}
	    		if ( attachTo == "onload_visitor" ) {
	    			if ( visitor == 1 ) { $("body").append(pluginBuilt); }
	    		}
	    	}
	    }
	}
}

function runOnClickHook(target_) {
	if ( target_ != "" && target_ != undefined ) {
		args = { pluginPath: target_ };
		click_handler = make_ajax( "Universal/attachClickPlugin.php", "POST", "text", args );
		click_handler.onreadystatechange = function() {
			if ( click_handler.readyState == 4 && click_handler.status == 200 ) {
				$( "body" ).append( click_handler.responseText );
			}
		}
	}
}


//Plugin Store
function preloadPlugins(pluginsClass, mobile) {
	if (mobile == 1) {
		buildLoader = "\
			<div id='loader-buffer' class='loading-buffer-large'>\
				<img src='../../images/loadSign_white.gif'/>\
			</div>\
		";
	} else {
		buildLoader = "\
			<div id='loader-buffer' class='loading-buffer-large'>\
				<img src='../images/loadSign_white.gif'/>\
			</div>\
		";
	}

	$("#plugins-list").append(buildLoader);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/collectPluginsFromClass.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/collectPluginsFromClass.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginsClass="+pluginsClass+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$("#loader-buffer").remove();
    		if ( requestType.responseText != "" ) {
	    		getPluginsIds = requestType.responseText.split(",");
	    		for ( i = 0; i < getPluginsIds.length; i++ ) {
	    			buildPluginStoreContainer( getPluginsIds[i], mobile );
	    		}
	    	}
    	}
    }
}

//Build plugin store plugin container
function buildPluginStoreContainer(pluginID, mobile) {
	build_ = "\
		<div id='container-"+pluginID+"' class='plugin-container'>\
		</div>\
	";

	if ( $("#container-"+pluginID).length ) {
		$("#container-"+pluginID).replaceWith(build_);
	} else {
		$("#plugins-list").append(build_);
	}

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/buildPluginStoreContainer.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/buildPluginStoreContainer.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		$("#container-"+pluginID).append(requestType.responseText).addClass("active");		
    	}
    }
}

//Like / Unlike plugin
function likeUnlikePlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/likeUnlikePlugin.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/likeUnlikePlugin.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$("#container-"+pluginID).removeClass("active");		
    			buildPluginStoreContainer(pluginID, mobile);
    		} else {
    			alert(requestType.responseText);
    		}
    	}
    }
}

//Hate / Unhate plugin
function hateUnhatePlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/hateUnhatePlugin.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/hateUnhatePlugin.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$("#container-"+pluginID).removeClass("active");		
    			buildPluginStoreContainer(pluginID, mobile);
    		} else {
    			alert(requestType.responseText);
    		}
    	}
    }
}

//Install Plugin
function installPlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/installPlugin.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/installPlugin.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$("#container-"+pluginID).removeClass("active");		
    			buildPluginStoreContainer(pluginID, mobile);
    		} else {
    			alert(requestType.responseText);
    		}
    	}
    }
}

//Uninstall Plugin
function uninstallPlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (mobile == 0) { requestType.open("POST", "Universal/uninstallPlugin.php", true); }
	else
	if (mobile == 1) { requestType.open("POST", "../Universal/uninstallPlugin.php", true); }

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&mobile="+mobile);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			if ( window.location.href.indexOf("pluginStore") > -1 ) {
	    			$("#container-"+pluginID).removeClass("active");		
	    			buildPluginStoreContainer(pluginID, mobile);
	    		} else {
	    			window.location.reload(true);
	    		}
    		} else {
    			alert(requestType.responseText);
    		}
    	}
    }
}

//Search for plugin
function searchForPlugin(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if(code == 13) { //Enter keycode
		getPluginName = $("#plugin-search").val().split(' ').join('_');
		window.location.href = "pluginStore.php?s="+getPluginName;
	}
}

//Draft post
function draftPost() {
	postTitle = $( "#post" ).children( "#titleIdCode" ).val();
	postLink = $( "#post" ).children( "#postImg" ).val();
	postContent = $( "#post" ).children( "#content" ).val();

	console.log( postTitle );
	console.log( postLink );
	console.log( postContent );

	if(typeof(Storage) !== "undefined") {
		localStorage.postTitle = postTitle;
		localStorage.postLink = postLink;
		localStorage.postContent = postContent;
	}
}

//Crear draft
function clearDraft() {
	if(typeof(Storage) !== "undefined") {
		localStorage.postTitle = "";
		localStorage.postLink = "";
		localStorage.postContent = "";
	}
}

//Add shortcode
function addShortcode( type ) {
	currentVal = jQuery("#content").val() + " ";

	if ( type == "bold" ) {
		document.getElementById("content").value = currentVal + "[b] ... [/b]";
	} else if ( type == "italic" ) {
		document.getElementById("content").value = currentVal + "[i] ... [/i]";
	} else if ( type == "underline" ) {
		document.getElementById("content").value = currentVal + "[u] ... [/u]";
	} else if ( type == "strike" ) {
		document.getElementById("content").value = currentVal + "[scratch] ... [/scratch]";
	}

	document.getElementById("content").focus();
	draftPost();
}

//Load badges
function loadBadges( mobile, isVisited ) {
	path = "";
	if ( mobile == 0 ) {
		path = "Universal/";
	} else if ( mobile == 1 ) {
		path = "../Universal/";
	}

	build_ = "\
		<div id='badges'>\
			<a href='badgesPage.php' id='nb-link' class='link'>No badges yet</a>\
		</div>\
	";

	$( build_ ).insertBefore( "#followers" );

	userID = "";
	if ( isVisited == 0 ) { userID = "sender"; }
	else if ( isVisited == 1 ) { userID = window.location.href.split("?")[1]; }

	//Story Teller
	args = { startPath: path, userID: userID };
	storyTeller = make_ajax( path+"storyTellerBadge.php", "POST", "text", args );
	storyTeller.onreadystatechange = function() {
        if ( storyTeller.readyState == 4 && storyTeller.status == 200 ) {
 			if ( storyTeller.responseText != "NB" ) {
 				$( "#nb-link" ).remove();
 				$( "#badges" ).append( storyTeller.responseText );
 			}
        }
    }

    //Messenger
    args = { startPath: path, userID: userID };
    messenger = make_ajax( path+"messengerBadge.php", "POST", "text", args );
	messenger.onreadystatechange = function() {
        if ( messenger.readyState == 4 && messenger.status == 200 ) {
 			if ( messenger.responseText != "NB" ) {
 				$( "#nb-link" ).remove();
 				$( "#badges" ).append( messenger.responseText );
 			}
        }
    }

    //Plugin Dev
    args = { startPath: path, userID: userID };
    pluginDev = make_ajax( path+"pluginDevBadge.php", "POST", "text", args );
	pluginDev.onreadystatechange = function() {
        if ( pluginDev.readyState == 4 && pluginDev.status == 200 ) {
 			if ( pluginDev.responseText != "NB" ) {
 				$( "#nb-link" ).remove();
 				$( "#badges" ).append( pluginDev.responseText );
 			}
        }
    }

    //Leader
    args = { startPath: path, userID: userID };
    leader = make_ajax( path+"leaderBadge.php", "POST", "text", args );
	leader.onreadystatechange = function() {
        if ( leader.readyState == 4 && leader.status == 200 ) {
 			if ( leader.responseText != "NB" ) {
 				$( "#nb-link" ).remove();
 				$( "#badges" ).append( leader.responseText );
 			}
        }
    }

    //Cluber
    args = { startPath: path, userID: userID };
    cluber = make_ajax( path+"cluberBadge.php", "POST", "text", args );
	cluber.onreadystatechange = function() {
        if ( cluber.readyState == 4 && cluber.status == 200 ) {
 			if ( cluber.responseText != "NB" ) {
 				$( "#nb-link" ).remove();
 				$( "#badges" ).append( cluber.responseText );
 			}
        }
    }

    //Follower
    args = { startPath: path, userID: userID };
    follower = make_ajax( path+"followerBadge.php", "POST", "text", args );
	follower.onreadystatechange = function() {
        if ( follower.readyState == 4 && follower.status == 200 ) {
 			if ( follower.responseText != "NB" ) {
 				$( "#nb-link" ).remove();
 				$( "#badges" ).append( follower.responseText );
 			}
        }
    }
}


//Word counter
function countWords(e, container ) {
	if ( $(container).val().trim() != "" ) {
		if ( $(container).val().trim().split(/\s+/).length > 0 ) {
			$(".word-counter").html( $(container).val().trim().split(/\s+/).length ); 
		} else {
			$(".word-counter").html("1");
		}
	} else {
		$(".word-counter").html("0");
	}
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

	/*requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
			if ( resultType == "text" ) { $( appendTo ).append( requestType.responseText ); }
			else if ( resultType == "xml" ) { $( appendTo ).append( requestType.responseXML ); }
    	}
    }*/
}


//Save author meta
function saveAuthorMeta() {
	profileURL = $( "#profile-picture" ).val().trim();
	socialProfile = $( "#social-profile" ).val().trim();
	authCode = $( "#authentication-code" ).val().trim();
	hobbies = $( "#hobbies" ).val().trim();
	birth_date = $( "#birth-date" ).val().trim();

	stopFlag = 0;

	if ( authCode === undefined || authCode == "" ) {
		stopFlag = 1;
		alert("Enter your authentication code.");
	}

	if ( hobbies === undefined || hobbies == "" ) {
		stopFlag = 1;
		alert("Tell us your hobbies.");
	}

	if ( birth_date === undefined || birth_date == "" ) {
		stopFlag = 1;
		alert("Choose your birth date.");
	}

	if ( validateDate( birth_date ) == false ) {
		stopFlag = 1;
		alert("Choose propper birth date!");
	}

	if ( stopFlag == 0 ) {
		args = "profile_image="+ profileURL +"&social_profile="+ socialProfile +"&authentication_code="+ authCode +"&hobbies="+ hobbies +"&birth_date="+ birth_date;

		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}

		requestType.open("POST", "Universal/setUpUserMeta.php", true);
		
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send(args);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				if ( requestType.responseText == "READY" ) {
					window.location.reload(true);
				} else {
					console.log(requestType.responseText);
				}
			}
		}
	}
}

//Validate date
function validateDate( str ) {
	var regex = /^(\d{4})\-(\d{2})\-(\d{2})$/;
  	return regex.test( str );
}

//Append events via jQuery
$( document ).ready(function() {
	if ( $("#front-page").length <= 0 ) {
		if( navigator.userAgent.match(/Android/i)
		 || navigator.userAgent.match(/webOS/i)
		 || navigator.userAgent.match(/iPhone/i)
		 || navigator.userAgent.match(/iPad/i)
		 || navigator.userAgent.match(/iPod/i)
		 || navigator.userAgent.match(/BlackBerry/i)
		 || navigator.userAgent.match(/Windows Phone/i)
		 ) {
		 	
			//Collect plugins
			visitor = 0;
			if ( window.location.href.indexOf("openBloger.php") > -1 ) { visitor = 1; }
			collectPlugins(visitor, 1);

		} else {
			if (document.cookie.indexOf("previewBar=onlineFriends") >= -1 && document.cookie.indexOf("previewBar=ohanaContainer") == -1) {
				switchFriendsContainers("#onlineFriends", ".left-button"); 
				loadOnlineFriends(0);
			}
			else
			if (document.cookie.indexOf("previewBar=ohanaContainer") > -1)  {
				switchFriendsContainers("#ohanaContainer", ".right-button");
				loadOhana(0);
			}

			loadSuggestions(0);

			//Collect plugins
			visitor = 0;
			if ( window.location.href.indexOf("openBloger.php") > -1 ) { visitor = 1; }
			collectPlugins(visitor, 0);
		}
	}
});

//Index page - Effects
/*
function dragToTOP(elementId) {
	$("#"+elementId).fadeOut("fast");
}
function dragToBOT(elementId) {
	$("#"+elementId).fadeIn("fast");
}

function setIndex(elementId, index) {
	document.getElementById(elementId).style.display = 'block';
	document.getElementById(elementId).style.zIndex = index;
}
function removeIndex(elementId) {
	document.getElementById(elementId).style.zIndex = 0;
}
*/