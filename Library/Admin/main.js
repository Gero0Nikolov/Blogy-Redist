function Entry() {
	$("#body").fadeIn("fast");
}

function sendLog() {
	getMail = $("#body").find("#login-form").children("#username").val().toLowerCase();
	getPass = $("#body").find("#login-form").children("#password").val();

	setFlag = 0;

	if (getMail.trim() == "") {
		alert("Enter your e-mail first !");
		setFlag = 1;
	}

	if (getPass.trim() == "") {
		alert("Enter your password !");
		setFlag = 1;
	}

	if (setFlag == 0) {
		document.getElementById("login-form").action = "checkLog.php";
		document.forms['login-form'].submit();
	}
}

function requestPlaces(container, cmd) {
	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$(container).children(".places").append(load_sign);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getPlaces.php", true);

	if (cmd == 0) { requestType.send(); }
	else
	if (cmd == 1) {	
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("allPlaces=1"); 
	}

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			console.log( requestType.responseText );
			$(container).children(".places").children("#loadSign").remove();
 			$(container).children(".places").append(requestType.responseText);
 		}
 	}
}

function requestReports(container) {
	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$(container).find("#content-container").append(load_sign);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getReports.php", true);

	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#content-container").children("#loadSign").remove();
 			$(container).children("#content-container").append(requestType.responseText);
 		}
 	}
}

function requestPlugins(container, cmd) {
	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$(container).children(".plugins").append(load_sign);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getPlugins.php", true);

	if (cmd == 0) { requestType.send(); }
	else
	if (cmd == 1) {	
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("allPlugins=1"); 
	}

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children(".plugins").children("#loadSign").remove();
 			$(container).children(".plugins").append(requestType.responseText);
 		}
 	}
}

function requestBloggers(container, id) {
	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$(container).children("#content-container").append(load_sign);

	//Send request to get Author_UID
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getBloggers.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+id);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			isLogedIn = requestType.responseText.split("~")[0];
			isBanned = requestType.responseText.split("~")[1];
			authorID_Text = requestType.responseText.split("~")[2];
			authorID_NEXT_ID = requestType.responseText.split("~")[3];

			//Send request to parse & build author
			var request_build;
			if (window.XMLHttpRequest) {
				request_build = new XMLHttpRequest();
			} else {
				request_build = new ActiveXObject("Microsoft.XMLHTTP");
			}
			request_build.open("POST", "giveMePerson.php", true);
			request_build.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			request_build.send("authorId="+authorID_Text+"&banned="+isBanned+"&online="+isLogedIn);

			request_build.onreadystatechange = function() {
				if (request_build.readyState == 4 && request_build.status == 200) {
					$("#loadSign").remove();
					$(container).children("#content-container").append(request_build.responseText);

					if (authorID_NEXT_ID > 0) {
						requestBloggers(container, authorID_NEXT_ID);
					}
				}
			}
		}
	}
}

//Unload blogger
function unloadBlogger(id) {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "unloadBlogger.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+id);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("."+id).find(".unload-button").remove();
		}
	}
}

//Ban bloger
function banBlogger(id) {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "banBlogger.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+id);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("."+id).find(".ban-button").remove();

			new_button = "\
				<button type='button' class='option-button delete-button ban-button' onclick='unbanBlogger(\""+id+"\");' title='Unban Blogger'>\
					Unban\
				</button>\
			";
			$("."+id).children("#controls-container").append(new_button);
		}
	}
}

//Unban blogger
function unbanBlogger(id) {
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "unbanBlogger.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+id);

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("."+id).find(".ban-button").remove();

			new_button = "\
				<button type='button' class='option-button delete-button ban-button' onclick='banBlogger(\""+id+"\");' title='Ban Blogger'>\
					Ban\
				</button>\
			";
			$("."+id).children("#controls-container").append(new_button);
		}
	}
}

//Show place on map
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

//Place row manipulations
function selectPlace(id) {
	if (selectedPlaces.indexOf(id) > -1) {
		selectedPlaces.splice(selectedPlaces.indexOf(id), 1);
		$("."+id).children(".select-button").removeClass("selected").addClass("unselected").prop('title', 'Select place');
	} else {
		selectedPlaces.push(id);
		$("."+id).children(".select-button").removeClass("unselected").addClass("selected").prop('title', 'Unselect place');
	}
}

function removePreview() {
	$("#class-container").fadeOut("fast");
	setTimeout(function(){ $("#class-container").remove(); }, 150);
}

