<?php

//** Connect to SQL Server and select Database.
if (!mysql_connect("localhost", "kemo_kasper_dk", "QvK54Xpa")) exit;
if (!mysql_select_db("kemo_kasper_dk")) exit;

//** Start a Session so we can remember each user.
session_start();
if (!isset($_SESSION['HTTP_REFERER'])) $_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];

//** Execute a SQL statement and return the result.
function sql($statment,$assoc=1) {
	if(!$result = mysql_query($statment)) {
		echo mysql_error();
		exit;
	}
	else return $result;
}

//** Send Email to a new user with this function.
function sendEmail($p_mail, $m_id) {
	$TempSql=sql("select * from `player`, locations where p_active=1 and p_location=l_id and `p_mail`='".$p_mail."'");
	if ($TempRow=mysql_fetch_row($TempSql))
	{
		$txtVer = array("\r\n", "\n"); 
		$htmVer = array("<br>", "<br>");

		if ($m_id>0) $TempSqlMail=sql("select m_name, m_body from mails where m_id=".$m_id);
		elseif ($TempRow[2]==1) $TempSqlMail=sql("select m_name, m_body from mails where m_id=16");
		else $TempSqlMail=sql("select m_name, m_body from mails where m_id=3");

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
			$messageHTML= str_replace('[l_mail]', $TempRow[28], $messageHTML);
			
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

//** REMINDER/NEWS E-MAIL's
//** A Function to Send Mails merged with user data.
//**
function sendReminder() {
	echo "<div id=\"myarea\">";

	$txtVer = array("\r\n", "\n"); 
	$htmVer = array("<br>", "<br>");
	$m_id=$_REQUEST['m_id'];
	$TempSqlMail=sql("select m_name, m_body from mails where m_id=".$m_id);
	if($TempRowMail=mysql_fetch_row($TempSqlMail)) {

		$txt='';
		$messageSubject='Deltag i interview om Kemo-Kasper'."\n";

		if($_REQUEST['TEST']=='No') 
					$TempSql=sql("select * from `player`, locations where p_location=l_id and (p_zip>=0 and p_zip<=2999) and p_active=1 and p_news!=".$m_id." limit 30");
		else	$TempSql=sql("select * from `player`, locations where p_location=l_id and (p_zip>=0 and p_zip<=2999) and (p_mail='reneosterballe@gmail.com' or p_mail='jv@fmkb.dk' or p_mail='reneidk@hotmail.com' or p_mail='jakob@yes2mail.dk') limit 4");

		while ($TempRow=mysql_fetch_row($TempSql)) {
			$x++;
			$messageHTML= str_replace($txtVer, $htmVer, $TempRowMail[1]);

			$messageHTML= str_replace('[p_id]', $TempRow[0], $messageHTML);
			$messageHTML= str_replace('[p_first]', $TempRow[4], $messageHTML);
			$messageHTML= str_replace('[p_name]', $TempRow[5], $messageHTML);
			$messageHTML= str_replace('[p_adr]', $TempRow[6], $messageHTML);
			$messageHTML= str_replace('[p_zip]', $TempRow[7], $messageHTML);
			$messageHTML= str_replace('[p_mail]', $TempRow[8], $messageHTML);
			$messageHTML= str_replace('[p_pwd]', $TempRow[18], $messageHTML);

			$messageHTML= str_replace('[l_name]', $TempRow[25], $messageHTML);
			$messageHTML= str_replace('[l_adr]', $TempRow[26], $messageHTML);
			$messageHTML= str_replace('[l_zip]', $TempRow[27], $messageHTML);
			$messageHTML= str_replace('[l_mail]', $TempRow[28], $messageHTML);
			
			$boundary = md5(uniqid(time())); 

			$body_html		= '<html><body>'.$messageHTML.'</body></html>';
			$body_simple	= str_replace($htmVer, $txtVer, $messageHTML);
			$body_plain		= strip_tags($body_simple);

			$headers  = 'From: Kemo-Kasper <nyheder@kemo-kasper.dk>'."\n"; 
			$headers .= 'To: '.$TempRow[4].' <' . $TempRow[8] . ">\n";
			//** $headers .= 'To: mailtest <mailtest@mailtest.web.surftown.dk>'."\n";

			$headers .= 'MIME-Version: 1.0' ."\n"; 
			$headers .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '"' . "\n\n";

			$headers .= $body_plain . "\n";
			$headers .= '--' . $boundary . "\n";

			$headers .= 'Content-Type: text/plain; charset=ISO-8859-1; format=flowed' ."\n"; 
			$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\n";
			$headers .= $body_plain . "\n";
			$headers .= '--' . $boundary . "\n";
			$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1; format=flowed' ."\n";
			$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\n";
			$headers .= $body_html . "\n"; 
			$headers .= '--' . $boundary . "--\n"; 
	
			$txt.= '<br/>'.$x.':'.$TempRow[8].','."\r\n";

			mail('', $messageSubject, '', $headers);
			sql("update player set p_news=".$m_id." where p_id=".$TempRow[0]);
		}
		echo $txt;
		echo '<br/><br/><a href="#">CONTINUE (SEND MORE) ... ? (PLEASE PRESS [F5])</a>';
	}
	echo "</div>";
}

//**
//** This simply replaces DB line brakes with HTML <br/> brakes
//**
function echoHTML($text) {
	$cr="\r";
	echo str_replace($cr, '<br />', $text);
}

//**
//** Here we display the Buttom Info (Logo and contact info)
//** This goes on every page (except the front page)
//** You should All-Ways call this at the end of a Page-Display.
//**
function dspFMKB() {
	echo '<br/><table class="myident" border="0" cellspacing="0" cellpadding="0" width="700">';
	echo '<tr><td colspan="2" align="left"><img src="hr_long.png" width="696" height="8" alt="" /></td></tr>';
	echo '<tr valign="top" align="left"><td width="150"><a href="http://www.fmkb.dk"><img src="fmkb_logo_circle.png" width="150" height="150" border="0" alt="" /></a>';
	echo "</td><td align=\"left\" width=\"546\">";

	$TempResult=sql("select * from `mails` where m_id=13");
	$TempRow = mysql_fetch_row($TempResult);
	echo '<div class="fmkb">';
	echoHTML($TempRow[2]);
	echo '</div>';
	echo '</td></tr></table>';	
	echo '<img src="kk-bund-banner.png" border="0" alt="" width="780" /><br /><img src="boomtown_banner1.png" border="0" alt="" width="780" />';
}
//**
//** Print Certificate
//**
function dspDiplom() {
	echo "<div id=\"myarea\">";
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<form id="diplom" action="diplom.php" target="diplomwindow" method="post">';
	echo '<h1>UDSKRIV DIPLOM</h1><h2>Indtast turneringskode:</h2><input name="p_id" size="10" maxlength="10" />';
	echo '<br/><input id="bt_send" type="image" src="bt_send.png" />';
	echo '</form>';

	dspFMKB();
	echo '</div>';
}
//**
//** Stats PRIVATE
//**
function dspSTATS() {

	echo "<div id=\"myarea\">";
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<h1>STATISTIK</h1>';
	$TempRow = mysql_fetch_row(sql("select count(*) from top"));
	echo "<h3>Antal Spil til dato: $TempRow[0]</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_mk='D'"));
	$ialt=$TempRow[0];
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_mk='D' and p_s_id>0 and p_games>0"));
	$teams=$TempRow[0];
	echo "<h3>Drenge ialt (aktive): $ialt ($teams)</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_mk='P'"));
	$ialt=$TempRow[0];
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_mk='P' and p_s_id>0 and p_games>0"));
	$teams=$TempRow[0];
	echo "<h3>Piger ialt (aktive): $ialt ($teams)</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_win>0"));
	echo "<hr/><h2>Antal præmier udtrukket: $TempRow[0]</h2>";
	echo "<b>FØLGENDE SPILLERE HAR SPILLET</b>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_user='BOOM2'"));
	echo "<hr/><h3>Lokation: Århus: $TempRow[0]</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_user='BOOM3'"));
	echo "<h3>Lokation: København: $TempRow[0]</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_user='BOOM4'"));
	echo "<h3>Lokation: Odense: $TempRow[0]</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_user='BOOM5'"));
	echo "<h3>Lokation: Aalborg: $TempRow[0]</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_user='BOOM1'"));
	echo "<h3>Lokation: Hjemmefra: $TempRow[0]</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from teams"));
	echo "<hr/><h3>Antal Teams Oprettet: $TempRow[0]</h3>";
	$TempRow = mysql_fetch_row(sql("select count(*) from sponsor"));
	echo "<h1>Antal Sponsorer Oprettet: $TempRow[0]</h1>";

	dspFMKB();
	echo '</div>';
}

