<?php

if(!mysql_connect("localhost", "kemo_kasper_dk", "QvK54Xpa")){
	echo mysql_error();
	exit;
}
if(!mysql_select_db("kemo_kasper_dk")){
	echo mysql_error();
	exit;
}
function sql($statment,$assoc=1) {
	if(!$result = mysql_query($statment))
	{
		echo mysql_error();
		exit;
	}
	else return $result;
}

function sendEmail($p_id, $m_id, $from)
{

	$TempSql=sql("select * from `player`, locations where p_location=l_id and `p_id`=".$p_id);
	if ($TempRow=mysql_fetch_row($TempSql))
	{
		$p_mail		=$TempRow[8];

		$txtVer = array("\r\n", "\n"); 
		$htmVer = array("<br>", "<br>");

		$TempSqlMail=sql("select m_name, m_body from mails where m_id=".$m_id);
		if ($TempRowMail=mysql_fetch_row($TempSqlMail)) {

			$messageHTML= str_replace($txtVer, $htmVer, $TempRowMail[1]);
			$messageHTML= str_replace('[p_first]', $TempRow[4], $messageHTML);
			$messageHTML= str_replace('[p_name]', $TempRow[5], $messageHTML);
			$messageHTML= str_replace('[p_adr]', $TempRow[6], $messageHTML);
			$messageHTML= str_replace('[p_zip]', $TempRow[7], $messageHTML);
			$messageHTML= str_replace('[p_mail]', $TempRow[8], $messageHTML);
			$messageHTML= str_replace('[p_pwd]', $TempRow[18], $messageHTML);
	
			$messageHTML= str_replace('[l_name]', $TempRow[25], $messageHTML);
			$messageHTML= str_replace('[l_adr]', $TempRow[26], $messageHTML);
			$messageHTML= str_replace('[l_zip]', $TempRow[27], $messageHTML);
			$messageHTML= str_replace('[l_phone1]', $TempRow[28], $messageHTML);
			
			$headers  = 'From: Kemo-Kasper <' . $from . ">\n"; 
			$headers .= 'To: '.$TempRow[4].' <' . $p_mail . ">\n";
			$headers .= 'Return-Path: ' . $from . "\n"; 
			$headers .= 'MIME-Version: 1.0' ."\n"; 
			$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1' ."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\n";
			$headers .= $messageHTML . "\n"; 

			$messageSubject=$TempRowMail[0];

		mail('', $messageSubject,'', $headers);
		}
	}
}

function sendEmail2($p_id, $m_id, $from)
{
	$TempSql=sql("select `p_mail` from `player` where `p_id`=".$p_id);
	if ($TempRow=mysql_fetch_row($TempSql))
	{
		$p_mail		=$TempRow[0];

		$txtVer = array("\r\n", "\n"); 
		$htmVer = array("<br>", "<br>");

		$TempSql=sql("select m_name, m_body from mails where m_id=".$m_id);
		if ($TempRow=mysql_fetch_row($TempSql)) {

			$messageHTML = str_replace($txtVer, $htmVer, $TempRow[1]);

			$headers  = 'From: Kemo-Kasper <' . $from . ">\n"; 
			$headers .= 'To: ' . $p_mail . "\n";
			$headers .= 'MIME-Version: 1.0' ."\n"; 
			$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1' ."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\n";
			$headers .= $messageHTML . "\n"; 

			$messageSubject=$TempRow[0];

		mail('', $messageSubject,'', $headers);
		}
	}
}