function previewPlace(id) {
	document.cookie = "previewIndex="+id;

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
			build = "\
				<div id='class-container' onclick='removePreview();'>\
					<div id='sideBar' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>\
						<h1>Tagged bloggers</h1>\
						<div id='users-list'>\
						</div>\
					</div>\
					<div id='contentBar' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>\
						<div id='place-container'>\
							<h1>"+placeTitle+"</h1>\
							<p>\
								"+placeStory+"\
							</p>\
							<div id='mapController'>\
							</div>\
						</div>\
					</div>\
				</div>\
			";

			$("body").append(build);

			$("#class-container").fadeIn("fast");

			showOnMap(placeCords.split("#")[0], placeCords.split("#")[1], "mapController");
			parseBlogger(taggedPeople, taggedPeople.split(",").length - 1, 0);
		}
	}
}

//Parse blogger
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

 		   	build = "\
 		   		<button onclick=\"window.open('http://blogy.omni.world?"+blogerIdArgument+"');\" target='_blank'>\
 		   			<div style='background-image:url("+parseResult[0]+"); background-size: cover; background-position: 50%;' class='img'></div>\
 		   			"+parseResult[2]+" "+parseResult[3]+"\
 		   		</button>\
 		   	";

			$("#users-list").append(build);
 		 	document.cookie = "userId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

 		 	if (callBacks > 0) {
 		 		parseBlogger(blogerId.toString(), callBacks - 1, indexPointer + 1);
 		 	}
     	}
    }
}

function addPlace(id) {
	removeOnlyThis = 0;

	if (selectedPlaces.indexOf(id) > -1 && selectedPlaces.length >= 2) {
		document.cookie = "addIndex="+selectedPlaces.toString();
	}
	else {
		document.cookie = "addIndex="+id;
		removeOnlyThis = 1;
	}

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "addPlace.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				if (removeOnlyThis == 1) {
					$("."+id).fadeOut("fast");
					$("."+id).remove();
				} else {
					for (i = 0; i < selectedPlaces.length; i++) {
						$("."+selectedPlaces[i]).fadeOut("fast");
						$("."+selectedPlaces[i]).remove();
					}
					selectedPlaces = [];
				}

				document.cookie = "addIndex=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			}
		}
	}
}

function deletePlace(id) {
	removeOnlyThis = 0;

	if (selectedPlaces.indexOf(id) > -1 && selectedPlaces.length >= 2) {
		document.cookie = "deleteIndex="+selectedPlaces.toString();
	}
	else {
		document.cookie = "deleteIndex="+id;
		removeOnlyThis = 1;
	}

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("GET", "removePlace.php", true);
	requestType.send();

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				if (removeOnlyThis == 1) {
					$("."+id).fadeOut("fast");
					$("."+id).remove();
				} else {
					for (i = 0; i < selectedPlaces.length; i++) {
						$("."+selectedPlaces[i]).fadeOut("fast");
						$("."+selectedPlaces[i]).remove();
					}
					selectedPlaces = [];
				}

				document.cookie = "deleteIndex=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
			}
		}
	}
}

//Preview report
function previewReport(id) {
	$("."+id).children("#report-container").slideToggle("fast");
}

//Remove report
function removeReport(id) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "removeReport.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("deleteIndex="+id); 

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				$("."+id).fadeOut("fast");
				$("."+id).remove();
			}
		}
	}
}

//Pull trusted users
function requestTrustedUsers(container) {
	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$(container).children("#content-container").append(load_sign);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getTrustedUsers.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send(); 

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#content-container").children("#loadSign").remove();
			$(container).children("#content-container").append(requestType.responseText);
		}
	}
}

//Show - Hide add admin container
function showHideAddAdmin() {
	$("#input-save").children("#admin-id-container").val("");
	$("#input-save").slideToggle("fast");
}

//Add admin
function addAdmin() {
	getId = $("#input-save").children("#admin-id-container").val();

	if (getId.trim() == "") { alert("Add ID first."); }
	else {
		//Send request
		var requestType;
		if (window.XMLHttpRequest) {
			requestType = new XMLHttpRequest();
		} else {
			requestType = new ActiveXObject("Microsoft.XMLHTTP");
		}
		requestType.open("POST", "addAdmin.php", true);
		requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		requestType.send("authorId="+getId);

		requestType.onreadystatechange = function() {
			if (requestType.readyState == 4 && requestType.status == 200) {
				if (requestType.responseText == "READY") {
					location.reload();
				}
			}
		}
	}
}

//Delete Admin
function deleteAdmin(id) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "removeAdmin.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+id); 

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				$("."+id).fadeOut("fast");
				$("."+id).remove();
			}
		}
	}
}

//Request partners
function requestPartners(container) {
	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$(container).children("#content-container").append(load_sign);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getPartners.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send(); 

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#content-container").children("#loadSign").remove();
			$(container).children("#content-container").append(requestType.responseText);
		}
	}
}

//Preview partner
function previewPartner(url) {
	window.open(url);
}

//Edit partner
function editPartner(id) {
	$("."+id).children("#editor-container").slideToggle("fast");
}

