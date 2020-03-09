<?php

$strTableName="xcountries";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="xcountries";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT xc_id,  xc_name,  xc_currency,  xc_code,  xc_desc ";
$gsqlFrom="FROM xcountries ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  xc_id,  xc_name,  xc_currency,  xc_code,  xc_desc  FROM xcountries  ";
$gstrSQL = gSQLWhere("");

include("xcountries_settings.php");
include("xcountries_events.php");
?>