function sendTip($m_id, $from, $to, $mail, $film) {
	if ($from!='' && $to!='' && $mail!='') {

		$txtVer = array("\r\n", "\n"); 
		$htmVer = array("<br>", "<br>");

		$TempSql=sql("select m_name, m_body from mails where m_id=".$m_id);
		if ($TempRow=mysql_fetch_row($TempSql)) {

			$messageHTML = str_replace($txtVer, $htmVer, $TempRow[1]);
			$messageHTML= str_replace('[from]', $from, $messageHTML);
			$messageHTML= str_replace('[to]', $to, $messageHTML);
			$messageHTML= str_replace('[film]', $film, $messageHTML);


			$headers  = 'From: Kemo-Kasper.dk <nyheder@kemo-kasper.dk>' . "\n"; 
			$headers .= 'To: '.$to.'<' . $mail . '>' ."\n";
			$headers .= 'MIME-Version: 1.0' ."\n"; 
			$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1' ."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\n";
			$headers .= $messageHTML . "\n"; 
			$messageSubject=$TempRow[0];
			mail('', $messageSubject,'', $headers);
			echo '<SendTip result="'.$mail.'">';
			echo "</SendTip>";
		}
	}
}


function AddNewsMail($p_newsmail) {
	echo "<AddNewsMail>\r\n";

	if (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $p_newsmail))
	{
		echo "	<result status=\"0\">Ugyldig Email adresse</result>\r\n";
		echo "</AddNewsMail>\r\n";
		exit;
	}

	$host = explode('@', $p_newsmail); 
	if(!checkdnsrr($host[1].'.', 'MX'))
	{
		echo "	<result status=\"0\">Ugyldigt Email domæne</result>\r\n";
		echo "</AddNewsMail>\r\n";
		exit;
	}


	$GetSql=sql("select p_mail from player where p_mail='".$p_newsmail."'");
	if ($GetRow = mysql_fetch_row($GetSql))
	{
		echo "	<result status=\"9\">Tryk på højre musetast og vælg Play/Afspil</result>\r\n";
		echo "</AddNewsMail>\r\n";
		exit;
	}

	$result=sql("insert into player (p_first, p_name, p_adr, p_zip, p_mail, p_newsaccept, p_mk, p_born, p_location, p_s_id, p_active) values('*', '*','*',0,'{$p_newsmail}',1, 'D', 0,1,1,1)");
		echo "	<result status=\"{$result}\">Tak for hjælpen, du modtager en e-mail med link til spillet!</result>\r\n";
		echo "</AddNewsMail>\r\n";


	$GetSql=sql("select p_id from player where p_mail='".$p_newsmail."'");
	$GetRow = mysql_fetch_row($GetSql);
	sendEmail2($GetRow[0], 9, "nyheder@kemo-kasper.dk");
}

