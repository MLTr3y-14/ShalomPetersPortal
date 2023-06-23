<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

<title>VIO-DVLA - Learners permit, Provisional Drivers Licence, Booking a driving test online</title>


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

		$('#lic_replace').click(function(){
			$('#fmr_lic_div').show('slow');
		});

		$('#lic_new').click(function(){
			$('#fmr_lic_div').hide('slow');
		});
		
		$('#lic_renew').click(function(){
			$('#fmr_lic_div').hide('slow');
		});
		
		$('#learn_replace').click(function(){
			$('#fmr_lic_div').hide('slow');
		});
		
		$('#learn_new').click(function(){
			$('#fmr_lic_div').hide('slow');
		})
    });
    
</script>

<body>
<?php 

	if(isset($_POST['licencetype'])){
		if($_POST['licencetype'] == 'learn_new'){
			?> <script>document.location = 'dl_apply2.php';</script> <?php
		}else if($_POST['licencetype'] == 'learn_replace'){
			?> <script>document.location = 'dl_apply2_1.php?learn_repl=true';</script> <?php
		}else if($_POST['licencetype'] == 'lic_new'){
			?> <script>document.location = 'dl_apply2_1.php?lic_new=true';</script> <?php
		}else if($_POST['licencetype'] == 'lic_renew'){
			?> <script>document.location = 'dl_apply2_1.php?lic_renew=true';</script> <?php
		}else if($_POST['licencetype'] == 'lic_replace'){
			?> <script>document.location = 'dl_apply2_1.php?lic_replace=true';</script> <?php
		} 
	}

?>

<form id="form" action="" method="post" name="form">
Choose what you want to apply for:
<table border="0" >
	<tr>
	<td>
	  <div align="left">
		<input type="radio" name="licencetype" id="learn_new" value="learn_new" />
	  I want to apply for a new learner's permit<br />
	  <input type="radio" name="licencetype" id="learn_new" value="learn_replace" />
	  I want to replace my learner's permit<br />
	  <input type="radio" name="licencetype" id="lic_new" value="lic_new" />
	  I want to apply for a new driver's licence<br />
	  <input type="radio" name="licencetype" id="lic_renew" value="lic_renew" />
	  I want to renew my driver's licence<br />
	  <input type="radio" name="licencetype" id="lic_replace" value="lic_replace" />
	  I want to replace my driver's licence<br />
	  </div>
	  </td>
  </tr>
  <tr>
	<td>
	<div id="fmr_lic_div">
	<table border="0" width="100%">
	 <tr>
		<td><div align="right" >Former licence was:</div></td>
		<td>
		<div align="left">
		<input type="radio" name="fmr_status" id="radio" value="Stolen" />
		  Stolen<br />
		  <input type="radio" name="fmr_status" id="radio2" value="Defaced" />
		  Defaced<br />
		  <input type="radio" name="fmr_status" id="radio3" value="Lost" />
		  Lost<br />
		  <input type="radio" name="fmr_status" id="radio4" value="Destroyed" />
		  Destroyed<br />
		 </div>
		</td>
	 </tr>
	 </table></div>
</table>
<p>
	<input  type="submit" value="Apply" name="submit"/>	
  </p>
<p><br></p>
</form>


</body></html>