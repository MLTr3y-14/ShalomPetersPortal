<?PHP
	session_start();
	$end=time();
	global $userNames;
	global $TimerOut;
	$TimerOut="False";
	$file = "welcome.php?pg=";
/*    session variables like user login info ..    */ 
	
	if (isset($_SESSION['AdmNo']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	$dat = date('Y'.'-'.'m'.'-'.'d');
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include '../library/config.php';
	include '../library/opendb.php';
	include '../formatstring.php';
	include '../function.php';
    include("start_timer.php");
    include("end_timer.php");
	
	$AdmissionNo = $_SESSION['AdmNo'];
	$query = "select Stu_Class,Stu_Full_Name,Stu_Photo,Stu_Sec from tbstudentmaster where AdmissionNo='$AdmissionNo'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$StuClass  = $dbarray['Stu_Class'];
	$FullName  = $dbarray['Stu_Full_Name'];
	$StudFileName  = $dbarray['Stu_Photo'];
	$Stu_Section  = $dbarray['Stu_Sec'];
	$Login = "Log in Student: ".$FullName; 
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['PostReply'])){
		?> <script>document.location = 'welcome.php?open_post=true';</script> <?php
	}
		
	//GET ACTIVE TERM AND SESSION
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$ActiveValue = 1;
	$ActiveSession  = Get_Active_Session(1);
	
	if(isset($_POST['OptSubject']))
	{	
		$OptSubject = $_POST['OptSubject'];
	}	
	if(isset($_POST['SubmitShare']))
	{
		$PageHasError = 0;
		$OptSubject = $_POST['OptSubject'];
		$postissue = $_POST['postissue'];
		if($postissue == ""){
			$errormsg = "<font color = red size = 1>Post is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptSubject']){
			$errormsg = "<font color = red size = 1>Select Class</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{	
			$num_rows = 0;
			$q = "Insert into tbclasspost(SubjID,SessionID,PostDate,PostMsg,SenderID,Section,SenderType) Values ('$OptSubject','$ActiveSession','$dat','$postissue','$AdmissionNo','$Activeterm','Student')";
			$result = mysql_query($q,$conn);
			$postissue = "";
			$OptSubject = "";
		}
	}
	if(isset($_POST['SendReply']))
	{
		$PageHasError = 0;
		$PostID = $_POST['PostID'];
		$fSubComment = $_POST['fSubComment'];
		
		if(!$_POST['fSubComment']){
			$errormsg = "<font color = red size = 1>Post is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['PostID']){
			$errormsg = "<font color = red size = 1>Invalid Post ID</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$num_rows = 0;

			$q = "Insert into tbclasspost_reply(ClassPostID,Respondant,PostDate,PostMsg,SenderType) Values ('$PostID','$AdmissionNo','$dat','$fSubComment','Student')";
			$result = mysql_query($q,$conn);
			$fSubComment = "";
		}
	}
	if(isset($_GET['post_id']))
	{
		$postid = $_GET['post_id'];
		$q = "Delete From tbclasspost where ID = '$postid'";
		$result = mysql_query($q,$conn);

		$q = "Delete From tbclasspost_reply where ClassPostID = '$postid'";
		$result = mysql_query($q,$conn);
	}
	$PostAction = "lastest";
	if(isset($_GET['Oth_post_id']))
	{
		$SelPostID = $_GET['Oth_post_id'];
		$PostAction = "Others";
	}									
	if($OptSubject==""){
		$OptSubject = $DefaultSubj;
	}
?>
<HTML xmlns="http://ww.w3org.org/1999/xhtml" xml:lang=" en-gb" lang="en-gb" dir="ltr"><HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8"><TITLE>Student Online Learning System @ SkoolNET</TITLE>
<BASE href=".">
<BASE href=".">
<BASE href=".">
<TITLE>Student Online Learning System @ SkoolNET</TITLE>
<LINK href="#" rel="shortcut icon" type="image/x-icon">
<LINK rel="stylesheet" type="text/css" href="./css2/style.css">
<LINK rel="stylesheet" type="text/css" href="./css2/menu.css">
<LINK rel="stylesheet" href="./css2/inner_style.css" type="text/css">
<LINK rel="stylesheet" href="./css2/joomla_classes.css" type="text/css">

