// Purpose: functions shared between all dialog windows
var wp_current_obj = null
if (window.dialogArguments) {
	obj = dialogArguments.wp_current_obj
	wp_current_obj = obj
	parentWindow = dialogArguments
} else if (parent.window.dialogArguments) {
	obj = parent.window.obj
	wp_current_obj = obj
	parentWindow = parent.window.parentWindow
} else if (window.opener) {
	obj = window.opener.wp_current_obj
	wp_current_obj = obj
	parentWindow = window.opener
} else if (parent.window.opener) {
	obj = parent.window.obj
	wp_current_obj = obj
	parentWindow = parent.window.parentWindow
} else {
	obj = null
	wp_current_obj = null
	parentWindow = null
}
function colordialog(Action) {
	colorwin = wp_openDialog(parentWindow.wp_directory+"selcolor.php?action="+Action+"&lang=" + obj.instance_lang ,'modal' , 296, 352)
}
function onClose() {
	if (parentWindow.wp_directory) {
		obj.edit_object.focus()
	}
}
function hideLoadMessage() {
	document.getElementById('dialogLoadMessage').style.display = 'none'
}
function showLoadMessage() {
	document.getElementById('dialogLoadMessage').style.display = 'block'
}
function formStop() {
	return false
}
//window.onerror = hide_error;
function hide_error() {
	if (!parentWindow.wp_debug_mode) {
		return true;
	}
}
function make_url_with_base (url) {
	if (obj) {
		if (obj.baseURLurl) {
			if (obj.baseURLurl != '') {
				//var editor = this.editor;				
				if (obj._baseDomain) {
					url = url.replace(/^\//gi, obj._baseDomain+'/');
					url = url.replace(/^([^#][^:"]*)$/gi, obj.baseURLurl+'$1');
				} else {
					url = url.replace(/^\//gi, obj._domain+'/');
					url = url.replace(/^([^#][^:"]*)$/gi, obj._location+'$1');
				}
				while(url.match(/([^:][^\/])\/[^\/]*\/\.\.\//gi)) {
					url = url.replace(/([^:][^\/])\/[^\/]*\/\.\.\//gi, '$1/');
				}
				//url = url.replace(/([^:][^\/])\/[^\/]*\/\.\./gi, '$1');
				url = url.replace(/\/\.\//gi, '/');
			}
		}
	}
	return url;
}