//**
//** The front page is Special (it uses a background image)
//** All elements should be positioned through CSS with X,Y
//**
function dspHome() {
	echo '<div id="myhome"><div id="mynews">';
	$TempResult=sql("select * from news where n_active=1 order by n_start desc limit 3");
	while($TempRow = mysql_fetch_row($TempResult)) {

		echo $TempRow[4]."<br />";
		echo "<b>".$TempRow[5]."</b><br />";
		echo '<a href="?PR='.$TempRow[0].'"><img src="readmore.png" border="0" alt="Læs mere" /></a><br /><br />';
	}
	//	echo '<a style="position: absolute; left: 200px; top: 150px" href="?SB=Yes"><img src="big_spil.png" border="0" alt="Spil" /></a>';
	echo '<a style="position: absolute; left: 308px; top: 150px" href="?RGM=Yes"><img src="big_registrer.png" border="0" alt="Registrer" /></a>';
	//**	echo '<a style="position: absolute; left: 500px; top: 150px" href="?TUR=Yes"><img src="live01.gif" border="0" alt="Følg turneringen" /></a>';
	echo '</div></div>';
	echo '<img src="kk_home_sponsors.png" border="0" alt="" height="140" width="780" />';
}
//**
//** The front page is Special (it uses a background image)
//** All elements should be positioned through CSS with X,Y
//** This function makes the Special Page for the Tournament Day.
function dspHomeTour() {
	echo '<div id="myhome"><div id="mynews">';

	if ($_SESSION['p_id']>0) {
		echo '<p class="myindent"><font size="+1">Dit turnerings ID er:<br/><br/>'.$_SESSION['p_id'].'</font></p>';
		echo '<br/><br/><span class="myindent"><a href="?SPD=Yes"><img src="big_spil.png" border="0" alt="Spil" /></a></span>';
	}
	else {
		echo '<form id="login" name="login" method="get" action="index.php">';
		echo '<input name="LI" type="hidden" value="Yes" />';
		echo '<input name="HOMETOUR" type="hidden" value="Yes" />';
		echo '<span class="myindent">Din Email</span><br /><input class="myindent" name="p_mail" type="text" size="20" maxlength="30" value="'.$_REQUEST['p_mail'].'" /><br />';
		echo '<span class="myindent">Dit kodeord</span><br /><input class="myindent" name="p_pwd" type="password" size="20" maxlength="20" value="'.$_REQUEST['p_pwd'].'" />';
		echo '<br /><br /><input class="myindent" type="image" src="bt_login2.png" />';
		echo "</form>";
	}
	//**	echo '<a style="position: absolute; left: 308px; top: 150px" href="?RG=Yes"><img src="big_registrer.png" border="0" alt="Registrer" /></a>';
	echo '<a style="position: absolute; left: 500px; top: 150px" href="?TUR=Yes"><img src="live01.gif" border="0" alt="Følg turneringen" /></a>';
	echo '</div></div>';
	echo '<img src="kk_home_sponsors.png" border="0" alt="" height="140" width="780" />';
}

//**
//** Here we simply embed some other source.
//** This other source could be IFAMES and images
//** The Other Source is defined first as $txt
//** UNIQUE! Outher elements ... Same as FILMS
//**
function dspRingTone() {
  $txt='<div id="flashcontent">RINGETONE</div><script type="text/javascript">
   var so = new SWFObject("ringeTone.swf", "ringetone", "780", "500", "8", "#ffffff");
   so.write("flashcontent");
</script>';

	echo "<div id=\"myarea\">";
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo $txt;
	dspFMKB();
	echo '</div>';
}
//**
//** Here we simply embed some other source.
//** This other source could be IFAMES and images
//** The Other Source is defined first as $txt
//** UNIQUE! Outher elements ... Same as FILMS
//**
function dspChat() {
	echo "<div id=\"myarea\">";
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<iframe src="chat/chat.html" height=510 width="780" scrolling="no" border="0" frameborder="0"></iframe>';
	dspFMKB();
	echo '</div>';
}
//**
//** This is flv_player with playlist from RSS
//** NOT USED
//**
function dspTurnering() {

	$kills = array(0,0,0,0,0,0);
	$mk = array("", "D","P");
	$class = array("", "", "bblack2", "bblack2", "bblack2", "bblack2");
	$mkfull = array("", "DRENGE","PIGER");
	$boomtown = array("Deltager ikke", "Hjemmefra", "Århus", "København", "Odense", "Aalborg");
	$webcam = array("", "", "7", "8", "3", "4");
	$webcam2 = array("", "", "7", "8", "7", "8");

	$limit=4;
	$p_location=4;
	if ($_REQUEST['Limit']>10) $limit=0+$_REQUEST['Limit'];
	if ($_REQUEST['p_location']>0) {
		$p_location=0+$_REQUEST['p_location'];
	}
	$class[$p_location]="borange2";

	$TempResult=sql("select p_user, sum(p_tkills) from player where p_user='BOOM1' or p_user='BOOM2' or p_user='BOOM3' or p_user='BOOM4' or p_user='BOOM5' group by p_user order by p_user asc limit 5");
	while($TempRow = mysql_fetch_row($TempResult)) {
		$loc=(int)(substr($TempRow[0],4,1));
		$kills[$loc]=number_format($TempRow[1], 0, '.', '.');
		$kills[0]=$kills[0]+$TempRow[1];
	}
	$total=number_format($kills[0], 0, '.', '.');

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	//**	echo '<img src="bh_turnering.png" width="780" height="85" border="0" alt="" /><br/>';
	echo '<br/><img src="film_direkte.png" width="780" alt=""/><br/>';
	echo "<div id=\"myarea\">";
	echo "<div id=\"myareaINNER\" style=\"text-align: left; \">";

	echo '<table width=700 border=0 cellspacing=0 cellpadding=0>';
	echo '<tr>';
	echo '<td>';
	echo '	<table border=0 cellpadding=0>';
	echo '		<tr>';
	echo "			<td><span class=".$class[2]."><a href=\"?TUR=Yes&p_location=2\">".$boomtown[2]."</a></span></td>";
	echo "			<td><span class=".$class[3]."><a href=\"?TUR=Yes&p_location=3\">".$boomtown[3]."</a></span></td>";
	echo "			<td><span class=".$class[4]."><a href=\"?TUR=Yes&p_location=4\">".$boomtown[4]."</a></span></td>";
	echo "			<td><span class=".$class[5]."><a href=\"?TUR=Yes&p_location=5\">".$boomtown[5]."</a></span></td>";
	echo '		</tr>';
	echo '	</table>';
	echo '<br/>';

	echo '<img src="film_boomtown.png"/>';

	echo '</td><td width=20>&nbsp;</td>';
	echo '<td>';
	echo '<table border="0" cellspacing=0>';
	echo "	<tr><td><img src=\"boomtown_2.png\" /></td><td style=\"font-weight: bold; font-size: 18pt; color: #f12300; \" align=right>$kills[2]</td></tr>";
	echo "	<tr><td colspan=2><img src=\"hr_line_right.png\" width=340 /></td></tr>";
	echo "	<tr><td><img src=\"boomtown_3.png\" /></td><td style=\"font-weight: bold; font-size: 18pt; color: #f12300; \" align=right>$kills[3]</td></tr>";
	echo "	<tr><td colspan=2><img src=\"hr_line_right.png\" width=340 /></td></tr>";
	echo "	<tr><td><img src=\"boomtown_4.png\" /></td><td style=\"font-weight: bold; font-size: 18pt; color: #f12300; \" align=right>$kills[4]</td></tr>";
	echo "	<tr><td colspan=2><img src=\"hr_line_right.png\" width=340 /></td></tr>";
	echo "	<tr><td><img src=\"boomtown_5.png\" /></td><td style=\"font-weight: bold; font-size: 18pt; color: #f12300; \" align=right>$kills[5]</td></tr>";
	echo "	<tr><td colspan=2><img src=\"hr_line_right.png\" width=340 /></td></tr>";
	echo "	<tr><td><img src=\"boomtown_1.png\" /></td><td style=\"font-weight: bold; font-size: 18pt; color: #f12300; \" align=right>$kills[1]</td></tr>";
	echo "	<tr><td colspan=2><img src=\"hr_line_right.png\" width=340 /></td></tr>";
	echo "	<tr><td><img src=\"boomtown_0.png\" /></td><td style=\"font-weight: bold; font-size: 18pt; color: #000000; \" align=right>$total</td></tr>";
	echo '</table>';
	echo '</td>';
	echo '</tr>';
	echo '</table>';

	echo '<img src="hr_long.png" width="696" height="8" alt="" />';

	
	$txt ="<table border=0  cellpadding=2 width=700>";

	while($y++<2) {
		$txt.='<tr><th colspan=4><b>'.$mkfull[$y].'</b></th></tr>';
		$txt.='<tr>';
		$txt.="<th align=left><b>NR.</b></th>";
		$txt.="<th align=left><b>NAVN</b></th>";
		$txt.="<th align=left><b>HOLD</b></th>";
		$txt.="<th align=right><b>DRÆBTE KRÆFTCELLER</b></font></th>";
		$txt.="</tr>";

		$SQL="select p_s_id, p_tkills, ucase(p_first), p_born, p_win from `player` where p_active=1 and p_tkills>0 and p_s_id>0 and p_mk=\"".$mk[$y]."\" and p_user='BOOM".$p_location."' order by p_tkills desc limit ".$limit;
		$x=0;
		$TempResult=sql($SQL);
		while($TempRow = mysql_fetch_row($TempResult)) {
			$TeamSql=sql("select ts_name, s_banner from teams, sponsor where ts_s_id=s_id and ts_id=".$TempRow[0]);
			$TeamRow=mysql_fetch_row($TeamSql);
			$x++;
			$age=2009-$TempRow[3];
			$number=number_format($TempRow[1], 0, '.', '.');
			$txt.="<tr>";
			if ($x==1) {
			$txt.="<td align=left><b>{$x}.</b></td>";
			$txt.="<td align=left><b>{$TempRow[2]}, {$age} år.</b></td>";
			$txt.="<td align=left><b>{$TeamRow[0]}</b></td>";
			$txt.="<td align=right><b>{$number}</b></font></td>";
			}
			else {
			$txt.="<td align=left>{$x}.</td>";
			$txt.="<td align=left>{$TempRow[2]}, {$age} år.</td>";
			$txt.="<td align=left>{$TeamRow[0]}</td>";
			$txt.="<td align=right>{$number}</font></td>";
			}
			$txt.="</tr>";
		}
	}
	$txt.="</table>";
	echo $txt;

	echo '<img src="hr_long.png" width="696" height="8" alt="" />';

	echo '</div>';
	echo '</div>';
}
//**
//** This is flv_player with playlist from RSS
//** NOT USED
//**
function dspFilmPlayer() {

  $txt='<div id="flashcontent">DU SKAL AKTIVERE ACTIVE-X I DIN BROWSER FOR AT SE FILMENE</div><script type="text/javascript">
   var so = new SWFObject("kk_flv_player.swf", "mymovie", "700", "400", "8", "#ffffff");
   so.addParam("flashvars", "&height=400&width=700&autoscroll=true&displaywidth=500&overstretch=true&loop=true&allowfullscreen=true&thumbsinplaylist=false&autostart=true&file=kk_flv_playlist.xml");
   so.write("flashcontent");
</script>';

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<br/><img src="bh_film.png" width="780" border="0" alt="" />';

	echo "<div id=\"myarea\">";
	echo '<img src="hr_long.png" width="700" height="8" alt="" />';
	echo "<div id=\"myareaINNER\" style=\"text-align: left; \">";

	echo $txt;

	echo '</div>';
	dspFMKB();
	echo '</div>';
}
//**
//** Display movies
//**
function dspFilm() {

  $txt='<div id="flashcontent">FILM2</div><script type="text/javascript">
   var so = new SWFObject("filmPage2.swf", "filmpage", "700", "400", "8", "#ffffff");
   so.addParam("flashvars", "&film='.$_REQUEST['film'].'");
   so.write("flashcontent");
</script>';

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<br/><img src="bh_film.png" width="780" border="0" alt="" />';
	echo "<div id=\"myarea\">";
	echo '<img src="hr_long.png" width="696" height="8" alt="" />';
	echo "<div id=\"myareaINNER\" style=\"text-align: left; \">";
	echo $txt;
	echo '</div>';
	dspFMKB();
	echo '</div>';
}
//**
//** Here we simply embed some other source.
//** This other source could be IFAMES and images
//** The Other Source is defined first as $txt
//** Maybee it should be parsed as a parameter...
//**
//** SAME as: dspCredits()
function dspFilmOptiker() {

  $txt='<div id="flashcontent">OPTIKER</div><script type="text/javascript">
   var so = new SWFObject("optikeren.swf", "credits", "780", "500", "8", "#ffffff");
   so.write("flashcontent");
</script>';

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo "<div id=\"myarea\">";
	echo $txt;
	echo '</div>';
}

