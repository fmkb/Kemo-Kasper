<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/player_variables.php");
	include("include/Dialog_settings.php");
	include("include/raffle_settings.php");
	include("include/top_settings.php");
include("include/languages.php");

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{
	echo "<p>".mlang_message("NO_PERMISSIONS")." <a href=\"login.php\">".mlang_message("BACK_TO_LOGIN")."</a></p>";
	exit();
}


include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();


//	process reqest data, fill session variables

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

//	Before Process event
if(function_exists("BeforeProcessList"))
	BeforeProcessList($conn);

if(@$_REQUEST["a"]=="showall")
	$_SESSION[$strTableName."_search"]=0;
else if(@$_REQUEST["a"]=="search")
{
	$_SESSION[$strTableName."_searchfield"]=postvalue("SearchField");
	$_SESSION[$strTableName."_searchoption"]=postvalue("SearchOption");
	$_SESSION[$strTableName."_searchfor"]=postvalue("SearchFor");
	if(postvalue("SearchFor")!="" || postvalue("SearchOption")=='Empty')
		$_SESSION[$strTableName."_search"]=1;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}
else if(@$_REQUEST["a"]=="advsearch")
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
//	reset search and page number
	$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}
else
	$mastertable=$_SESSION[$strTableName."_mastertable"];

if(@$_REQUEST["language"])
	$_SESSION["language"]=@$_REQUEST["language"];

$var=GoodFieldName(mlang_getcurrentlang())."_langattrs";
$xt->assign($var,"selected");
$xt->assign("langselector_attrs","name=lang onchange=\"javascript: window.location='player_list.php?language='+this.options[this.selectedIndex].value\"");
$xt->assign("languages_block",true);

if(@$_REQUEST["orderby"])
	$_SESSION[$strTableName."_orderby"]=@$_REQUEST["orderby"];

if(@$_REQUEST["pagesize"])
{
	$_SESSION[$strTableName."_pagesize"]=@$_REQUEST["pagesize"];
	$_SESSION[$strTableName."_pagenumber"]=1;
}

if(@$_REQUEST["goto"])
	$_SESSION[$strTableName."_pagenumber"]=@$_REQUEST["goto"];


//	process reqest data - end

$includes="";



$includes.="<script type=\"text/javascript\" src=\"include/jquery.js\"></script>\r\n";
$includes.="<script type=\"text/javascript\" src=\"include/ajaxsuggest.js\"></script>\r\n";

$includes.="<script type=\"text/javascript\" src=\"include/jsfunctions.js\"></script>\n".
"<script type=\"text/javascript\">".
"\nvar bSelected=false;".
"\nvar TEXT_FIRST = \"".mlang_message("FIRST")."\";".
"\nvar TEXT_PREVIOUS = \"".mlang_message("PREVIOUS")."\";".
"\nvar TEXT_NEXT = \"".mlang_message("NEXT")."\";".
"\nvar TEXT_LAST = \"".mlang_message("LAST")."\";".
"\nvar TEXT_PLEASE_SELECT='".jsreplace(mlang_message("PLEASE_SELECT"))."';".
"\nvar TEXT_SAVE='".jsreplace(mlang_message("SAVE"))."';".
"\nvar TEXT_CANCEL='".jsreplace(mlang_message("CANCEL"))."';".
"\nvar TEXT_INLINE_ERROR='".jsreplace(mlang_message("INLINE_ERROR"))."';".
"\nvar TEXT_PREVIEW='".jsreplace(mlang_message("PREVIEW"))."';".
"\nvar TEXT_HIDE='".jsreplace(mlang_message("HIDE"))."';".
"\nvar TEXT_LOADING='".jsreplace(mlang_message("LOADING"))."';".
"\nvar locale_dateformat = ".$locale_info["LOCALE_IDATE"].";".
"\nvar locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";".
"\nvar bLoading=false;\r\n";

	$includes.="var INLINE_EDIT_TABLE='player_edit.php';\r\n";
	$includes.="var INLINE_ADD_TABLE='player_add.php';\r\n";
	$includes.="var INLINE_VIEW_TABLE='player_view.php';\r\n";
	$includes.="var SUGGEST_TABLE='player_searchsuggest.php';\r\n";
	$includes.="var MASTER_PREVIEW_TABLE='player_masterpreview.php';\r\n";
$includes.="\n</script>\n";
$includes.="<div id=\"search_suggest\"></div>";
$includes.="<div id=\"master_details\" onmouseover=\"RollDetailsLink.showPopup();\" onmouseout=\"RollDetailsLink.hidePopup();\"> </div>";

$body = array();
$body["begin"]=$includes.'
<form name="frmSearch" method="GET" action="player_list.php">
<input type="Hidden" name="a" value="search">
<input type="Hidden" name="value" value="1">
<input type="Hidden" name="SearchFor" value="">
<input type="Hidden" name="SearchOption" value="">
<input type="Hidden" name="SearchField" value="">
</form>';


//	process session variables
//	order by
$strOrderBy="";
$order_ind=-1;


$recno=1;
$numrows=0;
$rowid=0;

