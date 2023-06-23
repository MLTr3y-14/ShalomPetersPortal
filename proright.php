<?PHP
	$mMS_0 = "display:none";
	$mMS_1 = "display:none";
	$mMS_2 = "display:none";
	$mMS_3 = "display:none";
	$mMS_4 = "display:none";
	$mMS_5 = "display:none";
	$mMS_6 = "display:none";
	$mMS_7 = "display:none"; 
	$mMS_8 = "display:none";
	$mMS_9 = "display:none";
	
	$mAd_0 = "display:none";
	$mAd_1 = "display:none";
	$mAd_2 = "display:none";
	$mAd_3 = "display:none";
	$mAd_4 = "display:none";
	$mAd_5 = "display:none";
	
	$mEx_0 = "display:none";
	$mEx_1 = "display:none";
	$mEx_2 = "display:none";
	$mEx_3 = "display:none";
	$mEx_4 = "display:none";
	$mEx_5 = "display:none";
	$mEx_6 = "display:none";
	$mEx_7 = "display:none";
	$mEx_8 = "display:none";
	$mEx_9 = "display:none";
	
	$mfee_0 = "display:none";
	$mfee_1 = "display:none";
	$mfee_2 = "display:none";
	$mfee_3 = "display:none";
	
	$mLib_0 = "display:none";
	$mLib_1 = "display:none";
	$mLib_2 = "display:none";
	$mLib_3 = "display:none";
	
	$mTT_0 = "display:none";
	$mTT_1 = "display:none";
	$mTT_2 = "display:none";
	$mTT_3 = "display:none";
	$mTT_4 = "display:none";
	$mTT_5 = "display:none";
	$mTT_6 = "display:none";
	$mTT_7 = "display:none";
	$mTT_8 = "display:none";
	
	$mAtt_0 = "display:none";
	$mAtt_1 = "display:none";
	$mAtt_2 = "display:none";
	$mAtt_3 = "display:none";
	
	$mPR_0 = "display:none";
	$mPR_1 = "display:none";
	$mPR_2 = "display:none";
	$mPR_3 = "display:none";
	$mPR_4 = "display:none";
	$mPR_5 = "display:none";
	
	$mRpt_0 = "display:none";
	$mRpt_1 = "display:none";
	$mRpt_2 = "display:none";
	$mRpt_3 = "display:none";
	$mRpt_4 = "display:none";
	$mRpt_5 = "display:none";
	$mRpt_6 = "display:none";
	$mRpt_7 = "display:none";
	
	$mUti_0 = "display:none";
	$mUti_1 = "display:none";
	$mUti_2 = "display:none";
	$mUti_3 = "display:none";
	$mUti_4 = "display:none";
	$mUti_5 = "display:none";
	$mUti_6 = "display:none";
	$mUti_7 = "display:none";
	$mUti_8 = "display:none";
	$mUti_9 = "display:none";
	//$mUti_7 = "visibility:hidden";
	
	$query = "select * from tbseluserprofile where UserProfileID='$Sel_UserProfile'";
	$result = mysql_query($query,$conn);
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0 ) {
		while ($row = mysql_fetch_array($result)) 
		{
			if($row["Profile_right"] == "MS_0"){$mMS_0 = "";}
			if($row["Profile_right"] == "MS_1"){$mMS_1 = "";}
			if($row["Profile_right"] == "MS_2"){$mMS_2 = "";}
			if($row["Profile_right"] == "MS_3"){$mMS_3 = "";}
			if($row["Profile_right"] == "MS_4"){$mMS_4 = "";}
			if($row["Profile_right"] == "MS_5"){$mMS_5 = "";}
			if($row["Profile_right"] == "MS_6"){$mMS_6 = "";}
			if($row["Profile_right"] == "MS_7"){$mMS_7 = "";}
			if($row["Profile_right"] == "MS_8"){$mMS_8 = "";}
			if($row["Profile_right"] == "MS_9"){$mMS_9 = "";}
			
			if($row["Profile_right"] == "Ad_0"){$mAd_0 = "";}
			if($row["Profile_right"] == "Ad_1"){$mAd_1 = "";}
			if($row["Profile_right"] == "Ad_2"){$mAd_2 = "";}
			if($row["Profile_right"] == "Ad_3"){$mAd_3 = "";}
			if($row["Profile_right"] == "Ad_4"){$mAd_4 = "";}
			if($row["Profile_right"] == "Ad_5"){$mAd_5 = "";}

			
			if($row["Profile_right"] == "Ex_0"){$mEx_0 = "";}
			if($row["Profile_right"] == "Ex_1"){$mEx_1 = "";}
			if($row["Profile_right"] == "Ex_2"){$mEx_2 = "";}
			if($row["Profile_right"] == "Ex_3"){$mEx_3 = "";}
			if($row["Profile_right"] == "Ex_4"){$mEx_4 = "";}
			if($row["Profile_right"] == "Ex_5"){$mEx_5 = "";}
			if($row["Profile_right"] == "Ex_6"){$mEx_6 = "";}
			if($row["Profile_right"] == "Ex_7"){$mEx_7 = "";}
			if($row["Profile_right"] == "Ex_8"){$mEx_8 = "";}
			if($row["Profile_right"] == "Ex_9"){$mEx_9 = "";}
			
			if($row["Profile_right"] == "fee_0"){$mfee_0 = "";}
			if($row["Profile_right"] == "fee_1"){$mfee_1 = "";}
			if($row["Profile_right"] == "fee_2"){$mfee_2 = "";}
			if($row["Profile_right"] == "fee_3"){$mfee_3 = "";}
			
			if($row["Profile_right"] == "Lib_0"){$mLib_0 = "";}
			if($row["Profile_right"] == "Lib_1"){$mLib_1 = "";}
			if($row["Profile_right"] == "Lib_2"){$mLib_2 = "";}
			if($row["Profile_right"] == "Lib_3"){$mLib_3 = "";}
			if($row["Profile_right"] == "Lib_4"){$mLib_4 = "";}
			
			if($row["Profile_right"] == "TT_0"){$mTT_0 = "";}
			if($row["Profile_right"] == "TT_1"){$mTT_1 = "";}
			if($row["Profile_right"] == "TT_2"){$mTT_2 = "";}
			if($row["Profile_right"] == "TT_3"){$mTT_3 = "";}
			if($row["Profile_right"] == "TT_4"){$mTT_4 = "";}
			if($row["Profile_right"] == "TT_5"){$mTT_5 = "";}
			if($row["Profile_right"] == "TT_6"){$mTT_6 = "";}
			if($row["Profile_right"] == "TT_7"){$mTT_7 = "";}
			if($row["Profile_right"] == "TT_8"){$mTT_8 = "";}
			
			if($row["Profile_right"] == "Att_0"){$mAtt_0 = "";}
			if($row["Profile_right"] == "Att_1"){$mAtt_1 = "";}
			if($row["Profile_right"] == "Att_2"){$mAtt_2 = "";}
			if($row["Profile_right"] == "Att_3"){$mAtt_3 = "";}
	
			if($row["Profile_right"] == "PR_0"){$mPR_0 = "";}
			if($row["Profile_right"] == "PR_1"){$mPR_1 = "";}
			if($row["Profile_right"] == "PR_2"){$mPR_2 = "";}
			if($row["Profile_right"] == "PR_3"){$mPR_3 = "";}
			if($row["Profile_right"] == "PR_4"){$mPR_4 = "";}
			if($row["Profile_right"] == "PR_5"){$mPR_5 = "";}
			
			if($row["Profile_right"] == "Rpt_0"){$mRpt_0 = "";}
			if($row["Profile_right"] == "Rpt_1"){$mRpt_1 = "";}
			if($row["Profile_right"] == "Rpt_2"){$mRpt_2 = "";}
			if($row["Profile_right"] == "Rpt_3"){$mRpt_3 = "";}
			if($row["Profile_right"] == "Rpt_4"){$mRpt_4 = "";}
			if($row["Profile_right"] == "Rpt_5"){$mRpt_5 = "";}
			if($row["Profile_right"] == "Rpt_6"){$mRpt_6 = "";}
			if($row["Profile_right"] == "Rpt_7"){$mRpt_7 = "";}
			
			if($row["Profile_right"] == "Uti_0"){$mUti_0 = "";}
			if($row["Profile_right"] == "Uti_1"){$mUti_1 = "";}
			if($row["Profile_right"] == "Uti_2"){$mUti_2 = "";}
			if($row["Profile_right"] == "Uti_3"){$mUti_3 = "";}
			if($row["Profile_right"] == "Uti_4"){$mUti_4 = "";}
			if($row["Profile_right"] == "Uti_5"){$mUti_5 = "";}
			if($row["Profile_right"] == "Uti_6"){$mUti_6 = "";}
			if($row["Profile_right"] == "Uti_7"){$mUti_7 = "";}
			if($row["Profile_right"] == "Uti_8"){$mUti_8 = "";}
			if($row["Profile_right"] == "Uti_9"){$mUti_9 = "";}
		}
	}
	if(isset($_GET['PR_id']))
	{
		$SelProfileID = $_GET['PR_id'];
		$query1= "select Profile_Name from tbuserprofile where ID='$SelProfileID'";
		$result1 = mysql_query($query1,$conn);
		$dbarray1 = mysql_fetch_array($result1);
		$SelProName = $dbarray1['Profile_Name'];
		
		$query = "select * from tbseluserprofile where UserProfileID='$SelProfileID'";
		$result = mysql_query($query,$conn);
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result)) 
			{
				if($row["Profile_right"] == "MS_0"){$MS_0 = "checked=checked";}
				if($row["Profile_right"] == "MS_1"){$MS_1 = "checked=checked";}
				if($row["Profile_right"] == "MS_2"){$MS_2 = "checked=checked";}
				if($row["Profile_right"] == "MS_3"){$MS_3 = "checked=checked";}
				if($row["Profile_right"] == "MS_4"){$MS_4 = "checked=checked";}
				if($row["Profile_right"] == "MS_5"){$MS_5 = "checked=checked";}
				if($row["Profile_right"] == "MS_6"){$MS_6 = "checked=checked";}
				if($row["Profile_right"] == "MS_7"){$MS_7 = "checked=checked";}
				if($row["Profile_right"] == "MS_8"){$MS_8 = "checked=checked";}
				if($row["Profile_right"] == "MS_9"){$MS_9 = "checked=checked";}
				
				if($row["Profile_right"] == "Ad_0"){$Ad_0 = "checked=checked";}
				if($row["Profile_right"] == "Ad_1"){$Ad_1 = "checked=checked";}
				if($row["Profile_right"] == "Ad_2"){$Ad_2 = "checked=checked";}
				if($row["Profile_right"] == "Ad_3"){$Ad_3 = "checked=checked";}
				if($row["Profile_right"] == "Ad_4"){$Ad_4 = "checked=checked";}
				if($row["Profile_right"] == "Ad_5"){$Ad_5 = "checked=checked";}

				
				if($row["Profile_right"] == "Ex_0"){$Ex_0 = "checked=checked";}
				if($row["Profile_right"] == "Ex_1"){$Ex_1 = "checked=checked";}
				if($row["Profile_right"] == "Ex_2"){$Ex_2 = "checked=checked";}
				if($row["Profile_right"] == "Ex_3"){$Ex_3 = "checked=checked";}
				if($row["Profile_right"] == "Ex_4"){$Ex_4 = "checked=checked";}
				if($row["Profile_right"] == "Ex_5"){$Ex_5 = "checked=checked";}
				if($row["Profile_right"] == "Ex_6"){$Ex_6 = "checked=checked";}
				if($row["Profile_right"] == "Ex_7"){$Ex_7 = "checked=checked";}
				if($row["Profile_right"] == "Ex_8"){$Ex_8 = "checked=checked";}
				if($row["Profile_right"] == "Ex_9"){$Ex_9 = "checked=checked";}
				
				if($row["Profile_right"] == "fee_0"){$fee_0 = "checked=checked";}
				if($row["Profile_right"] == "fee_1"){$fee_1 = "checked=checked";}
				if($row["Profile_right"] == "fee_2"){$fee_2 = "checked=checked";}
				if($row["Profile_right"] == "fee_3"){$fee_3 = "checked=checked";}
				
				if($row["Profile_right"] == "Lib_0"){$Lib_0 = "checked=checked";}
				if($row["Profile_right"] == "Lib_1"){$Lib_1 = "checked=checked";}
				if($row["Profile_right"] == "Lib_2"){$Lib_2 = "checked=checked";}
				if($row["Profile_right"] == "Lib_3"){$Lib_3 = "checked=checked";}
				if($row["Profile_right"] == "Lib_4"){$Lib_4 = "checked=checked";}
				
				if($row["Profile_right"] == "TT_0"){$TT_0 = "checked=checked";}
				if($row["Profile_right"] == "TT_1"){$TT_1 = "checked=checked";}
				if($row["Profile_right"] == "TT_2"){$TT_2 = "checked=checked";}
				if($row["Profile_right"] == "TT_3"){$TT_3 = "checked=checked";}
				if($row["Profile_right"] == "TT_4"){$TT_4 = "checked=checked";}
				if($row["Profile_right"] == "TT_5"){$TT_5 = "checked=checked";}
				if($row["Profile_right"] == "TT_6"){$TT_6 = "checked=checked";}
				if($row["Profile_right"] == "TT_7"){$TT_7 = "checked=checked";}
				if($row["Profile_right"] == "TT_8"){$TT_8 = "checked=checked";}
				
				if($row["Profile_right"] == "Att_0"){$Att_0 = "checked=checked";}
				if($row["Profile_right"] == "Att_1"){$Att_1 = "checked=checked";}
				if($row["Profile_right"] == "Att_2"){$Att_2 = "checked=checked";}
				if($row["Profile_right"] == "Att_3"){$Att_3 = "checked=checked";}
				
				if($row["Profile_right"] == "PR_0"){$PR_0 = "checked=checked";}
				if($row["Profile_right"] == "PR_1"){$PR_1 = "checked=checked";}
				if($row["Profile_right"] == "PR_2"){$PR_2 = "checked=checked";}
				if($row["Profile_right"] == "PR_3"){$PR_3 = "checked=checked";}
				if($row["Profile_right"] == "PR_4"){$PR_4 = "checked=checked";}
				if($row["Profile_right"] == "PR_5"){$PR_5 = "checked=checked";}
				
				if($row["Profile_right"] == "Rpt_0"){$Rpt_0 = "checked=checked";}
				if($row["Profile_right"] == "Rpt_1"){$Rpt_1 = "checked=checked";}
				if($row["Profile_right"] == "Rpt_2"){$Rpt_2 = "checked=checked";}
				if($row["Profile_right"] == "Rpt_3"){$Rpt_3 = "checked=checked";}
				if($row["Profile_right"] == "Rpt_4"){$Rpt_4 = "checked=checked";}
				if($row["Profile_right"] == "Rpt_5"){$Rpt_5 = "checked=checked";}
				if($row["Profile_right"] == "Rpt_6"){$Rpt_6 = "checked=checked";}
				if($row["Profile_right"] == "Rpt_7"){$Rpt_7 = "checked=checked";}
				
				if($row["Profile_right"] == "Uti_0"){$Uti_0 = "checked=checked";}
				if($row["Profile_right"] == "Uti_1"){$Uti_1 = "checked=checked";}
				if($row["Profile_right"] == "Uti_2"){$Uti_2 = "checked=checked";}
				if($row["Profile_right"] == "Uti_3"){$Uti_3 = "checked=checked";}
				if($row["Profile_right"] == "Uti_4"){$Uti_4 = "checked=checked";}
				if($row["Profile_right"] == "Uti_5"){$Uti_5 = "checked=checked";}
				if($row["Profile_right"] == "Uti_6"){$Uti_6 = "checked=checked";}
				if($row["Profile_right"] == "Uti_7"){$Uti_7 = "checked=checked";}
				if($row["Profile_right"] == "Uti_8"){$Uti_8 = "checked=checked";}
				if($row["Profile_right"] == "Uti_9"){$Uti_9 = "checked=checked";}
			}
		}
	}
	
	
	if(isset($_POST['SelTick']))
	{
		$SelProfileID = $_POST['Sel_Profile'];
		$MS_0 = "checked=checked";
		$MS_1 = "checked=checked";
		$MS_2 = "checked=checked";
		$MS_3 = "checked=checked";
		$MS_4 = "checked=checked";
		$MS_5 = "checked=checked";
		$MS_6 = "checked=checked";
		$MS_7 = "checked=checked";
		$MS_8 = "checked=checked";
		$MS_9 = "checked=checked";
		
		$Ad_0 = "checked=checked";
		$Ad_1 = "checked=checked";
		$Ad_2 = "checked=checked";
		$Ad_3 = "checked=checked";
		$Ad_4 = "checked=checked";
		$Ad_5 = "checked=checked";

		$Ex_0 = "checked=checked";
		$Ex_1 = "checked=checked";
		$Ex_2 = "checked=checked";
		$Ex_3 = "checked=checked";
		$Ex_4 = "checked=checked";
		$Ex_5 = "checked=checked";
		$Ex_6 = "checked=checked";
		$Ex_7 = "checked=checked";
		$Ex_8 = "checked=checked";
		$Ex_9 = "checked=checked";
		
		$fee_0 = "checked=checked";
		$fee_1 = "checked=checked";
		$fee_2 = "checked=checked";
		$fee_3 = "checked=checked";
		
		$Lib_0 = "checked=checked";
		$Lib_1 = "checked=checked";
		$Lib_2 = "checked=checked";
		$Lib_3 = "checked=checked";
		$Lib_4 = "checked=checked";
		
		$TT_0 = "checked=checked";
		$TT_1 = "checked=checked";
		$TT_2 = "checked=checked";
		$TT_3 = "checked=checked";
		$TT_4 = "checked=checked";
		$TT_5 = "checked=checked";
		$TT_6 = "checked=checked";
		$TT_7 = "checked=checked";
		$TT_8 = "checked=checked";
		
		$Att_0 = "checked=checked";
		$Att_1 = "checked=checked";
		$Att_2 = "checked=checked";
		$Att_3 = "checked=checked";
		
		$PR_0 = "checked=checked";
		$PR_1 = "checked=checked";
		$PR_2 = "checked=checked";
		$PR_3 = "checked=checked";
		$PR_4 = "checked=checked";
		$PR_5 = "checked=checked";
		
		$Rpt_0 = "checked=checked";
		$Rpt_1 = "checked=checked";
		$Rpt_2 = "checked=checked";
		$Rpt_3 = "checked=checked";
		$Rpt_4 = "checked=checked";
		$Rpt_5 = "checked=checked";
		$Rpt_6 = "checked=checked";
		$Rpt_7 = "checked=checked";
		
		$Uti_0 = "checked=checked";
		$Uti_1 = "checked=checked";
		$Uti_2 = "checked=checked";
		$Uti_3 = "checked=checked";
		$Uti_4 = "checked=checked";
		$Uti_5 = "checked=checked";
		$Uti_6 = "checked=checked";
		$Uti_7 = "checked=checked";
		$Uti_8 = "checked=checked";
		$Uti_9 = "checked=checked";
	}
?>
