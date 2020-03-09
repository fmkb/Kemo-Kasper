<?php

$strTableName="Q1";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="Q1";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT Q1_id,  Q1_work,  Q1_where,  Q1_why,  Q1_rating,  Q1_speakothers,  Q1_changedme,  Q1_playoften,  Q1_infolevel,  Q1_SNotice,  Q1_SNoticewhere,  Q1_SNoticemainsponsor,  Q1_SNoticeteam,  Q1_SNoticeother,  Q1_Shavechangedview,  Q1_Sbuyproducts,  Q1_Snameothers,  Q1_Comments,  Q1_p_id ";
$gsqlFrom="FROM Q1 ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  Q1_id,  Q1_work,  Q1_where,  Q1_why,  Q1_rating,  Q1_speakothers,  Q1_changedme,  Q1_playoften,  Q1_infolevel,  Q1_SNotice,  Q1_SNoticewhere,  Q1_SNoticemainsponsor,  Q1_SNoticeteam,  Q1_SNoticeother,  Q1_Shavechangedview,  Q1_Sbuyproducts,  Q1_Snameothers,  Q1_Comments,  Q1_p_id  FROM Q1  ";
$gstrSQL = gSQLWhere("");

include("Q1_settings.php");
include("Q1_events.php");
?>