$xt->assign("p_active_orderlinkattrs","href=\"player_list.php?orderby=ap_active\"");
$xt->assign("p_active_fieldheader",true);
$xt->assign("p_s_id_orderlinkattrs","href=\"player_list.php?orderby=ap_s_id\"");
$xt->assign("p_s_id_fieldheader",true);
$xt->assign("p_first_orderlinkattrs","href=\"player_list.php?orderby=ap_first\"");
$xt->assign("p_first_fieldheader",true);
$xt->assign("p_name_orderlinkattrs","href=\"player_list.php?orderby=ap_name\"");
$xt->assign("p_name_fieldheader",true);
$xt->assign("p_zip_orderlinkattrs","href=\"player_list.php?orderby=ap_zip\"");
$xt->assign("p_zip_fieldheader",true);
$xt->assign("p_mail_orderlinkattrs","href=\"player_list.php?orderby=ap_mail\"");
$xt->assign("p_mail_fieldheader",true);
$xt->assign("p_newsaccept_orderlinkattrs","href=\"player_list.php?orderby=ap_newsaccept\"");
$xt->assign("p_newsaccept_fieldheader",true);
$xt->assign("p_win_orderlinkattrs","href=\"player_list.php?orderby=ap_win\"");
$xt->assign("p_win_fieldheader",true);
$xt->assign("p_mk_orderlinkattrs","href=\"player_list.php?orderby=ap_mk\"");
$xt->assign("p_mk_fieldheader",true);
$xt->assign("p_born_orderlinkattrs","href=\"player_list.php?orderby=ap_born\"");
$xt->assign("p_born_fieldheader",true);
$xt->assign("p_ip_orderlinkattrs","href=\"player_list.php?orderby=ap_ip\"");
$xt->assign("p_ip_fieldheader",true);
$xt->assign("p_datetime_orderlinkattrs","href=\"player_list.php?orderby=ap_datetime\"");
$xt->assign("p_datetime_fieldheader",true);
$xt->assign("p_tscore_orderlinkattrs","href=\"player_list.php?orderby=ap_tscore\"");
$xt->assign("p_tscore_fieldheader",true);
$xt->assign("p_country_orderlinkattrs","href=\"player_list.php?orderby=ap_country\"");
$xt->assign("p_country_fieldheader",true);
$xt->assign("p_mobile_orderlinkattrs","href=\"player_list.php?orderby=ap_mobile\"");
$xt->assign("p_mobile_fieldheader",true);

if(@$_SESSION[$strTableName."_orderby"])
{
	$order_field=substr($_SESSION[$strTableName."_orderby"],1);
	$order_dir=substr($_SESSION[$strTableName."_orderby"],0,1);
	$order_ind=GetFieldIndex($order_field);

	$dir="a";
	$img="down";

	if($order_dir=="a")
	{
		$dir="d";
		$img="up";
	}

	$xt->assign(GoodFieldName($order_field)."_orderimage","<img src=\"images/".$img.".gif\" border=0>");
	$xt->assign(GoodFieldName($order_field)."_orderlinkattrs","href=\"player_list.php?orderby=".$dir.GoodFieldName($order_field)."\"");

	if($order_ind)
	{
		if($order_dir=="a")
			$strOrderBy="order by ".($order_ind)." asc";
		else 
			$strOrderBy="order by ".($order_ind)." desc";
	}
}
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;

//	page number
$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;

$xt->assign("rpp".$PageSize."_selected","selected");

// delete record
$selected_recs=array();
if (@$_REQUEST["mdelete"])
{
	foreach(@$_REQUEST["mdelete"] as $ind)
	{
		$keys=array();
		$keys["p_id"]=refine($_REQUEST["mdelete1"][$ind-1]);
		$selected_recs[]=$keys;
	}
}
elseif(@$_REQUEST["selection"])
{
	foreach(@$_REQUEST["selection"] as $keyblock)
	{
		$arr=split("&",refine($keyblock));
		if(count($arr)<1)
			continue;
		$keys=array();
		$keys["p_id"]=urldecode(@$arr[0]);
		$selected_recs[]=$keys;
	}
}

$records_deleted=0;
foreach($selected_recs as $keys)
{
	$where = KeyWhere($keys);

	$strSQL="delete from ".AddTableWrappers($strOriginalTableName)." where ".$where;
	$retval=true;
	if(function_exists("AfterDelete") || function_exists("BeforeDelete"))
	{
		$deletedrs = db_query(gSQLWhere($where),$conn);
		$deleted_values = db_fetch_array($deletedrs);
	}
	if(function_exists("BeforeDelete"))
		$retval = BeforeDelete($where,$deleted_values);
	if($retval && @$_REQUEST["a"]=="delete")
	{
		$records_deleted++;
				// delete associated uploaded files if any
		DeleteUploadedFiles($where);
		LogInfo($strSQL);
		db_exec($strSQL,$conn);
		if(function_exists("AfterDelete"))
			AfterDelete($where,$deleted_values);
	}
}

if(count($selected_recs))
{
	if(function_exists("AfterMassDelete"))
		AfterMassDelete($records_deleted);
}

//deal with permissions

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


$strPerm = GetUserPermissions();
$allow_add=(strpos($strPerm,"A")!==false);
$allow_delete=(strpos($strPerm,"D")!==false);
$allow_edit=(strpos($strPerm,"E")!==false);
$allow_search=(strpos($strPerm,"S")!==false);
$allow_export=(strpos($strPerm,"P")!==false);
$allow_import=(strpos($strPerm,"I")!==false);


