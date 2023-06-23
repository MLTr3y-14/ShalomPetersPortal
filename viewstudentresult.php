<?PHP
session_start();
include("conf/conf.php");
    $dbConf = new AAConf();
    $databaseURL = $dbConf->get_databaseURL();
    $databaseUName = $dbConf->get_databaseUName();
    $databasePWord = $dbConf->get_databasePWord();
    $databaseName = $dbConf->get_databaseName();

        //Set DB Info. in-session
    $_SESSION['databaseURL']=$databaseURL;
    $_SESSION['databaseUName']=$databaseUName;
    $_SESSION['databasePWord']=$databasePWord;
    $_SESSION['databaseName']=$databaseName;


    $connection = mysql_connect($databaseURL,$databaseUName,$databasePWord);
        // or die ("Error while connecting to localhost");
    $db = mysql_select_db($databaseName,$connection);
        //or die ("Error while connecting to database");

    //$rowArray;
    include("function/getclassname.php");
   // $AdmnNo = '56372-101';
	//$sAdmnNo = '56372-101';
	$AdmnNo = $_SESSION['adminNo'];
	$sAdmnNo = $_SESSION['adminNo'];
    $query = "SELECT * FROM tbstudentmaster where AdmissionNo = '$AdmnNo'";
    $result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$StudentName = $row['Stu_Full_Name'];
        $StuClassID = $row['Stu_Class'];
		$StuPhoto = $row['Stu_Photo'];
		    $StuClassTeacher = 'MR DELE/08023103800/08134412413';
    //while($row = mysql_fetch_array($result)){
           // $rowArray[$rowID] = $row['Sector'];
           // $rowID = $rowID +1;
       // }

        //Update the session with the sectors.
        $StuClass = getclassname($StuClassID);
    $_SESSION['AdmnNo']=$AdmnNo;
	$ExamId = '1';
     

    //mysql_close($connection);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- Mirrored from similestudios.com/GQP/ by HTTrack Website Copier/3.x [XR&CO'2010], Wed, 24 Aug 2011 18:55:15 GMT -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::. SkoolNET Manager Student/Parent Portal .::</title>
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../images/favicon.ico" rel="shortcut icon" />
<link href="css/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="jquery.html"></script>
    <script src="js/jquery-1.4.2.js" type="text/javascript"></script>
    <script type="text/javascript"> 
$(function(){
//drop
	$(".a_menu_drop").hover(
	  	function(){
			var thisDiv = $(this).find("div.submenu");
			if(thisDiv.is(":visible")){ return; }
			$('a:first',this).addClass('zelet');
			var thisPosition = $(this).position();
			var thisLeft = thisPosition.left;
			var thisChildTop = thisPosition.top+21;
				thisDiv.css({'left':thisLeft-'px','top':thisChildTop+'px','zIndex':1});
				if(!thisDiv.is(':visible')){
					thisDiv.slideDown(300).show();
				}
      	},function(){
			$('a:first',this).removeClass('zelet');
			$(this).find("div.submenu").slideUp(350);
    });
});
</script>
</head>


