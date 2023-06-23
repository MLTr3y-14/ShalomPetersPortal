<DIV id="homeDiv"  style="background:#999999">
  <UL id="homeList">
	<LI><A href="welcome.php?pg=Student Blog"><strong>Home</strong></A></LI>
  </UL>
</DIV>
<DIV id="navDiv" style="background:#999999">
	<UL class="menu">
	  	<LI class="item1"><A href="welcome.php?pg=Student Blog"><SPAN><strong>Home</strong></SPAN></A></LI>
		<LI class="parent item27"><A href="http://www.nexzoninvestment.com/" target=_blank><strong>About Us</strong></A></LI>
		<LI class="parent item2"><A href="#"><strong>eLearning</strong></A>
			<UL>
				<LI class="parent item254"><A href="#"><SPAN>Courses / Subject List</SPAN></A>
					<UL>
<?PHP
						$query = "select SubjectId from tbclasssubjectrelation where ClassId = '$StuClass'";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$SubjectId = $row["SubjectId"];
								$SubjectName = GetSubjectName($row["SubjectId"]);
?>
								<LI class="item354"><A href="subject.php?subjID=<?PHP echo $SubjectId; ?>"><SPAN><?PHP echo $SubjectName; ?></SPAN></A></LI>
<?PHP
							}
						}
?>
					</UL>
				</LI>
				<LI class="parent item255"><A href="timetable.php"><SPAN>Timetable</SPAN></A>
					<UL>
						<LI class="item366"><A href="timetable.php"><SPAN>Class Timetable</SPAN></A></LI>
						<LI class="item365"><A href="#"><SPAN>Session Key Date</SPAN></A></LI>
					</UL>
				</LI>
				<LI class="parent item305"><A href="#"><SPAN>Study Resources</SPAN></A>
					<UL>
						<LI class="item348"><A href="welcome.php?pg=Student Blog"><SPAN>Student Blog</SPAN></A></LI>
						<LI class="item349"><A href="classwrk.php?pg=Find Student"><SPAN>Find Course Mate</SPAN></A></LI>
						<LI class="item350"><A href="classwrk.php?pg=Email Class"><SPAN>Email Class</SPAN></A></LI>
						<LI class="item350"><A href="classwrk.php?pg=Submit Class Note"><SPAN>Download Lecture Note</SPAN></A></LI>
					</UL>
				</LI>
			</UL>
	  </LI>
		<LI class="parent item308"><A href="payment.php"><strong>Fees</strong></A>
			<UL>
				<LI class="item311"><A href="payment.php"><SPAN>Payment Receipt</SPAN></A></LI>
				<LI class="item313"><A href="receiptPDF.php?admNo=<?PHP echo $AdmissionNo; ?>" target="_blank"><SPAN>Generate pdf formate of your receipt</SPAN></A></LI>
			</UL>
	  </LI>
		<LI class="parent item308"><A href="result.php?pg=Student Result"><strong>Result</strong></A>
			<UL>
				<LI class="item311"><A href="result.php?pg=Student Result"><SPAN>View Overall Result</SPAN></A></LI>
				<LI class="item313"><A href="result.php?pg=Students attendance"><SPAN>View Attendance Result</SPAN></A></LI>
			</UL>
	  </LI>
		<LI class="parent item309"><A href="#"><strong>Account</strong></A>
			<UL>
				<LI class="item311"><A href="myaccount.php?pg=My Details"><SPAN>My Profile</SPAN></A></LI>
				<LI class="item313"><A href="AdmissionPDF.php?admNo=<?PHP echo $AdmissionNo; ?>" target="_blank"><SPAN>Download my admission records</SPAN></A></LI>
				<LI class="item315"><A href="myaccount.php?pg=Change Password"><SPAN>Manage my SOLS Password</SPAN></A></LI>
			</UL>
	  </LI>
		<LI class="parent item309"><A href="#"><strong>Online Help</strong></A>
			<UL>
				<LI class="item311"><A href="#"><SPAN>Download userguide</SPAN></A></LI>
				<LI class="item313"><A href="#"><SPAN>Contact Us</SPAN></A></LI>
			</UL>
	  </LI>
		<LI class="item523"><strong><A href="#">Logout</A></strong></LI>
	</UL>
</DIV>