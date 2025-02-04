<?php
include_once (dirname(__FILE__).'/config.php');
include_once(dirname(__FILE__).'/editor_functions.php');
include_once (dirname(__FILE__).'/includes/common.php');
include_once (dirname(__FILE__).'/lang/'.$lang_include);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $lang['titles']['insert_hr']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo WP_WEB_DIRECTORY; ?>dialoge_theme.css" type="text/css">
<script language="JavaScript" type="text/javascript" src="<?php echo WP_WEB_DIRECTORY; ?>js/dialogEditorShared.js?version=<?php echo $version ?>"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo WP_WEB_DIRECTORY; ?>js/dialogShared.js?version=<?php echo $version ?>"></script>
<script type="text/javascript">
<!--//
var CURRENT_RULER
function loadRuler() {
	var nodes = []
	if (wp_is_ie) {
		if (obj.edit_object.document.selection.type == "Control") {
			var sel = obj.edit_object.document.selection.createRange()
			nodes[0] = sel(0);
		}
	} else {
		var sel = obj.edit_object.getSelection();
		var range
		var num = sel.rangeCount
		var j = 0;
		for (var i=0; i < num; i++) {
			range = sel.getRangeAt(i);
			if (i == 0) this.range = range//.cloneRange();
			var container = range.startContainer
			var endContainer = range.endContainer
			var pos = range.startOffset;
			if (container == endContainer) {
				if (range.endOffset == (pos+1)) {
					if (container.tagName) {
						var cn = container.childNodes
						if (cn[pos]) {
							if (cn[pos].tagName) {
								nodes[j] = cn[pos];
								j++;
							} else {
								//return false;
							}
						}
					}
				}
			}
		}
	}
	var form = document.hr_form;
	if (nodes[0]) {
		if (nodes[0].nodeName == 'HR') {
			var h = nodes[0];
			CURRENT_RULER = h;
			if (form.align) {
				var a;
				if (a=h.getAttribute('align')) {
					form.align.value = a;
				}
			}
			if (form.width) {
				var width;
				if (a=h.style.width) {
					width = a;
				} else if (a=h.getAttribute('width')) {
					width = a;
				}
				if (width) {
					if (width.search('%') != -1 ) {
						form.percent2.value = '%';
					} else {
						form.percent2.value = 'px';
					}
					form.width.value = width.replace(/[^0-9]/gi,'');
				}
			}
			if (form.size) {
				var height;
				if (a=h.style.height) {
					height = a;
				} else if (a=h.getAttribute('size')) {
					height = a;
				}
				if (height) {
					form.size.value = height.replace(/[^0-9]/gi,'');
				}
			}
			if (form.color) {
				var color;
				if (a=h.style.backgroundColor) {
					color = a;
				} else if (a=h.getAttribute('color')) {
					color = a;
				}
				if (color) {
					form.color.value = color;
					document.getElementById('borderchosencolor').style.backgroundColor = color;
				}
			}
		}
	}

}

function wp_docolor(obj,Action,color) {   
	if (color != null) {
		if (Action == 1){
			document.hr_form.color.value = color;
			document.getElementById('borderchosencolor').style.backgroundColor = color;
		}
	}
}
// creates the ruler attribute strings
function insertruler() {
	if (CURRENT_RULER) {
		if (document.hr_form.align.value) {
			CURRENT_RULER.setAttribute('align', document.hr_form.align.value);
		} 
		if (document.hr_form.color.value) {
			color = ' color="'+ document.hr_form.color.value +'" style="background-color:' + document.hr_form.color.value + '\" noshade="noshade"';
			CURRENT_RULER.setAttribute('color', document.hr_form.color.value);
			CURRENT_RULER.style.backgroundColor = document.hr_form.color.value;
		} 
		if (document.hr_form.size.value) {
			CURRENT_RULER.setAttribute('size', document.hr_form.size.value);
		} 
		if (document.hr_form.width.value) {
			CURRENT_RULER.setAttribute('width', document.hr_form.width.value + document.hr_form.percent2.value.replace(/[^0-9%]/));
		} 	
	} else {
		var code;
		var align;
		var color;
		var size;
		var width;
		if (document.hr_form.align.value == "") {
			align = "";
		} else {
			align = " align=" + document.hr_form.align.value + " ";
		} 
		if (document.hr_form.color.value == "") {
			color = "";
		} else {
			color = ' color="'+ document.hr_form.color.value +'" style="background-color:' + document.hr_form.color.value + '\" noshade="noshade"';
		} 
		if (document.hr_form.size.value == "") {
			size = "";
		} else {
			size = " size=" + document.hr_form.size.value + " ";
		} 
		if (document.hr_form.width.value == "") {
			width = "";
		} else {
			width = " width=" + document.hr_form.width.value + document.hr_form.percent2.value + " ";
		} 	
		if (document.all) {
			code="<hr " + align + color + size + width + ">";
			parentWindow.wp_create_hr(obj, code);
		} else {
			parentWindow.wp_create_hr(obj,document.hr_form.align.value,document.hr_form.color.value,document.hr_form.size.value,document.hr_form.width.value,document.hr_form.percent2.value);
		}
	}
	window.close();
	return false;
}
//-->
</script>
</head>
<body onLoad="loadRuler();hideLoadMessage();">
<?php include('./includes/load_message.php'); ?>
<div class="dialog_content" align="center"> 
	<form name="hr_form" id="hr_form" onSubmit="return insertruler();">
		<fieldset>
		<legend><?php echo $lang['horizontal_rule']; ?></legend>
		<table cellpadding="1" cellspacing="3" border="0" width="204">
			<tr> 
				<td><?php echo $lang['alignment']; ?></td>
				<td> <select name="align" id="align" style="width:100; ">
						<option selected="selected" value=""><?php echo $lang['default']; ?></option>
						<option value="left"><?php echo $lang['left']; ?></option>
						<option value="center"><?php echo $lang['center']; ?></option>
						<option value="right"><?php echo $lang['right']; ?></option>
					</select> </td>
			</tr>
			<tr> 
				<td><?php echo $lang['height']; ?></td>
				<td> <input type="text" name="size" id="size" style="width:100;"></td>
			</tr>
			<tr> 
				<td><?php echo $lang['width']; ?></td>
				<td> <input type="text" name="width" id="width" size="4"> <select name="percent2" id="percent2" style="width:55; font-family:Verdana">
						<option value="%" selected="selected">%</option>
						<option value="px"><?php echo $lang['pixels']; ?></option>
					</select> </td>
			</tr>
			<tr> 
				<td><?php echo $lang['color']; ?></td>
				<td> <button class="colordisplay" type="button" onClick="colordialog(1);"> 
					<div id="borderchosencolor">&nbsp;</div>
					<?php echo $lang['choose']; ?></button>
					<input type="hidden" name="color" id="color" value=""> </td>
			</tr>
		</table>
		</fieldset>
		<br>
		<button type="submit"><?php echo $lang['ok']; ?></button>
		&nbsp;&nbsp; 
		<button type="button" onClick="window.close();"><?php echo $lang['cancel']; ?></button>
		<br>
	</form>
</div>
</body>
</html>
