<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Kemokasper sender post</title>
</head>
<body>
<?php
global $dbServer, $dbUsername, $dbPassword, $dbDatabase;

$dbServer="localhost";
$dbUsername="kemo_kasper_dk";
$dbPassword="QvK54Xpa";
$dbDatabase="kemo_kasper_dk";

if(!mysql_connect($dbServer, $dbUsername, $dbPassword)){
	echo mysql_error();
	exit;
}
if(!mysql_select_db($dbDatabase)){
	echo mysql_error();
	exit;
}
function sql($statment,$assoc=1) {
	if(!$result = mysql_query($statment))	echo mysql_error();
	else return $result;
}

function sendEmail($p_id, $m_id, $from)
{
	if(!$m_id>0) $m_id=2;
	if(!$p_id>0) $p_id=2;

	$TempSql=sql("select `p_first`, `p_name`, `p_mail`, `l_name`, `l_adr`, `l_zip`, `l_phone1` from `player`, `locations` where `p_location`=`l_id` and `p_id`=".$p_id);
	if ($TempRow=mysql_fetch_row($TempSql))
	{
		$p_first	=$TempRow[0];
		$p_name		=$TempRow[1];
		$p_mail		=$TempRow[2];
		$l_location	=$TempRow[3];
		$l_adr		=$TempRow[4];
		$l_zip		=$TempRow[5];
		$l_phone1	=$TempRow[6];

		$dataf = array("[p_first]", "[p_name]", "[p_mail]", "[l_name]", "[l_adr]", "[l_zip]", "[l_phone1]");
		$datan = array("<b>{$p_first}</b>", "<b>{$p_name}</b>", "<a href=mailto:{$p_mail}>{$p_mail}</a>", "<b>{$l_name}</b>", "<b>{$l_adr}</b>", "<b>{$l_zip}</b>", "<b>{$l_phone1}</b>");

		$txtVer = array("\r\n", "\n"); 
		$htmVer = array("<br>", "<br>");

		$TempSql=sql("select m_name, m_body from mails where m_id=".$m_id);
		if ($TempRow=mysql_fetch_row($TempSql)) {

			$messageHTML = str_replace($dataf, $datan, $TempRow[1]);
			$messageHTML = str_replace($txtVer, $htmVer, $messageHTML); 

			Print $messageHTML; 

			$headers  = 'From: ' . $from . "\n"; 
			$headers .= 'To: ' . $p_mail . "\n";
			$headers .= 'Return-Path: ' . $from . "\n"; 
			$headers .= 'MIME-Version: 1.0' ."\n"; 
			$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1' ."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\n";
			$headers .= $messageHTML . "\n"; 

			$messageSubject=$TempRow[0];

/*		mail('', $messageSubject,'', $headers); */

		}
	}
}
sendEmail($_REQUEST['p_id'], $_REQUEST['m_id'], "nyheder@kemo-kasper.dk");
?>
</body>
</html>