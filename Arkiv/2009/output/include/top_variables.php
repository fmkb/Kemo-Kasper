<?php

$strTableName="top";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="top";

$gPageSize=30;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT t_id,  t_user,  t_score,  t_datetime,  t_ip,  t_p_id,  t_ts_id,  t_kills ";
$gsqlFrom="FROM `top` ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  t_id,  t_user,  t_score,  t_datetime,  t_ip,  t_p_id,  t_ts_id,  t_kills  FROM `top`  ";
$gstrSQL = gSQLWhere("");

include("top_settings.php");
include("top_events.php");
?>