//**
//** Here we simply embed some other source.
//** This other source could be IFAMES and images
//** The Other Source is defined first as $txt
//** Maybee it should be parsed as a parameter...
//**
//** SAME as: dspFilm()
//**
function dspCredits() {

  $txt='<div id="flashcontent">CREDITS</div><script type="text/javascript">
   var so = new SWFObject("credits.swf", "credits", "780", "600", "8", "#ffffff");
   so.write("flashcontent");
</script>';

	echo "<div id=\"myarea\">";
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo $txt;
	dspFMKB();
	echo '</div>';
}

//**
//** Here we simply embed some other source.
//** This other source could be IFAMES and images
//** The Other Source is defined first as $txt
//** UNIQUE! No outher elements ???
//**
function dspBeta() {

	$txt = '<object type="application/x-shockwave-flash" height="625" width="780" data="kasperFinal.swf">'."\r\n";
	$txt.= '<param name="movie" value="kasperFinal.swf" />'."\r\n";
	$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
	$txt.= "</object>"."\r\n";

	echo $txt;
}

//**
//** Here we embed some other source.
//** UNIQUE! No buttom elements ???
//**
function dspGame() {
	$txt = '<object type="application/x-shockwave-flash" height="625" width="780" data="kasperFinal.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'&s_banner=admin/images/'.$_SESSION['s_banner'].'&ts_name='.$_SESSION['ts_name'].'">'."\r\n";
	$txt.= '<param name="movie" value="kasperFinal.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'&s_banner=admin/images/'.$_SESSION['s_banner'].'&ts_name='.$_SESSION['ts_name'].'" />'."\r\n";
	$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
	$txt.= "</object>"."\r\n";

	echo $txt;
}
//**
//** Here we embed some other source.
//** UNIQUE! No buttom elements ???
//**
function dspGameDay() {
	$txt = '<object type="application/x-shockwave-flash" height="625" width="780" data="kasperGameDay.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'&s_banner=admin/images/'.$_SESSION['s_banner'].'&ts_name='.$_SESSION['ts_name'].'">'."\r\n";
	$txt.= '<param name="movie" value="kasperGameDay.swf?s_id='.$_SESSION['p_id'].'&p_id='.$_SESSION['p_id'].'&p_mk='.$_SESSION['p_mk'].'&p_highscore='.$_SESSION['p_scorehigh'].'&p_first='.$_SESSION['p_first'].'&s_banner=admin/images/'.$_SESSION['s_banner'].'&ts_name='.$_SESSION['ts_name'].'" />'."\r\n";
	$txt.= '<param name="allowScriptAccess" value="sameDomain" />';
	$txt.= "</object>"."\r\n";

	echo $txt;
}

//**
//** User logout - minimum SESSION p_id=0 and Clear
//** Nowhere should any test be made by other than
//** the SESSION p_id !!!
//**
function dspLogOut() {
	$_SESSION['p_id']=0;
	if ($_REQUEST['HOMETOUR']!="") dspHomeTour();
	else dspSpil();
}

//**
//** Change user to play at another location.
//**
function dspChangeLocation() {
	if ($_SESSION['p_id']>0 || isset($_REQUEST['p_location'])) {
		sql("update `player` set p_location=".$_REQUEST['p_location']." where p_id=".$_SESSION['p_id']);
		$_SESSION['p_location']=$_REQUEST['p_location'];
	}
	dspSpil();
}

//**
//** The Login have 2 phases !
//** - First-timers have to choose a Team
//** - others just have to validate
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

		if($_REQUEST['HOMETOUR']!="") dspHomeTour();
		else dspSpil();
	}
	else {
		$mail=$_REQUEST['p_mail'];
		$pwd=$_REQUEST['p_pwd'];
		$TempResult=sql("select * from `player` where p_active=1 and p_mail='$mail' and p_pwd='$pwd'");
		//** O.K. Login ? **//
		if ($TempRow = mysql_fetch_row($TempResult)) {
			$_SESSION['p_id']=$TempRow[0];
			$_SESSION['p_location']=$TempRow[2];
			$_SESSION['p_first']=$TempRow[4];
			$_SESSION['p_mk']=$TempRow[15];
			$_SESSION['p_score']=$TempRow[10];
			$_SESSION['p_scorehigh']=$TempRow[11];
			$_SESSION['p_games']=$TempRow[12];
			$_SESSION['p_s_id']=$TempRow[3];
			if($_SESSION['p_s_id']>0) {
				$TempResult=sql("select ts_id, ts_name, s_banner, s_name from `teams`, `sponsor` where ts_id=0".$_SESSION['p_s_id']." and ts_s_id=s_id and s_active>0");
				$TempRow = mysql_fetch_row($TempResult);
				$_SESSION['ts_id']=$TempRow[0];
				$_SESSION['ts_name']=$TempRow[1];
				$_SESSION['s_banner']=$TempRow[2];
				$_SESSION['s_name']=$TempRow[3];
				if($_REQUEST['HOMETOUR']!="") dspHomeTour();
				else dspSpil();
			}
			else dspSponsorTeams();
		}
		else {
			if($_REQUEST['HOMETOUR']!="") dspHomeTour();
			else dspSpil();
		}
	}
}

//**
//** Show all winners
//**
function dspWinners() {
	echo "<div id=\"myarea\">";
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<div id="myareaINNER" style="text-align: left;">';
	echo '<br/><h1>OVERSIGT OVER ALLE VINDERE</h1><img src=trophy.png />';
	echo 'Se vindere ved DM i Kemo-Kasper 2007. <b>Konkurrencen er slut</b>';	

	echo '<table><tr>';
	$mk = array("", "D", "P");

	while ($x++<2) {
		echo '<td>';
		$TempResult=sql("select p_id, `p_first`, (2009-p_born) as age, `ZZCity`, `r_text`, r_date, r_id from `raffle`, `player`, `ZZIP` WHERE `r_p_id`>0 and `r_p_id`=`p_id` and p_mk='".$mk[$x]."' and `p_zip`=`ZZIP` ORDER BY `raffle`.`r_date` desc");
		while ($TempRow = mysql_fetch_row($TempResult)) {
			echo '<br/><img border="0" src="hr_line_left.png" height="8" alt="" />';
			echo '<br/>Dato: '.$TempRow[5];
			echo "<p style=\"margin: 0 0 0 0; color: #f12300; left: 20px;\"><b><font size=\"+1\">".$TempRow[1].", ".$TempRow[2]."år.</font> Fra ".$TempRow[3]."</b></p>";
			echo "<b>Vandt ".$TempRow[4]."</b>";
			//** sql('update player set p_win='.$TempRow[6]. ' where p_id='.$TempRow[0]);
		}
		echo '</td>';
	}

	echo '</tr></table>';
	echo '</div>';
	dspFMKB();
	echo '</div>';

}

