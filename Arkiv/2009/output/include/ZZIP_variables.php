<?php

$strTableName="ZZIP";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="ZZIP";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT ZZIP,   ZZCity ";
$gsqlFrom="FROM ZZIP ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT ZZIP,   ZZCity   FROM ZZIP ";
$gstrSQL = gSQLWhere("");

include("ZZIP_settings.php");
include("ZZIP_events.php");
?>