//	make sql "select" string

//$strSQL = $gstrSQL;
$strWhereClause="";

//	add search params

if(@$_SESSION[$strTableName."_search"]==1)
//	 regular search
{  
	$strSearchFor=trim($_SESSION[$strTableName."_searchfor"]);
	$strSearchOption=trim($_SESSION[$strTableName."_searchoption"]);
	if(@$_SESSION[$strTableName."_searchfield"])
	{
		$strSearchField = $_SESSION[$strTableName."_searchfield"];
		if($where = StrWhere($strSearchField, $strSearchFor, $strSearchOption, ""))
			$strWhereClause = whereAdd($strWhereClause,$where);
		else
			$strWhereClause = whereAdd($strWhereClause,"1=0");
	}
	else
	{
		$strWhere = "1=0";
		if($where=StrWhere("p_active", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_s_id", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_first", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_name", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_adr", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_zip", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_mail", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_newsaccept", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_score", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_scorehigh", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_games", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_win", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_mk", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_born", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_user", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_pwd", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_ip", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_datetime", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_tscore", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_tkills", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_country", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("p_mobile", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		$strWhereClause = whereAdd($strWhereClause,$strWhere);
	}
}
else if(@$_SESSION[$strTableName."_search"]==2)
//	 advanced search
{
	$sWhere="";
	foreach(@$_SESSION[$strTableName."_asearchfor"] as $f => $sfor)
		{
			$strSearchFor=trim($sfor);
			$strSearchFor2="";
			$type=@$_SESSION[$strTableName."_asearchfortype"][$f];
			if(array_key_exists($f,@$_SESSION[$strTableName."_asearchfor2"]))
				$strSearchFor2=trim(@$_SESSION[$strTableName."_asearchfor2"][$f]);
			if($strSearchFor!="" || true)
			{
				if (!$sWhere) 
				{
					if($_SESSION[$strTableName."_asearchtype"]=="and")
						$sWhere="1=1";
					else
						$sWhere="1=0";
				}
				$strSearchOption=trim($_SESSION[$strTableName."_asearchopt"][$f]);
				if($where=StrWhereAdv($f, $strSearchFor, $strSearchOption, $strSearchFor2,$type))
				{
					if($_SESSION[$strTableName."_asearchnot"][$f])
						$where="not (".$where.")";
					if($_SESSION[$strTableName."_asearchtype"]=="and")
	   					$sWhere .= " and ".$where;
					else
	   					$sWhere .= " or ".$where;
				}
			}
		}
		$strWhereClause = whereAdd($strWhereClause,$sWhere);
	}




if($mastertable=="teams")
{
	$where ="";
		$where.= GetFullFieldName("p_s_id")."=".make_db_value("p_s_id",$_SESSION[$strTableName."_masterkey1"]);
	$strWhereClause = whereAdd($strWhereClause,$where);
}

$strSQL = gSQLWhere($strWhereClause);

//	order by
$strSQL.=" ".trim($strOrderBy);

//	save SQL for use in "Export" and "Printer-friendly" pages

$_SESSION[$strTableName."_sql"] = $strSQL;
$_SESSION[$strTableName."_where"] = $strWhereClause;
$_SESSION[$strTableName."_order"] = $strOrderBy;

$rowsfound=false;

//	select and display records
if($allow_search)
{
	//	add count of child records to SQL
	if($bSubqueriesSupported)
	{
	$sqlWhere="";
	$masterkeys=array();
	$masterkeys[]="`p_id`";
	$detailkeys=array();
	$detailkeys[]="`d_from_p_id`";
	$masterwhere ="";
	foreach($masterkeys as $idx=>$val)
	{
		if($masterwhere)
			$masterwhere.=" and ";
			$strOriginalDetailsTable="Dialog";
		$masterwhere.=AddTableWrappers($strOriginalDetailsTable).".".$detailkeys[$idx]."=".AddTableWrappers($strOriginalTableName).".".$masterkeys[$idx];
	}

	$sqlWhere = whereAdd($sqlWhere,$masterwhere);
	$countsql = "select count(*) from ".AddTableWrappers($strOriginalDetailsTable)." where ".$sqlWhere;
		$gsqlHead.=",(".$countsql.") as Dialog_cnt ";
	$sqlWhere="";
	$masterkeys=array();
	$masterkeys[]="`p_id`";
	$detailkeys=array();
	$detailkeys[]="`r_p_id`";
	$masterwhere ="";
	foreach($masterkeys as $idx=>$val)
	{
		if($masterwhere)
			$masterwhere.=" and ";
			$strOriginalDetailsTable="raffle";
		$masterwhere.=AddTableWrappers($strOriginalDetailsTable).".".$detailkeys[$idx]."=".AddTableWrappers($strOriginalTableName).".".$masterkeys[$idx];
	}

	$sqlWhere = whereAdd($sqlWhere,$masterwhere);
	$countsql = "select count(*) from ".AddTableWrappers($strOriginalDetailsTable)." where ".$sqlWhere;
		$gsqlHead.=",(".$countsql.") as raffle_cnt ";
	$sqlWhere="";
	$masterkeys=array();
	$masterkeys[]="`p_id`";
	$detailkeys=array();
	$detailkeys[]="`t_p_id`";
	$masterwhere ="";
	foreach($masterkeys as $idx=>$val)
	{
		if($masterwhere)
			$masterwhere.=" and ";
			$strOriginalDetailsTable="top";
		$masterwhere.=AddTableWrappers($strOriginalDetailsTable).".".$detailkeys[$idx]."=".AddTableWrappers($strOriginalTableName).".".$masterkeys[$idx];
	}

	$sqlWhere = whereAdd($sqlWhere,$masterwhere);
	$countsql = "select count(*) from ".AddTableWrappers($strOriginalDetailsTable)." where ".$sqlWhere;
		$gsqlHead.=",(".$countsql.") as top_cnt ";
	}
	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryList"))
		BeforeQueryList($strSQL,$strWhereClause,$strOrderBy);
//	Rebuild SQL if needed
	if($strSQL!=$strSQLbak)
	{
//	changed $strSQL - old style	
		$numrows=GetRowCount($strSQL);
	}
	else
	{
		$strSQL = gSQLWhere($strWhereClause);
		$strSQL.=" ".trim($strOrderBy);
		$numrows=gSQLRowCount($strWhereClause,0);
	}
	LogInfo($strSQL);

//	 Pagination:
	if(!$numrows)
	{
		$rowsfound=false;
		$message=mlang_message("NO_RECORDS");
		$message_block=array();
		$message_block["begin"]="<span name=\"notfound_message\">";
		$message_block["end"]="</span>";
		$xt->assignbyref("message_block",$message_block);
		$xt->assign("message",$message);
	}
	else
	{
		$rowsfound=true;
		$maxRecords = $numrows;
		$xt->assign("records_found",$numrows);
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
		$xt->assign("page",$mypage);
		$xt->assign("maxpages",$maxpages);
		

//	write pagination
	if($maxpages>1)
	{
		$xt->assign("pagination_block",true);
		$xt->assign("pagination","<script language=\"JavaScript\">WritePagination(".$mypage.",".$maxpages.");
		function GotoPage(nPageNumber)
		{
			window.location='player_list.php?goto='+nPageNumber;
		}
		</script>");
	}
		
		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);

//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
	$recordsonpage=$PageSize;
	$colsonpage=1;
	if($colsonpage>$recordsonpage)
		$colsonpage=$recordsonpage;
	if($colsonpage<1)
		$colsonpage=1;


//	fill $rowinfo array
	$rowinfo = array();
	$rowinfo["data"]=array();
	$shade=false;
	$editlink="";
	$copylink="";

	

//	add grid data	
	
	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowList"))
		{
			if(!BeforeProcessRowList($data))
				continue;
		}
		break;
	}

	while($data && $recno<=$PageSize)
	{
	
		$row=array();
		if(!$shade)
		{
			$row["rowattrs"]="class=shade onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = 'shade';\"";
			$shade=true;
		}
		else
		{
			$row["rowattrs"]="onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = '';\"";
			$shade=false;
		}
		$row["grid_record"]=array();
		$row["grid_record"]["data"]=array();
		$row["rowattrs"].=" rowid=\"".$rowid."\"";
		$rowid++;
		for($col=1;$data && $recno<=$PageSize && $col<=$colsonpage;$col++)
		{
			$record=array();

	$editable=CheckSecurity($data[""],"Edit");
	$record["edit_link"]=$editable;
	$record["inlineedit_link"]=$editable;
	$record["view_link"]=true;
	$record["copy_link"]=true;
	$record["checkbox"]=$editable;
	if($allow_export)
		$record["checkbox"]=true;


//	detail tables
			$masterquery="mastertable=player";
	
			$detailid=array();
			$masterquery.="&masterkey1=".rawurlencode($data["p_id"]);
			$detailid[]=make_db_value("p_id",$data["p_id"],"","","Dialog");
	//	add count of child records to SQL
		if(!$bSubqueriesSupported)
	{
	$sqlHead="SELECT d_id,  d_from_p_id,  d_to_p_id,  d_message,  d_datetime,  d_msg_type ";
	$sqlFrom="FROM Dialog ";
	$sqlWhere="";
	$sqlTail="";
	$masterkeys=array();
	$masterkeys[]="`p_id`";
	$detailkeys=array();
	$detailkeys[]="d_from_p_id";
	$masterwhere ="";
	foreach($masterkeys as $idx=>$val)
	{
		if($masterwhere)
			$masterwhere.=" and ";
	$masterwhere.=GetFullFieldName($detailkeys[$idx],"Dialog")."=".$detailid[$idx];
	}
	$data["Dialog_cnt"]=gSQLRowCount_int($sqlHead,$sqlFrom,$sqlWhere,$sqlTail,$masterwhere,0);
	}

//detail tables
	$record["Dialog_dtable_link"]=$allow_Dialog;
	$record["Dialog_dtablelink_attrs"]="href=\"Dialog_list.php?".$masterquery."\" id=\"master_Dialog".$recno."\"";
	$dpreview=true;
		if($data["Dialog_cnt"]+0)
		$record["Dialog_childcount"]=true;
	else
		$dpreview=false;
	$record["Dialog_childnumber"]=$data["Dialog_cnt"];
		if(!($data["Dialog_cnt"]+0))
	{
		$record["Dialog_dtable_link"]=false;
		$dpreview=false;
	}
	if($dpreview)
	{
			}
	
			$masterquery="mastertable=player";
	
			$detailid=array();
			$masterquery.="&masterkey1=".rawurlencode($data["p_id"]);
			$detailid[]=make_db_value("p_id",$data["p_id"],"","","raffle");
	//	add count of child records to SQL
		if(!$bSubqueriesSupported)
	{
	$sqlHead="SELECT r_id,   r_name,   r_img,   r_text,   r_date,   r_p_id ";
	$sqlFrom="FROM raffle ";
	$sqlWhere="";
	$sqlTail="";
	$masterkeys=array();
	$masterkeys[]="`p_id`";
	$detailkeys=array();
	$detailkeys[]="r_p_id";
	$masterwhere ="";
	foreach($masterkeys as $idx=>$val)
	{
		if($masterwhere)
			$masterwhere.=" and ";
	$masterwhere.=GetFullFieldName($detailkeys[$idx],"raffle")."=".$detailid[$idx];
	}
	$data["raffle_cnt"]=gSQLRowCount_int($sqlHead,$sqlFrom,$sqlWhere,$sqlTail,$masterwhere,0);
	}

//detail tables
	$record["raffle_dtable_link"]=$allow_raffle;
	$record["raffle_dtablelink_attrs"]="href=\"raffle_list.php?".$masterquery."\" id=\"master_raffle".$recno."\"";
	$dpreview=true;
		if($data["raffle_cnt"]+0)
		$record["raffle_childcount"]=true;
	else
		$dpreview=false;
	$record["raffle_childnumber"]=$data["raffle_cnt"];
		if(!($data["raffle_cnt"]+0))
	{
		$record["raffle_dtable_link"]=false;
		$dpreview=false;
	}
	if($dpreview)
	{
			$record["raffle_dtablelink_attrs"].=" onmouseover=\"RollDetailsLink.showPopup(this,'raffle_detailspreview.php'+this.href.substr(this.href.indexOf('?')));\" onmouseout=\"RollDetailsLink.hidePopup();\"";
		}
	
			$masterquery="mastertable=player";
	
			$detailid=array();
			$masterquery.="&masterkey1=".rawurlencode($data["p_id"]);
			$detailid[]=make_db_value("p_id",$data["p_id"],"","","top");
	//	add count of child records to SQL
		if(!$bSubqueriesSupported)
	{
	$sqlHead="SELECT t_id,  t_user,  t_score,  t_datetime,  t_ip,  t_p_id,  t_ts_id,  t_kills ";
	$sqlFrom="FROM `top` ";
	$sqlWhere="";
	$sqlTail="";
	$masterkeys=array();
	$masterkeys[]="`p_id`";
	$detailkeys=array();
	$detailkeys[]="t_p_id";
	$masterwhere ="";
	foreach($masterkeys as $idx=>$val)
	{
		if($masterwhere)
			$masterwhere.=" and ";
	$masterwhere.=GetFullFieldName($detailkeys[$idx],"top")."=".$detailid[$idx];
	}
	$data["top_cnt"]=gSQLRowCount_int($sqlHead,$sqlFrom,$sqlWhere,$sqlTail,$masterwhere,0);
	}

//detail tables
	$record["top_dtable_link"]=$allow_top;
	$record["top_dtablelink_attrs"]="href=\"top_list.php?".$masterquery."\" id=\"master_top".$recno."\"";
	$dpreview=true;
		if($data["top_cnt"]+0)
		$record["top_childcount"]=true;
	else
		$dpreview=false;
	$record["top_childnumber"]=$data["top_cnt"];
		if(!($data["top_cnt"]+0))
	{
		$record["top_dtable_link"]=false;
		$dpreview=false;
	}
	if($dpreview)
	{
			}
	

//	key fields
	$keyblock="";
	$editlink="";
	$copylink="";
	$keylink="";
	$keyblock.= rawurlencode($data["p_id"]);
	$editlink.="editid1=".htmlspecialchars(rawurlencode($data["p_id"]));
	$copylink.="copyid1=".htmlspecialchars(rawurlencode($data["p_id"]));
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["p_id"]));

	$record["editlink_attrs"]="href=\"player_edit.php?".$editlink."\" id=\"editlink".$recno."\"";
	$record["inlineeditlink_attrs"]= "href=\"player_edit.php?".$editlink."\" onclick=\"return inlineEdit('".$recno."','".$editlink."');\" id=\"ieditlink".$recno."\"";
	$record["copylink_attrs"]="href=\"player_add.php?".$copylink."\" id=\"copylink".$recno."\"";
	$record["viewlink_attrs"]="href=\"player_view.php?".$editlink."\" id=\"viewlink".$recno."\"";
	$record["checkbox_attrs"]="name=\"selection[]\" value=\"".$keyblock."\" id=\"check".$recno."\"";


//	p_active - 
			$value="";
				$value=DisplayLookupWizard("p_active",$data["p_active"],$data,$keylink,MODE_LIST);
			$record["p_active_value"]=$value;

//	p_s_id - 
			$value="";
				$value=DisplayLookupWizard("p_s_id",$data["p_s_id"],$data,$keylink,MODE_LIST);
			$record["p_s_id_value"]=$value;

//	p_first - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_first", ""),"field=p%5Ffirst".$keylink,"",MODE_LIST);
			$record["p_first_value"]=$value;

//	p_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_name", ""),"field=p%5Fname".$keylink,"",MODE_LIST);
			$record["p_name_value"]=$value;

//	p_zip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_zip", ""),"field=p%5Fzip".$keylink,"",MODE_LIST);
			$record["p_zip_value"]=$value;

//	p_country - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_country", ""),"field=p%5Fcountry".$keylink,"",MODE_LIST);
			$record["p_country_value"]=$value;

//	p_mail - Email Hyperlink
			$value="";
				$value = GetData($data,"p_mail", "Email Hyperlink");
			$record["p_mail_value"]=$value;

//	p_mobile - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_mobile", ""),"field=p%5Fmobile".$keylink,"",MODE_LIST);
			$record["p_mobile_value"]=$value;

//	p_newsaccept - Checkbox
			$value="";
				$value = GetData($data,"p_newsaccept", "Checkbox");
			$record["p_newsaccept_value"]=$value;

//	p_win - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_win", ""),"field=p%5Fwin".$keylink,"",MODE_LIST);
			$record["p_win_value"]=$value;

//	p_mk - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_mk", ""),"field=p%5Fmk".$keylink,"",MODE_LIST);
			$record["p_mk_value"]=$value;

//	p_born - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_born", ""),"field=p%5Fborn".$keylink,"",MODE_LIST);
			$record["p_born_value"]=$value;

//	p_ip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_ip", ""),"field=p%5Fip".$keylink,"",MODE_LIST);
			$record["p_ip_value"]=$value;

//	p_datetime - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"p_datetime", "Short Date"),"field=p%5Fdatetime".$keylink,"",MODE_LIST);
			$record["p_datetime_value"]=$value;

//	p_tscore - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_tscore", ""),"field=p%5Ftscore".$keylink,"",MODE_LIST);
			$record["p_tscore_value"]=$value;
			if(function_exists("BeforeMoveNextList"))
				BeforeMoveNextList($data,$record,$col);
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$record["grid_recordheader"]=true;
			$record["grid_vrecord"]=true;
			$row["grid_record"]["data"][]=$record;
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowList"))
				{
					if(!BeforeProcessRowList($data))
						continue;
				}
				break;
			}
			$recno++;
			
		}
		while($col<=$colsonpage)
		{
			$record = array();
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$row["grid_record"]["data"][]=$record;
			$col++;
		}
//	assign row spacings for vertical layout
		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		
		$rowinfo["data"][]=$row;
	}
	if(count($rowinfo["data"]))
		$rowinfo["data"][count($rowinfo["data"])-1]["grid_rowspace"]=false;
	$xt->assignbyref("grid_row",$rowinfo);


}