//**
//** The Main page for login and playing.
//**
function dspSpil() {
	$boomtown = array("Deltager ikke", "Hjemmefra", "hos Boomtown Århus", "hos Boomtown København", "hos Boomtown Odense", "hos Boomtown Aalborg");
	$selected = array("selected", "selected", "selected", "selected", "selected", "selected");

	$TempResult=sql("select * from `mails` where m_id=10");
	$TempRow = mysql_fetch_row($TempResult);

	echo "<div id=\"myarea\">";
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';

	echo "<br />";
	echo "<br/><table width=\"780\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	echo "<tr valign=top>";
	echo "	<td width=\"245\" align=\"left\">";

	if($_SESSION['p_id']>0 && $_SESSION['p_s_id']>0) {
	
		echo "<img class=myindent src=\"bh_spilnu.png\" alt=\"Spil nu\" /><br />";

		echo '<p class="myindent"><b>Hej '.$_SESSION['p_first'].'</b> Du er logget på.</p>'; 
		echo '<form method="post" action="index.php">';
		echo '<input name="SP" type="hidden" value="Yes" />';
		echo '<input class="myindent" type="image" src="bt_spil.png" />';
		echo "</form>";
		//** echo "<img src=\"hr_line_left.png\" alt=\"\" />";

		echo '<p class="myindent">';
		echo "Du spiller for:<br />";
		echo "<b>".$_SESSION['ts_name']."</b></br/>";
		echo '<img src="admin/images/'.$_SESSION['s_banner'].'" width="140" height="54" border="0" alt="" />';

		//**	echo '<form id="changelocation" name="changelocation" method="post" action="index.php?CL=Yes">';
		//**	echo '<select name="p_location" onChange="document.changelocation.submit();">';
		//**	while ($x++<5) {
		//**		if ($_SESSION['p_location']==$x) $selected=" selected"; else $selected="";
		//**		echo '<option value="'.$x.'"'.$selected.'>'.$boomtown[$x].'</option>';
		//**	}
		//**	echo '</select>';
		//**	echo '</form>';
		//**	if ($_SESSION['p_location']==1) {
		//**	echo '<b>HUSK!</b> der er 8 hovedpræmier i hver af de fire Boomtown netcaféer. Vælg herover i hvilken Boomtown du gerne vil spille.';
		//**	}
		echo "</p>"; 

		//** echo "<img src=\"hr_line_left.png\" alt=\"\" />";
		echo "<br /><span class=bblack><a href=\"index.php?LO=Yes\">Log af</a></span><br />";
	}
	else {
		echo "<img class=myindent src=\"bh_login.png\" alt=\"Log på og spil\" /><br />";
		echo '<form id="login" name="login" method="post" action="index.php">';
		echo '<input name="LI" type="hidden" value="Yes" />';
		echo '<span class="myindent">Din Email</span><br /><input class="myindent" name="p_mail" type="text" size="20" maxlength="30" value="'.$_REQUEST['p_mail'].'" /><br />';
		echo '<span class="myindent">Dit kodeord</span><br /><input class="myindent" name="p_pwd" type="password" size="20" maxlength="30" value="'.$_REQUEST['p_pwd'].'" />';
		echo '<br /><br /><input class="myindent" type="image" src="bt_login.png" />';
		echo "</form>";

		//**	echo "<img src=\"hr_line_left.png\" alt=\"\" />";
		echo "<p class=myindent><b>Prøv</b> spillet <b>uden</b> at registrere dig</p>";
		echo "<span class=bblack><a href=\"?SB=Yes\">Prøv Spillet</a></span>";
	}

/*
	echo "<img src=\"hr_line_left.png\" alt=\"\" /><br />";
	//**	echo "<img src=\"b_spil_high1.png\" border=\"0\" alt=\"High Scores\" /><br /><br />";
	echo '<span class="myindent"><h2>Månedens Top5</h2></span>';
	echo "<table class=myindent>";

	$TempResult=sql("select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born from `top`, `player` where DATE_SUB(CURDATE(),INTERVAL 30 DAY)<=`t_datetime` and t_p_id=p_id group by t_p_id order by maxscore desc limit 5");
	$x=0;
	while($TempRow = mysql_fetch_row($TempResult)) {
		$x++;
		$age=2009-$TempRow[3];
		$number=number_format($TempRow[1], 0, '.', '.');
		echo "<tr><td align=\"right\"><b>".$number."</b></td><td>".$TempRow[2].", ".$age." år</td></tr>";
	}
	echo "</table>";
*/

	echo "<img src=\"hr_line_left.png\" alt=\"\" /><br />";
	echo "<img src=\"b_spil_high2.png\" border=\"0\" alt=\"High Scores\" /><br /><br />";
	echo "<table class=myindent>";

	$TempResult=sql("select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born from `top`, `player` where t_p_id=p_id group by t_p_id order by maxscore desc limit 5");
	$x=0;
	while($TempRow = mysql_fetch_row($TempResult)) {
		$x++;
		$age=2009-$TempRow[3];
		$number=number_format($TempRow[1], 0, '.', '.');
		echo "<tr><td align=\"right\"><font color=#f12300><b>".$number."</b></font></td><td>".$TempRow[2].", ".$age." år</td></tr>";
	}
	echo "</table>";

	echo "<img src=\"hr_line_left.png\" alt=\"\" /><br />";
	echo "<img src=\"b_spil_high3.png\" alt=\"High Scores\" /><br /><br />";
	echo "<table class=myindent>";

	$TempResult=sql("select t_ts_id, avg(t_score) as maxscore from `top`, `teams` where t_ts_id=ts_id and DATE_SUB(CURDATE(),INTERVAL 30 DAY)<=`t_datetime` group by t_ts_id order by maxscore desc limit 5");
	while($TempRow = mysql_fetch_row($TempResult)) {
		$TeamSql=sql("select ucase(ts_name), s_banner from teams, sponsor where ts_s_id=s_id and ts_id=".$TempRow[0]);
		$TeamRow=mysql_fetch_row($TeamSql);
		$number=number_format($TempRow[1], 0, '.', '.');
		echo "<tr><td align=\"right\"><font color=#f12300><b>".$number."</b></font></td><td><b>".$TeamRow[0] ."</b></td></tr>";
	}
	echo "</table>";

	echo "	</td>";

	echo '<td width="4" style="background-image: url(vertigo.png);"></td><td>&nbsp;</td>';

	echo "	<td width=460 align=\"left\">";
	echo "<img src=\"b_spil_prizes1.png\" alt=\"\" /><br /><br />";

	echo "<table><tr>";

		echo "<td width=210>";
	$TempResult=sql("select * from `mails` where m_id=27");
	$TempRow = mysql_fetch_row($TempResult);
	echoHTML($TempRow[2]);
	echo "<br/><span class=bblack><a href=\"?WIN=Yes\" valign=middle>Se vinderne</a></span><br />";
		echo "</td><td width=40></td>";

		echo "<td width=210 valign=top>";
	$TempResult=sql("select `p_first`, (2009-p_born) as age, `ZZCity`, `r_text` from `raffle`, `player`, `ZZIP` WHERE `r_p_id`>0 and `r_p_id`=`p_id` and `p_zip`=`ZZIP` ORDER BY `raffle`.`r_date` desc limit 2");
	while ($TempRow = mysql_fetch_row($TempResult)) {
		$win="<p style=\"margin: 0; color: #f12300; left: 20px;\"><b><font size=\"+1\">".$TempRow[0].", ".$TempRow[1]."år.</font><br/>fra ".$TempRow[2]."</b></p>";
		echo $win;
		echo "Har vundet: ";
		echoHTML($TempRow[3]);
		echo "<br/>";
	}
	echo "</td></tr></table>";

	//**	echo "<br /><img src=\"b_spil_prizes1b.png\" alt=\"\" />";

	//**	echo "<table><tr align=top>";
	$x=0;
	//**	$TempResult=sql("select * from `raffle` where (r_id!=3 and r_id!=4) and r_p_id=0 order by r_date asc limit 2");
	//**	while($TempRow = mysql_fetch_row($TempResult))
	//**	{
	//**		$x++;
	//**		echo "<td width=204>";
	//**		echo '<img src="admin/images/'.$TempRow[2].'" alt="" width="200" /><br />';
	//**		echoHTML($TempRow[3]);
	//**		echo "</td>";
	//**		if ($x==1) echo '<td width="4" style="background-image: url(vertigo.png);"></td>';
	//**	}
	//**	echo "</tr></table>";

	//	echo "<br /><img src=\"b_spil_prizes0.png\" width=\"447\" height=\"414\" alt=\"\" /><br />";

	// Find hovedpræmien år 2007
	$TempResult=sql("select * from `raffle` where r_id=3");
	$TempRow = mysql_fetch_row($TempResult);
	//	echoHTML($TempRow[3]);

	if($_SESSION['p_id']>0 && $_SESSION['p_s_id']>0) {
	

		echo '<p><b>';
		echo 'Hej '.$_SESSION['p_first'].'</b> Du er logget på.</p>'; 


		echo '<form method="post" action="index.php">';
		echo '<input name="SP" type="hidden" value="Yes" />';
		echo '<input class="myindent" type="image" src="bt_spil.png" />';
		echo "</form>";

		echo '<p>';
		echo "Du spiller for:<br />";
		echo "<b>".$_SESSION['ts_name']."</b></br/>";
		echo '<img src="admin/images/'.$_SESSION['s_banner'].'" width="140" height="54" border="0" alt="" />';

		//**	echo '<form id="changelocation" name="changelocation" method="post" action="index.php?CL=Yes">';
		//**	echo '<select name="p_location" onChange="document.changelocation.submit();">';
		//**	while ($x++<5) {
		//**		if ($_SESSION['p_location']==$x) $selected=" selected"; else $selected="";
		//**		echo '<option value="'.$x.'"'.$selected.'>'.$boomtown[$x].'</option>';
		//**	}
		//**	echo '</select>';
		//**	echo '</form>';
		//**	if ($_SESSION['p_location']==1) {
		//**	echo '<b>HUSK!</b> der er 8 hovedpræmier i hver af de fire Boomtown netcaféer. Vælg herover i hvilken Boomtown du gerne vil spille.';
		//**	}
		echo "</p>"; 

	}




	echo "</td>";
	echo "</tr>";
	echo "</table>";

	dspFMKB();
	echo "</div>";
}

