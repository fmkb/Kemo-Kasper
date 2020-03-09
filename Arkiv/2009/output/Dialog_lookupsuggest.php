<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Dialog_variables.php");
include("include/languages.php");

$conn = db_connect();

$field = postvalue('searchField');
$value = postvalue('searchFor');
$lookupValue = postvalue('lookupValue');
$LookupSQL = "";
$response = array();
$output = "";

	if(!@$_SESSION["UserID"]) { return;	}
	if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search")) { return;	}

	if($field=="d_from_p_id") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`p_id`";
		$LookupSQL .= ",concat(P_First,' ',P_Name)";
		$LookupSQL .= " FROM `player` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "concat(P_First,' ',P_Name) LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `p_name`";
			$LookupSQL.=" DESC";	
	}
	if($field=="d_to_p_id") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`p_id`";
		$LookupSQL .= ",concat(P_First,' ',P_Name)";
		$LookupSQL .= " FROM `player` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "concat(P_First,' ',P_Name) LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `p_name`";
			$LookupSQL.=" DESC";	
	}

$rs=db_query($LookupSQL,$conn);

$found=false;
while ($data = db_fetch_numarray($rs)) 
{
	if(!$found && $data[0]==$lookupValue)
		$found=true;
	$response[] = $data[0];
	$response[] = $data[1];
}


if ($output = array_chunk($response,40)) {
	foreach( $output[0] as $value ) {
		echo $value."\n";
		//echo str_replace("\n","\\n",$value)."\n";
	}
}

?>