//Save changes
function saveChangesPartner(id) {
	getId = $("."+id).children("#editor-container").children("#partnerId").val();
	getURL = $("."+id).children("#editor-container").children("#partnerLink").val();
	getLogo = $("."+id).children("#editor-container").children("#partnerLogo").val();

	setFlag = 0;

	if (getId.trim() == "") {
		alert("What is the ID of the partner ?");
		setFlag = 1;
	} 
	else
	if (getURL.trim() == "") {
		alert("What is the URL of the partner ?");
		setFlag = 1;
	}
	else
	if (getLogo.trim() == "") {
		alert("what is the logo of the partner ?");
		setFlag = 1;
	}

	if (setFlag == 0) {
		$("."+id).children("#editor-container").attr('action', 'saveChangesPartner.php');
		$("."+id).children("#editor-container").submit();
	}
}

//Delete Admin
function deletePartner(id) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "removePartner.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("authorId="+id); 

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			if (requestType.responseText == "READY") {
				$("."+id).fadeOut("fast");
				$("."+id).remove();
			}
		}
	}
}

//Request all views
function requestViews(container, type) {
	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$(container).children("#content-container").append(load_sign);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getViews.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("type="+type); 

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$(container).children("#content-container").children("#loadSign").remove();
			$(container).children("#content-container").append(requestType.responseText);
		}
	}
}

//Request views on date
function requestViewsOnDate(container, type) {
	$("body").find(container).empty();
	$("body").find(container).toggle();

	load_sign = "\
		<div id='loadSign'>\
			<img src='../images/loadSign.GIF'/>\
		</div>\
	";
	$("body").find(container).append(load_sign);

	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}
	requestType.open("POST", "getViews.php", true);
	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("type="+type); 

	requestType.onreadystatechange = function() {
		if (requestType.readyState == 4 && requestType.status == 200) {
			$("body").find(container).children("#loadSign").remove();
			$("body").find(container).append(requestType.responseText);
		}
	}
}

//Open plugin description
function openPluginDescription(target_) {
	$(target_).slideToggle("fast");
}

//Download plugin project
function downloadPluginProject(path, slug, author) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "downloadPluginProject.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginPath="+path+"&pluginSlug="+slug+"&pluginAuthor="+author);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		window.open(requestType.responseText);
    	}
    }
}

//Add requestedPlugin
function addRequestedPlugin(pluginID, pluginSlug, pluginAuthor, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "addRequestedPlugin.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&pluginSlug="+pluginSlug+"&pluginAuthor="+pluginAuthor);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) { $(".plugins").empty(); requestPlugins('#left-container', 0); }
    		else { alert(requestType.responseText); }
    	}
    }
}

//Decline requested plugin
function declineRequestedPlugin(pluginID, pluginAuthor, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "declineRequestedPlugin.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID+"&pluginAuthor="+pluginAuthor);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) { $(".plugins").empty(); requestPlugins('#left-container', 0); }
    		else { alert(requestType.responseText); }
    	}
    }
}

//Activate plugin
function activatePlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "activatePlugin.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$(".plugin-"+pluginID+" #enable").replaceWith("<button id='disable' class='option-button delete-button' onclick='disablePlugin("+pluginID+", "+mobile+");'>Disable</button>");
    		}
    		else { alert(requestType.responseText); }
    	}
    }
}

//Disable plugin
function disablePlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "disablePlugin.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$(".plugin-"+pluginID+" #disable").replaceWith("<button id='enable' class='option-button' onclick='activatePlugin("+pluginID+", "+mobile+");'>Enable</button>");
    		}
    		else { alert(requestType.responseText); }
    	}
    }
}

//Feature plugin
function featurePlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "featurePlugin.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$(".plugin-"+pluginID+" #feature").replaceWith("<button id='unfeature' class='option-button delete-button' onclick='unfeaturePlugin("+pluginID+", "+mobile+");'>Unfeature</button>");
    		}
    		else { alert(requestType.responseText); }
    	}
    }
}

//Unfeature plugin
function unfeaturePlugin(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "unfeaturePlugin.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$(".plugin-"+pluginID+" #unfeature").replaceWith("<button id='feature' class='option-button' onclick='featurePlugin("+pluginID+", "+mobile+");'>Feature</button>");
    		}
    		else { alert(requestType.responseText); }
    	}
    }
}

//Delete plugin from store
function deletePluginFromStore(pluginID, mobile) {
	//Send request
	var requestType;
	if (window.XMLHttpRequest) {
		requestType = new XMLHttpRequest();
	} else {
		requestType = new ActiveXObject("Microsoft.XMLHTTP");
	}

	requestType.open("POST", "deletePluginFromStore.php", true);

	requestType.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	requestType.send("pluginID="+pluginID);

	requestType.onreadystatechange = function() {
    	if (requestType.readyState == 4 && requestType.status == 200) {
    		if ( requestType.responseText == "READY" ) {
    			$(".plugin-"+pluginID).remove();
    		}
    		else { alert(requestType.responseText); }
    	}
    }
}