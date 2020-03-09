<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/teams_variables.php");
include("include/languages.php");

$mode=postvalue("mode");

if(!@$_SESSION["UserID"])
{ 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	return;
}

include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect(); 
$recordsCounter = 0;

//	process masterkey value
$mastertable=postvalue("mastertable");
if($mastertable!="")
{
	$_SESSION[$strTableName."_mastertable"]=$mastertable;
//	copy keys to session
	$i=1;
	while(isset($_REQUEST["masterkey".$i]))
	{
		$_SESSION[$strTableName."_masterkey".$i]=$_REQUEST["masterkey".$i];
		$i++;
	}
	if(isset($_SESSION[$strTableName."_masterkey".$i]))
		unset($_SESSION[$strTableName."_masterkey".$i]);
}
else
	$mastertable=$_SESSION[$strTableName."_mastertable"];

//$strSQL = $gstrSQL;

if($mastertable=="sponsor")
{
	$where ="";
		$where.= GetFullFieldName("ts_s_id")."=".make_db_value("ts_s_id",$_SESSION[$strTableName."_masterkey1"]);
}


$str = SecuritySQL("Search");
if(strlen($str))
	$where.=" and ".$str;
$strSQL = gSQLWhere($where);

$strSQL.=" ".$gstrOrderBy;

$rowcount=gSQLRowCount($where,0);

$xt->assign("row_count",$rowcount);
if ( $rowcount ) {
	$xt->assign("details_data",true);
	$rs=db_query($strSQL,$conn);
	$display_count=10;
	if($mode=="inline")
		$display_count*=2;
	if($rowcount>$display_count+2)
	{
		$xt->assign("display_first",true);
		$xt->assign("display_count",$display_count);
	}
	else
		$display_count = $rowcount;

	$rowinfo=array();
		
	while (($data = db_fetch_array($rs)) && $recordsCounter<$display_count) {
		$recordsCounter++;
		$row=array();
		$keylink="";
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["ts_id"]));

	//	ts_id - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"ts_id", ""),"field=ts%5Fid".$keylink,"",MODE_PRINT);
			$row["ts_id_value"]=$value;
	//	ts_p0 - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"ts_p0", ""),"field=ts%5Fp0".$keylink,"",MODE_PRINT);
			$row["ts_p0_value"]=$value;
	//	ts_s_id - 
		    $value="";
				$value=DisplayLookupWizard("ts_s_id",$data["ts_s_id"],$data,$keylink,MODE_PRINT);
			$row["ts_s_id_value"]=$value;
	//	ts_name - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"ts_name", ""),"field=ts%5Fname".$keylink,"",MODE_PRINT);
			$row["ts_name_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("teams_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>