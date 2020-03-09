<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/sponsor_variables.php");
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
	$keys["s_id"]=postvalue("editid1");

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



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"s_id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["s_id"]));

////////////////////////////////////////////
//	s_active - 
	$value="";
		$value=DisplayLookupWizard("s_active",$data["s_active"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("s_active_value",$value);
	$xt->assign("s_active_fieldblock",true);
////////////////////////////////////////////
//	s_name - 
	$value="";
		$value = ProcessLargeText(GetData($data,"s_name", ""),"","",MODE_VIEW);
	$xt->assign("s_name_value",$value);
	$xt->assign("s_name_fieldblock",true);
////////////////////////////////////////////
//	s_contact - 
	$value="";
		$value = ProcessLargeText(GetData($data,"s_contact", ""),"","",MODE_VIEW);
	$xt->assign("s_contact_value",$value);
	$xt->assign("s_contact_fieldblock",true);
////////////////////////////////////////////
//	s_adr - 
	$value="";
		$value = ProcessLargeText(GetData($data,"s_adr", ""),"","",MODE_VIEW);
	$xt->assign("s_adr_value",$value);
	$xt->assign("s_adr_fieldblock",true);
////////////////////////////////////////////
//	s_zip - 
	$value="";
		$value = ProcessLargeText(GetData($data,"s_zip", ""),"","",MODE_VIEW);
	$xt->assign("s_zip_value",$value);
	$xt->assign("s_zip_fieldblock",true);
////////////////////////////////////////////
//	s_country - 
	$value="";
		$value=DisplayLookupWizard("s_country",$data["s_country"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("s_country_value",$value);
	$xt->assign("s_country_fieldblock",true);
////////////////////////////////////////////
//	s_phone1 - 
	$value="";
		$value = ProcessLargeText(GetData($data,"s_phone1", ""),"","",MODE_VIEW);
	$xt->assign("s_phone1_value",$value);
	$xt->assign("s_phone1_fieldblock",true);
////////////////////////////////////////////
//	s_phone2 - 
	$value="";
		$value = ProcessLargeText(GetData($data,"s_phone2", ""),"","",MODE_VIEW);
	$xt->assign("s_phone2_value",$value);
	$xt->assign("s_phone2_fieldblock",true);
////////////////////////////////////////////
//	s_total - Number
	$value="";
		$value = ProcessLargeText(GetData($data,"s_total", "Number"),"","",MODE_VIEW);
	$xt->assign("s_total_value",$value);
	$xt->assign("s_total_fieldblock",true);
////////////////////////////////////////////
//	s_paid - Number
	$value="";
		$value = ProcessLargeText(GetData($data,"s_paid", "Number"),"","",MODE_VIEW);
	$xt->assign("s_paid_value",$value);
	$xt->assign("s_paid_fieldblock",true);
////////////////////////////////////////////
//	s_logo - File-based Image
	$value="";
		if(CheckImageExtension($data["s_logo"])) 
	{
			$value="<img";
						$value.=" border=0";
		$value.=" src=\"".htmlspecialchars(AddLinkPrefix("s_logo",$data["s_logo"]))."\">";
	}
	$xt->assign("s_logo_value",$value);
	$xt->assign("s_logo_fieldblock",true);
////////////////////////////////////////////
//	s_banner - File-based Image
	$value="";
		if(CheckImageExtension($data["s_banner"])) 
	{
			$value="<img";
						$value.=" border=0";
		$value.=" src=\"".htmlspecialchars(AddLinkPrefix("s_banner",$data["s_banner"]))."\">";
	}
	$xt->assign("s_banner_value",$value);
	$xt->assign("s_banner_fieldblock",true);
////////////////////////////////////////////
//	s_www - Hyperlink
	$value="";
		$value = GetData($data,"s_www", "Hyperlink");
	$xt->assign("s_www_value",$value);
	$xt->assign("s_www_fieldblock",true);
////////////////////////////////////////////
//	s_mail - Email Hyperlink
	$value="";
		$value = GetData($data,"s_mail", "Email Hyperlink");
	$xt->assign("s_mail_value",$value);
	$xt->assign("s_mail_fieldblock",true);
////////////////////////////////////////////
//	s_cmt - 
	$value="";
		$value = ProcessLargeText(GetData($data,"s_cmt", ""),"","",MODE_VIEW);
	$xt->assign("s_cmt_value",$value);
	$xt->assign("s_cmt_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='sponsor_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "sponsor_view.htm";
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
