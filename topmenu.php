 <?PHP
	$query = "select UserprofileID from tbusermaster where UserName='$userNames'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$Sel_UserProfile  = $dbarray['UserprofileID'];
	//$Sel_UserProfile = 7;
	//echo $Sel_UserProfile;
	include 'proright.php';
?>
<script language="JavaScript">
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->
</script>
		<DIV>
				<UL id=nav>
  					<LI style=" <?PHP echo $mMS_0; ?>"><A title="System Setup" href="#">System Setup</A>
						<UL>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mMS_1; ?>"><A title="Application Setup" href="mas_setup.php?subpg=School Name and Logo Setup"><SPAN>School Name and Logo Setup</SPAN></A></LI> 
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mMS_2; ?>"><A title="City Master" href="mas_setup.php?subpg=City Master">City Master</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mMS_3; ?>"><A title="Document Master"  href="mas_setup.php?subpg=Document Master">Document Master</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mMS_4; ?>"><A title="Religion Master" href="mas_setup.php?subpg=Religion Master">Religion Master</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mMS_5; ?>"><A title="School Charges" href="mas_setup.php?subpg=School Charges">School Charges</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mMS_6; ?>"><A title="Class Master" href="mas_setup2.php?subpg=Class Master">Class Master</A> </LI>
							
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mMS_8; ?>"><A title="Session" href="session.php?subpg=Create New Session">Session</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Create New Session" href="session.php?subpg=Create New Session">Create New Session</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Change Session" href="session.php?subpg=Change Active Session">Change Active Session</A> </LI>
								</UL>
						  </LI>
						  <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mMS_9; ?>"><A title="Create New Session" href="session.php?subpg=Change Active Term">Change Active Term</A> </LI>
						</UL>
				 	</LI>
					<LI style="<?PHP echo $mAd_0; ?>"><A title="Admission"  href="#"><SPAN>Admission</SPAN></A> 
						<UL>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mAd_1; ?>"><A title="Enquiry" href="enquiry.php?subpg=Enquiry">Enquiry</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mAd_2; ?>"><A title="Registration" href="registration.php?subpg=Registration">Registration</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mAd_3; ?>"><A title="Admission" href="registration.php?subpg=Admit Registered Student">Admit Registered Student</A> </LI>
							
							
						</UL>
					</LI>
                    <LI style="<?PHP echo $mAd_0; ?>"><A title="Admission"  href="#"><SPAN>Students</SPAN></A>
                    <UL>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mSt_1; ?>"><A title="Enquiry" href="uploadstudentphoto.php?subpg=Select Student To Upload Picture">Upload Student Photo</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mSt_2; ?>"><A title="Registration" href="viewstudentinfo.php?subpg=View Student Info">View Student Info</A> </LI>
							
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mSt_3; ?>"><A title="Edit Student Info" href="admission.php?subpg=Edit Student Info">Edit Student Info</A> </LI>
							
						</UL>
					</LI> 
					<LI style="<?PHP echo $mCls_0; ?>"><A title="Class Work"  href="#"><SPAN>Class Activities</SPAN></A> 
						<UL>
                            <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mCls_1; ?>"><A title="Promote Class Student" href="classactivities.php?subpg=Promote Class Student">Promote Class Student</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mCls_1; ?>"><A title="Submit Class Note" href="">View Class Student</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mCls_2; ?>"><A title="Find Cass Student" href="classactivities.php?subpg=Daily Class Activities">Daily Class Activities</A> </LI>
							<!--<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mCls_3; ?>"><A title="Email Class" href="classwrk.php?subpg=Email Class">Email Class</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mCls_4; ?>"><A title="Student Post" href="classwrk.php?subpg=Student Post">Student Post (Blog)</A> </LI>-->
						</UL>
					</LI>
					<LI style="<?PHP echo $mEx_0; ?>"><A title="Examination"  href="#"><SPAN>Examination</SPAN></A> 
						<UL>
							<LI style="background: url(images/menu_arrow.gif);"><A title="Exam Setup" href="#">Exam Setup</A> 
								<UL>
								    <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_1; ?>"><A title="Examination Master" href="exam.php?subpg=Examination Master">Examination Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_2; ?>"><A title="Manage Weighted Result" href="exam2.php?subpg=Manage Weighted Result">Manage Weighted Result</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_3; ?>"><A title="Grade Master" href="exam.php?subpg=Grade master">Grade Master</A> </LI>
								</UL>
							</LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_4; ?>"><A title="Mark Setup" href="exam2.php?subpg=Class Subject Mark Setup">Class Subject Mark Setup</A> </LI>
                            <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_5; ?>"><A title="Student Performance" href="updatestudentresult.php?subpg=Update Student Result">Update Student Result</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_5; ?>"><A title="Student Performance" href="performance.php?subpg=Student Performance">Student Performance</A> </LI>
							<!--<LI style="background: url(images/menu_arrow.gif);"><A title="Progress Header" href="#">Progress Header Setup</A> 
								<UL>
								    <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_6; ?>"><A title="Progress Header" href="progress.php?subpg=Progress Header">Progress Header</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_7; ?>"><A title="Progress Sub Header Setup" href="progress.php?subpg=Progress Sub Header">Progress Sub Header Setup</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_8; ?>"><A title="Progress Skills Setup" href="progress.php?subpg=Progress Skills">Progress Skills Setup</A> </LI>
								</UL>
							</LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mEx_9; ?>"><A title="Student Progress Report" href="progress.php?subpg=Student Progress Report">Student Progress Report</A> </LI>-->
                            
						</UL>
					</LI>
					<LI style="<?PHP echo $mfee_0; ?>"><A title="Fees"  href="#"><SPAN>Fees</SPAN></A> 
						<UL>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mfee_1; ?>"><A title="Charge Detail Masters" href="mas_setup2.php?subpg=Hosteller and Day Scholar Charges">School Charges Master</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mfee_2; ?>"><A title="Update Student Fee" href="fees.php?subpg=Update Student Fee">Update Student Fee</A> </LI>
							
						</UL>
					</LI>
					<LI style="<?PHP echo $mLib_0; ?>"><A title="Library"  href="#"><SPAN>Library</SPAN></A> 
						<UL>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mLib_1; ?>"><A title="Library Setup" href="#">Library Setup</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Category Master" href="librarysetup.php?subpg=Category Master">Category Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Sub Category Master " href="librarysetup.php?subpg=Sub Category Master">Sub Category Master </A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Binding Master" href="librarysetup.php?subpg=Binding Master">Binding Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Country Master" href="librarysetup.php?subpg=Country Master">Country Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Author Master" href="librarysetup.php?subpg=Author Master">Author Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Publisher Master" href="librarysetup.php?subpg=Publisher Master">Publisher Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Publication Place Master" href="librarysetup.php?subpg=Publication Place Master">Publication Place Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Language" href="librarysetup.php?subpg=Language">Language</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Supplier Master" href="librarysetup.php?subpg=Supplier Master">Supplier Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Book Condition" href="librarysetup.php?subpg=Book Condition">Book Condition</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Series Master" href="librarysetup.php?subpg=Series Master">Series Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Currency Master" href="librarysetup.php?subpg=Currency Master">Currency Master</A> </LI>
									
								</UL>
							</LI>
                            <LI style="background: url(images/menu_No_arrow.gif);"><A title="Book Master" href="library.php?subpg=Book Master">Book Master</A> </LI>
							<!--<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mLib_2; ?>"><A title="Library Fine Policy" href="library.php?subpg=Library Fine Policy">Library Fine Policy</A> </LI>-->
                            <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mLib_2; ?>"><A title="Issue A Book" href="librarybookaction.php?subpg=Issue A Book">Issue A Book</A> </LI>
                            <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mLib_2; ?>"><A title="Library Fine Policy" href="librarybookaction.php?subpg=Receive A Book">Receive A Book</A> </LI>
							<!--<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mLib_3; ?>"><A title="Library Setup" href="#">Book Issued</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Book Issued to Student Log" href="librarybook.php?subpg=Book Issued to Student Log">Book Issued to Student Log</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Book Issued to Employee Log" href="librarybook.php?subpg=Book Issued to Employee Log">Book Issued to Employee Log</A> </LI>
								</UL>
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mLib_4; ?>"><A title="Library Setup" href="#">Book Returned</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Book Returned from Student Log" href="librarybook.php?subpg=Book Returned from Student Log">Book Returned from Student Log</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);"><A title="Book Returned from Employee Log" href="librarybook.php?subpg=Book Returned from Employee Log">Book Returned from Employee Log</A> </LI>
								</UL>
							</LI>-->
						</UL>
					</LI>
                    <LI style="<?PHP echo $mPR_0; ?>"><A title="Pay Roll"  href="#"><SPAN>Pay Roll</SPAN></A> 
						<UL>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mPR_1; ?>"><A title="Setup" href="#">Setup</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Department Master" href="payroll.php?subpg=Department Master">Department Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Designation Master" href="payroll.php?subpg=Designation Master">Designation Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Employee Category" href="payroll.php?subpg=Employee Category">Employee Category</A> </LI>
								</UL>
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mPR_2; ?>"><A title="Employee Master" href="#">Employee Master</A> 
                            <UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Department Master" href="payroll.php?subpg=Employee Master">Update New Employee Details</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Designation Master" href="payroll.php?subpg=Upload Employee Picture">Upload Employee Picture</A> </LI>
                                    <LI style="background: url(images/menu_No_arrow.gif);"><A title="Designation Master" href="payroll.php?subpg=View Employee Info">View Employee Info</A> </LI>
                                     <LI style="background: url(images/menu_No_arrow.gif);"><A title="Designation Master" href="payroll.php?subpg=Edit Employee Info">Edit Employee Info</A> </LI>
									
								</UL></LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mPR_3; ?>"><A title="Allowance Category" href="#">Allowance Setup</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Allowance Category" href="allowance.php?subpg=Allowance Category">Allowance Category</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Allowance Master" href="allowance.php?subpg=Allowance Master">Allowance Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Allowance Setup" href="allowance.php?subpg=Allowance Setup">Allowance Setup</A> </LI>
								</UL>
							</LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mPR_4; ?>"><A title="Salary Detail" href="salary.php?subpg=Salary%20Detail">Salary Detail</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mPR_5; ?>"><A title="Generate Salary" href="salary.php?subpg=Generate%20Salary">Generate Salary</A> </LI>
						</UL>
					</LI>
					<LI style="<?PHP echo $mTT_0; ?>"><A title="Time Table"  href="#"><SPAN>Time Table</SPAN></A> 
						<UL>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mTT_1; ?>"><A title="Subject Master" href="#">Subject Setup</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Subject Master" href="subject.php?subpg=Subject Master">Subject Master</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title=">Class Subject" href="subject.php?subpg=Class Subject">Class Subject</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Teacher Subject" href="subject.php?subpg=Teacher Subject">Teacher Subject</A> </LI>
								</UL>
							</LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mTT_2; ?>"><A title="Room Master" href="timetable.php?subpg=Room Master">Room Master</A> </LI>
							
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mTT_4; ?>"><A title="Create time table" href="timetable.php?subpg=Edit%20Class%20Time%20Table">Edit time table</A> </LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mTT_5; ?>"><A title="Create time table" href="#">View time table</A> 
								<UL>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Class timetable" href="timetable.php?subpg=Class%20time%20table">Class Timetable</A> </LI>
									<LI style="background: url(images/menu_No_arrow.gif);"><A title="Teacher timetable" href="teacherTT.php?subpg=Teacher%20time%20table">Teacher Timetable</A> </LI>
								</UL>
							</LI>
							
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mTT_7; ?>"><A title="Teachers Duty Master" href="teacherTT.php?subpg=Teachers Duty Master">Teachers Duty Master</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mTT_8; ?>"><A title="Assign Teachers Duty" href="teacherTT.php?subpg=Assign Teachers Duty">Assign Teachers Duty</A> </LI>
						</UL>
					</LI>
					<LI style="<?PHP echo $mAtt_0; ?>"><A title="Attendance"  href="#"><SPAN>Attendance</SPAN></A> 
						<UL>
						    <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mAtt_1; ?>"><A title="Lecture attendance" href="attendance.php?subpg=Lecture attendance">Lecture attendance</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mAtt_2; ?>"><A title="Students" href="attendance.php?subpg=Class Daily Attendance">Class Daily Attendance</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mAtt_3; ?>"><A title="Employee" href="attendance.php?subpg=Employee attendance">Employee</A> </LI>
						</UL>
					</LI>
					
					<LI style="<?PHP echo $mRpt_0; ?>"><A title="Report Center"  href="#"><SPAN>Report Center</SPAN></A> 
						<UL>
						    <LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_1; ?>"><A title="Fees" href="fees.php?subpg=Fee Defaulter Details">Fees</A> </LI>
								
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_2; ?>"><A title="Library" href="libreport.php?subpg=Books in Library">Library</A> 
								
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_3; ?>"><A title="Examination" href="performance.php?subpg=Subject Report">Examination</A> 
								
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_4; ?>"><A title="Attendance" href="attreport.php?subpg=Student attendance details">Attendance</A> 
								
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_5; ?>"><A title="Class" href="classreport.php?subpg=Class charges">Class</A> 
								
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_6; ?>"><A title="Student" href="studreport.php?subpg=Student Details">Student</A> 
								
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_7; ?>"><A title="Employee" href="empreport.php?subpg=Department And Designation Wise details">Employee</A> 
								
							</LI>
						<!--	<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_8; ?>"><A title="Hostel" href="hostreport.php?subpg=Roll Call">Hostel</A> 
								
							</LI>
							<LI style="background: url(images/menu_arrow.gif);<?PHP echo $mRpt_9; ?>"><A title="Clinic Details" href="hostreport.php?subpg=Clinic Details">Clinic Details</A></LI>-->
						</UL>
					</LI>
					<LI style="<?PHP echo $mUti_0; ?>"><A title="Utilities"  href="#"><SPAN>Utilities</SPAN></A> 
						<UL>
						    <LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_1; ?>"><a title="Pop Ups" 
	href="JavaScript: newWindow = openWin('popup.php?pg=Send Message', '', 'width=370,height=220,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()">Pop Ups</a></LI>
							<!--<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_2; ?>"><A title="Event Calendar" href="utilities.php?subpg=Event Calendar">Event Calendar</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_3; ?>"><A title="Remarks" href="remark.php?subpg=Employee Remarks">Remarks</A> 
							</LI>-->
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_4; ?>"><A title="User Profile" href="utilities.php?subpg=User Profile">User Profile</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_5; ?>"><A title="Profile Right" href="utilities.php?subpg=Profile Right">Profile Right</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_6; ?>"><A title="User master" href="utilities.php?subpg=User master">User master</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_7; ?>"><A title="Change Password" href="utilities.php?subpg=Change Password">Change Password</A> </LI>
							<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_8; ?>"><A title="Hostel Management" href="expensemodule/index.php?subpg=Expense Management">Expense Management</A> </LI>
						  <!--<LI style="background: url(images/menu_No_arrow.gif);<?PHP echo $mUti_9; ?>"><A title="Clinic Management" href="clinic.php?subpg=Clinic Management">Clinic Management</A> </LI>-->
						</UL>
					</LI>
				</UL>
			</DIV>