if($allow_search)
{

	$searchfor_attrs="autocomplete=off onkeydown=\"return listenEvent(event,this,'ordinary');\" onkeyup=\"searchSuggest(event,this,'ordinary');\"";
	if($_SESSION[$strTableName."_search"]==1)
	{
//	fill in search variables
	//	field selection
		if(@$_SESSION[$strTableName."_searchfield"])
			$xt->assign(GoodFieldName(@$_SESSION[$strTableName."_searchfield"])."_searchfieldoption","selected");
	// search type selection
		$xt->assign(GoodFieldName(@$_SESSION[$strTableName."_searchoption"])."_searchtypeoption","selected");
		$searchfor_attrs.=" value=\"".htmlspecialchars(@$_SESSION[$strTableName."_searchfor"])."\"";
	}
	$searchfor_attrs.=" name=\"ctlSearchFor\" id=\"ctlSearchFor\"";
	$xt->assign("searchfor_attrs",$searchfor_attrs);
	$xt->assign("searchbutton_attrs","onClick=\"javascript: RunSearch();\"");
	$xt->assign("showallbutton_attrs","onClick=\"javascript: document.forms.frmSearch.a.value = 'showall'; document.forms.frmSearch.submit();\"");
}

$xt->assign("login_block",true);
$xt->assign("username",htmlspecialchars($_SESSION["UserID"]));
$xt->assign("logoutlink_attrs","onclick=\"window.location.href='login.php?a=logout';\"");

	$xt->assign("toplinks_block",true);

	$xt->assign("print_link",$allow_export);
	$xt->assign("printall_link",$allow_export);
	$xt->assign("printlink_attrs","href=\"player_print.php\" onclick=\"window.open('player_print.php','wPrint');return false;\"");
	$xt->assign("printalllink_attrs","href=\"player_print.php?all=1\" onclick=\"window.open('player_print.php?all=1','wPrint');return false;\"");
	$xt->assign("export_link",$allow_export);
	$xt->assign("exportlink_attrs","href=\"player_export.php\" onclick=\"window.open('player_export.php','wExport');return false;\"");
	
	$xt->assign("printselected_link",$allow_export);
	$xt->assign("printselectedlink_attrs","disptype=\"control1\" onclick=\"
	if(!\$('input[@type=checkbox][@checked][@name^=selection]').length)
		return true;
	document.forms.frmAdmin.action='player_print.php';
	document.forms.frmAdmin.target='_blank';
	document.forms.frmAdmin.submit(); 
	document.forms.frmAdmin.action='player_list.php'; 
	document.forms.frmAdmin.target='_self';return false\"
	href=\"player_print.php\"");
	$xt->assign("exportselected_link",$allow_export);
	$xt->assign("exportselectedlink_attrs","disptype=\"control1\" onclick=\"
	if(!\$('input[@type=checkbox][@checked][@name^=selection]').length)
		return true;
	document.forms.frmAdmin.action='player_export.php';
	document.forms.frmAdmin.target='_blank';
	document.forms.frmAdmin.submit(); 
	document.forms.frmAdmin.action='player_list.php'; 
	document.forms.frmAdmin.target='_self';return false;\"
	href=\"player_export.php\"");
	
	$xt->assign("add_link",$allow_add);
	$xt->assign("copy_column",$allow_add);
	$xt->assign("inlineadd_link",$allow_add);
	$xt->assign("addlink_attrs","href=\"player_add.php\" onClick=\"window.location.href='player_add.php'\"");
	$xt->assign("inlineaddlink_attrs","href=\"player_add.php\" onclick=\"return inlineAdd(newrecord_id++);\"");

	$xt->assign("selectall_link",$allow_delete || $allow_export  || $allow_edit);
	$xt->assign("selectalllink_attrs","href=# onclick=\"var i; 
	bSelected=!bSelected;
if ((typeof frmAdmin.elements['selection[]'].length)=='undefined')
	frmAdmin.elements['selection[]'].checked=bSelected;
else
for (i=0;i<frmAdmin.elements['selection[]'].length;++i) 
	frmAdmin.elements['selection[]'][i].checked=bSelected\"");
	
	$xt->assign("checkbox_column",$allow_delete || $allow_export  || $allow_edit);
	$xt->assign("checkboxheader_attrs","onClick = \"var i; 
if ((typeof frmAdmin.elements['selection[]'].length)=='undefined')
	frmAdmin.elements['selection[]'].checked=this.checked;
else
for (i=0;i<frmAdmin.elements['selection[]'].length;++i) 
	frmAdmin.elements['selection[]'][i].checked=this.checked;\"");
	$xt->assign("editselected_link",$allow_edit);
	$xt->assign("editselectedlink_attrs","href=\"player_edit.php\" disptype=\"control1\" name=\"edit_selected\" onclick=\"\$('input[@type=checkbox][@checked][@id^=check]').each(function(i){
				if(!isNaN(parseInt(this.id.substr(5))))
					\$('a#ieditlink'+this.id.substr(5)).click();});\"");
	$xt->assign("saveall_link",$allow_edit||$allow_edit);
	$xt->assign("savealllink_attrs","disptype=\"control1\" name=\"saveall_edited\" style=\"display:none\" onclick=\"\$('a[@id^=save_]').click();\"");
	$xt->assign("cancelall_link",$allow_edit||$allow_edit);
	$xt->assign("cancelalllink_attrs","disptype=\"control1\" name=\"revertall_edited\" style=\"display:none\" onclick=\"\$('a[@id^=revert_]').click();\"");
	

	$xt->assign("edit_column",$allow_edit);
	$xt->assign("edit_headercolumn",$allow_edit);
	$xt->assign("edit_footercolumn",$allow_edit);
	$xt->assign("inlineedit_column",$allow_edit);
	$xt->assign("inlineedit_headercolumn",$allow_edit);
	$xt->assign("inlineedit_footercolumn",$allow_edit);
	
	$xt->assign("view_column",$allow_search);
	
 	$xt->assign("Dialog_dtable_column",$allow_Dialog);
 	$xt->assign("raffle_dtable_column",$allow_raffle);
 	$xt->assign("top_dtable_column",$allow_top);


