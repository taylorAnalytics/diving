function openUserType(evt, usrTypeName) {
	// Declare all variables:
	var i, tabcontent, tablinks;
	
	// Get all elements with class "tabcontent" and hide them
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].className = "tabcontent";
	}
	
	// Get all elements with class "tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	
	// Show the current tab, and add an "active" class to the link that opened the tab
	document.getElementById(usrTypeName).className += " selected";
	evt.currentTarget.className += " active";
}

function openDeleteMenu() {
	
	// Get all elements with class "checkDelete" and remove the class "hidden"
	var checkDelete = document.getElementsByClassName("checkDelete");
	for (var i = 0; i < checkDelete.length; i++) {
		checkDelete[i].className = checkDelete[i].className.replace(" hidden", " visible");
	}
	var hidDel = document.getElementsByClassName("hidDel");
	for (var i = 0; i < hidDel.length; i++) {
		hidDel[i].className = hidDel[i].className.replace(" hidden", " inline");
	}	
}

function openSelect() {
	// Get all elements with class "checkMain" and remove the class "hidden":
	var checkMain = document.getElementsByClassName("checkMain");
	for (var i = 0; i < checkMain.length; i++) {
		checkMain[i].className = checkMain[i].className.replace(" hidden", " visible");
	}
}

function checkAll() {
	// Get all elements with class "checkDelete"
	var checkDelete = document.getElementsByClassName("checkDelete");
	for (var i = 0; i < checkDelete.length; i++) {
		checkDelete[i].checked = true;
		checkDelete[i].checked = "checked";
	}
}

function unCheckAll() {
	// Get all elements with class "checkDelete"
	var checkDelete = document.getElementsByClassName("checkDelete");
	for (var i = 0; i < checkDelete.length; i++) {
		checkDelete[i].checked = false;
	}
}
function eanCheck() {
	if (document.getElementById("air").checked) {
		document.getElementById("o2level").style.display = 'none';
	} else {
		document.getElementById("o2level").style.display = 'inline';
	}
}
function showDives() {
	
	var tab = document.getElementById("hidden-dive");
	var but = document.getElementById("dive-button");
	if (but.innerHTML === 'Show dive list') {
		tab.style.display = 'inline-block';
		but.innerHTML = 'Hide dive list';
	} else {
		but.innerHTML = 'Show dive list';
		tab.style.display = 'none';
	}
}
function showDiv() {
	var div = document.getElementById("delete-dive");
	div.style.display="inline-block";
}
function hideDiv() {
	var div = document.getElementById("delete-dive");
	div.style.display= "none";
}
