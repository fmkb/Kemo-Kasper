<?php

$strTableName="MQ Chart";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="player";


$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT p_mk,  count(p_mk) ";
$gsqlFrom="FROM player ";
$gsqlWhere="";
$gsqlTail="group by p_mk ";
// $gstrSQL = "SELECT p_mk,  count(p_mk)  FROM player group by p_mk  ";
$gstrSQL = gSQLWhere("");

include("MQ_Chart_settings.php");
include("MQ_Chart_events.php");
?>