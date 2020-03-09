<?php

$strTableName="mails";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="mails";

$gPageSize=30;

$gstrOrderBy="ORDER BY m_name";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT m_id,  m_name,  m_body ";
$gsqlFrom="FROM mails ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  m_id,  m_name,  m_body  FROM mails  ORDER BY m_name  ";
$gstrSQL = gSQLWhere("");

include("mails_settings.php");
include("mails_events.php");
?>