<SCRIPT type="text/javascript" src="./css2/textsizer.js"></SCRIPT>
<SCRIPT type="text/javascript" src="./css2/initpage.js"></SCRIPT>
<script src="jquery/jquery-1.2.6.min.js" type="text/javascript"></script>
<script src="jquery/jquery.validate.js" type="text/javascript"></script>
<script src="jquery/jquery-ui.min.js" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        $("#form").validate();
        $('#ddate').datepicker({
            altField: '#ddate',
            altFormat: 'dd/mm/yy'
        });
        $("select#state").change(function(){
            $.getJSON("state_select.php", {
                id: $(this).val(),
                ajax: 'true'
            }, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("select#lga").html(options);
            })
        });
		
		 $('#fmr_lic_div').hide();

		$('#note_upload').click(function(){
			$('#fmr_lic_div').show('slow');
		});

		$('#find_stud').click(function(){
			$('#fmr_lic_div').show('slow');
		});
		
		$('#Close_Find').click(function(){
			$('#fmr_lic_div').hide('slow');
		});
		
		$('#open_post').click(function(){
			$('#fmr_lic_div').show('slow');
		});
		
		$('#learn_new').click(function(){
			$('#fmr_lic_div').hide('slow');
		})
    });
    
</script>
<style type="text/css">
<!--
.style4 {color: #000000; font-weight: bold; font-size: 12px; }
-->
</style>
</HEAD>
<BODY onLoad="pageMaskLoad()">
<?PHP include 'pagemask.php'; ?>
<DIV id=header>
	<DIV id=top-menus>
		<DIV id=uowlogo>
			<A href="welcome.php?pg=Student Blog"><IMG src="Images/img_logo_lpc.png" alt="SkoolNet Manager" title="SkoolNet Manager"></A>
		</DIV>
		<DIV id=search>
			<FORM style="FLOAT: right" action="" method=get>
			</FORM>
		</DIV>
		<DIV id=top-links>			
			<UL> 
			  <LI><A href="http://www.lagospastoralcollege.org" target="_blank">SkoolNET HOME</A> 
			  <LI><A href="../index.php" target="_blank">SOLS HOME</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Contact Us&subpg=none" target="_blank"><STRONG>Contact us </STRONG></A> 
			
			  <LI><A 
			  href="Logout.php"><STRONG>Logout</STRONG></A>			  </LI>
			</UL>
		</DIV>
		<DIV id=section-title><IMG title="Student Online Learning System" src="Images/uow066001.png"></DIV>
	</DIV>
</DIV>
<DIV id="containerDiv">
  <DIV id="navcontainer">
	<?PHP include 'topmenu.php'; ?>
  </DIV>
   <DIV id="body">
    <DIV id="cpanelStrip">
      <DIV id="cpanelStripLeft"></DIV>
      <DIV id="cpanelStripMiddle">
        <DIV id="breadcrumbsDiv">		
			<DIV class="moduletable">
				<SPAN class="breadcrumbs pathway">
					<UL>
						<LI class="firstCrumb">Contact Us </LI>
						<LI>&nbsp;</LI>
						<LI><A href="" class="pathway" title="Products">Fee Receipt  </A></LI>
						<LI class="sep"> <IMG src="./Images/arrow_rtl.png" alt=""></LI> 
						<LI></LI>
						<LI class="lastCrumb">Student Blog </LI>
					</UL>
				</SPAN>
			</DIV>
		</DIV>
        <DIV id="innerCPanel">
			<UL class="imageList">
			   <LI class="first"><A href=""><IMG src="./Images/inner-textsizer.gif" width="18" height="11" border="0" usemap="#Map"></A></LI>
		  </UL>
			 <UL class="textList">
			   <LI class="first">Text Sizer</LI>
			   <LI class="second"><A href="" title="Student Online Learning System" target="_blank"></A></LI>
			   <LI class="third"></LI>
			</UL>
        </DIV>
      </DIV>
      <DIV id="cpanelStripRight"></DIV>
    </DIV>
    <DIV id="leftColumn">
      <DIV id="relatedPages">
        <DIV id="relatedPagesTop"></DIV>
        <DIV id="relatedPagesMiddle">
          <H3>RELATED PAGES</H3>
          <DIV class="moduletable">
			<DIV class="moduletable">
				<?PHP include 'sidemenu.php'; ?>		
			</DIV>
          </DIV>
        </DIV>
        <DIV id="relatedPagesBottom"></DIV>
      </DIV>
      <DIV id="lowerBanner">
        <DIV id="lowerBannerTop"></DIV>
        <DIV id="lowerBannerMiddle"> 		
			<DIV class="moduletable">
				<DIV class="bannergroup">
					<DIV class="banneritem" style="background:#333333; height:260px">
						&nbsp;
						<DIV class="clr"></DIV>
					</DIV>
				</DIV>
			</DIV>
	 	</DIV>
        <DIV id="lowerBannerBottom"></DIV>
      </DIV>
    </DIV>
    <DIV id="rightColumn">
		<DIV id="bannerDiv">
			<DIV class="moduletable">
				<DIV class="bannergroup">
					<DIV class="banneritem">
					  <table width="100%" border="0" align="center" cellpadding="6" cellspacing="6">
						  <tr bgcolor="#ECE9D8">
							<td width="42"><span class="style4">Sr.</span></td>
							<td width="186"><span class="style4">Particulars</span></td>
							<td width="115"><div align="center" class="style4">
							  <div align="right">Amount</div>
							</div></td>
							<td width="141"><div align="center" class="style4">
							  <div align="right">Payables</div>
							</div></td>
							<td width="168"><div align="center" class="style4">
							  <div align="right">Paid Amount</div>
							</div></td>
						  </tr>
<?PHP
							$counter = 0;
							$TotalAmt = 0;
							$TotalPay = 0;
							$TotalPaid= 0;
							$GrandPayable = 0;
							$GrandPaid = 0;
							$query1 = "select ChargeName from tbclasscharges where ClassID IN (Select Stu_Class from tbstudentmaster where AdmissionNo = '$AdmissionNo')";
							$result1 = mysql_query($query1,$conn);
							$num_rows1 = mysql_num_rows($result1);
							if ($num_rows1 > 0 ) {
								while ($row1 = mysql_fetch_array($result1)) 
								{
									$ChargeName = $row1["ChargeName"];
									$chargeID = GetChargeID($ChargeName);
									
									$query = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$AdmissionNo' And Term = '$Activeterm' And Status = '0') And ChargeID = '$chargeID'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$AmtPayable  = $dbarray['Payable'];
									$PaidAmount  = $dbarray['PaidAmount'];
									if($PaidAmount ==""){
										$PaidAmount  = "0";
									}
									
									$query3 = "select ChargeName, Amount from tbclasscharges where ClassID='$StuClass' And ChargeName IN (Select ChargeName from tbchargemaster where ID ='$chargeID' )";
									$result3 = mysql_query($query3,$conn);
									$dbarray3 = mysql_fetch_array($result3);	
									$ChargeName  = $dbarray3['ChargeName'];
									if($ChargeName !=""){
										$counter = $counter+1;
										$Amount  = $dbarray3['Amount'];
										if($AmtPayable ==""){
											$AmtPayable  = $dbarray3['Amount'];
										}
										$TotalAmt = $TotalAmt +$Amount;
										$TotalPay = $TotalPay +$AmtPayable;
										$TotalPaid = $TotalPaid +$PaidAmount;
									
?>
						  
										  <tr>
											<td width="42"><?PHP echo $counter; ?></td>
											<td width="186"><?PHP echo $ChargeName; ?></td>
											<td width="115">
												<div align="right">
												  <input name="Amount<?PHP echo $counter; ?>" type="text" size="20" value="<?PHP echo $Amount; ?>" disabled="disabled" style="text-align:right; background-color:#FFFFFF">
											  </div></td>
											<td width="141">
											  <div align="right">
											    <input name="Pay<?PHP echo $counter; ?>" type="text" size="20" value="<?PHP echo $AmtPayable; ?>" style="text-align:right;  background-color:#FFFFFF" disabled="disabled">
									          </div></td>
											<td width="168">
											  <div align="right">
											    <input name="paid<?PHP echo $counter; ?>" type="text" size="20" value="<?PHP echo $PaidAmount; ?>" style="text-align:right; background-color:#FFFFFF" disabled="disabled">
									          </div></td>
										  </tr>
<?PHP
									}
								 }
							 }
							$GrandPayable = $TotalPay + $FinedPayable;
							$GrandPaid = $TotalPaid + $FinedPaid;
?>
						   <tr>
							<td width="42">&nbsp;</td>
							<td width="186"><div align="right"><strong>TOTAL</strong></div></td>
							<td width="115"><div align="right">
							  <input name="TotalAmount" type="text" size="20" value="<?PHP echo number_format($TotalAmt,2); ?>" style="text-align:right; background-color:#FFFFCC" disabled="disabled">
						     </div></td>
							<td width="141">
							  <div align="right">
							    <input name="TotalPayable" type="text" size="20" value="<?PHP echo number_format($TotalPay,2); ?>" style="text-align:right; background-color:#FFFFCC" disabled="disabled">
					          </div></td>
							<td width="168">
							  <div align="right">
							    <input name="TotalPaid" type="text" size="20" value="<?PHP echo number_format($TotalPaid,2); ?>" style="text-align:right; background-color:#FFFFCC" disabled="disabled">
					          </div></td>
						  </tr>
						  <tr>
							<td width="42">&nbsp;</td>
							<td width="186">&nbsp;</td>
							<td width="115"><div align="right"><strong>FINE</strong></div></td>
							<td width="141">
							  <div align="right">
							    <input name="FinedPayable" type="text"value="<?PHP echo $FinedPayable; ?>" size="20" style="text-align:right; background:#FFFFFF" disabled="disabled"/>
					          </div></td>
							<td width="168">
							  <div align="right">
							    <input name="FinedPaid" type="text"value="<?PHP echo $FinedPaid; ?>" size="20" style="text-align:right; background:#FFFFFF" disabled="disabled"/>
					          </div></td>
						  </tr>
						  <tr>
							<td width="42">&nbsp;</td>
							<td width="186">&nbsp;</td>
							<td width="115"><div align="right"><strong>GRAND TOTAL</strong></div></td>
							<td width="141">
							  <div align="right">
							    <input name="GrandPayable" type="text" size="20" value="<?PHP echo number_format($GrandPayable,2); ?>" style="text-align:right; background-color:#FFFFCC" disabled="disabled">
					          </div></td>
							<td width="168">
							  <div align="right">
							    <input name="GrandPaid" type="text" size="20" value="<?PHP echo number_format($GrandPaid,2); ?>" style="text-align:right; background-color:#FFFFCC" disabled="disabled">
					          </div></td>
						  </tr>
					  </table>
					  
					  
					  
					  <DIV class="clr"></DIV>
				  </DIV>
				</DIV>
			</DIV>
            </DIV>
	        <DIV id="contentDiv">				
				<SPAN class="article_separator">&nbsp;</SPAN>
		  </DIV>
		</DIV>
	</DIV>
	<DIV id="categoryMenu"></DIV>  
	<DIV id="footer">
		Powered by Nexzon Investment Limited   </DIV>
</DIV>
<MAP name="Map" id="Map">
  <AREA shape="rect" coords="-5,0,11,17" href="javascript:ts('body', 1);" alt="Increase Font">
  <AREA shape="rect" coords="-5,0,18,26" href="javascript:ts('body', -1);" alt="Decrease Font">
</MAP>

<SCRIPT type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</SCRIPT><SCRIPT src="./css2/ga.js" type="text/javascript"></SCRIPT>
<SCRIPT type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10296102-1");
pageTracker._trackPageview();
} catch(err) {}</SCRIPT>


</BODY></HTML>