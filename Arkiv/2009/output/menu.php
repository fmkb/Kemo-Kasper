<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");

if(!@$_SESSION["UserID"])
{
	header("Location: login.php");
	return;
}

include("include/languages.php");

include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessMenu"))
	BeforeProcessMenu($conn);

$xt->assign("body",true);

$xt->assign("username",$_SESSION["UserID"]);
$xt->assign("changepwd_link",$_SESSION["AccessLevel"] != ACCESS_LEVEL_GUEST);
$xt->assign("changepwdlink_attrs","onclick=\"window.location.href='changepwd.php';return false;\"");
$xt->assign("logoutlink_attrs","onclick=\"window.location.href='login.php?a=logout';\"");

$xt->assign("loggedas_block",true);
$xt->assign("logout_link",true);

$allow_player=true;
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
}
$allow_Dialog=true;
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
}
$allow_sponsor=true;
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
}
$allow_teams=true;
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
}
$allow_top=true;
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
}
$allow_news=true;
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
}
$allow_raffle=true;
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
}
$allow_mails=true;
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
}
$allow_xcountries=true;
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
}
$allow_MQ_Chart=true;
if($allow_MQ_Chart)
{
	$createmenu=true;
	$xt->assign("MQ_Chart_tablelink",true);
	$page="";
		$page="chart";
	$xt->assign("MQ_Chart_tablelink_attrs","href=\"MQ_Chart_".$page.".php\"");
}
$allow_Accept_news_Chart=true;
if($allow_Accept_news_Chart)
{
	$createmenu=true;
	$xt->assign("Accept_news_Chart_tablelink",true);
	$page="";
		$page="chart";
	$xt->assign("Accept_news_Chart_tablelink_attrs","href=\"Accept_news_Chart_".$page.".php\"");
}


$templatefile="menu.htm";
if(function_exists("BeforeShowMenu"))
	BeforeShowMenu($xt,$templatefile);

$xt->display($templatefile);
?>