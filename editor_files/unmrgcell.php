<?php
include_once (dirname(__FILE__).'/config.php');
include_once(dirname(__FILE__).'/editor_functions.php');
include_once (dirname(__FILE__).'/includes/common.php');
include_once (dirname(__FILE__).'/lang/'.$lang_include);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Unmerge Cell</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="<?php echo WP_WEB_DIRECTORY; ?>dialoge_theme.css" type="text/css" />
<script language="JavaScript" type="text/javascript" src="<?php echo WP_WEB_DIRECTORY; ?>js/dialogEditorShared.js?version=<?php echo $version ?>"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo WP_WEB_DIRECTORY; ?>js/dialogShared.js?version=<?php echo $version ?>"></script>
<script type="text/javascript" language="JavaScript">
<!--//
function end() {
	if (document.getElementById('right').checked) {
		parentWindow.wp_unMergeRight(obj);
	} else if (document.getElementById('below').checked) {
		parentWindow.wp_unMergeDown(obj);
	}
	window.close();
	return false;
}
//-->
</script>
</head>
<body onLoad="hideLoadMessage();">
<?php include('./includes/load_message.php'); ?>
<div class="dialog_content" align="center"> 
	<form name="add_form" id="add_form" onSubmit="return end()">
		<fieldset>
		<legend><?php echo $lang['unmerge_cell2']; ?></legend>
		<table id="background" width="100%" border="0" cellspacing="3" cellpadding="0">
			<tr> 
				<td><p> 
						<input id="right" type="radio" name="where" value="right" checked="checked" />
						<?php echo $lang['unmerge_right']; ?></p>
					<p> 
						<input id="below" type="radio" name="where" value="below" />
						<?php echo $lang['unmerge_below']; ?></p></td>
			</tr>
		</table>
		</fieldset>
		<br />
		<div align="center"> 
			<button type="submit" id="ok"><?php echo $lang['ok']; ?></button>
			&nbsp; 
			<button type="button" onClick="window.close();"><?php echo $lang['cancel']; ?></button>
		</div>
	</form>
</div>
</body>
</html>
