<html>
	<head>
	</head>
	<body onload="window.print(); return false">

<?php

//** Connect to SQL Server and select Database.
if (!mysql_connect("localhost", "kemo_kasper_dk", "QvK54Xpa")) exit;
if (!mysql_select_db("kemo_kasper_dk")) exit;

//** Execute a SQL statement and return the result.
function sql($statment,$assoc=1) {
	if(!$result = mysql_query($statment)) {
		echo mysql_error();
		exit;
	}
	else return $result;
}

$TempSql = sql("select p_first, p_name, p_born, p_tscore, p_tkills, p_zip, ZZCity from player, ZZIP where p_zip=ZZIP and p_active=1 and p_id=".$_REQUEST['p_id']);
if($TempRow = mysql_fetch_row($TempSql)) {
	echo '<div id "n1" style="position: absolute; top: 330px; left: 340px; font-family: Lucida Grande, Lucida Sans, Lucida Sans Unicode, sans-serif; font-size: 16pt;">';
	echo $TempRow[0]." ".$TempRow[1];
	echo '</div>';

	echo '<div id "s1" style="position: absolute; top: 760px; left: 340px; font-family: Lucida Grande, Lucida Sans, Lucida Sans Unicode, sans-serif; font-size: 16pt;">';
	echo $TempRow[3];
	echo '</div>';

	echo '<div id "s2" style="position: absolute; top: 760px; left: 550px; font-family: Lucida Grande, Lucida Sans, Lucida Sans Unicode, sans-serif; font-size: 16pt;">';
	echo $TempRow[4];
	echo '</div>';

}

?>
	</body>
</html>

