<?php

$strTableName="raffle";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="raffle";

$gPageSize=30;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT r_id,   r_name,   r_img,   r_text,   r_date,   r_p_id ";
$gsqlFrom="FROM raffle ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT r_id,   r_name,   r_img,   r_text,   r_date,   r_p_id   FROM raffle ";
$gstrSQL = gSQLWhere("");

include("raffle_settings.php");
include("raffle_events.php");
?>