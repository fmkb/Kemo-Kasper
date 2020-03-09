<?php

if(!mysql_connect("localhost", "kemo_kasper_dk", "QvK54Xpa")){
	echo mysql_error();
	exit;
}
if(!mysql_select_db("kemo_kasper_dk")){
	echo mysql_error();
	exit;
}
session_start();

function sql($statment,$assoc=1) {
	if(!$result = mysql_query($statment))
	{
		echo mysql_error();
		exit;
	}
	else return $result;
}

function sendEmail($p_mail, $m_id)
{
	$TempSql=sql("select * from `player`, locations where p_location=l_id and `p_mail`='".$p_mail."'");
	if ($TempRow=mysql_fetch_row($TempSql))
	{

		$txtVer = array("\r\n", "\n"); 
		$htmVer = array("<br>", "<br>");

		if ($TempRow[2]==1) $TempSqlMail=sql("select m_name, m_body from mails where m_id=16");
		else $TempSqlMail=sql("select m_name, m_body from mails where m_id=3");

		if ($TempRowMail=mysql_fetch_row($TempSqlMail)) {

			$messageHTML= str_replace($txtVer, $htmVer, $TempRowMail[1]);
			$messageHTML= str_replace('[p_first]', $TempRow[4], $messageHTML);
			$messageHTML= str_replace('[p_name]', $TempRow[5], $messageHTML);
			$messageHTML= str_replace('[p_adr]', $TempRow[6], $messageHTML);
			$messageHTML= str_replace('[p_zip]', $TempRow[7], $messageHTML);
			$messageHTML= str_replace('[p_mail]', $TempRow[8], $messageHTML);
			$messageHTML= str_replace('[p_pwd]', $TempRow[18], $messageHTML);
	
			$messageHTML= str_replace('[l_name]', $TempRow[22], $messageHTML);
			$messageHTML= str_replace('[l_adr]', $TempRow[23], $messageHTML);
			$messageHTML= str_replace('[l_zip]', $TempRow[24], $messageHTML);
			$messageHTML= str_replace('[l_mail]', $TempRow[25], $messageHTML);
			
			$headers  = 'From: Kemo-Kasper <nyheder@kemo-kasper.dk>'."\n"; 
			$headers .= 'To: '.$TempRow[5].' <' . $p_mail . ">\n";
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

//***********************************************
//**
function sendNewsLetter($m_id) {
	echo "<div id=\"myarea\">";
	$txtVer = array("\r\n", "\n"); 
	$htmVer = array("<br>", "<br>");

	$TempSqlMail=sql("select m_name, m_body from mails where m_id=".$m_id);
	$TempRowMail=mysql_fetch_row($TempSqlMail);
	$messageHTML= str_replace($txtVer, $htmVer, $TempRowMail[1]);

	$TempSql=sql("select * from `player` where p_id=278 or p_id=279 or p_id=280");
	while ($TempRow=mysql_fetch_row($TempSql)) {
			$messageHTML= str_replace('[p_first]', $TempRow[4], $messageHTML);
			$messageHTML= str_replace('[p_name]', $TempRow[5], $messageHTML);
			$messageHTML= str_replace('[p_adr]', $TempRow[6], $messageHTML);
			$messageHTML= str_replace('[p_zip]', $TempRow[7], $messageHTML);
			$messageHTML= str_replace('[p_mail]', $TempRow[8], $messageHTML);
			$messageHTML= str_replace('[p_pwd]', $TempRow[18], $messageHTML);
			
			$headers  = 'From: Kemo-Kasper <nyheder@kemo-kasper.dk>'."\n"; 
			$headers .= 'To: '.'Beta tester'.' <' . $TempRow[8] . ">\n";
			$headers .= 'Return-Path: ' . $from . "\n"; 
			$headers .= 'MIME-Version: 1.0' ."\n"; 
			$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1' ."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\n";
			$headers .= $messageHTML . "\n"; 

			$messageSubject='Nyhedsbrev fra Kemo-Kasper.dk';
			echo '<br/>'.$TempRow[8].','."\r\n";
		mail('', $messageSubject,'', $headers);
	}
	echo "</div>";

}

//***********************************************
//**
function echoHTML($text) {
	$cr="\r";
	echo str_replace($cr, '<br />', $text);
}

//***********************************************
//**
function dspFMKB() {
	echo "<br /><table width=\"740\"><tr valign=\"top\" align=left><td>";
	echo "<img src=\"fmkb_logo_circle.png\" width=\"159\" alt=\"\" />";
	echo "</td><td align=\"left\">";

	$TempResult=sql("select * from `mails` where m_id=13");
	$TempRow = mysql_fetch_row($TempResult);
	echoHTML($TempRow[2]);

	echo "</td></tr></table>";
}

//***********************************************
//**
function dspHome() {

	echo '<div id="myhome">';
	echo '<div id="mynews">';
	$TempResult=sql("select * from news order by n_id desc limit 2");
	while($TempRow = mysql_fetch_row($TempResult)) {

		echo $TempRow[4]."<br />";
		echo "<b>".$TempRow[5]."</b><br />";
		echo '<a href="?PR='.$TempRow[0].'"><span style="color: #000000; font-size=4px; background-color: #ffffff; decoration: none;"> LÆS MERE </span></a><br /><br />';
	}
	echo '<a style="position: absolute; left: 300px; top: 150px" href="?BG=Yes"><img src="big_spil.png" border="0" alt="Spil" /></a>';
	echo '<a style="position: absolute; left: 400px; top: 150px" href="?RG=Yes"><img src="big_registrer.png" border="0" alt="Registrer" /></a>';
	echo '</div>';
	echo '</div>';
	echo '<img src="kk_home_sponsors.png" border="0" alt="" height="140" width="780" />';
}

//***********************************************
//**
function dspFilm() {

	$txt = '<object type="application/x-shockwave-flash" height="540" width="780" data="filmPage.swf">'."\r\n";
	$txt.= '<param name="movie" value="filmPage.swf" />'."\r\n";
	$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
	$txt.= '</object>'."\r\n";

	echo "<div id=\"myarea\">";
	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
	echo $txt;
	dspFMKB();
	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
	echo '</div>';
}
//***********************************************
//**
function dspBeta() {

	$txt = '<object type="application/x-shockwave-flash" height="625" width="780" data="kasperBeta.swf">'."\r\n";
	$txt.= '<param name="movie" value="kasperBeta.swf" />'."\r\n";
	$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
	$txt.= '</object>'."\r\n";
	echo $txt;
}

//***********************************************
//**
function dspLogOut() {
	$_SESSION['p_id']=0;
	dspSpil;
}

//***********************************************
//**
function dspLogin() {

	if($_REQUEST['p_s_id']>0 && $_SESSION['p_id']>0) {
		sql("update `player` set p_s_id=".$_REQUEST['p_s_id']." where p_id=".$_SESSION['p_id']);

		$_SESSION['p_s_id']=$_REQUEST['p_s_id'];
		$TempResult=sql("select ts_id, ts_name, s_banner, s_name from `teams`, `sponsor` where ts_id=0".$_SESSION['p_s_id']." and ts_s_id=s_id and s_active>0");
		$TempRow = mysql_fetch_row($TempResult);
		$_SESSION['ts_id']=$TempRow[0];
		$_SESSION['ts_name']=$TempRow[1];
		$_SESSION['s_banner']=$TempRow[2];
		$_SESSION['s_name']=$TempRow[3];

		dspSpil();
	}
	else
	{
		$mail=$_REQUEST['p_mail'];
		$pwd=$_REQUEST['p_pwd'];
		$TempResult=sql("select * from `player` where p_mail='$mail' and p_pwd='$pwd'");
		if ($TempRow = mysql_fetch_row($TempResult))
		{
		//** O.K. Login **//
			$_SESSION['p_id']=$TempRow[0];
			$_SESSION['p_first']=$TempRow[4];
			$_SESSION['p_mk']=$TempRow[15];
			$_SESSION['p_score']=$TempRow[10];
			$_SESSION['p_scorehigh']=$TempRow[11];
			$_SESSION['p_games']=$TempRow[12];
			$_SESSION['p_s_id']=$TempRow[3];
			$txt = '<object type="application/x-shockwave-flash" height="625" width="780" data="kasperFinal.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'">'."\r\n";
			$txt.= '<param name="movie" value="kasperFinal.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'" />'."\r\n";
			$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
			$txt.= '</object>'."\r\n";
			$_SESSION['Game']=$txt;
			if($_SESSION['p_s_id']>0)
			{
				$TempResult=sql("select ts_id, ts_name, s_banner, s_name from `teams`, `sponsor` where ts_id=0".$_SESSION['p_s_id']." and ts_s_id=s_id and s_active>0");
				$TempRow = mysql_fetch_row($TempResult);
				$_SESSION['ts_id']=$TempRow[0];
				$_SESSION['ts_name']=$TempRow[1];
				$_SESSION['s_banner']=$TempRow[2];
				$_SESSION['s_name']=$TempRow[3];

				$txt = '<object type="application/x-shockwave-flash" height="625" width="780" data="kasperFinal.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'&s_banner=admin/images/'.$_SESSION['s_banner'].'&ts_name='.$_SESSION['ts_name'].'">'."\r\n";
				$txt.= '<param name="movie" value="kasperFinal.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'&s_banner=admin/images/'.$_SESSION['s_banner'].'&ts_name='.$_SESSION['ts_name'].'" />'."\r\n";
				$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
				$txt.= '</object>'."\r\n";
				$_SESSION['Game']=$txt;

				dspGame();
			}
			else dspSponsorTeams();
		}
		else
		{
			dspSpil();
		}
	}
}

//***********************************************
//**
function dspGame() {
	echo "<div id=\"myarea\">";
	echo $_SESSION['Game'];
	echo "</div>";
}

//***********************************************
//**
function dspRingTone() {
		$txt = '<object type="application/x-shockwave-flash" height="500" width="780" data="ringeTone.swf?S_ID=123456789'.$_SESSION['p_first'].'">'."\r\n";
		$txt.= '<param name="movie" value="ringeTone.swf?S_ID=123456789&p_first='.$_SESSION['p_first'].'" />'."\r\n";
		$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
		$txt.= '</object>'."\r\n";
		echo "<div id=\"myarea\">";
		echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
		echo $txt;
		dspFMKB();
		echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
		echo "</div>";
}

//***********************************************
//**
function dspSpil() {

	$TempResult=sql("select * from `mails` where m_id=10");
	$TempRow = mysql_fetch_row($TempResult);

	echo "<div id=\"myarea\">";
	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';

	echo "<br /><br />";
	echo "<table width=\"750\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	echo "<tr valign=top>";
	echo "	<td width=\"245\" align=\"left\">";


	if(isset($_SESSION['p_id'])) {
	
		echo "<img class=myindent src=\"bh_spilnu.png\" alt=\"Spil nu\" /><br />";

		echo '<span class="myindent">Hej '.$_SESSION['p_first'].' Du er logget på</span>'; 
		echo '<form method="post" action="index.php">';
		echo '<input name="SP" type="hidden" value="Yes" />';
		echo '<input class="myindent" type="image" src="bt_spil.png" />';
		echo "<form>";
		echo "<img src=\"hr_line_left.png\" alt=\"\" />";

		echo '<p class="myindent">';
		echo "Du spiller på:<br />";
		echo '<img src="admin/images/'.$_SESSION['s_banner'].'" width="140" height="54" border="1" alt="" /><br />';
		echo "Team <b>".$_SESSION['ts_name']."</b>";
		echo "</p>"; 
		echo "<img src=\"hr_line_left.png\" alt=\"\" />";
		echo "<br /><span class=bblack><a href=\"index.php?LO=Yes\">Log af</a></span><br />";

	}
	else {

		echo "<img class=myindent src=\"bh_login.png\" alt=\"Log på og spil\" /><br />";
		echo '<form method="post" action="index.php">';
		echo '<input name="LI" type="hidden" value="Yes" />';
		echo '<span class="myindent">Din Email</span><br /><input class="myindent" name="p_mail" type="text" size="30" maxlength="30" value="'.$_REQUEST['p_mail'].'" /><br />';
		echo '<span class="myindent">Dit kodeord</span><br /><input class="myindent" name="p_pwd" type="password" size="30" maxlength="30" value="'.$_REQUEST['p_pwd'].'" />';
		echo '<br /><input class="myindent" type="image" src="bt_login.png" />';
		echo "<form>";

		echo "<img src=\"hr_line_left.png\" alt=\"\" />";
		echo "<p class=myindent>Ikke registreret? Registrer dig for at deltage i konkurrencen</p>";
		echo "<span class=bblack><a href=\"?RG=Yes\">Registrer</a></span><br />";
		echo "<img src=\"hr_line_left.png\" alt=\"\" />";
		echo "<p class=myindent>Prøv spillet uden at deltage i konkurrencen</p>";
		echo "<span class=bblack><a href=\"?SB=Yes\" valign=middle>Prøv spillet</a></span><br />";
	}
	echo "<img src=\"hr_line_left.png\" alt=\"\" /><br />";
	
	echo "<img src=\"b_spil_high1.png\" border=\"0\" alt=\"High Scores\" /><br />";
	$TempResult=sql("select * from `top` where t_datetime>='".date('Y-m-d')."' order by T_Score desc limit 10");
	while($TempRow = mysql_fetch_row($TempResult)) {
		$number=number_format($TempRow[2], 0, '.', '.');
		echo "<br/><span class=myindent><b>".$number." - ".$TempRow[1] ."</b></span>";
	}

	echo "<img src=\"hr_line_left.png\" alt=\"\" /><br />";
	echo "<img src=\"b_spil_high2.png\" border=\"0\" alt=\"High Scores\" /><br />";
	$TempResult=sql("select * from `top` order by T_Score desc limit 5");
	while($TempRow = mysql_fetch_row($TempResult)) {
		$number=number_format($TempRow[2], 0, '.', '.');
		echo "<br/><span class=myindent><b>".$number." - ".$TempRow[1] ."</b></span>";
	}
	echo "<img src=\"hr_line_left.png\" alt=\"\" /><br />";
	echo "	<img src=\"b_spil_high3.png\" alt=\"High Scores\" /><br />";
	$TempResult=sql("select * from `top` group by t_ts_id order by T_Score desc limit 5");
	while($TempRow = mysql_fetch_row($TempResult)) {
		$number=number_format($TempRow[2], 0, '.', '.');
		echo "<br/><span class=myindent><b>".$number." - ".$TempRow[1] ."</b></span>";
	}
	echo "<img src=\"hr_line_left.png\" alt=\"\" /><br />";
	echo "<span class=myindent>Vil du se alle HIGHSCORE lister?</span>";
	echo "<table><tr><td align=center><span class=bblack><a href=\"?PL=Yes\" valign=middle>Placeringer</a></span></td></tr></table><br />";


	echo "	</td>";
	echo "	<td align=\"left\">";
	echo "<img src=\"b_spil_prizes1.png\" alt=\"\" />";
	echo "<br/>Tekst<img src=\"b_spil_prizes1b.png\" alt=\"\" />";

	echo "<table><tr>";
	$TempResult=sql("select * from `raffle` where r_id!=3 limit 2");
	while($TempRow = mysql_fetch_row($TempResult))
	{
		echo "<td>";
		echo '<img src="admin/images/'.$TempRow[2].'" alt="" width="200" /><br />';
		echoHTML($TempRow[3]);
		echo "</td>";
	}
	echo "</tr></table>";

	echo "<br /><img src=\"b_spil_prizes0.png\" alt=\"\" /><br />";

	$TempResult=sql("select * from `raffle` where r_id=3");
	$TempRow = mysql_fetch_row($TempResult);
	echoHTML($TempRow[3]);

	echo "</td>";
	echo "</tr>";
	echo "</table>";

	dspFMKB();

	echo "</div>";

	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
}

//***********************************************
//**
function dspPress() {

	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
	echo '<div id="myarea">';
	echo '<img src="bh_presse.png" width="780" border="0" alt="" />';

	echo "<div id=\"myareaINNER\" style=\"text-align: left; \">";

	if ((int)($_REQUEST['PR'])>0)
	{
		$TempResult=sql("select * from `news` where n_id=".(int)($_REQUEST['PR']));
		$TempRow = mysql_fetch_row($TempResult);
		echo "<br /><b>".$TempRow[4]."</b>";
		echo "<br /><b>".$TempRow[5]."</b><br />";
		echoHTML($TempRow[6]);
		echo "<br />";
	}
	else
	{
		$TempResult=sql("select * from `news`");
		while ($TempRow = mysql_fetch_row($TempResult)) {
			echo "<br /><b>".$TempRow[4]."</b>";
			echo "<br /><b>".$TempRow[5]."</b><br />";
			echoHTML($TempRow[6]);
			echo "<br />";
			echo '<a href="?PR='.$TempRow[0].'"><span style="text-color: #000000; font-size=4px; background-color: #ffffff;"> LÆS MERE </span></a><br /><br />';
		}
	}

	dspFMKB();
	echo "</div>";
	echo "</div>";

	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
}

//***********************************************
//**
function dspDefault() {
	$TempResult=sql("select * from `mails` where m_id=10");
	$TempRow = mysql_fetch_row($TempResult);

	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
	echo '<div id="myarea">';
	echo '<br /><br /><img src="bh_omturnering.jpg" width="780" border="0" alt="" />';

	echo "<div id=\"myareaINNER\">";

	echo "<table width=\"740\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	echo "<tr>";
	echo "	<td width=\"300\" align=\"left\">";
	echoHTML($TempRow[2]);
	echo "	</td>";
	echo "	<td><img src=\"kk-cartoon01.png\" width=\"314\" alt=\"\" /></td>";
	echo "</tr>";
	echo "<tr align=left>";
	echo "	<td colspan=\"2\"><img src=\"kk-whois.png\" alt=\"\" /></td>";
	echo "</tr>";
	echo "</table>";

	dspFMKB();

	echo "</div>";
	echo "</div>";

	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
}

//***********************************************
//**
function dspSponsors() {
	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
	echo '<div id="myarea">';
	echo '<a href="#sponsor"><img src="kk-blivsponsor-banner.png" border="0" alt="" /></a>';
	echo '<br /><br /><img src="bh_sponsorer.png" border="0" alt="" />';

	echo "<div id=\"myareaINNER\">";

	$TempResult=sql("select s_id, ucase(s_name), s_contact, s_adr, s_zip, s_phone1, s_phone2, s_total, s_logo, s_banner, s_www, ZZCity from `sponsor`, `ZZIP` where s_id>1 and s_active>0 and `s_zip`=`ZZIP` order by `s_total` desc");
	$y=0;
	$txt="<table width=\"735\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr align=\"left\">";
	while($TempRow = mysql_fetch_row($TempResult)) {
		$hold=(int)($TempRow[7]);
		$free=$hold*10-8;
		$y=$y+1;
		if($y==3)
		{
			$y=1;
			$txt.="</tr><tr align=\"left\">";
		}
		$txt.="<td width=\"340\">";
		//** $txt.="<img src=\"team.png\" border=\"0\" width=\"325\" height=\"10\" alt=\"\" /><br />";
		$txt.="<table width=\"325\" border=\"0\" cellspacing=3><tr><td width=144>";
		$txt.="<img src=\"admin/images/{$TempRow[9]}\" border=\"2\" style=\"border-color: white\" width=\"140\" height=\"54\" align=\"left\" alt=\"\" /></td>";
		$txt.="<td><b>{$TempRow[1]}</b><br />";
		$txt.="	{$TempRow[3]}"."<br />";
		$txt.="	{$TempRow[4]} {$TempRow[11]}"."<br />";
	  	$txt.="	<a href=\"{$TempRow[10]}\" target=\"_blank\"><b>Hjemmeside</b></a><br />";
		$txt.="</span></td></tr></table>";
		$txt.="<br /><br /></td>";
		$total=$total+$hold;
		$x++;
	}
	$txt.="</tr></table>";

	echo $txt;
	$TempResult=sql("select * from `mails` where m_id=11");
	$TempRow = mysql_fetch_row($TempResult);
	$Lefttxt=$TempRow[2];

	$TempResult=sql("select * from `mails` where m_id=12");
	$TempRow = mysql_fetch_row($TempResult);
	$Righttxt=$TempRow[2];

	echo '<div id="sponsor"><img src="bliv_sponsor.png" border="0" alt="" width="740" height="57" /></div><br />';
	echo "<table width=\"740\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	echo "<tr valign=\"top\"><td width=\"340\" align=\"left\">";
	echoHTML($Lefttxt);
	echo "</td><td align=\"left\">";
	echoHTML($Righttxt);
	echo "</td></tr></table>";

	dspFMKB();

	echo "</div>";
	echo "</div>";

	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
}

//***********************************************
//**
function dspPlaces() {

	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
	echo '<div id="myarea">';
	echo '<div id="submenu" style="width: 780px; height: 27px; background: #000000; text-align: left;">';
	echo '<nobr><a href="?PL=Yes&Dagens=Yes"><img border="0" name="D1" src="butt_DAGENS.png" onmouseover="document.D1.src=\'butt_DAGENS_down.png\'" onmouseout="document.D1.src=\'butt_DAGENS.png\'" height="27" alt="" /></a>';
	echo '<a href="?PL=Yes&Dagens=No"><img border="0" name="D2" src="butt_ALLTIME.png" onmouseover="document.D2.src=\'butt_ALLTIME_down.png\'" onmouseout="document.D2.src=\'butt_ALLTIME.png\'" height="27" alt="" /></a><img border="0" src="toppind.png" height="27" alt="" /></nobr>';
	echo '';
	echo '</div>';
	echo "<div id=\"myareaINNER\"><br />";
	
	echo '<table width=740><tr><td align=left width=222>';
	
	if($_REQUEST['Dagens']=='Yes') {
		if ($_REQUEST['Teams']=='Yes') {
			$m_id=22;
			$SQL="select *, sum(t_score) as maxscore from `top`, `teams` where t_datetime>='".date('Y-m-d')."' and t_ts_id=ts_id group by t_ts_id order by maxscore desc limit 10";
			echo '<img src="bh_dagenshigh_hold.png" width="222" height="115" border="0" alt="" /></td>';
		}
		elseif ($_REQUEST['p_mk']=="D") {
			$m_id=23;
			$SQL="select * from `top`, `player` where t_datetime>='".date('Y-m-d')."' and t_p_id=p_id and p_mk=\"D\" order by T_Score desc limit 10";
			echo '<img src="bh_dagenshigh_drenge.png" width="222" height="115" border="0" alt="" /></td>';
		}
		elseif ($_REQUEST['p_mk']=="P") {
			$m_id=24;
			$SQL="select * from `top`, `player` where t_datetime>='".date('Y-m-d')."' and t_p_id=p_id and p_mk=\"P\" order by T_Score desc limit 10";
			echo '<img src="bh_dagenshigh_pige.png" width="222" height="115" border="0" alt="" /></td>';
		}
		else {
			$m_id=21;
			$SQL="select * from `top` where t_datetime>='".date('Y-m-d')."' order by T_Score desc limit 10";
			echo '<img src="bh_dagenshigh.png" width="222" height="115" border="0" alt="" /></td>';
		}
	}
	else {
		if ($_REQUEST['Teams']=='Yes') {
			$m_id=18;
			$SQL="select *, sum(t_score) as maxscore from `top`, `teams` where t_ts_id=ts_id group by t_ts_id order by maxscore desc limit 10";
			echo '<img src="bh_alltime_hold.png" width="222" height="115" border="0" alt="" /></td>';
		}
		elseif ($_REQUEST['p_mk']=="D") {
			$m_id=19;
			$SQL="select * from `top`, `player` where t_p_id=p_id and p_mk=\"D\" order by T_Score desc limit 10";
			echo '<img src="bh_alltime_drenge.png" width="222" height="115" border="0" alt="" /></td>';
		}
		elseif ($_REQUEST['p_mk']=="P") {
			$m_id=20;
			$SQL="select * from `top`, `player` where t_p_id=p_id and p_mk=\"P\" order by T_Score desc limit 10";
			echo '<img src="bh_alltime_pige.png" width="222" height="115" border="0" alt="" /></td>';
		}
		else {
			$m_id=17;
			$SQL="select * from `top` order by T_Score desc limit 10";
			echo '<img src="bh_alltime.png" width="222" height="115" border="0" alt="" /></td>';
		}
	}
	$TempResult=sql("select * from `mails` where m_id=".$m_id);
	$TempRow = mysql_fetch_row($TempResult);
	$Toptxt=$TempRow[2];

	echo '<td align="left" width="450">';
	echoHTML($Toptxt);

	echo '<br/><table><tr>';
	echo "<td><span class=bblack2><a href=\"?PL=Yes&Teams=Yes&Dagens=".$_REQUEST['Dagens']."\">Hold</a></span></td>";
	echo "<td><span class=bblack2><a href=\"?PL=Yes&Dagens=".$_REQUEST['Dagens']."\">Alle spillere</a></span></td>";
	echo "<td><span class=bblack2><a href=\"?PL=Yes&p_mk=D&Dagens=".$_REQUEST['Dagens']."\">Drengene</a></span></td>";
	echo "<td><span class=bblack2><a href=\"?PL=Yes&p_mk=P&Dagens=".$_REQUEST['Dagens']."\">Pigerne</a></span></td>";
	echo '<td></tr></table>';
	echo '<tr></table>'."\r\n";
	echo "<br />";

	$y=0;
	$txt="<table width=\"700\" cellpadding=\"0\" cellspacing=\"4\" border=\"0\">";
	//**	$txt.="<tr>";
	//**	$txt.="<td>		<h2>Nummer		</h2></td>";
	//**	$txt.="<td align=left>	<h2>Navn		</h2></td>";
	//**	$txt.="<td align=right>	<h2>Dræbte Kræftceller	</h2></td>";
	//**	$txt.="<td align=right>	<h2>Point		</h2></td>";
	//**	$txt.="</tr>"."\r\n";


	$TempResult=sql($SQL);
	while($TempRow = mysql_fetch_row($TempResult)) {
		$TeamSql=sql("select ts_name, s_banner from teams, sponsor where ts_s_id=s_id and ts_id=".$TempRow[6]);
		$TeamRow=mysql_fetch_row($TeamSql);
		$x++;
		$txt.="<tr valign=top>";
		$txt.='<td width="200"><img src="admin/images/'.$TeamRow[1].'" /></td>';
		$txt.="<td><b>{$x}.</b></td>";
		if ($_REQUEST['Teams']=='Yes') {
			$number=number_format($TempRow[21], 0, '.', '.');
			$txt.="<td align=left><b>{$TeamRow[0]}</b><br/>";
			$txt.="<font color=#f12300><b>Team Total score {$number}</b></font><br/>";
			$txt.="<br /></td>";
		}
		else {
			$number=number_format($TempRow[2], 0, '.', '.');
			$txt.="<td align=left><b>{$TempRow[1]}</b><br/>";
			$txt.="<font color=#f12300><b>Score {$number}</b></font><br/>";
			$txt.="Spiller for: {$TeamRow[0]}</td>";
		}
		$txt.="</tr>";
	}
	//**	if ($x==0) { $txt.="<tr><td colspan=4><h2>Nu har du Chancen! Kom på HighScore listen. Der er ingen, som har spillet endnu ...</h2></td></tr>"; }
	//**	if ($x==9) { $txt.="<tr><td colspan=4><h2>SKYND DIG! Du kan stadig nå det! Kom på HighScore listen. Der er 1 ledig plads ...</h2></td></tr>"; }
	//**	if ($x<9) { $txt.="<tr><td colspan=4><h2>Du kan stadig nå det! Kom på HighScore listen. Her er stadig plads ...</h2></td></tr>"; }
	$txt.="</table>";
	echo $txt;

	dspFMKB();

	echo "</div>";
	echo "</div>";

	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
}

//***********************************************
//**
function dspNewWelcome() {
	$url = "http://www.kemo-kasper.dk/actions.php?AP=Yes&". $_SERVER['QUERY_STRING'];

	$the_page = fopen($url, "r");
	while(!feof($the_page))
	{
		$text .= fgetss($the_page, 255);
	}
	fclose($the_page);

	$test=trim($text);
	if ($test=="OK")
	{
		echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
		echo '<div id="myarea">';
		$TempResult=sql("select * from `mails` where m_id=15");
		$TempRow = mysql_fetch_row($TempResult);
		echoHTML($TempRow[2]);
		sendEmail($_REQUEST['p_mail'], 3);
		echo "</div>";
		echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
	}
	else {
		$fejl="<h3>FEJL:".$text.":</h3>";
		dspRegister($fejl);
	}
}

//***********************************************
//**
function dspSponsorTeams() {
	echo '<div id="myarea">';
	echo "<hr /><h3>Vælg et hold som du vil spille for...</h3>";
	echo "<form action=\"index.php\" method=\"get\">";
	echo "<input type=\"hidden\" name=\"ST\" value=\"Yes\" />";
	$TempResult=sql("select ts_id, ts_name, s_banner, s_name from `teams`, `sponsor` where ts_s_id=s_id and s_active>0 and `ts_p9` is NULL limit 16");
	$x=0;
	while($TempRow = mysql_fetch_row($TempResult)) {
		$x++;
		echo '<a href="?LI=Yes&p_s_id='.$TempRow[0].'"><img src="admin/images/'.$TempRow[2].'" width="140" height="54" alt="'.$TempRow[1].'" border="0" /></a>';
		if ($x==4) {echo "<br /><br />"."\r\n"; $x=0; } else echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	echo "</form>";
	echo "</div>";
	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
}

//***********************************************
//**
function dspRegister($fejl) {

	$txt ='<form id="register" method="get" action="index.php">';
	$txt.='<input type="hidden" name="NW" value="Yes" />';
	$txt.='<table>';
	$txt.='<tr align="left"><td colspan="3"><br />Jeg ønsker at deltage i DM i Kemo-Kasper på et 10-mands firmahold og registrerer mig hermed.<br /></td></tr>';
	$txt.='<tr align="left"><td width="280">Fornavn	</td><td width="280">Adresse	</td><td width="280">Alder</td></tr>';
	$txt.='<tr align="left">';
	$txt.='	<td><input type="text" name="p_first" size="30" maxlength="30" value="'.$_REQUEST['p_first'].'" /></td>';
	$txt.='	<td><input type="text" name="p_adr" size="30" maxlength="30" value="'.$_REQUEST['p_adr'].'" /></td>';
	$txt.='	<td>';
	$txt.='		<select name="p_born" size="1">';
	$age=2003;
	while($age>1950) {
		$age=$age-1;
		$txt.='<option value="'.(int)($age).'">Jeg er '.(int)(2007-$age).' år gammel</option>';
	}
	$txt.='		</select>';
	$txt.=' </td>';
	$txt.='</tr>';

	$txt.='<tr align="left"><td>Efternavn</td><td>Postnummer	</td><td>Køn</td></tr>';
	$txt.='<tr align="left">';
	$txt.='	<td><input type="text" name="p_name" size="30" maxlength="30" value="'.$_REQUEST['p_name'].'" /></td>';
	$txt.='	<td><input type="text" name="p_zip" size="4" maxlength="4" value="'.$_REQUEST['p_zip'].'" /></td>';
	$txt.='	<td>';
	$txt.='		<select name="p_mk" size="1">';
		$txt.='<option value="D">'.'Jeg er en dreng'.'</option>';
		$txt.='<option value="P">'.'Jeg er en pige'.'</option>';
	$txt.='		</select>';
	$txt.=' </td>';
	$txt.='</tr>';

	$txt.='<tr align="left"><td>Jeg ønsker at spille ...</td><td>E-mail adresse	</td><td>Nyhedsbrev</td></tr>';
	$txt.='<tr align="left">';

	$txt.='	<td>';
	$txt.='		<select name="p_location" size="1">';
		$txt.='<option value="1">'.'Hjemmefra'.'</option>';
		$txt.='<option value="2">'.'Boomtown netcafé Århus'.'</option>';
		$txt.='<option value="3">'.'Boomtown netcafé København'.'</option>';
		$txt.='<option value="4">'.'Boomtown netcafé Odense'.'</option>';
		$txt.='<option value="5">'.'Boomtown netcafé Aalborg'.'</option>';
	$txt.='		</select>';
	$txt.=' </td>';

	$txt.='	<td><input type="text" name="p_mail" size="30" maxlength="30" value="'.$_REQUEST['p_mail'].'" /></td>';
	$txt.='	<td><input type="checkbox" name="p_news" checked="checked" />Ja tak, send mig også nyhedsbrev fra FMKB</td>';
	$txt.='</tr>';
	$txt.='</table>';
	$txt.='<br /><input type="image" src="bt_send.png" />';
	$txt.='</form><br />';

	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';

	echo '<div id="myarea">';
	echo '<br /><img src="bh_registrer.png" border="0" alt="" />';
	echo '<br />'.$fejl.'<br />';
	echo "<div id=\"myareaINNER\">";
	echo $txt;

	dspFMKB();

	echo "</div>";
	echo "</div>";

	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da" lang="da">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="imagetoolbar" content="false" />
	<meta name="publisher" content="Yes2Mail ApS" />
	<meta name="copyright" content="Copyright © 2007 Yes2Mail ApS, René Østerballe" />
	<meta name="author" content="© 2007 René Østerballe" />

	<meta name="description" content="Danmarks Mesterskab i Kemo Kasper 2007 prensenteret af Foreningen Familier med kræftramte børn" />
	<title>DM i Kemo Kasper 2007</title>
	<link rel="stylesheet" type="text/css" href="kemokasper.css" />
	<link rel="stylesheet" type="text/css" media="handheld" href="kemokasper_wap.css" />
	<script src="kemokasper.js" type="text/javascript"></script>
</head>

<body>
<center>

<div id="menu" style="top: 15px; width: 780px; height: 26px;">
	<a href="index.php"><img border="0" src="butt_HOME.png" height="25" width="61" alt="" /></a>
	<a href="?BG=Yes"><img border="0" src="butt_SPIL.png" height="25" alt="" /></a>
	<a href="?RT=Yes"><img border="0" src="butt_RING.png" height="25" alt="" /></a>
	<a href="?DF=Yes"><img border="0" src="butt_FILM.png" height="25" alt="" /></a>
	<a href="?RG=Yes"><img border="0" src="butt_REG.png" height="25" alt="" /></a>
	<a href="?AG=Yes"><img border="0" src="butt_OM.png" height="25" alt="" /></a>
	<a href="?LS=Yes"><img border="0" src="butt_SPONS.png" height="25" alt="" /></a>
	<a href="?PL=Yes"><img border="0" src="butt_PLACE.png" height="25" alt="" /></a>
	<a href="?PR=Yes"><img border="0" src="butt_PRESSE.png" height="25" alt="" /></a>
	<a href="#"><img border="0" src="butt_CREDITS.png" height="25" alt="" /></a>
</div>

<?php
	if ($_REQUEST['LS']!="") dspSponsors();
	elseif ($_REQUEST['RG']!="") dspRegister('');
	elseif ($_REQUEST['NW']!="") dspNewWelcome();
	elseif ($_REQUEST['ST']!="") dspSponsorTeams();
	elseif ($_REQUEST['DF']!="") dspFilm();
	elseif ($_REQUEST['BG']!="") dspSpil();
	elseif ($_REQUEST['RT']!="") dspRingTone();
	elseif ($_REQUEST['SP']!="") dspGame();
	elseif ($_REQUEST['SB']!="") dspBeta();
	elseif ($_REQUEST['AG']!="") dspDefault();
	elseif ($_REQUEST['PL']!="") dspPlaces();
	elseif ($_REQUEST['PR']!="") dspPress();
	elseif ($_REQUEST['LI']!="") dspLogin();
	elseif ($_REQUEST['NEWSLETTER25']!="") sendNewsLetter(25);
	else dspHome();
?>

</center>
</body>
</html>
