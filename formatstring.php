<?php 
function formatHTMLStr($StrName){
	if(stristr($StrName,"'")){
		$StrName = str_replace("'","~",$StrName);
	}
	return $StrName;
}
function formatDatabaseStr($StrName){
	if(stristr($StrName,"~")){
		$StrName = str_replace("~","'",$StrName);
	}
	return $StrName;
}
function Convert_To_Long_Date($StrDataDate){
	$arrDate=explode("-",$StrDataDate);
	
	if($arrDate[1]==1 or $arrDate[1]==01){
		$udateDate = $arrDate[2]." "."January ".$arrDate[0];
	}elseif($arrDate[1]==2 or $arrDate[1]==02){
		$udateDate = $arrDate[2]." "."February ".$arrDate[0];
	}elseif($arrDate[1]==3 or $arrDate[1]==03){
		$udateDate = $arrDate[2]." "."March ".$arrDate[0];
	}elseif($arrDate[1]==4 or $arrDate[1]==04){
		$udateDate = $arrDate[2]." "."April ".$arrDate[0];
	}elseif($arrDate[1]==5 or $arrDate[1]==05){
		$udateDate = $arrDate[2]." "."May ".$arrDate[0];
	}elseif($arrDate[1]==6 or $arrDate[1]==06){
		$udateDate = $arrDate[2]." "."June ".$arrDate[0];
	}elseif($arrDate[1]==7 or $arrDate[1]==07){
		$udateDate = $arrDate[2]." "."July ".$arrDate[0];
	}elseif($arrDate[1]==8 or $arrDate[1]==08){
		$udateDate = $arrDate[2]." "."August ".$arrDate[0];
	}elseif($arrDate[1]==9 or $arrDate[1]==09){
		$udateDate = $arrDate[2]." "."September ".$arrDate[0];
	}elseif($arrDate[1]==10){
		$udateDate = $arrDate[2]." "."October ".$arrDate[0];
	}elseif($arrDate[1]==11){
		$udateDate = $arrDate[2]." "."November ".$arrDate[0];
	}elseif($arrDate[1]==12){
		$udateDate = $arrDate[2]." "."December ".$arrDate[0];
	}
	return $udateDate;			
}
?>
