<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/news_variables.php");
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
	$keys["n_id"]=postvalue("editid1");

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



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"n_id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["n_id"]));

////////////////////////////////////////////
//	n_id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"n_id", ""),"","",MODE_VIEW);
	$xt->assign("n_id_value",$value);
	$xt->assign("n_id_fieldblock",true);
////////////////////////////////////////////
//	n_active - 
	$value="";
		$value=DisplayLookupWizard("n_active",$data["n_active"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("n_active_value",$value);
	$xt->assign("n_active_fieldblock",true);
////////////////////////////////////////////
//	n_start - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"n_start", "Short Date"),"","",MODE_VIEW);
	$xt->assign("n_start_value",$value);
	$xt->assign("n_start_fieldblock",true);
////////////////////////////////////////////
//	n_end - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"n_end", "Short Date"),"","",MODE_VIEW);
	$xt->assign("n_end_value",$value);
	$xt->assign("n_end_fieldblock",true);
////////////////////////////////////////////
//	n_date - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"n_date", "Short Date"),"","",MODE_VIEW);
	$xt->assign("n_date_value",$value);
	$xt->assign("n_date_fieldblock",true);
////////////////////////////////////////////
//	n_head - 
	$value="";
		$value = ProcessLargeText(GetData($data,"n_head", ""),"","",MODE_VIEW);
	$xt->assign("n_head_value",$value);
	$xt->assign("n_head_fieldblock",true);
////////////////////////////////////////////
//	n_teaser - 
	$value="";
		$value = ProcessLargeText(GetData($data,"n_teaser", ""),"","",MODE_VIEW);
	$xt->assign("n_teaser_value",$value);
	$xt->assign("n_teaser_fieldblock",true);
////////////////////////////////////////////
//	n_text - 
	$value="";
		$value = ProcessLargeText(GetData($data,"n_text", ""),"","",MODE_VIEW);
	$xt->assign("n_text_value",$value);
	$xt->assign("n_text_fieldblock",true);
////////////////////////////////////////////
//	n_file - File-based Image
	$value="";
		if(CheckImageExtension($data["n_file"])) 
	{
				 	// show thumbnail
		$thumbname="th_".$data["n_file"];
		if(substr("/admin/pictures/news/",0,7)!="http://" && !file_exists(GetUploadFolder("n_file").$thumbname))
			$thumbname=$data["n_file"];
		$value="<a";
					$value .= " target=_blank";
		$value.=" href=\"".htmlspecialchars(AddLinkPrefix("n_file",$data["n_file"]))."\">";
		$value.="<img";
		if($thumbname==$data["n_file"])
		{
								}
		$value.=" border=0";
		$value.=" src=\"".htmlspecialchars(AddLinkPrefix("n_file",$thumbname))."\"></a>";
	}
	$xt->assign("n_file_value",$value);
	$xt->assign("n_file_fieldblock",true);
////////////////////////////////////////////
//	n_type - 
	$value="";
		$value = ProcessLargeText(GetData($data,"n_type", ""),"","",MODE_VIEW);
	$xt->assign("n_type_value",$value);
	$xt->assign("n_type_fieldblock",true);
////////////////////////////////////////////
//	n_country - 
	$value="";
		$value=DisplayLookupWizard("n_country",$data["n_country"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("n_country_value",$value);
	$xt->assign("n_country_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='news_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "news_view.htm";
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