<body>
<div id="container">
	<div class="header">
		<div class="topmenu">
            <div id="nav">
                <div id="nav_menu"> 
            	<ul> 
                <li class="a_menu_drop"><a href="studentinfo.php">HOME</a> 
                    	<!--<div class="submenu ui-corner-bottom"> 
                        	<ul> 
                                <li><a href="kitchens.php"> Kitchens| Closets | Cabinets</a></li> 
                                <li><a href="consulting.php"> GQP Consulting</a></li> 
                                <li><a href="prof_customer.php"> Professionals</a></li> 
                                
							</ul> 
                        </div> -->
                    </li> 
                	<li class="a_menu_drop"><a href="viewstudentresult.php">MY EXAMINATION RESULT</a> 
                    	<!--<div class="submenu ui-corner-bottom"> 
                        	<ul> 
                                <li><a href="kitchens.php"> Kitchens| Closets | Cabinets</a></li> 
                                <li><a href="consulting.php"> GQP Consulting</a></li> 
                                <li><a href="prof_customer.php"> Professionals</a></li> 
                                
							</ul> 
                        </div> -->
                    </li> 
                    
                    <li class="a_menu_drop"><a href="viewstudentfee.php">MY FEES</a> 
                    	<!--<div class="submenu ui-corner-bottom"> 
                        	<ul>
                            	<li><a href="gqp_about.html"> About GQP</a></li> 
                                <li><a href="gqp_mission.html"> Mission Statement</a></li> 
                                <li><a href="gqp_references.html"> References</a></li> 
                                <li><a href="gqp_why.html"> Why GQP</a></li> 
                                
							</ul> 
                        </div> -->
                    </li> 
                    
                   <!-- <li class="a_menu_drop"><a href="#">MY ATTENDANCE</a>
                    	<!--<div class="submenu ui-corner-bottom"> 
                        	<ul>
                                <li><a href="gqp_way.html"> The G.Q.P Way</a></li>
                                <li><a href="gqp_quality.php"> Quality Assured</a></li>  
                                <li><a href="gqp_successful.html"> Tips to a successful fit</a></li> 
                                
                                
							</ul> 
                        </div>-->
                    </li> 
                    
                    <!--<li class="a_menu_drop"><a href="#">NEWS</a>-->
                    	
                </li> 
                    
                    <li class="a_menu_drop"><a href="gallery/gallery.html">GALLERY</a>
                    	
                    </li>
                                  
                </ul>
            </div>

        </div>
		</div>
	</div>
    <div class="clear"></div>

 <!-- <div class="banner"></div>  -->
  <div>
						<div class="projecttxt"><a href="kitchens.php"> </a></div>
						<div class="projecttxt-1"><a href="consulting.php"> </a></div>
						<div class="projecttxt-2"><a href="prof_customer.php"></a></div>
						<div class="clear"></div>
  </div>
	<div class="clear"></div>
	<div class="workzone"> 
      <div id="innerworkzone"> <p style="font-size:15px;font-weight:700"> <img src="images/<?PHP echo $StuPhoto ?>" width="50" height="50"> &nbsp;&nbsp;&nbsp;&nbsp;  <?PHP echo $StudentName;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CLASS: <?PHP echo $StuClass; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CLASS TEACHER: <?PHP echo $StuClassTeacher; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2011/2012 Session/1st Term</p> </div>
		<div class="workzone-left4"> 
          
		                <p> <table  width="97%" align="center" cellpadding="5" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 97%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">
						  <tbody>
							<tr>
							  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Subject</strong></font></td>
							  <td width="59%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Course Work</strong></font></td>
							  <td width="11%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Actual Score / <br>
						      Max Score </strong></font></td>
							  <td width="9%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong> (%) </strong></font></td>
							  <td width="7%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Grade</strong></font></td>
							</tr>
<?PHP
						$SumFinalMarks = 0;
						$SumMaxMarks = 0;
						$SumPercent = 0;
						$SumMaxPercent = 0;
						$query0 = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$StuClassID') order by Subj_Priority";
						$result0 = mysql_query($query0);
						$num_rows0 = mysql_num_rows($result0);
						if ($num_rows0 > 0 ) {
							while ($row0 = mysql_fetch_array($result0)) 
							{
								$subjID = $row0["ID"];
								$Subjname = $row0["Subj_name"];
								$FinalMarks = 0;
								$numrows = 0;
								$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$StuClassID' and SubjectId='$subjID' and ExamId='$ExamId'";
								$result4  = mysql_query($query4) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$Tot_TD = $row4['numrows'];
								$AvgWidth = 100/ ($Tot_TD+1);
								
								$i=0;
								$query3 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$StuClassID' and SubjectId='$subjID' and ExamId='$ExamId'";
								$result3 = mysql_query($query3);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$arr_Exam_Id[$i] = $row["ID"];
										$arr_Exam_Setup[$i][1] = $row["ResultType"];
										$arr_Exam_Setup[$i][2] = $row["Percentage"];
										$i = $i+1;
									}
								}
								$query = "select MaxMark from tbexammarkssetup where ClassID='$StuClassID' And ExamID='$ExamId' And SubjectID='$subjID'";
								$result = mysql_query($query);
								$dbarray = mysql_fetch_array($result);
								$MaxPercent  = $dbarray['MaxMark'];								
?>
								<tr>
								  <td width="14%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><b><?PHP echo $Subjname; ?></b></td>
								  <td width="59%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;">
								  	<table width="100%" border="0" align="right" cellpadding="3" cellspacing="3">
									  <tr>
<?PHP
										for($i=1;$i<=$Tot_TD;$i++)
										{
											echo "<TD width='$AvgWidth%' align='center' valign='top'  bgcolor='#f4f4f4'><strong><span class='style23'>".$arr_Exam_Setup[$i-1][1].' ('.$arr_Exam_Setup[$i-1][2].')'."</span></strong></TD>";
										}
?>
									  </tr>
									  <tr>
