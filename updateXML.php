<?php
function UpdateXML_File(){
include 'library/config.php';
include 'library/opendb.php';
$fp = "";
$strFilename="activecalendar/data/xmlevents.txt";
$content = "<?xml version='1.0' encoding='UTF-8'?>\n
<!DOCTYPE activecalendar [\n
<!ELEMENT activecalendar (eventcontent+, event+)>\n
<!ELEMENT eventcontent (contentyear, contentmonth, contentday, contents, contentlink)>\n
<!ELEMENT contentyear (#PCDATA)>\n
<!ELEMENT contentmonth (#PCDATA)>\n
<!ELEMENT contentday (#PCDATA)>\n
<!ELEMENT contents (item+)>\n
<!ELEMENT item (#PCDATA)>\n
<!ELEMENT contentlink (#PCDATA)>\n
<!ELEMENT event (eventyear, eventmonth, eventday, eventstyle, eventlink)>\n
<!ELEMENT eventyear (#PCDATA)>\n
<!ELEMENT eventmonth (#PCDATA)>\n
<!ELEMENT eventday (#PCDATA)>\n
<!ELEMENT eventstyle (#PCDATA)>\n
<!ELEMENT eventlink (#PCDATA)>\n
]>\n
<activecalendar>\n";
$counter = 0;
$count_Rows =0;
$text_body = "";
$query = "select * from calendar";
$result = mysql_query($query,$conn);
$num_rows = mysql_num_rows($result);
if ($num_rows > 0 )
{
	while ($row = mysql_fetch_array($result)) 
	{
		$counter = $counter +1;
		$event_date = $row["event_date"];
		$arr2=explode ('-', $event_date);
		$yr=$arr2[0];
		$mth=$arr2[1];
		$dy=$arr2[2];
		$Eventitem = $row["item"];
		$item_link = $row["item_link"];
		
		$text_body .= "
		<eventcontent>\n";
		$text_body .= "
		<contentyear>".$yr."</contentyear>\n";
		$text_body .= "
		<contentmonth>".$mth."</contentmonth>\n";
		$text_body .= "
		<contentday>".$dy."</contentday>\n";
		$text_body .= "
		<contents>\n";
		$text_body .= "
		<item>".$Eventitem."</item>\n";
		$text_body .= "
		</contents>\n";
		$text_body .= "
		<contentlink>".$item_link."</contentlink>\n";
		$text_body .= "
		</eventcontent>\n
		";
	}
}
	$content = $content.$text_body;
	$content = $content."
	<eventcontent>\n
		<contentyear>0</contentyear>\n
		<contentmonth>0</contentmonth>\n
		<contentday>0</contentday>\n
		<contents>\n
			<item></item>\n
		</contents>\n
		<contentlink></contentlink>\n
	</eventcontent>\n
	<event>\n
		<eventyear>0</eventyear>\n
		<eventmonth>0</eventmonth>\n
		<eventday>0</eventday>\n
		<eventstyle></eventstyle>\n
		<eventlink></eventlink>\n
	</event>\n
</activecalendar>\n\n";
		
$fp=@fopen($strFilename,"w");
fwrite($fp,$content);
fwrite($fp, " \n"); 
fclose($fp);
}
function UpdateFEED_File(){
include 'library/config.php';
include 'library/opendb.php';
$fp = "";
$strFilename="rssfeed.xml";
$content = "<?xml version='1.0' ?> 
<rss version='2.0'>
<channel>
		<title>Church Management Consult</title>
		<description>Our Vision is to provide support services that will strengthen the Church for the End-Time Harvest!</description>
		<link>http://www.churchmgtconsult.org</link> ";

$counter = 0;
$count_Rows =0;
$text_body = "";
$query = "select * from rssfeed";
$result = mysql_query($query,$conn);
$num_rows = mysql_num_rows($result);
if ($num_rows > 0 )
{
	while ($row = mysql_fetch_array($result)) 
	{
		$counter = $counter +1;
		$Title = $row["Title"];
		$Description = $row["Description"];
		$Link = $row["Link"];

		$text_body .= "
		<item>";
		$text_body .= "
		<title>Church Management Consult-".$Title."</title>";
		$text_body .= "
		<description>".$Description."</description>";
		$text_body .= "
		<link>".$Link."</link>
		</item>";
	}
}
$content = $content.$text_body;
$content = $content."
</channel>
</rss>";		
$fp=@fopen($strFilename,"w");
fwrite($fp,$content);
fclose($fp);
}
?>
