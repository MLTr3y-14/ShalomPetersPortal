<?php 

session_start();

if(!isset($_SESSION['att_tpl'])){
 		// tpl does not exist in session ... retreive it.

		$tpl;
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'ATT'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	} 

		$_SESSION['att_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'RST_SUBJ'";
     	$result = mysql_query($query);
 
     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['rst_subj_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'RST'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['rst_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'FEES_DEFAULTER'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['fees_default_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'FEE'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['fee_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'ROLL'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['roll_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'EXEAT'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['exeat_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'CLINIC'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['clinic_tpl'] = $tpl; 

 }


            if(isset($_POST['sender'])){

                //attendance
                saveTpl($_POST['att_tpl'], 'ATT', $_POST['sender']);
                $_SESSION['att_tpl'] = $_POST['att_tpl'];

                //attendance
                saveTpl($_POST['rst_subj_tpl'], 'RST_SUBJ', $_POST['sender']);
                $_SESSION['rst_subj_tpl'] = $_POST['rst_subj_tpl'];

                //attendance
                saveTpl($_POST['rst_tpl'], 'RST', $_POST['sender']);
                $_SESSION['rst_tpl'] = $_POST['rst_tpl'];

                //attendance
                saveTpl($_POST['fee_default_tpl'], 'FEE_DEFAULTER', $_POST['sender']);
                $_SESSION['fee_default_tpl'] = $_POST['fee_default_tpl'];

                //roll_tpl
                saveTpl($_POST['roll_tpl'], 'ROLL', $_POST['sender']);
                $_SESSION['roll_tpl'] = $_POST['atroll_tplt_tpl'];

                //attendance
                saveTpl($_POST['fee_tpl'], 'FEE', $_POST['sender']);
                $_SESSION['fee_tpl'] = $_POST['fee_tpl'];

                //exeat
                saveTpl($_POST['exeat_tpl'], 'EXEAT', $_POST['sender']);
                $_SESSION['exeat_tpl'] = $_POST['exeat_tpl'];

                //clinic
                saveTpl($_POST['clinic_tpl'], 'CLINIC', $_POST['sender']);
                $_SESSION['clinic_tpl'] = $_POST['clinic_tpl'];
                
                ?>
                  <script>
                      alert("template saved!");
                  </script>
                <?
            }

            function saveTpl($tpl, $tpl_type, $sender) {
        // inti db

                $sql = "INSERT INTO tpl_table (tpl, sender) VALUES('".$tpl."','".$sender."')";
                $result2 = mysql_query($sql);

        //close db
      }


?>

<style type="text/css">
<!--
.style1 {
	font-size: large;
	font-weight: bold;
}
.style2 {
	font-size: small;
	color: #006600;
}
-->
</style>
<form action="#" method="post" >
<table width="100%" border="0">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td><p>List of variables:</p>
          <p>&lt;date&gt; = date of request. <br/>&lt;admin_no&gt; = admission number. <br/>&lt;stud_name&gt; = student name. <br/>&lt;stud_class&gt; = student class.<br/> &lt;status&gt; = student status.</p>
          </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><label>Sender
            <input type="text" name="sender" id="sender" />
        </label></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Attendance SMS Template</legend>
        <textarea name="att_tpl" id="att_tpl" cols="45" rows="5"><?php echo $_SESSION['att_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Performance overall Template</legend>
        <textarea name="rst_tpl" id="rst_tpl" cols="45" rows="5"><?php echo $_SESSION['rst_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Performance subject-based Template</legend>
        <textarea name="rst_subj_tpl" id="rst_subj_tpl" cols="45" rows="5"><?php echo $_SESSION['rst_subj_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Fees defaulter SMS Template</legend>
        <textarea name="fees_default_tpl" id="fees_default_tpl" cols="45" rows="5"><?php echo $_SESSION['fee_default_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Student FEES  Template</legend>
        <textarea name="fees_tpl" id="fees_tpl" cols="45" rows="5"><?php echo $_SESSION['fee_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Roll call SMS Template</legend>
        <textarea name="roll_tpl" id="roll_tpl" cols="45" rows="5"><?php echo $_SESSION['roll_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Exeat SMS Template</legend>
        <textarea name="exeat_tpl" id="exeat_tpl" cols="45" rows="5"><?php echo $_SESSION['exeat_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Clinic SMS Template</legend>
        <textarea name="clinic_tpl" id="clinic_tpl" cols="45" rows="5"><?php echo $_SESSION['clinic_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
  </tr>
  <tr><td colspan="3"><div align="center">
    <input type="submit" name="button" id="button" value="Save" />
  </div></td></tr>
</table>
</form>
  