<?PHP
										$SubjMaxMark = 0;
										$sMaxMark = 0;
										for($i=1;$i<=$Tot_TD;$i++)
										{
											echo "<TD width='$AvgWidth%' align='center' valign='top' style='BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;'>";
											$query = "select Marks from tbstudentperformance where class_id = '$StuClassID' and AdmnNo = '$sAdmnNo' and ExamId = '$ExamId' And SubjectId = '$subjID' And ResultTypeId = '".$arr_Exam_Id[$i-1]."'";
											$result = mysql_query($query);
											$dbarray = mysql_fetch_array($result);
											$sMarks  = $dbarray['Marks'];
											$SubjMaxMark = $SubjMaxMark +$arr_Exam_Setup[$i-1][2];
											if($sMarks == 0){
												$sMarks = '';
											}
											if($sMarks !=""){
												$FinalMarks = $FinalMarks + $sMarks;
												$FinalPercentage = ($FinalMarks / $SubjMaxMark) * 100;
												$FinalPercentage = $FinalPercentage;
												echo $sMarks;//." / ".$arr_Exam_Setup[$i-1][2];
											}else{
												echo "-&nbsp;";
											}
											echo "</TD>";
										}
										$GradeName = "-";
										if($FinalMarks > 0){
											$query = "select GradeName from tbgradedetail where GradeFrom <='$FinalPercentage' And GradeTo >='$FinalPercentage'";
											$result = mysql_query($query);
											$dbarray = mysql_fetch_array($result);
											$GradeName  = $dbarray['GradeName'];
										}
										
										if($FinalMarks == 0){
											$sMaxMark = $SubjMaxMark;
											$SubjMaxMark =  " / ".$SubjMaxMark;
											$FinalMarks = "";
											$FinalPercentage = "";
										}else{
											$sMaxMark = $SubjMaxMark;
											$SubjMaxMark = " / ".$SubjMaxMark;
											$SumPercent = $SumPercent + $FinalPercentage;
											$SumMaxPercent = $SumMaxPercent +$MaxPercent; 
										}
										
?>
									  </tr>
								    </table>
								  </td>
								  <td width="11%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $FinalMarks; ?><?PHP echo $SubjMaxMark; ?></td>
								  <td width="9%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><strong><?PHP echo number_format($FinalPercentage,2); ?></strong></td>
								  <td width="7%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $GradeName; ?></td>
								</tr>
<?PHP
								$SumFinalMarks = $SumFinalMarks +$FinalMarks;
								$SumMaxMarks = $SumMaxMarks + $sMaxMark;
							}
						}
						$AvgActualMark= 0;
						//$AvgMark = ($SumPercent / $SumMaxPercent) * 100;
						if( $SumFinalMarks == 0){
							$AvgActualMark= 0;
						}else{
						$AvgActualMark = ($SumFinalMarks / $SumMaxMarks) * 100;
						}
						$SumMaxMarks  = " / ".$SumMaxMarks ;
						$query = "select GradeName from tbgradedetail where GradeFrom <='$AvgActualMark' And GradeTo >='$AvgActualMark'";
						$result = mysql_query($query);
						$dbarray = mysql_fetch_array($result);
						$GradeName  = $dbarray['GradeName'];
?>
						<tr>
						  <td width="14%" align="right">&nbsp;</td>
						  <td width="59%" align="right"><strong>Average Score</strong></td>
						  <td width="11%" align="center"><strong><?PHP echo $SumFinalMarks.$SumMaxMarks; ?></strong></td>
						  <td width="9%" align="center"><strong>
<?PHP 
						echo number_format($AvgActualMark,2);
						if($Method == "SMS"){
							$isSend_Status="False";
							//echo "/".$sAdmnNo.",".$Stu_Full_Name.",".GetExamName($ExamId).",".number_format($AvgMark,1).",".$Contact;
							//$isSend_Status = sendPerformanceOverall($sAdmnNo,$Stu_Full_Name,GetExamName($ExamId),number_format($AvgMark,1)."%".,$Contact);
							if($isSend_Status == "False"){
								echo "<meta http-equiv=\"Refresh\" content=\"0;url=performance.php?subpg=Student Result&res=error\">";
								exit;
							}elseif($isSend_Status == "True"){
								echo "<meta http-equiv=\"Refresh\" content=\"0;url=performance.php?subpg=Student Result&res=sent\">";
								exit;
							}	
						}
?>						  
						  % <strong> </td>
						  <td width="7%" align="center"><strong><?PHP echo $GradeName; ?></strong></td>
						</tr>