function AddPlayer($p_first, $p_name,$p_adr,$p_zip,$p_mail,$p_news, $p_mk, $p_born, $p_location, $HTTP_REFERER) {
	echo "<AddPlayer>\r\n";

	if ($p_first=="" || $p_name=="")
	{
		echo "	<result status=\"0\">Jeg mangler dit navn!</result>\r\n";
		echo "</AddPlayer>\r\n";
		exit;
	}
	if ($p_adr=="")
	{
		echo "	<result status=\"0\">Jeg mangler din adresse!</result>\r\n";
		echo "</AddPlayer>\r\n";
		exit;
	}

	if (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $p_mail))
	{
		echo "	<result status=\"0\">Ugyldig Email adresse</result>\r\n";
		echo "</AddPlayer>\r\n";
		exit;
	}
	$host = explode('@', $p_mail); 
	if(!checkdnsrr($host[1].'.', 'MX'))
	{
		echo "	<result status=\"0\">Ugyldigt Email domæne</result>\r\n";
		echo "</AddPlayer>\r\n";
		exit;
	}

	if ($p_zip=="" || $p_zip=="0")
	{
		echo "	<result status=\"0\">Jeg mangler dit postnummer!</result>\r\n";
		echo "</AddPlayer>\r\n";
		exit;
	}

	$GetSql=sql("select `ZZCity` from `ZZIP` where `ZZIP`=".$p_zip);
	if (!$GetRow = mysql_fetch_row($GetSql))
	{
		echo "	<result status=\"0\">Jeg kan ikke finde dit postnummer!</result>\r\n";
		echo "</AddPlayer>\r\n";
		exit;
	}

	$GetSql=sql("select p_mail from player where p_mail='".$p_mail."'");
	if ($GetRow = mysql_fetch_row($GetSql))
	{
		echo "	<result status=\"0\">Jeg har allerede oprettet dig som spiller!</result>\r\n";
		echo "</AddPlayer>\r\n";
		exit;
	}

	if ($p_mk=="M")	$p_mk="D";
	if ($p_mk=="K")	$p_mk="P";
	$p_newsaccept=0;
	if ($_REQUEST['p_news']=='on') $p_newsaccept=1;

	$result=sql("insert into player (p_first, p_name, p_adr, p_zip, p_mail, p_newsaccept, p_mk, p_born, p_location, p_s_id, p_active, p_pwd, p_ip) values('{$p_first}', '{$p_name}','{$p_adr}',$p_zip,'{$p_mail}',{$p_newsaccept}, '{$p_mk}', {$p_born}, {$p_location}, 0, 1, 'vd".$p_mk.$p_born."','".$HTTP_REFERER."')");
	echo "	<result status=\"{$result}\">OK</result>\r\n";
	echo "</AddPlayer>\r\n";

}
function AddScore($p_id, $score, $kills, $name) {
	echo "<AddScore>"."\r\n";
	$xscore	=$score;
	$t_ts_id=1;
	$xscorehigh=0;
	$p_id=0+$p_id;
	$GetSql=sql("select p_id, p_first, p_score, p_scorehigh, p_games, p_s_id, p_tscore, p_tkills from `player` where p_active=1 and p_id=".$p_id);
	if ($GetRow = mysql_fetch_row($GetSql))
	{
		echo "	<p_id>{$p_id}</p_id>\r\n";
		echo "	<p_name>{$name}</p_name>\r\n";
		$t_ts_id=$GetRow[5];
		$xscorehigh	=$GetRow[3];
		$xgames		=$GetRow[4]+1;
		$_SESSION['p_score']=$xscore;
		$_SESSION['p_games']=$xgames;
		if ($xscorehigh<$xscore)
		{
			echo "	<result status=\"0\">NEW PERSONAL HIGH SCORE ".$xscorehigh."</result>\r\n";
			$xscorehigh=$score;
			$_SESSION['p_scorehigh']=$xscorehigh;
		}
		//** $GetSql=sql("select count(*) as ranking from `player` where p_scorehigh>".$xscorehigh);
		//** $GetRow =  mysql_fetch_row($GetSql);
		//** echo "	<ranking>".$GetRow[0]."</ranking>\r\n";

		//** Identify Boowmtown Café
		$p_user="BOOM1";	
		$p_ip=$_SERVER['REMOTE_ADDR'];
		if(substr($p_ip, 0, 8)=='87.51.1.') $p_user="BOOM2";
		elseif(substr($p_ip, 0, 8)=='87.51.2.') $p_user="BOOM4";
		elseif(substr($p_ip, 0, 8)=='87.51.3.')
		{
			$p_num=(int)(substr($p_ip, 8, 3));
			if ($p_num>=150 && $p_num<=246) $p_user="BOOM3";
			else $p_user="BOOM5";
		}

	//**	if ($GetRow[6]>0)
			$TempResult=sql("update player set p_games=".$xgames.", p_score=".$xscore.", p_scorehigh=".$xscorehigh.", p_ip='".$_SERVER['REMOTE_ADDR']."' where p_id=".$p_id);
	//**	else
	//**		$TempResult=sql("update player set p_games=".$xgames.", p_score=".$xscore.", p_scorehigh=".$xscorehigh.", p_tscore=".$xscore.", p_tkills=".$kills.", p_ip='".$_SERVER['REMOTE_ADDR']."', p_user='".$p_user."' where p_id=".$p_id);
		$TempResult=sql("insert into top (t_user, t_score, t_ip, t_p_id, t_ts_id, t_kills) values('".$name."', ".$xscore.", '".$_SERVER['REMOTE_ADDR']."', ".$p_id.", ".$t_ts_id.", ".$kills.")");
	}
	echo "</AddScore>"."\r\n";
}

