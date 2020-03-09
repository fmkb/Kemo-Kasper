<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/top_variables.php");
include("include/languages.php");




//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";	
$message="";

$all=postvalue("all");
$pdf=postvalue("pdf");
$mypage=1;

$id=1;

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessView"))
	BeforeProcessView($conn);

$strWhereClause="";
if(!$all)
{
	$keys=array();
	$keys["t_id"]=postvalue("editid1");

//	get current values and show edit controls

	$strWhereClause = KeyWhere($keys);


	$strSQL=gSQLWhere($strWhereClause);
}
else
{
	if ($_SESSION[$strTableName."_SelectedSQL"]!="" && @$_REQUEST["records"]=="") 
	{
		$strSQL = $_SESSION[$strTableName."_SelectedSQL"];
		$strWhereClause=@$_SESSION[$strTableName."_SelectedWhere"];
	}
	else
	{
		$strWhereClause=@$_SESSION[$strTableName."_where"];
		$strSQL=gSQLWhere($strWhereClause);
	}
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
		$numrows=gSQLRowCount($strWhereClause,0);

}


$strSQLbak = $strSQL;
if(function_exists("BeforeQueryView"))
	BeforeQueryView($strSQL,$strWhereClause);
if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);

if(!$all)
{
	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
}
else
{
//	 Pagination:

	$nPageSize=0;
	if(@$_REQUEST["records"]=="page" && $numrows)
	{
		$mypage=(integer)@$_SESSION[$strTableName."_pagenumber"];
		$nPageSize=(integer)@$_SESSION[$strTableName."_pagesize"];
		if($numrows<=($mypage-1)*$nPageSize)
			$mypage=ceil($numrows/$nPageSize);
		if(!$nPageSize)
			$nPageSize=$gPageSize;
		if(!$mypage)
			$mypage=1;

		$strSQL.=" limit ".(($mypage-1)*$nPageSize).",".$nPageSize;
	}
	$rs=db_query($strSQL,$conn);
}

$data=db_fetch_array($rs);

include('libs/xtempl.php');
$xt = new Xtempl();

$out="";
$first=true;

$templatefile="";

while($data)
{



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"t_id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["t_id"]));

////////////////////////////////////////////
//	t_id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"t_id", ""),"","",MODE_VIEW);
	$xt->assign("t_id_value",$value);
	$xt->assign("t_id_fieldblock",true);
////////////////////////////////////////////
//	t_user - 
	$value="";
		$value = ProcessLargeText(GetData($data,"t_user", ""),"","",MODE_VIEW);
	$xt->assign("t_user_value",$value);
	$xt->assign("t_user_fieldblock",true);
////////////////////////////////////////////
//	t_score - 
	$value="";
		$value = ProcessLargeText(GetData($data,"t_score", ""),"","",MODE_VIEW);
	$xt->assign("t_score_value",$value);
	$xt->assign("t_score_fieldblock",true);
////////////////////////////////////////////
//	t_datetime - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"t_datetime", "Short Date"),"","",MODE_VIEW);
	$xt->assign("t_datetime_value",$value);
	$xt->assign("t_datetime_fieldblock",true);
////////////////////////////////////////////
//	t_ip - 
	$value="";
		$value = ProcessLargeText(GetData($data,"t_ip", ""),"","",MODE_VIEW);
	$xt->assign("t_ip_value",$value);
	$xt->assign("t_ip_fieldblock",true);
////////////////////////////////////////////
//	t_p_id - 
	$value="";
		$value=DisplayLookupWizard("t_p_id",$data["t_p_id"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("t_p_id_value",$value);
	$xt->assign("t_p_id_fieldblock",true);
////////////////////////////////////////////
//	t_ts_id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"t_ts_id", ""),"","",MODE_VIEW);
	$xt->assign("t_ts_id_value",$value);
	$xt->assign("t_ts_id_fieldblock",true);
////////////////////////////////////////////
//	t_kills - 
	$value="";
		$value = ProcessLargeText(GetData($data,"t_kills", ""),"","",MODE_VIEW);
	$xt->assign("t_kills_value",$value);
	$xt->assign("t_kills_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='top_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "top_view.htm";
if(!$all)
{
	if(function_exists("BeforeShowView"))
		BeforeShowView($xt,$templatefile,$data);
	if(!$pdf)
		$xt->display($templatefile);
	break;
}

}


?>