//**
//** Display NEWS from the DB. One or List.
//**
function dspPress() {

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<br/><img src="bh_presse.png" width="780" border="0" alt="" />';
	echo '<div id="myarea">';
	echo '<img src="hr_long.png" width="700" height="8" alt="" />';
	echo "<div id=\"myareaINNER\" style=\"text-align: left; \">";

	if ((int)($_REQUEST['PR'])>0)
	{
		$TempResult=sql("select * from `news` where n_id=".(int)($_REQUEST['PR']));
		$TempRow = mysql_fetch_row($TempResult);
		echo "<br/><h1>".$TempRow[5]."</h1>";
		echo "<b>Dato: ".$TempRow[4]."</b></br>";
		echoHTML($TempRow[6]);
	}
	else
	{
		$TempResult=sql("select * from `news` where n_active=1 order by n_start desc");
		while ($TempRow = mysql_fetch_row($TempResult)) {
			echo "<br/><h1>".$TempRow[5]."</h1>";
			echo "<b>Dato: ".$TempRow[4]."</b><br/>";
			echo '<a href="?PR='.$TempRow[0].'">LÆS MERE</a>';
		}
	}

	echo "</div>";

	dspFMKB();
	echo "</div>";
}

//**
//** Displays the Page "About".
//**
function dspDefault() {
	$TempRow = mysql_fetch_row(sql("select * from `mails` where m_id=33"));
	$Txt33=$TempRow[2];
	$TempRow = mysql_fetch_row(sql("select * from `mails` where m_id=29"));
	$Txt29=$TempRow[2];
	$TempRow = mysql_fetch_row(sql("select * from `mails` where m_id=30"));
	$Txt30=$TempRow[2];

	echo '<div id="myarea">';
	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<img src="bh_omturnering.jpg" width="780" border="0" alt="" />';

	echo "<div id=\"myareaINNER\">";
	echo '<img src="hr_long.png" width="696" height="8" alt="" />';

	echo "<table width=\"700\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";

	echo "<tr valign=top><td align=\"left\">";
	echoHTML($Txt33);
	echo '</td><td><img src="kk-cartoon01.png" /></td></tr>';

	echo "<tr align=left><td colspan=2><br/><img src=\"kk-whois.png\" alt=\"\" /><br/></td></tr>";

	echo "<tr valign=top><td align=\"left\">";
	echoHTML($Txt30);
	echo '</td><td><br/><br/><br/><br/><br/><img src="b_spil_prizes4.png" width="300" /></td></tr>';

	echo "<tr valign=top><td colspan=2 align=\"left\">";
	echoHTML($Txt29);
	echo "</td></tr>";


	echo "</table>";

	echo "</div>";
	dspFMKB();
	echo "</div>";
}

//**
//** Display all Sponsors with Logo and address.
//**
function dspSponsors() {
	echo '<div id="myarea">';

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<img src="bh_sponsorer.png" border="0" alt="" width="780" />';
	echo '<img src="hr_long.png" width="700" height="8" alt="" />';

	//** echo '<a href="#sponsor"><img src="kk-blivsponsor-banner.png" border="0" alt="" /></a>';

	$TempResult=sql("select * from `mails` where m_id=11");
	$TempRow = mysql_fetch_row($TempResult);
	$Lefttxt=$TempRow[2];

	$TempResult=sql("select * from `mails` where m_id=12");
	$TempRow = mysql_fetch_row($TempResult);
	$Righttxt=$TempRow[2];

	echo "<table width=\"700\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	echo "<tr valign=\"top\"><td width=\"340\" align=\"left\">";
	echoHTML($Lefttxt);
	echo "</td><td align=\"left\">";
	echoHTML($Righttxt);
	echo "</td></tr></table>";
	echo '<br/><img src="hr_long.png" alt="" /><br/>';
	echo "<div id=\"myareaINNER\">";

	$TempResult=sql("select s_id, ucase(s_name), s_contact, s_adr, s_zip, s_phone1, s_phone2, s_total, s_logo, s_banner, s_www, ZZCity, s_cmt from `sponsor`, `ZZIP` where s_id>1 and s_active>0 and `s_zip`=`ZZIP` order by `s_total` desc, s_name asc");
	$y=0;
	$txt="<table width=\"700\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr align=\"left\">";
	while($TempRow = mysql_fetch_row($TempResult)) {
		$hold=(int)($TempRow[7]);
		$free=$hold*10-8;
		$y=$y+1;
		if($y==3)
		{
			$y=1;
			$txt.="</tr><tr align=\"left\">";
		}
		$url=str_replace("http://","",$TempRow[10]);
		$txt.="<td width=\"340\">";
		$txt.="<table width=\"325\" border=\"0\" cellspacing=3><tr><td width=144>";
		$txt.="<img src=\"admin/images/{$TempRow[9]}\" border=\"2\" style=\"border-color: white\" width=\"140\" height=\"54\" align=\"left\" alt=\"\" /></td>";
		$txt.="<td><b>{$TempRow[1]}</b><br />";
		$txt.="{$TempRow[12]}"."<br />";
	  $txt.="	<a style=\"color: #00CCFF; text-decoration: none;\" href=\"{$TempRow[10]}\" target=\"_blank\"><b>{$url}</b></a><br />";
		$txt.="</span></td></tr></table>";
		$txt.="<br /><br /></td>";
		$total=$total+$hold;
		$x++;
	}
	$txt.="</tr></table>";

	echo $txt;

	echo "</div>";
	dspFMKB();
	echo "</div>";
}