function AddScoreBETA($p_id, $score, $name) {
	echo "<AddScore>";
	//** echo "<p_id>0</p_id>\r\n";
	//** echo "<p_name>{$name}</p_name>\r\n";
	//** $GetSql=sql("select count(*) as ranking from `top` where t_score>".$score);
	//** $GetRow=mysql_fetch_row($GetSql);
	//** echo "<ranking>".$GetRow[0]."</ranking>\r\n";
	//** $TempResult=sql("insert into top (t_user, t_score) values('".$name."', ".$score.")");
	echo "</AddScore>";
}

function dspSponsors() {
	echo "<Sponsors>\r\n";
	$TempResult=sql("select s_id, s_name, s_contact, s_adr, s_zip, s_phone1, s_phone2, s_paid, s_logo, s_banner, s_www, ZZCity from `sponsor`, `ZZIP` where s_active>0 and `s_zip`=`ZZIP`");
	while($TempRow = mysql_fetch_row($TempResult)) {
		echo "<item>\r\n";
		echo "	<s_id>{$TempRow[0]}</s_id>\r\n";
		echo "	<s_name>{$TempRow[1]}</s_name>\r\n";
		echo "	<s_contact>{$TempRow[2]}</s_contact>\r\n";
		echo "	<s_adr>{$TempRow[3]}</s_adr>\r\n";
		echo "	<s_zip>{$TempRow[4]}</s_zip>\r\n";
		echo "	<s_phone1>{$TempRow[5]}</s_phone1>\r\n";
		echo "	<s_phone2>{$TempRow[6]}</s_phone2>\r\n";
		echo "	<s_tickets>{$TempRow[7]}</s_tickets>\r\n";
		echo "	<s_imgURL>http://www.kemo-kasper.dk/admin/images/</s_imgURL>\r\n";
		echo "	<s_logo>{$TempRow[8]}</s_logo>\r\n";
		echo "	<s_banner>{$TempRow[9]}</s_banner>\r\n";
		echo "	<s_www>{$TempRow[10]}</s_www>\r\n";
		echo "	<ZZCity>{$TempRow[11]}</ZZCity>\r\n";
		echo "</item>\r\n";
	}
	echo "</Sponsors>\r\n";
}
function dspLocations() {
	echo "<Locations>\r\n";
	$TempResult=sql("select l_name, l_adr, l_zip, l_mail, l_phone1, l_phone2, l_open, l_close, ZZCity from `locations`, `ZZIP` where `l_zip`=`ZZIP`");
	while($TempRow = mysql_fetch_row($TempResult)) {
		echo "<item>\r\n";
		echo "	<l_name>{$TempRow[0]}</l_name>\r\n";
		echo "	<l_adr>{$TempRow[1]}</l_adr>\r\n";
		echo "	<l_zip>{$TempRow[2]}</l_zip>\r\n";
		echo "	<l_mail>{$TempRow[3]}</l_mail>\r\n";
		echo "	<l_phone1>{$TempRow[4]}</l_phone1>\r\n";
		echo "	<l_phone2>{$TempRow[5]}</l_phone2>\r\n";
		echo "	<l_open>{$TempRow[6]}</l_open>\r\n";
		echo "	<l_close>{$TempRow[7]}</l_close>\r\n";
		echo "	<ZZCity>{$TempRow[8]}</ZZCity>\r\n";
		echo "</item>\r\n";
	}
	echo "</Locations>\r\n";
}
function dspTopTeams() {
	echo "<TopTeams>\r\n";
	$TempResult=sql("select count(`p_id`), sum(`p_score`) as s_score, sum(`p_games`) as s_games, `s_name` from `player`, `sponsor` where `p_s_id`=`s_id` group by p_s_id desc LIMIT 50");
	while($TempRow = mysql_fetch_row($TempResult)) {
		echo "<item>\r\n";
		echo "	<p_players>{$TempRow[0]}</p_players>\r\n";
		echo "	<s_score>{$TempRow[1]}</s_score>\r\n";
		echo "	<s_games>{$TempRow[2]}</s_games>\r\n";
		echo "	<s_name>{$TempRow[3]}</s_name>\r\n";
		echo "</item>\r\n";
	}
	echo "</TopTeams>\r\n";
}
function dspTop100() {
	echo "<Top100>\r\n";
	$TempResult=sql("select `p_user`, `p_name`, `p_scorehigh`, `s_name` from `player`, `sponsor` where `p_s_id`=`s_id` order by `p_scorehigh` desc LIMIT 50");
	while($TempRow = mysql_fetch_row($TempResult)) {
		echo "<item>\r\n";
		echo "	<p_user>{$TempRow[0]}</p_user>\r\n";
		echo "	<p_name>{$TempRow[1]}</p_name>\r\n";
		echo "	<p_scorehigh>{$TempRow[2]}</p_scorehigh>\r\n";
		echo "	<s_name>{$TempRow[3]}</s_name>\r\n";
		echo "</item>\r\n";
	}
	echo "</Top100>\r\n";
}
function dspTop100BETA() {
	echo "<Top100>\r\n";
	$TempResult=sql("select `p_first`, max(`t_score`) as maxscore, `p_born`, `t_datetime` from `top`, `player` where p_active=1 and p_location>1 and t_datetime>='".date('Y-m-d')."' and `t_p_id`=`p_id` group by t_p_id order by maxscore desc limit 100");
	while($TempRow = mysql_fetch_row($TempResult)) {
		$x++;
		$age=2007-$TempRow[2];
		$number=number_format($TempRow[1], 0, '.', '.');
		echo "<item>";
		echo "	<p_name>{$TempRow[0]}, {$age}</p_name>";
		echo "	<p_scorehigh>{$number}</p_scorehigh>";
		//**	echo "	<s_name>BETA Test</s_name>";
		//**	echo "	<p_scoredate>{$TempRow[3]}</p_scoredate>";
		echo "</item>";
	}
	while ($x++<=0) {
		echo "<item>";
		echo "	<p_name>?</p_name>"."\r\n";
		echo "	<p_scorehigh>0</p_scorehigh>"."\r\n";
		//**	echo "	<s_name>BETA Test</s_name>"."\r\n";
		//**	echo "	<p_scoredate>".date('Y-m-d')."</p_scoredate>"."\r\n";
		echo "</item>"."\r\n";
	}
	echo "</Top100>\r\n";
}
session_start();
header("Content-Type: application/xml; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\r\n";

if ($_REQUEST['TP']!="") dspTop100BETA();
if ($_REQUEST['TT']!="") dspTopTeams();
if ($_REQUEST['LC']!="") dspLocations();
if ($_REQUEST['LS']!="") dspSponsors();
if ($_REQUEST['TIP']!="") SendTip(34, $_REQUEST['from'],$_REQUEST['to'],$_REQUEST['mail'],$_REQUEST['film']);
if ($_REQUEST['TIP2']!="") SendTip(36, $_REQUEST['from'],$_REQUEST['to'],$_REQUEST['mail'],$_REQUEST['film']);
if ($_REQUEST['TIP3']!="") SendTip(35, $_REQUEST['from'],$_REQUEST['to'],$_REQUEST['mail'],$_REQUEST['film']);
if ($_REQUEST['AS']!="") AddScore($_REQUEST['P_ID'],$_REQUEST['SCORE'],$_REQUEST['KILLS'],$_REQUEST['p_name']);
if ($_REQUEST['AP']!="") AddPlayer($_REQUEST['p_first'],$_REQUEST['p_name'],$_REQUEST['p_adr'],$_REQUEST['p_zip'],$_REQUEST['p_mail'],$_REQUEST['p_news'],$_REQUEST['p_mk'], $_REQUEST['p_born'], $_REQUEST['p_location'],$_REQUEST['HTTP_REFERER']);
if ($_REQUEST['AN']!="") AddNewsMail($_REQUEST['p_newsmail']);

?>