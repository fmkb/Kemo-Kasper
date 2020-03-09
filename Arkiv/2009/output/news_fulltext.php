<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/news_variables.php");
include("include/languages.php");

if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	DisplayCloseWindow(); 
	return;
}

$field = @$_GET["field"];
if(!CheckFieldPermissions($field))
	return DisplayCloseWindow();

//	construct sql


$keys=array();
$keys["n_id"]=postvalue("key1");
$where=KeyWhere($keys);

//$sql=$gstrSQL;
//$sql = AddWhere($sql,$where);

$conn=db_connect();


$sql = gSQLWhere($where);

$rs = db_query($sql,$conn);
if(!$rs || !($data=db_fetch_array($rs)))
  return DisplayCloseWindow();


$value=nl2br(htmlspecialchars($data[$field]));
echobig($value);
DisplayCloseWindow();
return;


function DisplayCloseWindow()
{
	echo "<br>";
	echo "<hr size=1 noshade>";
	echo "<a href=# onClick='window.close();return false;'>".mlang_message("CLOSE_WINDOW")."</a>";
}

?>