//**
//** HIGH Score rankings display.
//** 8 Different styles availble:
//**   4 daily     (/?Dagens=Yes)
//**   4 all time  (/?Dagens=No)
//**
function dspPlaces() {

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
/*
	echo '<div id="submenu" style="width: 780px; height: 27px; background: #000000; text-align: left;">';
	if ($_REQUEST['Dagens']=='Yes') {
		echo '<nobr><img border="0" name="D1" src="butt_DAGENS_down.png" height="27" alt="" /></a>';
		echo '<a href="?PL=Yes&Dagens=No"><img border="0" name="D2" src="butt_ALLTIME.png" onmouseover="document.D2.src=\'butt_ALLTIME_down.png\'" onmouseout="document.D2.src=\'butt_ALLTIME.png\'" height="27" alt="" /></a><img border="0" src="toppind.png" height="27" alt="" /></nobr>';
	}
	else
	{
		echo '<nobr><a href="?PL=Yes&Dagens=Yes"><img border="0" name="D1" src="butt_DAGENS.png" onmouseover="document.D1.src=\'butt_DAGENS_down.png\'" onmouseout="document.D1.src=\'butt_DAGENS.png\'" height="27" alt="" /></a>';
		echo '<img border="0" name="D2" src="butt_ALLTIME_down.png" height="27" alt="" /></a><img border="0" src="toppind.png" height="27" alt="" /></nobr>';
	}
	echo '</div>';
*/
	echo '<div id="myarea">';
	echo '<div id="myareaINNER">';

	echo '<table width="700" colspacing="0" cellpadding="0" border="0"><tr>';
	echo '<td align="left" colspan="2" width="222">';

	$class1="bblack2";
	$class2="bblack2";
	$class3="bblack2";
	$class4="bblack2";
	$limit=10;
	if ($_REQUEST['Limit']>10) $limit=0+$_REQUEST['Limit'];
	
	if($_REQUEST['Dagens']=='Yes') {
		if ($_REQUEST['p_mk']=="D") {
			$m_id=23;
			$class1="borange2";
			$SQL="select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born, p_win from `top`, `player` where DATE_SUB(CURDATE(),INTERVAL 30 DAY)<=`t_datetime` and t_p_id=p_id and p_active=1 and p_mk=\"D\" group by t_p_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_dagenshigh_drenge.png" width="222" height="115" border="0" alt="" />';
		}
		elseif ($_REQUEST['p_mk']=="P") {
			$m_id=24;
			$class2="borange2";
			$SQL="select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born, p_win from `top`, `player` where DATE_SUB(CURDATE(),INTERVAL 30 DAY)<=`t_datetime` and t_p_id=p_id and p_active=1 and p_mk=\"P\" group by t_p_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_dagenshigh_pige.png" width="222" height="115" border="0" alt="" />';
		}
		elseif ($_REQUEST['p_mk']=="All") {
			$m_id=21;
			$class3="borange2";
			$SQL="select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born, p_win from `top`, `player` where DATE_SUB(CURDATE(),INTERVAL 30 DAY)<=`t_datetime` and t_p_id=p_id and p_active=1 group by t_p_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_dagenshigh_spiller.png" width="222" height="115" border="0" alt="" />';
		}
		else {
			$class4="borange2";
			$m_id=22;
			$SQL="select t_ts_id, avg(t_score) as maxscore, ts_id from `top`, `teams` where DATE_SUB(CURDATE(),INTERVAL 30 DAY)<=`t_datetime` and t_ts_id=ts_id group by t_ts_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_dagenshigh_hold.png" width="222" height="115" border="0" alt="" />';
		}
	}
	else {
		if ($_REQUEST['p_mk']=="D") {
			$m_id=19;
			$class1="borange2";
			$SQL="select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born, p_win from `top`, `player` where t_p_id=p_id and p_active=1 and p_mk=\"D\" group by t_p_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_alltime_drenge.png" width="222" height="115" border="0" alt="" />';
		}
		elseif ($_REQUEST['p_mk']=="P") {
			$m_id=20;
			$class2="borange2";
			$SQL="select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born, p_win from `top`, `player` where t_p_id=p_id and p_active=1 and p_mk=\"P\" group by t_p_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_alltime_pige.png" width="222" height="115" border="0" alt="" />';
		}
		elseif ($_REQUEST['p_mk']=="All") {
			$m_id=17;
			$class3="borange2";
			$SQL="select t_ts_id, max(t_score) as maxscore, ucase(p_first), p_born, p_win from `top`, `player` where t_p_id=p_id and p_active=1 group by t_p_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_alltime_spiller.png" width="222" height="115" border="0" alt="" />';
		}
		else {
			$m_id=18;
			$class4="borange2";
			$SQL="select t_ts_id, avg(t_score) as maxscore, ts_id from `top`, `teams` where t_ts_id=ts_id group by t_ts_id order by maxscore desc limit ".$limit;
			echo '<img src="bh_alltime_hold.png" width="222" height="115" border="0" alt="" />';
		}
	}
	$TempResult=sql("select * from `mails` where m_id=".$m_id);
	$TempRow = mysql_fetch_row($TempResult);
	$Toptxt=$TempRow[2];

	echo '</td>';

	echo '<td colspan="2" align="left" width="460">';
	echoHTML($Toptxt);
	echo "<br/><table><tr>"."\r\n";
	echo "<td><span class=".$class4."><a href=\"?PL=Yes&Teams=Yes&Dagens=".$_REQUEST['Dagens']."\">Hold</a></span></td>";
	echo "<td><span class=".$class1."><a href=\"?PL=Yes&p_mk=D&Dagens=".$_REQUEST['Dagens']."\">Drengene</a></span></td>";
	echo "<td><span class=".$class2."><a href=\"?PL=Yes&p_mk=P&Dagens=".$_REQUEST['Dagens']."\">Pigerne</a></span></td>";
	echo "<td><span class=".$class3."><a href=\"?PL=Yes&p_mk=All&Dagens=".$_REQUEST['Dagens']."\">Alle spillere</a></span></td>";
	echo "</tr></table>"."\r\n";

	echo "</td></tr>"."\r\n";

	$y=0; $rows=0;

	$TempResult=sql($SQL);
	while($TempRow = mysql_fetch_row($TempResult)) {
		$rows++;
		$TeamSql=sql("select ts_name, s_banner from teams, sponsor where ts_s_id=s_id and ts_id=".$TempRow[0]);
		$TeamRow=mysql_fetch_row($TeamSql);
		$x++;
		$txt.='<tr><td colspan="4"><img border=0 src="hr_long.png" width="696" alt="" /></td></tr>';
		$txt.="<tr valign=top>";
		
		$txt.='<td valign="top" align="left"><b>'.$x.'</b></td><td><img src="admin/images/'.$TeamRow[1].'" width="140" border="0" height="54" align="top" /></td>';
		if ($_REQUEST['p_mk']=='D' || $_REQUEST['p_mk']=='P' || $_REQUEST['p_mk']=='All') {
			if($TempRow[4]>0) $trophy='<td width=62><img src="trophy.png" alt="Har vundet en træningspræmie." height="59" width="62" /></td>';
			else $trophy='<td width=62>&nbsp;</td>';
			$age=2009-$TempRow[3];
			$number=number_format($TempRow[1], 0, '.', '.');
			if ($x<5) 
				$txt.='<td align=left width=300 style="background-color: #f1f1f1;">';
			else
				$txt.="<td align=left width=300>";
			$txt.="<font color=#000000 size=+1><b>{$TempRow[2]}, {$age} år.</b></font><br/>";
			$txt.="<font color=#f12300 size=+1><b>Score {$number}</b></font><br/>";
			$txt.="Spiller på holdet: <b>{$TeamRow[0]}</b></td>";
			$txt.=$trophy;
		}
		else {
			$number=number_format($TempRow[1], 0, '.', '.');
			$txt.="<td colspan=2 align=left valign=top><div class='collapsable'>";
			$txt.="<font color=#000000 size=+1><b>{$TeamRow[0]}</b></font><br/>";
			$txt.="Hold gennemsnit: <font color=#f12300 size=+1><b>{$number}</b></font>";
			if ($_SESSION['p_id']>0) $txt.="<p style=\"margin: 0 0 0 0; border: 1px dotted #f12300; background-color: #f0f0f0\"><table border=0 cellpadding=0 colspacing=4><tr style=\"color:#f12300\"><th>Nr.</th><th width=110 align=left>Navn</th><th width=50 align=center>Alder</th><th width=110 align=left>By</th><th>Spil</th><th width=70 align=right>Bedste</th><th width=70 align=right>Seneste</th></tr>";
			$PlayerResult=sql("select p_first, p_born, p_games, p_score ,p_scorehigh, p_location, ZZCity from player, ZZIP where p_active=1 and p_zip=ZZIP and p_s_id=".$TempRow[0]." order by p_scorehigh desc");
			$s=0;
			while ($PlayerRow=mysql_fetch_row($PlayerResult)) {
				$plast=number_format($PlayerRow[3], 0, '.', '.');
				$phigh=number_format($PlayerRow[4], 0, '.', '.');
				$s++;
				if($PlayerRow[5]>1) $location="<font color=#00ff00>Ja</font>"; else $location="<font color=#ffff00>Ved ikke</font>";
				$age=2009-$PlayerRow[1];
			if ($_SESSION['p_id']>0) $txt.= "<tr><td align=center>".$s."</td><td>". $PlayerRow[0] . "</td><td align=center>" . $age . " år</td><td>".$PlayerRow[6]."</td><td align=right>".$PlayerRow[2]."</td><td align=right>".$phigh."</td><td align=right>".$plast."</td></tr>";
				
			}
			if ($_SESSION['p_id']>0) $txt.="</table></p></div></td>";
		}
		$txt.="</tr>";
	}

	if ($rows>9) {
			$txt.='<tr><td colspan="4"><img border=0 src="hr_long.png" width="696" alt="" /></td></tr>';
			$txt.= "<tr><td colspan=4 align=right><table><tr>"."\r\n";
			$txt.= "<td><span class=\"bblack2\"><a href=\"?PL=Yes&Limit=50&Teams=".$_REQUEST['Teams']."&p_mk=".$_REQUEST['p_mk']."&Dagens=".$_REQUEST['Dagens']."\">Top 50</a></span></td>";
			if ($rows>9) {
				$txt.= "<td><span class=\"bblack2\"><a href=\"?PL=Yes&Limit=100&Teams=".$_REQUEST['Teams']."&p_mk=".$_REQUEST['p_mk']."&Dagens=".$_REQUEST['Dagens']."\">Top 100</a></span></td>";
			}
			$txt.= "</tr></table></td></tr>"."\r\n";
	}

	$txt.="</table>";
	echo $txt;

	echo "</div>";

	dspFMKB();
	echo "</div>";
}

//**
//** This Creates a new Player
//**
function dspNewWelcomeMail() {
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
		$TempResult=sql("select * from `mails` where m_id=47");
		$TempRow = mysql_fetch_row($TempResult);

		echo "<div id=\"myareaINNER\">";
		echo "<table width=\"700\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>";
		echo "<td width=\"300\" align=\"left\">";
		echo "<img src=\"kk-cartoon03.png\" alt=\"\" /></td><td align=\"left\">";
		echoHTML($TempRow[2]);
		echo "</td></tr></table>";
		echo "</div>";
		dspFMKB();

		sendEmail($_REQUEST['p_mail'], 44);
		echo "</div>";
	}
	else {
		$fejl="<h2>FEJL:".$text."</h2>";
		dspRegisterMail($fejl);
	}
}
//**
//** This Creates a new Player
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

		echo "<div id=\"myareaINNER\">";
		echo "<table width=\"700\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>";
		echo "<td width=\"300\" align=\"left\">";
		echo "<img src=\"kk-cartoon03.png\" alt=\"\" /></td><td align=\"left\">";
		echoHTML($TempRow[2]);
		echo "</td></tr></table>";
		echo "</div>";
		dspFMKB();

		sendEmail($_REQUEST['p_mail'], 0);
		echo "</div>";
	}
	else {
		$fejl="<h2>FEJL:".$text."</h2>";
		dspRegister($fejl);
	}
}

