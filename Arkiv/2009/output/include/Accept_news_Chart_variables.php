<?php

$strTableName="Accept news Chart";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="player";


$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT p_newsaccept,  COUNT(p_newsaccept) ";
$gsqlFrom="FROM player ";
$gsqlWhere="";
$gsqlTail="group by p_newsaccept ";
// $gstrSQL = "SELECT p_newsaccept,  COUNT(p_newsaccept)  FROM player group by p_newsaccept  ";
$gstrSQL = gSQLWhere("");

include("Accept_news_Chart_settings.php");
include("Accept_news_Chart_events.php");
?>