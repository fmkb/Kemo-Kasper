<?php

$strTableName="sponsor";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="sponsor";

$gPageSize=30;

$gstrOrderBy="ORDER BY s_name";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT s_id,  s_active,  s_name,  s_contact,  s_adr,  s_zip,  s_phone1,  s_phone2,  s_total,  s_paid,  s_logo,  s_banner,  s_www,  s_mail,  s_cmt,  s_country ";
$gsqlFrom="FROM sponsor ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  s_id,  s_active,  s_name,  s_contact,  s_adr,  s_zip,  s_phone1,  s_phone2,  s_total,  s_paid,  s_logo,  s_banner,  s_www,  s_mail,  s_cmt,  s_country  FROM sponsor  ORDER BY s_name  ";
$gstrSQL = gSQLWhere("");

include("sponsor_settings.php");
include("sponsor_events.php");
?>