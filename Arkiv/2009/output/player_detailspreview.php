<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/player_variables.php");
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

if($mastertable=="teams")
{
	$where ="";
		$where.= GetFullFieldName("p_s_id")."=".make_db_value("p_s_id",$_SESSION[$strTableName."_masterkey1"]);
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
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["p_id"]));

	//	p_active - 
		    $value="";
				$value=DisplayLookupWizard("p_active",$data["p_active"],$data,$keylink,MODE_PRINT);
			$row["p_active_value"]=$value;
	//	p_s_id - 
		    $value="";
				$value=DisplayLookupWizard("p_s_id",$data["p_s_id"],$data,$keylink,MODE_PRINT);
			$row["p_s_id_value"]=$value;
	//	p_first - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_first", ""),"field=p%5Ffirst".$keylink,"",MODE_PRINT);
			$row["p_first_value"]=$value;
	//	p_name - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_name", ""),"field=p%5Fname".$keylink,"",MODE_PRINT);
			$row["p_name_value"]=$value;
	//	p_zip - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_zip", ""),"field=p%5Fzip".$keylink,"",MODE_PRINT);
			$row["p_zip_value"]=$value;
	//	p_mail - Email Hyperlink
		    $value="";
				$value = GetData($data,"p_mail", "Email Hyperlink");
			$row["p_mail_value"]=$value;
	//	p_newsaccept - Checkbox
		    $value="";
				$value = GetData($data,"p_newsaccept", "Checkbox");
			$row["p_newsaccept_value"]=$value;
	//	p_win - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_win", ""),"field=p%5Fwin".$keylink,"",MODE_PRINT);
			$row["p_win_value"]=$value;
	//	p_mk - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_mk", ""),"field=p%5Fmk".$keylink,"",MODE_PRINT);
			$row["p_mk_value"]=$value;
	//	p_born - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_born", ""),"field=p%5Fborn".$keylink,"",MODE_PRINT);
			$row["p_born_value"]=$value;
	//	p_ip - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_ip", ""),"field=p%5Fip".$keylink,"",MODE_PRINT);
			$row["p_ip_value"]=$value;
	//	p_datetime - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_datetime", "Short Date"),"field=p%5Fdatetime".$keylink,"",MODE_PRINT);
			$row["p_datetime_value"]=$value;
	//	p_tscore - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"p_tscore", ""),"field=p%5Ftscore".$keylink,"",MODE_PRINT);
			$row["p_tscore_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("player_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>