//**
//** Newsletter sign up
//** - Part of the Login/Registration process.
function dspRegisterMail($fejl) {

	$TempResult=sql("select * from `mails` where m_id=45");
	$TempRow = mysql_fetch_row($TempResult);

	$cr="\r";
	$infoTxt=str_replace($cr, '<br />', $TempRow[2]);

	$TempResult=sql("select * from `mails` where m_id=46");
	$TempRow = mysql_fetch_row($TempResult);

	$cr="\r";
	$infoTxt2=str_replace($cr, '<br />', $TempRow[2]);

	$txt ='<form id="register" method="get" action="index.php">';
	$txt.='<input type="hidden" name="NWM" value="Yes" />';
	$txt.='<input type="hidden" name="HTTP_REFERER" value="'.$_SESSION['HTTP_REFERER'].'" />';
	$txt.='<input type="hidden" name="p_location" value="1" />';
	$txt.='<table width=700 border=0 cellpadding=0 colspacing=0>';
	$txt.='<tr align="left"><td colspan="3">'.$infoTxt.'</td></tr>';

	$txt.='<tr align="left"><td width="280">Fornavn</td><td width="280">Efternavn(e)</td><td width="280">Alder</td></tr>';
	$txt.='<tr align="left">';
	$txt.='	<td><input type="text" name="p_first" size="30" maxlength="30" value="'.$_REQUEST['p_first'].'" /></td>';
	$txt.='	<td><input type="text" name="p_name" size="30" maxlength="30" value="'.$_REQUEST['p_name'].'" /></td>';
	$txt.='	<td>';
	$txt.='		<select name="p_born" size="1">';
	$age=2003;
	while($age>1940) {
		$age=$age-1;
		$txt.='<option value="'.(int)($age).'">Jeg er '.(int)(2009-$age).' år gammel</option>';
	}
	$txt.='		</select>';
	$txt.=' </td>';
	$txt.='</tr>';

	$txt.='<tr align="left"><td>Gade/Vej og nr.</td><td>Postnummer</td><td>Køn</td></tr>';
	$txt.='<tr align="left">';
	$txt.='	<td><input type="text" name="p_adr" size="30" maxlength="30" value="'.$_REQUEST['p_adr'].'" /></td>';
	$txt.='	<td><input type="text" name="p_zip" size="4" maxlength="4" value="'.$_REQUEST['p_zip'].'" /></td>';
	$txt.='	<td>';
	$txt.='		<select name="p_mk" size="1">';
		$txt.='<option value="D">'.'Jeg er en dreng'.'</option>';
		$txt.='<option value="P">'.'Jeg er en pige'.'</option>';
	$txt.='		</select>';
	$txt.=' </td>';
	$txt.='</tr>';

	$txt.='<tr align="left"><td>E-mail adresse</td><td colspan="2">Nyhedsbrev</td></tr>';
	$txt.='<tr align="left">';

	$txt.='	<td><input type="text" name="p_mail" size="30" maxlength="30" value="'.$_REQUEST['p_mail'].'" /></td>';
	$txt.='	<td colspan="2"><input style="background-color: #ffffff;" type="checkbox" name="p_news" checked="checked" />&nbsp;Ja tak, send mig nyhedsbreve fra FMKB</td>';
	$txt.='</tr>';
	$txt.='<tr><td colspan=3>&nbsp;<br/></td></tr>';
	$txt.='<tr align=left><td><input type="image" src="bt_send.png" /></td>';
	$txt.='    <td colspan="2">'.$infoTxt2.'</td></tr>';
	$txt.='</table>';
	$txt.='</form><br />';

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<br/><img src="bh_registrer.png" border="0" alt="" width="780" />';

	echo '<div id="myarea">';
	echo '<img src="hr_long.png" width="696" height="8" alt="" />';
	echo $fejl.'<br />';
		echo "<div id=\"myareaINNER\">";
	echo $txt;

	echo "</div>";

	dspFMKB();
	echo "</div>";
}

//**
//** Register a new player
//** - Part of the Login/Registration process.
function dspRegister($fejl) {

	$TempResult=sql("select * from `mails` where m_id=26");
	$TempRow = mysql_fetch_row($TempResult);

	$cr="\r";
	$infoTxt=str_replace($cr, '<br />', $TempRow[2]);

	$TempResult=sql("select * from `mails` where m_id=28");
	$TempRow = mysql_fetch_row($TempResult);

	$cr="\r";
	$infoTxt2=str_replace($cr, '<br />', $TempRow[2]);

	$txt ='<form id="register" method="get" action="index.php">';
	$txt.='<input type="hidden" name="NW" value="Yes" />';
	$txt.='<input type="hidden" name="HTTP_REFERER" value="'.$_SESSION['HTTP_REFERER'].'" />';
	$txt.='<table width=700 border=0 cellpadding=0 colspacing=0>';
	$txt.='<tr align="left"><td colspan="3">'.$infoTxt.'</td></tr>';

	if (1==2) {
	$txt.='<tr align="left"><td colspan="3"><br/>Jeg vil spille:</td></tr>';
	$txt.='<tr align="left">';

	$txt.='	<td>';
	$txt.='		<select name="p_location" size="1">';
		$txt.='<option value="3">'.'Hos Boomtown i København'.'</option>';
		$txt.='<option value="2">'.'Hos Boomtown i Århus'.'</option>';
		$txt.='<option value="4">'.'Hos Boomtown i Odense'.'</option>';
		$txt.='<option value="5">'.'Hos Boomtown i Aalborg'.'</option>';
		$txt.='<option value="1">'.'Hjemmefra'.'</option>';
	$txt.='		</select>';
	$txt.=' </td>';
	$txt.='	<td colspan="2"><h2>Vi håber at se dig hos BOOMTOWN!</h2></td>';
	$txt.='</tr>';
	}

	$txt.='<tr align="left"><td width="280">Fornavn</td><td width="280">Efternavn(e)</td><td width="280">Alder</td></tr>';
	$txt.='<tr align="left">';
	$txt.='	<td><input type="text" name="p_first" size="30" maxlength="30" value="'.$_REQUEST['p_first'].'" /></td>';
	$txt.='	<td><input type="text" name="p_name" size="30" maxlength="30" value="'.$_REQUEST['p_name'].'" /></td>';
	$txt.='	<td>';
	$txt.='		<select name="p_born" size="1">';
	$age=2003;
	while($age>1940) {
		$age=$age-1;
		$txt.='<option value="'.(int)($age).'">Jeg er '.(int)(2009-$age).' år gammel</option>';
	}
	$txt.='		</select>';
	$txt.=' </td>';
	$txt.='</tr>';

	$txt.='<tr align="left"><td>Gade/Vej og nr.</td><td>Postnummer</td><td>Køn</td></tr>';
	$txt.='<tr align="left">';
	$txt.='	<td><input type="text" name="p_adr" size="30" maxlength="30" value="'.$_REQUEST['p_adr'].'" /></td>';
	$txt.='	<td><input type="text" name="p_zip" size="4" maxlength="4" value="'.$_REQUEST['p_zip'].'" /></td>';
	$txt.='	<td>';
	$txt.='		<select name="p_mk" size="1">';
		$txt.='<option value="D">'.'Jeg er en dreng'.'</option>';
		$txt.='<option value="P">'.'Jeg er en pige'.'</option>';
	$txt.='		</select>';
	$txt.=' </td>';
	$txt.='</tr>';

	$txt.='<tr align="left"><td>E-mail adresse</td><td colspan="2">Nyhedsbrev</td></tr>';
	$txt.='<tr align="left">';

	$txt.='	<td><input type="text" name="p_mail" size="30" maxlength="30" value="'.$_REQUEST['p_mail'].'" /></td>';
	$txt.='	<td colspan="2"><input style="background-color: #ffffff;" type="checkbox" name="p_news" checked="checked" />&nbsp;Ja tak, send mig også nyhedsbrev fra FMKB</td>';
	$txt.='</tr>';
	$txt.='<tr><td colspan=3>&nbsp;<br/></td></tr>';
	$txt.='<tr align=left><td><input type="image" src="bt_send.png" /></td>';
	$txt.='    <td colspan="2">'.$infoTxt2.'</td></tr>';
	$txt.='</table>';
	$txt.='</form><br />';

	echo '<a href="/" alt=""><img src="kk-top-banner.png" border="0" alt="" width="780" /></a>';
	echo '<br/><img src="bh_registrer.png" border="0" alt="" width="780" />';

	echo '<div id="myarea">';
	echo '<img src="hr_long.png" width="696" height="8" alt="" />';
	echo $fejl.'<br />';
		echo "<div id=\"myareaINNER\">";
	echo $txt;

	echo "</div>";

	dspFMKB();
	echo "</div>";
}

//**
//** First time player selects a team here!
//** - Part of the Login/Registration process.
//**
function dspSponsorTeams() {
	echo '<div id="myarea">';
	echo '<img src="kk-top-banner.png" border="0" alt="" width="780" />';
	echo "<br/><h2>Vælg en hold som du vil spille for...</h2><hr/>";
	$x=0;
	$ts_id=0+$_REQUEST['ts_id'];
	$txt= "<form action=\"index.php\" method=\"get\">";
	$txt.="<input type=\"hidden\" name=\"ST\" value=\"Yes\" />";
	$txt.="<table border=1 colspacing=2 cellpadding=0>";
	$TempResult=sql("select ts_id, ts_name, s_banner, s_name from `teams`, `sponsor` where ts_p0=1 and ts_id>".$ts_id." and ts_s_id=s_id and s_active>0 order by s_name");
	while($TempRow = mysql_fetch_row($TempResult)) {
		if ($Players = mysql_fetch_row(sql("select count(*) from player where p_active=1 and p_s_id=".$TempRow[0]))) {
			if ($Players[0]<10) {
				if ($x==0) $txt.= "<tr>";
				$x++; $y++;
				$pladser=10-$Players[0];
				$ts_id=$TempRow[0];
				$txt.='<td><a href="?LI=Yes&p_s_id='.$TempRow[0].'"><img src="admin/images/'.$TempRow[2].'" width="140" height="54" alt="'.$TempRow[1].'" border="0" /></a><hr/>'.$TempRow[1].'<br/>Pladser: <b>'.$pladser.' af 10</b>.</td>';
				if ($x==4) {$txt.= "<tr>"; $x=0; }
			}
		}
	}
	if ($x<4) $txt.="</tr>";
	if ($ts_id>64) {$ts_id=0; }
	$txt.='</table>';
	//** $txt.='<input type="hidden" name="ts_id" value="'.$ts_id.'" />';
	//** $txt.='<hr/><input type=submit name=more value="Se flere hold?" />';
	$txt.="</form>";
	echo $txt;
	echo "</div>";
}

