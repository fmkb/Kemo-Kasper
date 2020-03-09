<?php

$strTableName="news";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="news";

$gPageSize=30;

$gstrOrderBy="ORDER BY n_active, n_start DESC";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT n_id,  n_active,  n_start,  n_end,  n_date,  n_head,  n_text,  n_link,  n_file,  n_type,  n_teaser,  n_country ";
$gsqlFrom="FROM news ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  n_id,  n_active,  n_start,  n_end,  n_date,  n_head,  n_text,  n_link,  n_file,  n_type,  n_teaser,  n_country  FROM news  ORDER BY n_active, n_start DESC  ";
$gstrSQL = gSQLWhere("");

include("news_settings.php");
include("news_events.php");
?>