<?PHP
							$i=0;
							$query4 = "select AdmissionNo from tbstudentmaster where Stu_Class = '$StuClassID'";
							$result4 = mysql_query($query4);
							$num_rows4 = mysql_num_rows($result4);
							if ($num_rows4 > 0 ) {
								while ($row4 = mysql_fetch_array($result4)) 
								{
									$AdmnNo = $row4["AdmissionNo"];
									$stMarks = 0;
									$query1 = "select ID from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$StuClassID')";
									$result1 = mysql_query($query1);
									$num_rows1 = mysql_num_rows($result1);
									if ($num_rows1 > 0 ) {
										while ($row1 = mysql_fetch_array($result1)) 
										{
											$SubId = $row1["ID"];
											$query3 = "select Marks from tbstudentperformance where class_id='$StuClassID' and AdmnNo='$AdmnNo' and ExamId='$ExamId' and SubjectId ='$SubId'";
											
											$result3 = mysql_query($query3);
											$num_rows = mysql_num_rows($result3);
											if ($num_rows > 0 ) {
												while ($row = mysql_fetch_array($result3)) 
												{
													$stMarks = $stMarks + $row["Marks"];
													//echo $stMarks."&nbsp;&nbsp;&nbsp;&nbsp;";
												}
											}
										}
									}
									$arr_Class_Rank[$AdmnNo]= $stMarks;
									//$arr_Class_Rank[$i][2]= $AdmnNo;
									//$i = $i+1;
									//echo "<br>";
								}
							}
							$counter1 = 1;
							$FinalMarks_array[$counter1-1] = 0;
							arsort($arr_Class_Rank);
									foreach ($arr_Class_Rank as $key => $val) {
										$AdmnNo = $key;
										 $FinalMarks = $val;
										 $FinalMarks_array[$counter1] = $FinalMarks;
										 if($FinalMarks==$FinalMarks_array[$counter1-1])
										 {
											 $StudentRanking = $StudentRanking1;
										 }else{
                                       $StudentRanking = $counter1;
									     $StudentRanking1 = $StudentRanking;
										 }
										 $counter1 = $counter1+1;
										//$StudentRanking = $counter1;
										
										
										if($StudentRanking==1){
												$StudentRanking = "1st";
											}elseif($StudentRanking==2){
												$StudentRanking = "2nd";
											}elseif($StudentRanking==3){
												$StudentRanking = "3rd";
											}elseif($StudentRanking!="-"){
												$StudentRanking = $StudentRanking."th";
											}
									$arr_Class_Rank_Value[$AdmnNo]= $StudentRanking;
									}
?>
							<tr>
							  <td width="14%" align="right">&nbsp;</td>
							  <td width="59%" align="right"><strong>Student Ranking / Position in class</strong></td>
							  <td colspan="2" align="left"><div style="margin-left:20px;"><strong><?PHP echo $arr_Class_Rank_Value[$sAdmnNo]; ?></div></strong></td>
							  <td align="left">&nbsp;</td>
							</tr>
						</tbody>
						</table>
					  </td>
					</tr>
					<tr>
					  <td align="left" colspan="2">
					    <TABLE width="97%" align="center" cellpadding="7">
							<TBODY>
							<TR>
							  <TD width="11%"  align="left"><strong>Comment </strong></TD>
							  <TD width="89%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;"><p>&nbsp;</p>
						      <p>&nbsp;</p></TD>
							</TR>
							<TR>
							  <TD width="11%"  align="left">&nbsp;</TD>
							  <TD width="89%"  align="right" valign="top" ><p>________________________________</p>
							  <p style="margin-right:50px;">Authorised Signatory</p></TD>
							</TR>
							</TBODY>
						</TABLE>
			  </td></tr></p>
                        </div>
	<div class="clear"></div>
<div class="clear"></div>
      <div class="clear"></div>

  </div>
  <div class="clear"></div>
<div class="clear"></div>
 </div>



<div class="footer">
  <div class="footerinside">
    <div class="footerlink">
			<a href="index.php" style="color:#FFF; font-size:18px; text-decoration:underline" > Logout </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Copyright  © 2008-2011  SkoolNET Management System. All rights reserved.
			  <?PHP ?>
    </div>
  </div>
</div>
</body>

<!-- Mirrored from similestudios.com/GQP/ by HTTrack Website Copier/3.x [XR&CO'2010], Wed, 24 Aug 2011 18:55:50 GMT -->
</html>

