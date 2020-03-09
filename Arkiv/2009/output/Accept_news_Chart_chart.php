<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Accept_news_Chart_variables.php");
include("include/languages.php");

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	echo "<p>".mlang_message("NO_PERMISSIONS")." <a href=\"login.php\">".mlang_message("BACK_TO_LOGIN")."</a></p>";
	return;
}

$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessChart"))
	BeforeProcessChart($conn);


//	process request data, fill session variables

if(!count($_POST) && !count($_GET))
{
	$sess_unset = array();
	foreach($_SESSION as $key=>$value)
		if(substr($key,0,strlen($strTableName)+1)==$strTableName."_" &&
			strpos(substr($key,strlen($strTableName)+1),"_")===false)
			$sess_unset[] = $key;
	foreach($sess_unset as $key)
		unset($_SESSION[$key]);
}

if(@$_REQUEST["a"]=="advsearch")
{
	$_SESSION[$strTableName."_asearchnot"]=array();
	$_SESSION[$strTableName."_asearchopt"]=array();
	$_SESSION[$strTableName."_asearchfor"]=array();
	$_SESSION[$strTableName."_asearchfor2"]=array();
	$tosearch=0;
	$asearchfield = postvalue("asearchfield");
	$_SESSION[$strTableName."_asearchtype"] = postvalue("type");
	if(!$_SESSION[$strTableName."_asearchtype"])
		$_SESSION[$strTableName."_asearchtype"]="and";
	foreach($asearchfield as $field)
	{
		$gfield=GoodFieldName($field);
		$asopt=postvalue("asearchopt_".$gfield);
		$value1=postvalue("value_".$gfield);
		$type=postvalue("type_".$gfield);
		$value2=postvalue("value1_".$gfield);
		$not=postvalue("not_".$gfield);
		if($value1 || $asopt=='Empty')
		{
			$tosearch=1;
			$_SESSION[$strTableName."_asearchopt"][$field]=$asopt;
			if(!is_array($value1))
				$_SESSION[$strTableName."_asearchfor"][$field]=$value1;
			else
				$_SESSION[$strTableName."_asearchfor"][$field]=combinevalues($value1);
			$_SESSION[$strTableName."_asearchfortype"][$field]=$type;
			if($value2)
				$_SESSION[$strTableName."_asearchfor2"][$field]=$value2;
			$_SESSION[$strTableName."_asearchnot"][$field]=($not=="on");
		}
	}
	if($tosearch)
		$_SESSION[$strTableName."_search"]=2;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}


include('libs/xtempl.php');
$xt = new Xtempl();


//	table selector
$allow_Dialog=true;
$allow_mails=true;
$allow_news=true;
$allow_player=true;
$allow_raffle=true;
$allow_sponsor=true;
$allow_top=true;
$allow_teams=true;
$allow_MQ_Chart=true;
$allow_Accept_news_Chart=true;
$allow_xcountries=true;

$createmenu=false;
if($allow_player)
{
	$createmenu=true;
	$xt->assign("player_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("player");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("player_tablelink_attrs","href=\"player_".$page.".php\"");
	$xt->assign("player_optionattrs","value=\"player_".$page.".php\"");
}
if($allow_Dialog)
{
	$createmenu=true;
	$xt->assign("Dialog_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("Dialog");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("Dialog_tablelink_attrs","href=\"Dialog_".$page.".php\"");
	$xt->assign("Dialog_optionattrs","value=\"Dialog_".$page.".php\"");
}
if($allow_sponsor)
{
	$createmenu=true;
	$xt->assign("sponsor_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("sponsor");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("sponsor_tablelink_attrs","href=\"sponsor_".$page.".php\"");
	$xt->assign("sponsor_optionattrs","value=\"sponsor_".$page.".php\"");
}
if($allow_teams)
{
	$createmenu=true;
	$xt->assign("teams_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("teams");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("teams_tablelink_attrs","href=\"teams_".$page.".php\"");
	$xt->assign("teams_optionattrs","value=\"teams_".$page.".php\"");
}
if($allow_top)
{
	$createmenu=true;
	$xt->assign("top_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("top");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("top_tablelink_attrs","href=\"top_".$page.".php\"");
	$xt->assign("top_optionattrs","value=\"top_".$page.".php\"");
}
if($allow_news)
{
	$createmenu=true;
	$xt->assign("news_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("news");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("news_tablelink_attrs","href=\"news_".$page.".php\"");
	$xt->assign("news_optionattrs","value=\"news_".$page.".php\"");
}
if($allow_raffle)
{
	$createmenu=true;
	$xt->assign("raffle_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("raffle");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("raffle_tablelink_attrs","href=\"raffle_".$page.".php\"");
	$xt->assign("raffle_optionattrs","value=\"raffle_".$page.".php\"");
}
if($allow_mails)
{
	$createmenu=true;
	$xt->assign("mails_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("mails");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("mails_tablelink_attrs","href=\"mails_".$page.".php\"");
	$xt->assign("mails_optionattrs","value=\"mails_".$page.".php\"");
}
if($allow_xcountries)
{
	$createmenu=true;
	$xt->assign("xcountries_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("xcountries");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("xcountries_tablelink_attrs","href=\"xcountries_".$page.".php\"");
	$xt->assign("xcountries_optionattrs","value=\"xcountries_".$page.".php\"");
}
if($allow_MQ_Chart)
{
	$createmenu=true;
	$xt->assign("MQ_Chart_tablelink",true);
	$page="";
		$page="chart";
	$xt->assign("MQ_Chart_tablelink_attrs","href=\"MQ_Chart_".$page.".php\"");
	$xt->assign("MQ_Chart_optionattrs","value=\"MQ_Chart_".$page.".php\"");
}
if($allow_Accept_news_Chart)
{
	$createmenu=true;
	$xt->assign("Accept_news_Chart_tablelink",true);
	$page="";
		$page="chart";
	$xt->assign("Accept_news_Chart_tablelink_attrs","href=\"Accept_news_Chart_".$page.".php\"");
	$xt->assign("Accept_news_Chart_optionattrs","value=\"Accept_news_Chart_".$page.".php\"");
}
if($createmenu)
	$xt->assign("menu_block",true);
	
$body=array();
$body["begin"]=  '<script type="text/javascript" language="javascript" src="libs/js/AnyChart.js"></script>
        <script type="text/javascript" src="include/jquery-1.2.2.pack.js"></script>';

$xt->assignbyref("body",$body);



$xt->assign("login_block",true);
$xt->assign("username",htmlspecialchars($_SESSION["UserID"]));
$xt->assign("logoutlink_attrs","onclick=\"window.location.href='login.php?a=logout';\"");

$xt->assign("chart_block",true);
$xt->assign("toplinks_block",true);
$xt->assign("asearch_link",true);
$xt->assign("asearchlink_attrs","onclick=\"window.location.href='Accept_news_Chart_search.php';return false;\"");
$xt->assign("search_records_block",true);
$xt->assign("recordcontrols_block",true);

$templatefile = "Accept_news_Chart_chart.htm";
if(function_exists("BeforeShowChart"))
	BeforeShowChart($xt,$templatefile);

$xt->display($templatefile);



?>