<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/MQ_Chart_variables.php");
include("include/languages.php");

$conn = db_connect();

$field = GetFieldByGoodFieldName(postvalue('field'));
$value = postvalue('value');

	if(!@$_SESSION["UserID"]) { return;	}
	if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search")) { return;	}

$output = loadSelectContent($field, $value);

foreach( $output as $value ) {
	echo $value."\n";
}

?>