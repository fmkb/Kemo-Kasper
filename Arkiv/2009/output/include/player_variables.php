<?php

$strTableName="player";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="player";

$gPageSize=30;

$gstrOrderBy="ORDER BY p_active, p_id DESC";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT p_id,  p_active,  p_location,  p_s_id,  p_first,  p_name,  p_adr,  p_zip,  p_mail,  p_newsaccept,  p_score,  p_scorehigh,  p_games,  p_time,  p_win,  p_mk,  p_born,  p_user,  p_pwd,  p_ip,  p_datetime,  p_tscore,  p_tkills,  p_country,  p_mobile,  p_avatar ";
$gsqlFrom="FROM player ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  p_id,  p_active,  p_location,  p_s_id,  p_first,  p_name,  p_adr,  p_zip,  p_mail,  p_newsaccept,  p_score,  p_scorehigh,  p_games,  p_time,  p_win,  p_mk,  p_born,  p_user,  p_pwd,  p_ip,  p_datetime,  p_tscore,  p_tkills,  p_country,  p_mobile,  p_avatar  FROM player  ORDER BY p_active, p_id DESC  ";
$gstrSQL = gSQLWhere("");

include("player_settings.php");
include("player_events.php");
?>