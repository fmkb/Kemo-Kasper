<?php

$duplet="hidden";

//** Execute a SQL statement and return the result.
function sql($statment,$assoc=1) {
	if(!$result = mysql_query($statment)) {
		echo mysql_error();
		exit;
	}
	else return $result;
}

//** Connect to SQL Server and select Database.
if (!mysql_connect("localhost", "kemo_kasper_dk", "QvK54Xpa")) exit;
if (!mysql_select_db("kemo_kasper_dk")) exit;

//** Start a Session so we can remember each user.
session_start();

if (!isset($_SESSION['HTTP_REFERER'])) $_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
if ($_REQUEST['p_id']>0) {
	$TempSql=sql("select p_id, p_first, p_born, p_mk, p_zip from player where p_active=1 and p_id=0".$_REQUEST['p_id']);
	if($TempRow=mysql_fetch_row($TempSql)) {
		$_SESSION['p_id']=$TempRow[0];
		$_SESSION['p_first']=$TempRow[1];
	}
}

if ($_REQUEST['send']=="SEND") {
	$err=0;
	if (!isset($_REQUEST['Q1_work'])) $err=1;
	if (!isset($_REQUEST['Q1_where'])) $err=2;
	if (!isset($_REQUEST['Q1_why'])) $err=3;
	if (!isset($_REQUEST['Q1_rating'])) $err=4;
	if (!isset($_REQUEST['Q1_speakothers'])) $err=5;
	if (!isset($_REQUEST['Q1_changedme'])) $err=6;
	if (!isset($_REQUEST['Q1_playoften'])) $err=7;
	if (!isset($_REQUEST['Q1_infolevel'])) $err=8;
	if (!isset($_REQUEST['Q1_SNotice'])) $err=9;
	if (!isset($_REQUEST['Q1_SNoticewhere'])) $err=10;
	if (!isset($_REQUEST['Q1_SNoticemainsponsor'])) $err=12;
	if (!isset($_REQUEST['Q1_SNoticeteam'])) $err=13;
	if (!isset($_REQUEST['Q1_SNoticeother'])) $err=14;
	if (!isset($_REQUEST['Q1_Shavechangedview'])) $err=15;
	if (!isset($_REQUEST['Q1_Sbuyproducts'])) $err=16;
	if ($err==0) {
		$TempSql=sql("select Q1_id from Q1 where Q1_p_id=".$_SESSION['p_id']);
		if(!$TempRow=mysql_fetch_row($TempSql)) {
//**			echo "insert into Q1 (Q1_work, Q1_where, Q1_why, Q1_rating, Q1_speakothers, Q1_changedme, Q1_playoften, Q1_infolevel, Q1_SNotice, Q1_SNoticewhere, Q1_SNoticemainsponsor, Q1_SNoticeteam, Q1_SNoticeother, Q1_Shavechangedview, Q1_Sbuyproducts, Q1_Snameothers, Q1_Comments, Q1_p_id) values(".$_REQUEST['Q1_work'].",".$_REQUEST['Q1_where'].",".$_REQUEST['Q1_why'].",".$_REQUEST['Q1_rating'].",".$_REQUEST['Q1_speakothers'].",".$_REQUEST['Q1_changedme'].",".$_REQUEST['Q1_playoften'].",".$_REQUEST['Q1_infolevel'].",".$_REQUEST['Q1_SNotice'].",".$_REQUEST['Q1_SNoticewhere'].",".$_REQUEST['Q1_SNoticemainsponsor'].",".$_REQUEST['Q1_SNoticeteam'].",".$_REQUEST['Q1_SNoticeother'].",".$_REQUEST['Q1_Shavechangedview'].",".$_REQUEST['Q1_Sbuyproducts'].",'".$_REQUEST['Q1_Snameothers']."','".$_REQUEST['Q1_Comments']."',".$_SESSION['p_id'].")";
			sql("insert into Q1 (Q1_work, Q1_where, Q1_why, Q1_rating, Q1_speakothers, Q1_changedme, Q1_playoften, Q1_infolevel, Q1_SNotice, Q1_SNoticewhere, Q1_SNoticemainsponsor, Q1_SNoticeteam, Q1_SNoticeother, Q1_Shavechangedview, Q1_Sbuyproducts, Q1_Snameothers, Q1_Comments, Q1_p_id) values(".$_REQUEST['Q1_work'].",".$_REQUEST['Q1_where'].",".$_REQUEST['Q1_why'].",".$_REQUEST['Q1_rating'].",".$_REQUEST['Q1_speakothers'].",".$_REQUEST['Q1_changedme'].",".$_REQUEST['Q1_playoften'].",".$_REQUEST['Q1_infolevel'].",".$_REQUEST['Q1_SNotice'].",".$_REQUEST['Q1_SNoticewhere'].",".$_REQUEST['Q1_SNoticemainsponsor'].",".$_REQUEST['Q1_SNoticeteam'].",".$_REQUEST['Q1_SNoticeother'].",".$_REQUEST['Q1_Shavechangedview'].",".$_REQUEST['Q1_Sbuyproducts'].",'".$_REQUEST['Q1_Snameothers']."','".$_REQUEST['Q1_Comments']."',".$_SESSION['p_id'].")");
			$formular="hidden";
			$tak="visible";
		}
		else {
			$formular="hidden";
			$tak="hidden";
			$duplet="visible";
		}
	}
	else {
		$fejlbesked="<h2>Du mangler at svarer på spørgsmål # $err</h2>.";
		$formular="visible";
		$tak="hidden";
	}
}
else {
	$formular="visible";
	$tak="hidden";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da" lang="da">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="imagetoolbar" content="no" />
		<meta http-equiv="imagetoolbar" content="false" />
		<meta name="publisher" content="Yes2Mail ApS" />
		<meta name="copyright" content="Copyright 2007, Yes2Mail ApS by: René Østerballe" />
		<meta name="author" content="René Chr. Østerballe" />
		<meta name="description" content="Evaluerings spørgeskema i forbindelse med afholdelsen af DM i Kemo-Kasper 2007" />
		<meta name="keywords" content="evaluering, konkurrence, børn, unge, præmier, turnering" />
		<title>Spørgeskema til DM i Kemo-Kasper 2007</title>
		<link rel="shortcut icon" href="/favicon2.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="kemokasper.css" />
		<style type="text/css">
			input, select { background-color: #ffffff; color: #000000} 
		</style>
	</head>
	<body style="margin-left: 100px; background-color: #ffffff;">
	<a href="http://www.fmkb.dk/"><img src="fmkb_logo_circle.png" width="150" height="150" border="0" alt="Foreningen: Familier med Kræftramte børn" /></a>
	<div id="formular" style="visibility: <?php echo $formular ?>;">
		<form action="qform1.php" method="get">
			<h1>SPØRGESKEMA</h1>
<?php
 echo $fejlbesked;
?>
			<p>Svar på spørgsmålene og hjælp os til at gøre Kemo-Kasper bedre.</p>
			<h3>1. Hvad laver du?</h3>
				<select name="Q1_work">
				<option value="">-- Vælg --</option>
				<option value="1" <?php if($_REQUEST['Q1_work']==1) echo 'selected="selected"'; ?>>Folkeskole 7-10 klasse</option>
				<option value="2" <?php if($_REQUEST['Q1_work']==2) echo 'selected="selected"'; ?>>Erhversskole grundudd./faglig uddannelse</option>
				<option value="3" <?php if($_REQUEST['Q1_work']==3) echo 'selected="selected"'; ?>>Gymnasium, HF, HH, HTX og tilsvarende</option>
				<option value="4" <?php if($_REQUEST['Q1_work']==4) echo 'selected="selected"'; ?>>Kort videregående uddannelse (2-3år)</option>
				<option value="5" <?php if($_REQUEST['Q1_work']==5) echo 'selected="selected"'; ?>>Mellemlang/lang videregående uddannelse (3 år +)</option>
				<option value="6" <?php if($_REQUEST['Q1_work']==6) echo 'selected="selected"'; ?>>Arbejder</option>
				<option value="7" <?php if($_REQUEST['Q1_work']==7) echo 'selected="selected"'; ?>>Andet</option>
			</select>

			<h3>2. Hvor hørte du første gang om DM i Kemo-Kasper?</h3>
			<select name="Q1_where">
				<option value="">-- Vælg --</option>
				<option value="10" <?php if($_REQUEST['Q1_where']==10) echo 'selected="selected"'; ?>>Fra venner</option>
				<option value="30" <?php if($_REQUEST['Q1_where']==30) echo 'selected="selected"'; ?>>I en avis artikel</option>
				<option value="31" <?php if($_REQUEST['Q1_where']==31) echo 'selected="selected"'; ?>>Annonce i MetroExpres</option>
				<option value="50" <?php if($_REQUEST['Q1_where']==50) echo 'selected="selected"'; ?>>I "Go´aften Danmark" på TV2</option>
				<option value="51" <?php if($_REQUEST['Q1_where']==51) echo 'selected="selected"'; ?>>I "Aftenshowet" på DR</option>
				<option value="52" <?php if($_REQUEST['Q1_where']==52) echo 'selected="selected"'; ?>>I Radioen</option>
				<option value="60" <?php if($_REQUEST['Q1_where']==60) echo 'selected="selected"'; ?>>I en FONA forretning</option>
				<option value="61" <?php if($_REQUEST['Q1_where']==61) echo 'selected="selected"'; ?>>I en af Legekædens butikker</option>
				<option value="62" <?php if($_REQUEST['Q1_where']==62) echo 'selected="selected"'; ?>>I en Boomtown Netcafé</option>
				<option value="63" <?php if($_REQUEST['Q1_where']==63) echo 'selected="selected"'; ?>>I en Monarch motorvejsrestaurant</option>
				<option value="70" <?php if($_REQUEST['Q1_where']==70) echo 'selected="selected"'; ?>>På hjemmeside men husker ikke hvilken</option>
				<option value="71" <?php if($_REQUEST['Q1_where']==71) echo 'selected="selected"'; ?>>På Danmarks Radios hjemmeside www.DR.dk</option>
				<option value="72" <?php if($_REQUEST['Q1_where']==72) echo 'selected="selected"'; ?>>På hjemmesiden www.GratisSpil.dk</option>
				<option value="73" <?php if($_REQUEST['Q1_where']==73) echo 'selected="selected"'; ?>>På hjemmesiden www.Gratisting.dk</option>
				<option value="75" <?php if($_REQUEST['Q1_where']==75) echo 'selected="selected"'; ?>>Kemo-Kasper Film på internettet</option>
				<option value="79" <?php if($_REQUEST['Q1_where']==79) echo 'selected="selected"'; ?>>På Familier med kræftramte børns hjemmeside</option>
				<option value="0" <?php if($_REQUEST['Q1_where']==0) echo 'selected="selected"'; ?>>Husker det ikke</option>
			</select>			
			<h3>3. Hvorfor deltog du i DM i Kemo-Kasper?</h3>
				<select name="Q1_why">
				<option value="">-- Vælg --</option>
					<option value="1" <?php if($_REQUEST['Q1_why']==1) echo 'selected="selected"'; ?>>Jeg interesserer mig generelt meget for computerspil</option>
					<option value="2" <?php if($_REQUEST['Q1_why']==2) echo 'selected="selected"'; ?>>Jeg vil gerne støtte en god sag (kræftramte børn)</option>
					<option value="3" <?php if($_REQUEST['Q1_why']==3) echo 'selected="selected"'; ?>>Jeg var tilfældigvis tilstede</option>
					<option value="4" <?php if($_REQUEST['Q1_why']==4) echo 'selected="selected"'; ?>>For at lave noget sammen med mine venner</option>
				</select>
			<h3>4. Hvad synes du om DM i Kemo-Kasper?</h3>
				<input type="radio" name="Q1_rating" value="30" <?php if($_REQUEST['Q1_rating']==30) echo 'checked="checked"'; ?> /> God
				<input type="radio" name="Q1_rating" value="20" <?php if($_REQUEST['Q1_rating']==20) echo 'checked="checked"'; ?> /> Middel
				<input type="radio" name="Q1_rating" value="10" <?php if($_REQUEST['Q1_rating']==10) echo 'checked="checked"'; ?> /> Dårlig
			
			<h3>5. Har du talt med andre om DM i Kemo-Kasper?</h3>
				<input type="radio" name="Q1_speakothers" value="1" <?php if($_REQUEST['Q1_speakothers']==1) echo 'checked="checked"'; ?> /> Ja
				<input type="radio" name="Q1_speakothers" value="2" <?php if($_REQUEST['Q1_speakothers']==2) echo 'checked="checked"'; ?> /> Nej
			
			<h3>6. Har DM i Kemo-Kasper ændret din opfattelse af Kræftbehandling?</h3>
				<input type="radio" name="Q1_changedme" value="1" <?php if($_REQUEST['Q1_changedme']==1) echo 'checked="checked"'; ?> /> Ja
				<input type="radio" name="Q1_changedme" value="2" <?php if($_REQUEST['Q1_changedme']==2) echo 'checked="checked"'; ?> /> Nej
				<input type="radio" name="Q1_changedme" value="3" <?php if($_REQUEST['Q1_changedme']==3) echo 'checked="checked"'; ?> /> Jeg har ingen speciel opfattelse af Kræftbehandling
			
			<h3>7. Hvor tit spiller du komputerspil?</h3>
				<input type="radio" name="Q1_playoften" value="50" <?php if($_REQUEST['Q1_playoften']==50) echo 'checked="checked"'; ?> /> Dagligt
				<input type="radio" name="Q1_playoften" value="40" <?php if($_REQUEST['Q1_playoften']==40) echo 'checked="checked"'; ?> /> 3-4 gange om ugen
				<input type="radio" name="Q1_playoften" value="30" <?php if($_REQUEST['Q1_playoften']==30) echo 'checked="checked"'; ?> /> 1-3 gange om ugen
				<input type="radio" name="Q1_playoften" value="20" <?php if($_REQUEST['Q1_playoften']==20) echo 'checked="checked"'; ?> /> Nogle gange hver måned
				<input type="radio" name="Q1_playoften" value="10" <?php if($_REQUEST['Q1_playoften']==10) echo 'checked="checked"'; ?> /> Sjældent eller aldrig
			
			<h3>8. Har du følt dig tilstrækkeligt informeret før, under og efter DM i Kemo-Kasper?</h3>
				<input type="radio" name="Q1_infolevel" value="1" <?php if($_REQUEST['Q1_infolevel']==1) echo 'checked="checked"'; ?> /> Ja
				<input type="radio" name="Q1_infolevel" value="2" <?php if($_REQUEST['Q1_infolevel']==2) echo 'checked="checked"'; ?> /> Nej
			
			<h3>9. Hvor mange firmaer tror du der var med til at sponsorer DM i Kemo-Kasper 2007?</h3>
				<input type="radio" name="Q1_SNotice" value="10" /> Færre end 10
				<input type="radio" name="Q1_SNotice" value="20" /> 10-20
				<input type="radio" name="Q1_SNotice" value="30" /> 20-30
				<input type="radio" name="Q1_SNotice" value="40" /> 30-40
				<input type="radio" name="Q1_SNotice" value="50" /> Flere end 40
			
			<h3>10. Hvor på Kemo-Kaspers hjemmeside lagde du mærke til sponsorer og firmalogoer?</h3>
				<input type="radio" name="Q1_SNoticewhere" value="1" /> På forsiden<br/>
				<input type="radio" name="Q1_SNoticewhere" value="2" /> Nå man oprettede sig som bruger<br/>
				<input type="radio" name="Q1_SNoticewhere" value="3" /> På Placeringsiden (HighScore listerne)<br/>
				<input type="radio" name="Q1_SNoticewhere" value="4" /> På Spil siden når man spillede spillet<br/>
				<input type="radio" name="Q1_SNoticewhere" value="5" /> På siden om Sponsorer<br/>
				<input type="radio" name="Q1_SNoticewhere" value="6" /> Nederst på alle hjemmesiderne<br/>
				<input type="radio" name="Q1_SNoticewhere" value="0" /> Der var slet ikke nogen firmalogoer
			
			<h3>11. Kan du huske navnene på nogle af de firmaer der var på hjemmesiden?</h3>
				<textarea name="Q1_Snameothers" rows="4" cols="80"><?php echo $_REQUEST['Q1_Snameothers']; ?></textarea>
				<p>(Skriv de navne du kan huske, gerne flere)</p>
			
			<h3>12. Hvem tror du der var sponsor af Hovedpræmierne?</h3>
				<input type="radio" name="Q1_SNoticemainsponsor" value="1" /> Legekæden<br/>
				<input type="radio" name="Q1_SNoticemainsponsor" value="2" /> MONARCH<br/>
				<input type="radio" name="Q1_SNoticemainsponsor" value="3" /> FONA<br/>
				<input type="radio" name="Q1_SNoticemainsponsor" value="4" /> Microsoft<br/>
				<input type="radio" name="Q1_SNoticemainsponsor" value="5" /> metroXpress<br/>
				<input type="radio" name="Q1_SNoticemainsponsor" value="6" /> Guldbageren<br/>
				<input type="radio" name="Q1_SNoticemainsponsor" value="7" /> Boomtown
			
			<h3>13. Kan du huske hvilket firmahold du spillede på?</h3>
				<select name="Q1_SNoticeteam">
					<option value="0">Det kan jeg ikke huske</option>
					<option value='30' >3-Stjernet A/S</option>
					<option value='33' >A. Winther A/S</option>
					<option value='10' >Alpi Danmark AS</option>
					<option value='5' >Bavarian Nordic A/S</option>
					<option value='27' >Be Free ApS</option>
					<option value='3' >Blend</option>
					<option value='29' >Bocca</option>
					<option value='53' >Boomtown</option>
					<option value='64' >Brand2Brand A/S</option>
					<option value='73' >Bravo Tours A/S</option>
					<option value='51' >Brdr. A-O Johansen A/S</option>
					<option value='11' >Bygteq it</option>
					<option value='70' >Caretowear</option>
					<option value='72' >Cirkus Arena</option>
					<option value='36' >Cocio</option>
					<option value='21' >Codan Forsikring A/S</option>
					<option value='19' >Colgate-Palmolive A/S</option>
					<option value='38' >COWI A/S</option>
					<option value='59' >Daloon A/S</option>
					<option value='26' >Dantherm A/S</option>
					<option value='63' >DELL A/S</option>
					<option value='23' >Den Gamle Fabrik</option>
					<option value='66' >Det Kongelige teater</option>
					<option value='18' >DFDS Seaways A/S</option>
					<option value='57' >Eksprestrykkeriet</option>
					<option value='58' >Europcar</option>
					<option value='76' >Fab-It </option>
					<option value='17' >Fakta A/S</option>
					<option value='8' >Fiberby</option>
					<option value='12' >FONA</option>
					<option value='77' >Goldwell A/S</option>
					<option value='74' >GRATISSPIL.DK</option>
					<option value='54' >Gratisting.dk</option>
					<option value='62' >Grinern.dk</option>
					<option value='13' >Guldbageren</option>
					<option value='69' >Helms TMT-Centret</option>
					<option value='1' >intet hold</option>
					<option value='16' >KMD A/S</option>
					<option value='9' >Lantmännen Mills A/S</option>
					<option value='28' >Legekæden A.M.B.A</option>
					<option value='60' >Logitech Nordic</option>
					<option value='67' >Make Help A/S</option>
					<option value='56' >MetroXpress A/S</option>
					<option value='31' >Microsoft Danmark</option>
					<option value='6' >Monarch A/S</option>
					<option value='68' >Nyhedsavisen</option>
					<option value='55' >One Day Productions</option>
					<option value='22' >Pathtrace A/S</option>
					<option value='24' >Pfizer A/S</option>
					<option value='20' >Royal Unibrew</option>
					<option value='71' >Røsfeld Data A/S</option>
					<option value='75' >Sennheiser Nordic A/S</option>
					<option value='65' >Statens Museum for Kunst</option>
					<option value='37' >Stryhns A/S</option>
					<option value='32' >Stryhns A/S</option>
					<option value='39' >Team Atari</option>
					<option value='35' >Team Dragon Ball</option>
					<option value='34' >The Body Shop</option>
					<option value='61' >Ubisoft Nordic A/S</option>
					<option value='7' >Unilever Bestfoods</option>
					<option value='4' >Uovo ApS</option>
					<option value='25' >Vestas</option>
					<option value='2' >Yes2Mail ApS</option>	
				</select>
			<h3>14. Har du lagt mærke til sponsor eller firmalogoer andre steder end på hjemmesiden?</h3>
				<input type="radio" name="Q1_SNoticeother" value="1" /> Ja, På flyers<br/>
				<input type="radio" name="Q1_SNoticeother" value="2" /> Ja, På plakater<br/>
				<input type="radio" name="Q1_SNoticeother" value="3" /> Ja, I avisannoncer<br/>
				<input type="radio" name="Q1_SNoticeother" value="4" /> Ja, I invitationer og/eller nyhedsbreve<br/>
				<input type="radio" name="Q1_SNoticeother" value="5" /> Nej, jeg vidste slet ikke der var sponsorer
			
			<h3>15. Har det påvirket dit syn på nogle virksomheder, at de har været sponsorer ved DM i Kemo-Kasper 2007?</h3>
				<input type="radio" name="Q1_Shavechangedview" value="1" <?php if($_REQUEST['Q1_Shavechangedview']==1) echo 'checked="checked"'; ?> /> Det har påvirket mit syn på dem i positiv retning<br/>
				<input type="radio" name="Q1_Shavechangedview" value="2" <?php if($_REQUEST['Q1_Shavechangedview']==2) echo 'checked="checked"'; ?> /> Det har påvirket mit syn på dem i negativ retning<br/>
				<input type="radio" name="Q1_Shavechangedview" value="0" <?php if($_REQUEST['Q1_Shavechangedview']==0) echo 'checked="checked"'; ?> /> Det har ikke påvirket mit syn
			
			<h3>16. Har du fået lyst til, at købe produkter fra en eller flere sponsorer?</h3>
				<input type="radio" name="Q1_Sbuyproducts" value="1" <?php if($_REQUEST['Q1_Sbuyproducts']==1) echo 'checked="checked"'; ?>/> Ja
				<input type="radio" name="Q1_Sbuyproducts" value="2" <?php if($_REQUEST['Q1_Sbuyproducts']==2) echo 'checked="checked"'; ?>/> Nej
			
			<h3>17. Har du ris, ros eller forslag til hvordan vi kan gøre DM i Kemo-Kasper bedre næste år?</h3>
			<textarea name="Q1_Comments" rows="5" cols="80"><?php echo $_REQUEST['Q1_Comments']; ?></textarea>
			
			<h3>Klik på ”send-knappen” for at indsende din besvarelse og deltage i lodtrækningen om spillene ”Hour of Victory” til XBOX360 eller ”My Pony Stables” til PC.</h3>
			<input type="submit" name="send" value="SEND" />
			</form>
		</div>
		<div id="tak" style="position: absolute; top: 170px; visibility: <?php echo $tak ?>;">
			<h1>Tak for din besvarelse</h1>
			<h2>Du deltager nu i lodtrækningen om spillene ”Hour of Victory” til XBOX360 eller ”My Pony Stables” til PC.</h2>
				<img src="questionaire_prize.png" alt="Hours of Victory eller My Pony Stables" />
			<h3>Vinderen får direkte besked af Familier med Kræftramte børn, den 20. november 2007.</h3>
			<p>Med venlig hilsen</p>
			<p><b>Familier med kræftramte børn<br/>og<br/>Kemo Kasper</b></p>
		</div>
		<div id="duplet" style="position: absolute; top: 170px; visibility: <?php echo $duplet ?>;">
			<h1>Vi har allerede modtaget din besvarelse</h1>
			<h2>Man kan kun besvare spørgeskemaet en gang</h2>
			<p>Med venlig hilsen</p>
			<p><b>FMKB</b></p>
		</div>
</body>
</html>