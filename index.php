<?PHP
	echo "<meta http-equiv=\"Refresh\" content=\"0;url=login.php\">";
	exit;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNET Manager</TITLE>
<META content="Viodvla.com is the official website of the Directorate for Road Traffic Services in Nigeria." name="Short Title">
<META content="Nigeria Centre for Road Traffic" name=AGLS.Function>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<LINK media=all href="css/rock.css" type=text/css rel=stylesheet>
<style type="text/css">
td img {display: block;}
</style>
<script language="JavaScript1.2" type="text/javascript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

//-->
</script>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg onLoad="MM_preloadImages('images/opt1_over.gif','images/opt2_over.gif','images/opt3_over.gif');">
<TABLE style="WIDTH: 100%" background="images/Top_pole.jpg">
<TBODY>
	<TR>
	  <TD height="55px" valign="top">
	  	<TABLE width="70%" border="0" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
		  <TR>
			<TD width="38%" align="left"><img src="images/edmis_logo.jpg" width="130" height="39"></TD>
			<TD width="62%" align="right"><a href="index.php">Home</a></TD>
		  </TR>
		</TABLE>
	  </TD>
	</TR>
</TBODY>
</TABLE>
<TABLE width="100%" bgcolor="#f4f4f4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br><br>
	  <TABLE width="70%" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD>
		<TABLE width="100%" style="WIDTH: 100%">
        <TBODY>
			<TR>
			  <TD style="FONT-WEIGHT: lighter; FONT-SIZE: 14pt; COLOR: #02679A; FONT-FAMILY: 'Arial'; HEIGHT: 46px; FONT-VARIANT: normal" 
			   align="center">SkoolNET Manager  </TD>
			</TR>
		</TBODY>
		</TABLE>
		<div align="center"><BR><BR> 
		
<?php
$useragent = ($_SERVER['HTTP_USER_AGENT']);
$recognized = "False";
if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'IE';
	$recognized = "False";
} elseif (preg_match( '|Opera ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Opera';
	$recognized = "False";
} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
        $browser_version=$matched[1];
        $browser = 'Firefox';
		$recognized = "True";
} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
        $browser_version=$matched[1];
        $browser = 'Safari';
		$recognized = "True";
} else {
        // browser not recognized!
    $browser_version = 0;
    $browser= ‘other’;
	$recognized = "False";
}
//print "browser: $browser $browser_version";
if($recognized == "True"){
?>	
	<table border="0" cellpadding="0" cellspacing="0" width="745">
	  <tr>
	   <td><img src="images/spacer.gif" width="3" height="1" border="0" alt="" /></td>
	   <td><img src="images/spacer.gif" width="244" height="1" border="0" alt="" /></td>
	   <td><img src="images/spacer.gif" width="245" height="1" border="0" alt="" /></td>
	   <td><img src="images/spacer.gif" width="248" height="1" border="0" alt="" /></td>
	   <td><img src="images/spacer.gif" width="5" height="1" border="0" alt="" /></td>
	   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
	  </tr>
	
	  <tr>
	   <td colspan="5"><img name="Login_options_r1_c1" src="images/Login_options_r1_c1.gif" width="745" height="2" border="0" id="Login_options_r1_c1" alt="" /></td>
	   <td><img src="images/spacer.gif" width="1" height="2" border="0" alt="" /></td>
	  </tr>
	  <tr>
	   <td rowspan="3"><img name="Login_options_r2_c1" src="images/Login_options_r2_c1.gif" width="3" height="260" border="0" id="Login_options_r2_c1" alt="" /></td>
	   <td><a href="login.php?pg=Monitory" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('Login_options_r2_c2','','images/opt1_over.gif',1);"><img name="Login_options_r2_c2" src="images/Login_options_r2_c2.gif" width="244" height="252" border="0" id="Login_options_r2_c2" alt="" /></a></td>
	   <td><a href="login.php?pg=Admin" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('Login_options_r2_c3','','images/opt2_over.gif',1);"><img name="Login_options_r2_c3" src="images/Login_options_r2_c3.gif" width="245" height="252" border="0" id="Login_options_r2_c3" alt="" /></a></td>
	   <td rowspan="2"><a href="login.php?pg=Teacher" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('Login_options_r2_c4','','images/opt3_over.gif',1);"><img name="Login_options_r2_c4" src="images/Login_options_r2_c4.gif" width="248" height="253" border="0" id="Login_options_r2_c4" alt="" /></a></td>
	   <td rowspan="3"><img name="Login_options_r2_c5" src="images/Login_options_r2_c5.gif" width="5" height="260" border="0" id="Login_options_r2_c5" alt="" /></td>
	   <td><img src="images/spacer.gif" width="1" height="252" border="0" alt="" /></td>
	  </tr>
	  <tr>
	   <td rowspan="2" colspan="2"><img name="Login_options_r3_c2" src="images/Login_options_r3_c2.gif" width="489" height="8" border="0" id="Login_options_r3_c2" alt="" /></td>
	   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
	  </tr>
	  <tr>
	   <td><img name="Login_options_r4_c4" src="images/Login_options_r4_c4.gif" width="248" height="7" border="0" id="Login_options_r4_c4" alt="" /></td>
	   <td><img src="images/spacer.gif" width="1" height="7" border="0" alt="" /></td>
	  </tr>
	</table>
<?php
	}else{
?>	
	<table border="0" cellpadding="0" cellspacing="0" width="745">
	  <tr>
	   <td>THIS APPLICATION IS OPTIMIZED FOR MOZILLA FIREFOX OR IT'S BEST VIEWED WITH MOZILLA FIREFOX.<BR> <a href="download/Firefox Setup 3.5.7.exe">CLICK HERE TO DOWNLOAD FIREFOX</a></td>
	  </tr>
	</table>
<?php
	}
?>
	
		</div>
		<BR>
		<TABLE width="100%" style="WIDTH: 100%">
        <TBODY>
			<TR>
			  <TD style="FONT-WEIGHT: lighter; FONT-SIZE: 14pt; COLOR: #000000; FONT-FAMILY: 'Arial'; HEIGHT: 46px; FONT-VARIANT: normal" 
			   align="center">Ready to get started? Choose options to login </TD>
			</TR>
		</TBODY>
		</TABLE>
        </TD>
	  </TR>
	 </TABLE>
      </TD></TR></TBODY></TABLE>
<TABLE style="WIDTH: 100%" background="images/footer.jpg">
<TBODY>
	<TR>
	  <TD height="101px" valign="middle">
	  	<TABLE width="70%" border="0" cellPadding=3 cellSpacing=0 align="center">
		  <TR>
			<TD align="center">Home | About SkoolNET Manager | Contact us | User Agreement | Privacy Policy | Copyright Policy</TD>
		  </TR>
		  <TR>
			<TD align="center"> Copyright © <?PHP echo date('Y'); ?> SkoolNet Manager. All right reserved.</TD>
		  </TR>
		</TABLE>	  
	  </TD>
	</TR>
</TBODY>
</TABLE> 	
</BODY></HTML>
