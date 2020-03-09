<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Q1_variables.php");
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
		$where.= GetFullFieldName("Q1_p_id")."=".make_db_value("Q1_p_id",$_SESSION[$strTableName."_masterkey1"]);
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
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["Q1_id"]));

	//	Q1_id - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_id", ""),"field=Q1%5Fid".$keylink,"",MODE_PRINT);
			$row["Q1_id_value"]=$value;
	//	Q1_work - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_work", ""),"field=Q1%5Fwork".$keylink,"",MODE_PRINT);
			$row["Q1_work_value"]=$value;
	//	Q1_where - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_where", ""),"field=Q1%5Fwhere".$keylink,"",MODE_PRINT);
			$row["Q1_where_value"]=$value;
	//	Q1_why - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_why", ""),"field=Q1%5Fwhy".$keylink,"",MODE_PRINT);
			$row["Q1_why_value"]=$value;
	//	Q1_rating - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_rating", ""),"field=Q1%5Frating".$keylink,"",MODE_PRINT);
			$row["Q1_rating_value"]=$value;
	//	Q1_speakothers - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_speakothers", ""),"field=Q1%5Fspeakothers".$keylink,"",MODE_PRINT);
			$row["Q1_speakothers_value"]=$value;
	//	Q1_changedme - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_changedme", ""),"field=Q1%5Fchangedme".$keylink,"",MODE_PRINT);
			$row["Q1_changedme_value"]=$value;
	//	Q1_playoften - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_playoften", ""),"field=Q1%5Fplayoften".$keylink,"",MODE_PRINT);
			$row["Q1_playoften_value"]=$value;
	//	Q1_infolevel - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_infolevel", ""),"field=Q1%5Finfolevel".$keylink,"",MODE_PRINT);
			$row["Q1_infolevel_value"]=$value;
	//	Q1_SNotice - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNotice", ""),"field=Q1%5FSNotice".$keylink,"",MODE_PRINT);
			$row["Q1_SNotice_value"]=$value;
	//	Q1_SNoticewhere - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticewhere", ""),"field=Q1%5FSNoticewhere".$keylink,"",MODE_PRINT);
			$row["Q1_SNoticewhere_value"]=$value;
	//	Q1_SNoticemainsponsor - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticemainsponsor", ""),"field=Q1%5FSNoticemainsponsor".$keylink,"",MODE_PRINT);
			$row["Q1_SNoticemainsponsor_value"]=$value;
	//	Q1_SNoticeteam - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticeteam", ""),"field=Q1%5FSNoticeteam".$keylink,"",MODE_PRINT);
			$row["Q1_SNoticeteam_value"]=$value;
	//	Q1_SNoticeother - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticeother", ""),"field=Q1%5FSNoticeother".$keylink,"",MODE_PRINT);
			$row["Q1_SNoticeother_value"]=$value;
	//	Q1_Shavechangedview - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_Shavechangedview", ""),"field=Q1%5FShavechangedview".$keylink,"",MODE_PRINT);
			$row["Q1_Shavechangedview_value"]=$value;
	//	Q1_Sbuyproducts - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_Sbuyproducts", ""),"field=Q1%5FSbuyproducts".$keylink,"",MODE_PRINT);
			$row["Q1_Sbuyproducts_value"]=$value;
	//	Q1_Snameothers - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_Snameothers", ""),"field=Q1%5FSnameothers".$keylink,"",MODE_PRINT);
			$row["Q1_Snameothers_value"]=$value;
	//	Q1_Comments - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_Comments", ""),"field=Q1%5FComments".$keylink,"",MODE_PRINT);
			$row["Q1_Comments_value"]=$value;
	//	Q1_p_id - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Q1_p_id", ""),"field=Q1%5Fp%5Fid".$keylink,"",MODE_PRINT);
			$row["Q1_p_id_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("Q1_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>