$xt->assign("p_active_fieldheadercolumn",true);
$xt->assign("p_active_fieldcolumn",true);
$xt->assign("p_active_fieldfootercolumn",true);
$xt->assign("p_s_id_fieldheadercolumn",true);
$xt->assign("p_s_id_fieldcolumn",true);
$xt->assign("p_s_id_fieldfootercolumn",true);
$xt->assign("p_first_fieldheadercolumn",true);
$xt->assign("p_first_fieldcolumn",true);
$xt->assign("p_first_fieldfootercolumn",true);
$xt->assign("p_name_fieldheadercolumn",true);
$xt->assign("p_name_fieldcolumn",true);
$xt->assign("p_name_fieldfootercolumn",true);
$xt->assign("p_zip_fieldheadercolumn",true);
$xt->assign("p_zip_fieldcolumn",true);
$xt->assign("p_zip_fieldfootercolumn",true);
$xt->assign("p_country_fieldheadercolumn",true);
$xt->assign("p_country_fieldcolumn",true);
$xt->assign("p_country_fieldfootercolumn",true);
$xt->assign("p_mail_fieldheadercolumn",true);
$xt->assign("p_mail_fieldcolumn",true);
$xt->assign("p_mail_fieldfootercolumn",true);
$xt->assign("p_mobile_fieldheadercolumn",true);
$xt->assign("p_mobile_fieldcolumn",true);
$xt->assign("p_mobile_fieldfootercolumn",true);
$xt->assign("p_newsaccept_fieldheadercolumn",true);
$xt->assign("p_newsaccept_fieldcolumn",true);
$xt->assign("p_newsaccept_fieldfootercolumn",true);
$xt->assign("p_win_fieldheadercolumn",true);
$xt->assign("p_win_fieldcolumn",true);
$xt->assign("p_win_fieldfootercolumn",true);
$xt->assign("p_mk_fieldheadercolumn",true);
$xt->assign("p_mk_fieldcolumn",true);
$xt->assign("p_mk_fieldfootercolumn",true);
$xt->assign("p_born_fieldheadercolumn",true);
$xt->assign("p_born_fieldcolumn",true);
$xt->assign("p_born_fieldfootercolumn",true);
$xt->assign("p_ip_fieldheadercolumn",true);
$xt->assign("p_ip_fieldcolumn",true);
$xt->assign("p_ip_fieldfootercolumn",true);
$xt->assign("p_datetime_fieldheadercolumn",true);
$xt->assign("p_datetime_fieldcolumn",true);
$xt->assign("p_datetime_fieldfootercolumn",true);
$xt->assign("p_tscore_fieldheadercolumn",true);
$xt->assign("p_tscore_fieldcolumn",true);
$xt->assign("p_tscore_fieldfootercolumn",true);
	
