<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Q1_variables.php");
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
	$keys["Q1_id"]=postvalue("editid1");

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



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"Q1_id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["Q1_id"]));

////////////////////////////////////////////
//	Q1_id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_id", ""),"","",MODE_VIEW);
	$xt->assign("Q1_id_value",$value);
	$xt->assign("Q1_id_fieldblock",true);
////////////////////////////////////////////
//	Q1_work - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_work", ""),"","",MODE_VIEW);
	$xt->assign("Q1_work_value",$value);
	$xt->assign("Q1_work_fieldblock",true);
////////////////////////////////////////////
//	Q1_where - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_where", ""),"","",MODE_VIEW);
	$xt->assign("Q1_where_value",$value);
	$xt->assign("Q1_where_fieldblock",true);
////////////////////////////////////////////
//	Q1_why - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_why", ""),"","",MODE_VIEW);
	$xt->assign("Q1_why_value",$value);
	$xt->assign("Q1_why_fieldblock",true);
////////////////////////////////////////////
//	Q1_rating - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_rating", ""),"","",MODE_VIEW);
	$xt->assign("Q1_rating_value",$value);
	$xt->assign("Q1_rating_fieldblock",true);
////////////////////////////////////////////
//	Q1_speakothers - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_speakothers", ""),"","",MODE_VIEW);
	$xt->assign("Q1_speakothers_value",$value);
	$xt->assign("Q1_speakothers_fieldblock",true);
////////////////////////////////////////////
//	Q1_changedme - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_changedme", ""),"","",MODE_VIEW);
	$xt->assign("Q1_changedme_value",$value);
	$xt->assign("Q1_changedme_fieldblock",true);
////////////////////////////////////////////
//	Q1_playoften - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_playoften", ""),"","",MODE_VIEW);
	$xt->assign("Q1_playoften_value",$value);
	$xt->assign("Q1_playoften_fieldblock",true);
////////////////////////////////////////////
//	Q1_infolevel - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_infolevel", ""),"","",MODE_VIEW);
	$xt->assign("Q1_infolevel_value",$value);
	$xt->assign("Q1_infolevel_fieldblock",true);
////////////////////////////////////////////
//	Q1_SNotice - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_SNotice", ""),"","",MODE_VIEW);
	$xt->assign("Q1_SNotice_value",$value);
	$xt->assign("Q1_SNotice_fieldblock",true);
////////////////////////////////////////////
//	Q1_SNoticewhere - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_SNoticewhere", ""),"","",MODE_VIEW);
	$xt->assign("Q1_SNoticewhere_value",$value);
	$xt->assign("Q1_SNoticewhere_fieldblock",true);
////////////////////////////////////////////
//	Q1_SNoticemainsponsor - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_SNoticemainsponsor", ""),"","",MODE_VIEW);
	$xt->assign("Q1_SNoticemainsponsor_value",$value);
	$xt->assign("Q1_SNoticemainsponsor_fieldblock",true);
////////////////////////////////////////////
//	Q1_SNoticeteam - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_SNoticeteam", ""),"","",MODE_VIEW);
	$xt->assign("Q1_SNoticeteam_value",$value);
	$xt->assign("Q1_SNoticeteam_fieldblock",true);
////////////////////////////////////////////
//	Q1_SNoticeother - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_SNoticeother", ""),"","",MODE_VIEW);
	$xt->assign("Q1_SNoticeother_value",$value);
	$xt->assign("Q1_SNoticeother_fieldblock",true);
////////////////////////////////////////////
//	Q1_Shavechangedview - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_Shavechangedview", ""),"","",MODE_VIEW);
	$xt->assign("Q1_Shavechangedview_value",$value);
	$xt->assign("Q1_Shavechangedview_fieldblock",true);
////////////////////////////////////////////
//	Q1_Sbuyproducts - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_Sbuyproducts", ""),"","",MODE_VIEW);
	$xt->assign("Q1_Sbuyproducts_value",$value);
	$xt->assign("Q1_Sbuyproducts_fieldblock",true);
////////////////////////////////////////////
//	Q1_Snameothers - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_Snameothers", ""),"","",MODE_VIEW);
	$xt->assign("Q1_Snameothers_value",$value);
	$xt->assign("Q1_Snameothers_fieldblock",true);
////////////////////////////////////////////
//	Q1_Comments - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_Comments", ""),"","",MODE_VIEW);
	$xt->assign("Q1_Comments_value",$value);
	$xt->assign("Q1_Comments_fieldblock",true);
////////////////////////////////////////////
//	Q1_p_id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Q1_p_id", ""),"","",MODE_VIEW);
	$xt->assign("Q1_p_id_value",$value);
	$xt->assign("Q1_p_id_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='Q1_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "Q1_view.htm";
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
