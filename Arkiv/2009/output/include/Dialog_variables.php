<?php

$strTableName="Dialog";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="Dialog";

$gPageSize=30;

$gstrOrderBy="ORDER BY d_datetime DESC";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT d_id,  d_from_p_id,  d_to_p_id,  d_message,  d_datetime,  d_msg_type ";
$gsqlFrom="FROM Dialog ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  d_id,  d_from_p_id,  d_to_p_id,  d_message,  d_datetime,  d_msg_type  FROM Dialog  ORDER BY d_datetime DESC  ";
$gstrSQL = gSQLWhere("");

include("Dialog_settings.php");
include("Dialog_events.php");
?>