$display_grid = $allow_search && $rowsfound;

$xt->assign("asearch_link",$allow_search);
$xt->assign("asearchlink_attrs","href=\"player_search.php\" onclick=\"window.location.href='player_search.php';return false;\"");
$xt->assign("import_link",$allow_import);
$xt->assign("importlink_attrs","href=\"player_import.php\" onclick=\"window.location.href='player_import.php';return false;\"");

$xt->assign("search_records_block",$allow_search);
$xt->assign("searchform",$allow_search);
$xt->assign("searchform_field",$allow_search);
$xt->assign("searchform_option",$allow_search);
$xt->assign("searchform_text",$allow_search);
$xt->assign("searchform_search",$allow_search);
$xt->assign("searchform_showall",$allow_search);

$xt->assign("delete_link",$allow_delete);
$xt->assign("deletelink_attrs","onclick=\"
	if(\$('input[@type=checkbox][@checked][@name^=selection]').length && confirm('".mlang_message("DELETE_CONFIRM")."'))
		frmAdmin.submit(); 
	return false;\"");
$xt->assign("usermessage",true);

if($display_grid)
{
	$xt->assign_section("grid_block",
	"<form method=\"POST\" action=\"player_list.php\" name=\"frmAdmin\" id=\"frmAdmin\">
	<input type=\"hidden\" id=\"a\" name=\"a\" value=\"delete\">",
	"</form>");
	$record_header=array("data"=>array());
	$record_footer=array("data"=>array());
	for($i=0;$i<$colsonpage;$i++)
	{
		$rheader=array();
		$rfooter=array();
		if($i<$colsonpage-1)
		{
			$rheader["endrecordheader_block"]=true;
			$rfooter["endrecordfooter_block"]=true;
		}
		$record_header["data"][]=$rheader;
		$record_footer["data"][]=$rfooter;
	}
	$xt->assignbyref("record_header",$record_header);
	$xt->assignbyref("record_footer",$record_footer);
	$xt->assign("grid_header",true);
	$xt->assign("grid_footer",true);



	$xt->assign("recordcontrols_block",true);
}


