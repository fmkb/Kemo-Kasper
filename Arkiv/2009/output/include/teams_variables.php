<?php

$strTableName="teams";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="teams";

$gPageSize=30;

$gstrOrderBy="ORDER BY ts_s_id";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT ts_id,  ts_name,  ts_p0,  ts_p1,  ts_p2,  ts_p9,  ts_s_id,  ts_Score ";
$gsqlFrom="FROM teams ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  ts_id,  ts_name,  ts_p0,  ts_p1,  ts_p2,  ts_p9,  ts_s_id,  ts_Score  FROM teams  ORDER BY ts_s_id  ";
$gstrSQL = gSQLWhere("");

include("teams_settings.php");
include("teams_events.php");
?>