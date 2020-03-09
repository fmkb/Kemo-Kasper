<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/raffle_variables.php");
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

if($mastertable=="player")
{
	$where ="";
		$where.= GetFullFieldName("r_p_id")."=".make_db_value("r_p_id",$_SESSION[$strTableName."_masterkey1"]);
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
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["r_id"]));

	//	r_id - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"r_id", ""),"field=r%5Fid".$keylink,"",MODE_PRINT);
			$row["r_id_value"]=$value;
	//	r_name - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"r_name", ""),"field=r%5Fname".$keylink,"",MODE_PRINT);
			$row["r_name_value"]=$value;
	//	r_img - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"r_img", ""),"field=r%5Fimg".$keylink,"",MODE_PRINT);
			$row["r_img_value"]=$value;
	//	r_date - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"r_date", "Short Date"),"field=r%5Fdate".$keylink,"",MODE_PRINT);
			$row["r_date_value"]=$value;
	//	r_p_id - 
		    $value="";
				$value=DisplayLookupWizard("r_p_id",$data["r_p_id"],$data,$keylink,MODE_PRINT);
			$row["r_p_id_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("raffle_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>