$xt->assign("details_block",$allow_search && $rowsfound);
$xt->assign("pages_block",$allow_search && $rowsfound);
$xt->assign("recordspp_block",$allow_search && $rowsfound);
$xt->assign("recordspp_attrs","onchange=\"javascript: document.location='player_list.php?pagesize='+this.options[this.selectedIndex].value;\"");
$xt->assign("grid_controls",$display_grid);



//	display master table info
$masterkeys=array();
if($mastertable=="teams")
{
//	include proper masterlist.php code
	include("include/teams_masterlist.php");
	$masterkeys[]=@$_SESSION[$strTableName."_masterkey1"];
	$params=array("detailtable"=>"player","keys"=>$masterkeys);
	$master=array();
	$master["func"]="DisplayMasterTableInfo_teams";
	$master["params"]=$params;
	$xt->assignbyref("showmasterfile",$master);
	$xt->assign("mastertable_block",true);
	$xt->assign("backtomasterlink_attrs","href=\"teams_list.php?a=return\"");

}

$linkdata="";

$linkdata .= "<script type=\"text/javascript\">\r\n";

	$linkdata .= "</script>\r\n";
$linkdata.="<script>
if(!$('[@disptype=control1]').length && $('[@disptype=controltable1]').length)
	$('[@disptype=controltable1]').hide();
</script>";
if($_SESSION[$strTableName."_search"]==1)
	$linkdata.= "<script>if(document.getElementById('SearchFor')) document.getElementById('ctlSearchFor').focus();</script>";

$body["end"]=$linkdata;
$xt->assignbyref("body",$body);


$strSQL=$_SESSION[$strTableName."_sql"];
$xt->assign("changepwd_link",$_SESSION["AccessLevel"] != ACCESS_LEVEL_GUEST);
$xt->assign("changepwdlink_attrs","href=\"changepwd.php\" onclick=\"window.location.href='changepwd.php';return false;\"");



$xt->assign("quickjump_attrs","onchange=\"window.location.href=this.options[this.selectedIndex].value;\"");

$xt->assign("endrecordblock_attrs","colid=\"endrecord\"");
$templatefile = "player_list.htm";
if(function_exists("BeforeShowList"))
	BeforeShowList($xt,$templatefile);

$xt->display($templatefile);

