<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/top_variables.php");
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
		$where.= GetFullFieldName("t_p_id")."=".make_db_value("t_p_id",$_SESSION[$strTableName."_masterkey1"]);
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
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["t_id"]));

	//	t_id - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_id", ""),"field=t%5Fid".$keylink,"",MODE_PRINT);
			$row["t_id_value"]=$value;
	//	t_user - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_user", ""),"field=t%5Fuser".$keylink,"",MODE_PRINT);
			$row["t_user_value"]=$value;
	//	t_score - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_score", ""),"field=t%5Fscore".$keylink,"",MODE_PRINT);
			$row["t_score_value"]=$value;
	//	t_datetime - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_datetime", "Short Date"),"field=t%5Fdatetime".$keylink,"",MODE_PRINT);
			$row["t_datetime_value"]=$value;
	//	t_ip - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_ip", ""),"field=t%5Fip".$keylink,"",MODE_PRINT);
			$row["t_ip_value"]=$value;
	//	t_p_id - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_p_id", ""),"field=t%5Fp%5Fid".$keylink,"",MODE_PRINT);
			$row["t_p_id_value"]=$value;
	//	t_ts_id - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_ts_id", ""),"field=t%5Fts%5Fid".$keylink,"",MODE_PRINT);
			$row["t_ts_id_value"]=$value;
	//	t_kills - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"t_kills", ""),"field=t%5Fkills".$keylink,"",MODE_PRINT);
			$row["t_kills_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("top_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>