// JavaScript Document



//Start Dropdown menu function

var timeout         = 200;

var closetimer		= 0;

var ddmenuitem      = 0;

function findPos(obj) {
	var curleft = curtop = 0;
        if (obj.offsetParent) {
			do {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
			} while (obj = obj.offsetParent);
			return curtop;
        }
}


function jsddm_open(){	

	jsddm_canceltimer();

	jsddm_close();

	//var num = $('#containerDiv').height()
    var num = document.getElementById('containerDiv').style.height;
	//$("#pageMask").height(num);
    document.getElementById('pageMask').style.height = num;
	//$("#pageMask").css("visibility","visible");	
	document.getElementById('pageMask').style.visibility = "visible";
	
	if (navigator.appName != "Microsoft Internet Explorer") {
		var coords = findPos(document.getElementById('navDiv'));
		document.getElementById('pageMask').style.top = coords + 34 - window.pageYOffset;
	}
	
	//alert(document.getElementById("navDiv").style.zIndex);

}



function jsddm_close(){	

	

	//if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');

	//$("#pageMask").css("visibility","hidden");
	document.getElementById('pageMask').style.visibility = "hidden";

	ddmenuitem = 0;

}



function jsddm_timer(){	closetimer = window.setTimeout(jsddm_close, timeout);}



function jsddm_canceltimer(){if(closetimer)

	{	window.clearTimeout(closetimer);

		closetimer = null;}}



function reset_status(){

	jsddm_close()

	document.getElementById('navDiv').style.backgroundPosition = "0px 0px";

}

document.onclick = reset_status;

//End Dropdown menu function



//Event.observe(window, 'onload', initialize, false);





//$(document).ready(function(){

//Menus
function pageMaskLoad(){
	var elem = document.getElementById('navDiv');		

	elem.onmouseover = function anonymous(event) {

		elem.style.zIndex = 1000;

		jsddm_open();

		

	}

	

	elem.onmouseout = function anonymous(event){

		//$("#navbar").css({"background-position":"0px 0px"});

		jsddm_close();

	}

	

	//init framework

	var IE6 = true

	

	if (typeof document.body.style.maxHeight != "undefined") {// IE 7, mozilla, safari, opera 9

	  IE6 = false

	}

	

	if(IE6){

		//alert(document.body.scrollHeight);

		if(document.body.scrollHeight < 665){

			document.getElementById('containerDiv').style.height = 665;

		}else{

			document.getElementById('containerDiv').style.height = document.body.scrollHeight;

		}

		if(screen.width > 1024){

			window.onresize = function(){

				var num = document.body.scrollHeight

				if(num > 665){

					document.getElementById('containerDiv').style.height = document.body.scrollHeight;

				}

			}

		}
	}
}
	

//});
