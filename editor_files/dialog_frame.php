<?php
include_once (dirname(__FILE__).'/config.php');
include_once(dirname(__FILE__).'/editor_functions.php');
include_once (dirname(__FILE__).'/includes/common.php');
include_once (dirname(__FILE__).'/lang/'.$lang_include);
$arr = explode('/',$_GET['window']);
$length = count($arr);
$title = str_replace('.php', '', $arr[$length - 1]);
if (isset($lang['titles'][$title])) {
	$title = $lang['titles'][$title];
} else if ($title == 'link') {
	$title = $lang['titles']['hyperlink'];
} else {
	$title = '';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo WP_WEB_DIRECTORY; ?>dialoge_theme.css" type="text/css">
<style type="text/css">
body {
	padding: 0px 0px;
	margin: 0px 0px;
}
</style>
<script language="JavaScript" type="text/javascript" src="<?php echo WP_WEB_DIRECTORY; ?>js/dialogShared.js?version=<?php echo $version ?>"></script>
</head>
<body>
<iframe src="<?php echo htmlspecialchars(stripslashes($_GET['window'] . '?' . $_SERVER["QUERY_STRING"])); ?>" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>
</body>
</html>