//**
//**
//**
function dspMessage() {
	$message=$_REQUEST['d_message'];
	
	sql("insert into Dialog (d_from_p_id, d_to_p_id, d_message) values(".$_SESSION['p_id'].", ".$_REQUEST['d_to_p_id'].", '".$message."')");
	dspDefault();
}

if ($_REQUEST['LS']!="") $title="Tak til disse sponsorer";
elseif ($_REQUEST['RG']!="") $title="Registrer dig til årets event";
elseif ($_REQUEST['RGM']!="") $title="Tilmeld dig nyhedbrev";
elseif ($_REQUEST['NW']!="") $title="Velkommen";
elseif ($_REQUEST['NWM']!="") $title="Velkommen";
elseif ($_REQUEST['ST']!="") $title="Vælg et Team";
elseif ($_REQUEST['DF']!="") $title="Officielle FILM";
elseif ($_REQUEST['DFO']!="") $title="Hos Optikeren";
elseif ($_REQUEST['BG']!="") $title="Spil nu";
elseif ($_REQUEST['RT']!="") $title="Få de fede ringetoner";
elseif ($_REQUEST['SP']!="") $title="Prøv Spillet";
elseif ($_REQUEST['SB']!="") $title="BETA version af spillet";
elseif ($_REQUEST['AG']!="") $title="Velkommen";
elseif ($_REQUEST['PL']!="") $title="Turnerings statistik - Placeringer";
elseif ($_REQUEST['PR']!="") $title="Nyheder og presse meddelelser";
elseif ($_REQUEST['LI']!="") $title="Log ind for at spille";
elseif ($_REQUEST['LO']!="") $title="Du er nu logget ud";
elseif ($_REQUEST['CR']!="") $title="Tak til disse sponsorer";
elseif ($_REQUEST['MS']!="") $title="Meddelelser";
elseif ($_REQUEST['CHAT']!="") $title="Chat - NYHED!";
elseif ($_REQUEST['NEWSLETTER25']!="") $title="Nyhedbrev afsendelse";
elseif ($_REQUEST['REMINDER001']!="") $title="Reminder Email sendt til disse adresser.";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da" lang="da">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="imagetoolbar" content="false" />
	<meta name="publisher" content="Yes2Mail ApS" />
	<meta name="copyright" content="Copyright 2007-09, Yes2Mail ApS, Source PHP, MySQL, XML and JavaScript by: René Østerballe" />
	<meta name="author" content="René Chr. Østerballe" />
	<meta name="description" content="Kemo Kasper præsenteret af foreningen Familier med Kræftramte Børn" />
	<meta name="keywords" content="Kræft, Cancer, Information, sjovt, Community, Børn, Unge, Film, Video, Flash, Spil, Gratis, Præmier, Turnering" />
	<meta name="verify-v1" content="dWi1X7Ctc9Zbdvek/HMrYDkJbAek6z2kYsrusFOL9LQ=" />
	<title>Kemo-Kasper 2009. <?php echo $title; ?></title>
	<link rel="icon" href="/favicon1.ico" type="image/x-icon" />	
	<link rel="shortcut icon" href="/favicon2.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="kemokasper.css" />
	<script src="swfobject.js" type="text/javascript"></script>
	<script src="kemokasper.js" type="text/javascript"></script>
</head>
<body>
<center>
<div id="menu" style="margin-top: 10px; margin-bottom: 0px; width: 780px; height: 26px;">
	<a href="/"><img border="0" height="25" alt="" src="butt_HOME.png"/></a>

	<!-- a href="?AG=Yes"><img border="0" height="25" alt="" src="butt_OM.png"/></a -->
	<a href="?RGM=Yes"><img border="0" height="25" alt="" src="butt_REG.png"/></a>
	<a href="?PL=Yes"><img border="0" height="25" alt="" src="butt_PLACE.png"/></a>
	<a href="?BG=Yes"><img border="0" height="25" alt="" src="butt_SPIL.png"/></a>
	<!-- a href="?RT=Yes"><img border="0" height="25" alt="" src="butt_RING.png"/></a -->
	<a href="?DF=Yes"><img border="0" height="25" alt="" src="butt_FILM.png"/></a>
	<a href="?LS=Yes"><img border="0" height="25" alt="" src="butt_SPONS.png"/></a>
	<!-- a href="?CR=Yes"><img border="0" height="25" alt="" src="butt_CREDITS.png"/></a -->
</div>
<?php

	if ($_SESSION['p_id']==999) {
		$TempResult=sql("select * from Dialog, player where d_to_p_id=".$_SESSION['p_id']." order by d_id desc limit 5");
		$x=0;
		while($TempRow = mysql_fetch_row($TempResult)) {
			$x++;
			if ($x==1) {
				$txt ='<div id="myInfo1" style="position: absolute; visibility: hidden; background-color: #00CC11; color: #f12300; width: 300px; margin-top: 80px; margin-left: 100px; border: 5px double #000000;">';
				$txt.= '<table width="100%" border="0"><tr>';
				$txt.= "<td width=\"100%\"><h3>BESKEDER</h3></td>"."\r\n";
				$txt.= "<td>";
				$txt.= "<input type=\"button\" value=\"X\" onclick=\"document.myInfo1.style.visibility=\"hidden\" />";
				$txt.= "</td></tr></table>"."\r\n";
			}
			$txt.=$TempRow[3] . "<br/>";
			$d_to_ip=$TempRow[1];
	  }
		if ($x>0) {
			$txt.='	<form action="/" method="post">';
			$txt.='		<input type="hidden" name="MS" value="Yes" />';
			$txt.='		<input type="hidden" name="d_to_p_id" value="'.$d_to_ip.'" />';
			$txt.='		<input type="text" name="d_message" value="Skriv dit svar her ..."/>';
			$txt.='		<input type="submit" name="send" value="Svar">';
			$txt.='	</form>';
			$txt.='</div>';
			echo $txt;
		}
	}

	if ($_REQUEST['LS']!="") dspSponsors();
	elseif ($_REQUEST['RGM']!="") dspRegisterMail('');
	elseif ($_REQUEST['RG']!="") dspRegister('');
	elseif ($_REQUEST['NW']!="") dspNewWelcome();
	elseif ($_REQUEST['NWM']!="") dspNewWelcomeMail();
	elseif ($_REQUEST['ST']!="") dspSponsorTeams();
	elseif ($_REQUEST['DF']!="") dspFilm();
	elseif ($_REQUEST['DFP']!="") dspFilmPlayer();
	elseif ($_REQUEST['DFO']!="") dspFilmOptiker();
	elseif ($_REQUEST['BG']!="") dspSpil();
	elseif ($_REQUEST['RT']!="") dspRingTone();
	elseif ($_REQUEST['SP']!="") dspGame();
	elseif ($_REQUEST['SPD']!="") dspGameDay();
	elseif ($_REQUEST['SB']!="") dspBeta();
	elseif ($_REQUEST['AG']!="") dspDefault();
	elseif ($_REQUEST['PL']!="") dspPlaces();
	elseif ($_REQUEST['PR']!="") dspPress();
	elseif ($_REQUEST['LI']!="") dspLogin();
	elseif ($_REQUEST['LO']!="") dspLogOut();
	elseif ($_REQUEST['CR']!="") dspCredits();
	elseif ($_REQUEST['MS']!="") dspMessage();
	elseif ($_REQUEST['CL']!="") dspChangeLocation();
	elseif ($_REQUEST['CHAT']!="") dspChat();
	elseif ($_REQUEST['HOMETOUR']!="") dspHome();
	elseif ($_REQUEST['TUR']!="") dspTurnering();
	elseif ($_REQUEST['DIP']!="") dspDiplom();
	elseif ($_REQUEST['WIN']!="") dspWinners();
	elseif ($_REQUEST['MAIL']!="") sendReminder();
	elseif ($_REQUEST['STATS']!="") dspSTATS();
	else dspHome();
?>
<br/><br/>
<SCRIPT SRC="http://www.peakcounter.dk/php/showpeakcounterimg.php?id=44090&amp;view=19" TYPE="text/javascript"></SCRIPT>
<NOSCRIPT><a target="_blank" href="http://www.peakcounter.dk/cgi-bin/peakcounter/toplist.cgi?id=44090"><img src="http://www.peakcounter.dk/grafik/peakcounter19.gif" alt='PeakCounter.dk' border=0><img src="http://www.peakcounter.dk/php/peakcounter.php?id=44090" alt='PeakCounter.dk' height=1 width=1 border=0></a></NOSCRIPT>
<script src="http://www.chart.dk/js/unified.asp" type="text/javascript"></script>
<script type="text/javascript">
 track_visitor(136917, '');
</script>
<noscript>
 <a href="http://www.chart.dk/ref.asp?id=136917" target="_blank">
  <img src="http://cluster.chart.dk/chart.asp?id=136917" border="0" alt="Chart.dk" />
 </a>
</noscript>
<a href="http://www.nope.dk/lb.php?id=27328" target="_blank"><img border="0" alt="" src="http://counter.nope.dk/counter.php?id=27328&amp;nc=0&amp;ver=2B"/></a>
</center>
</body>
</html>
