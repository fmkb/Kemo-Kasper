<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/player_variables.php");
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
	$keys["p_id"]=postvalue("editid1");

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



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"p_id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["p_id"]));

////////////////////////////////////////////
//	p_active - 
	$value="";
		$value=DisplayLookupWizard("p_active",$data["p_active"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("p_active_value",$value);
	$xt->assign("p_active_fieldblock",true);
////////////////////////////////////////////
//	p_s_id - 
	$value="";
		$value=DisplayLookupWizard("p_s_id",$data["p_s_id"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("p_s_id_value",$value);
	$xt->assign("p_s_id_fieldblock",true);
////////////////////////////////////////////
//	p_first - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_first", ""),"","",MODE_VIEW);
	$xt->assign("p_first_value",$value);
	$xt->assign("p_first_fieldblock",true);
////////////////////////////////////////////
//	p_name - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_name", ""),"","",MODE_VIEW);
	$xt->assign("p_name_value",$value);
	$xt->assign("p_name_fieldblock",true);
////////////////////////////////////////////
//	p_adr - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_adr", ""),"","",MODE_VIEW);
	$xt->assign("p_adr_value",$value);
	$xt->assign("p_adr_fieldblock",true);
////////////////////////////////////////////
//	p_zip - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_zip", ""),"","",MODE_VIEW);
	$xt->assign("p_zip_value",$value);
	$xt->assign("p_zip_fieldblock",true);
////////////////////////////////////////////
//	p_country - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_country", ""),"","",MODE_VIEW);
	$xt->assign("p_country_value",$value);
	$xt->assign("p_country_fieldblock",true);
////////////////////////////////////////////
//	p_mail - Email Hyperlink
	$value="";
		$value = GetData($data,"p_mail", "Email Hyperlink");
	$xt->assign("p_mail_value",$value);
	$xt->assign("p_mail_fieldblock",true);
////////////////////////////////////////////
//	p_mobile - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_mobile", ""),"","",MODE_VIEW);
	$xt->assign("p_mobile_value",$value);
	$xt->assign("p_mobile_fieldblock",true);
////////////////////////////////////////////
//	p_newsaccept - Checkbox
	$value="";
		$value = GetData($data,"p_newsaccept", "Checkbox");
	$xt->assign("p_newsaccept_value",$value);
	$xt->assign("p_newsaccept_fieldblock",true);
////////////////////////////////////////////
//	p_score - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_score", ""),"","",MODE_VIEW);
	$xt->assign("p_score_value",$value);
	$xt->assign("p_score_fieldblock",true);
////////////////////////////////////////////
//	p_scorehigh - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_scorehigh", ""),"","",MODE_VIEW);
	$xt->assign("p_scorehigh_value",$value);
	$xt->assign("p_scorehigh_fieldblock",true);
////////////////////////////////////////////
//	p_games - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_games", ""),"","",MODE_VIEW);
	$xt->assign("p_games_value",$value);
	$xt->assign("p_games_fieldblock",true);
////////////////////////////////////////////
//	p_win - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_win", ""),"","",MODE_VIEW);
	$xt->assign("p_win_value",$value);
	$xt->assign("p_win_fieldblock",true);
////////////////////////////////////////////
//	p_mk - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_mk", ""),"","",MODE_VIEW);
	$xt->assign("p_mk_value",$value);
	$xt->assign("p_mk_fieldblock",true);
////////////////////////////////////////////
//	p_born - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_born", ""),"","",MODE_VIEW);
	$xt->assign("p_born_value",$value);
	$xt->assign("p_born_fieldblock",true);
////////////////////////////////////////////
//	p_user - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_user", ""),"","",MODE_VIEW);
	$xt->assign("p_user_value",$value);
	$xt->assign("p_user_fieldblock",true);
////////////////////////////////////////////
//	p_pwd - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_pwd", ""),"","",MODE_VIEW);
	$xt->assign("p_pwd_value",$value);
	$xt->assign("p_pwd_fieldblock",true);
////////////////////////////////////////////
//	p_ip - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_ip", ""),"","",MODE_VIEW);
	$xt->assign("p_ip_value",$value);
	$xt->assign("p_ip_fieldblock",true);
////////////////////////////////////////////
//	p_datetime - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"p_datetime", "Short Date"),"","",MODE_VIEW);
	$xt->assign("p_datetime_value",$value);
	$xt->assign("p_datetime_fieldblock",true);
////////////////////////////////////////////
//	p_tscore - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_tscore", ""),"","",MODE_VIEW);
	$xt->assign("p_tscore_value",$value);
	$xt->assign("p_tscore_fieldblock",true);
////////////////////////////////////////////
//	p_tkills - 
	$value="";
		$value = ProcessLargeText(GetData($data,"p_tkills", ""),"","",MODE_VIEW);
	$xt->assign("p_tkills_value",$value);
	$xt->assign("p_tkills_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='player_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "player_view.htm";
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
