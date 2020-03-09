<?php

$strTableName="xstatus";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="xstatus";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT xs_id,   xs_text ";
$gsqlFrom="FROM xstatus ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT xs_id,   xs_text   FROM xstatus ";
$gstrSQL = gSQLWhere("");

include("xstatus_settings.php");
include("xstatus_events.php");
?>