<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/player_variables.php");
include("include/languages.php");

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Export"))
{
	echo "<p>".mlang_message("NO_PERMISSIONS")."<a href=\"login.php\">".mlang_message("BACK_TO_LOGIN")."</a></p>";
	return;
}

$all=postvalue("all");

include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();

//	Before Process event
if(function_exists("BeforeProcessPrint"))
	BeforeProcessPrint($conn);

$strWhereClause="";

if (@$_REQUEST["a"]!="") 
{
	
	$sWhere = "1=0";	
	
//	process selection
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
			$keys["p_id"]=urldecode($arr[0]);
			$selected_recs[]=$keys;
		}
	}

	foreach($selected_recs as $keys)
	{
		$sWhere = $sWhere . " or ";
		$sWhere.=KeyWhere($keys);
	}
//	$strSQL = AddWhere($gstrSQL,$sWhere);
	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
}
else
{
	$strWhereClause=@$_SESSION[$strTableName."_where"];
	$strSQL = gSQLWhere($strWhereClause);
}
if(postvalue("pdf"))
	$strWhereClause = @$_SESSION[$strTableName."_pdfwhere"];

$_SESSION[$strTableName."_pdfwhere"] = $strWhereClause;


$strOrderBy=$_SESSION[$strTableName."_order"];
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;
$strSQL.=" ".trim($strOrderBy);

$strSQLbak = $strSQL;
if(function_exists("BeforeQueryPrint"))
	BeforeQueryPrint($strSQL,$strWhereClause,$strOrderBy);

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

$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;

$recno=1;
$records=0;	
$pageindex=1;

if(!$all)
{	
	if($numrows)
	{
		$maxRecords = $numrows;
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);
	
	
	//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
		$recordsonpage=$PageSize;
		
}
else
{
	$rs=db_query($strSQL,$conn);
	$recordsonpage = $numrows;
	$maxpages=ceil($recordsonpage/30);
	$xt->assign("page_number",true);
}

$colsonpage=1;
if($colsonpage>$recordsonpage)
	$colsonpage=$recordsonpage;
if($colsonpage<1)
	$colsonpage=1;


//	fill $rowinfo array
	$pages = array();
	$rowinfo = array();
	$rowinfo["data"]=array();

	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowPrint"))
		{
			if(!BeforeProcessRowPrint($data))
				continue;
		}
		break;
	}

	while($data && ($all || $recno<=$PageSize))
	{
		$row=array();
		$row["grid_record"]=array();
		$row["grid_record"]["data"]=array();
		for($col=1;$data && ($all || $recno<=$PageSize) && $col<=1;$col++)
		{
			$record=array();
			$recno++;
			$records++;
			$keylink="";
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["p_id"]));


//	p_active - 
			$value="";
				$value=DisplayLookupWizard("p_active",$data["p_active"],$data,$keylink,MODE_PRINT);
			$record["p_active_value"]=$value;

//	p_s_id - 
			$value="";
				$value=DisplayLookupWizard("p_s_id",$data["p_s_id"],$data,$keylink,MODE_PRINT);
			$record["p_s_id_value"]=$value;

//	p_first - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_first", ""),"field=p%5Ffirst".$keylink,"",MODE_PRINT);
			$record["p_first_value"]=$value;

//	p_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_name", ""),"field=p%5Fname".$keylink,"",MODE_PRINT);
			$record["p_name_value"]=$value;

//	p_adr - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_adr", ""),"field=p%5Fadr".$keylink,"",MODE_PRINT);
			$record["p_adr_value"]=$value;

//	p_zip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_zip", ""),"field=p%5Fzip".$keylink,"",MODE_PRINT);
			$record["p_zip_value"]=$value;

//	p_country - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_country", ""),"field=p%5Fcountry".$keylink,"",MODE_PRINT);
			$record["p_country_value"]=$value;

//	p_mail - Email Hyperlink
			$value="";
				$value = GetData($data,"p_mail", "Email Hyperlink");
			$record["p_mail_value"]=$value;

//	p_mobile - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_mobile", ""),"field=p%5Fmobile".$keylink,"",MODE_PRINT);
			$record["p_mobile_value"]=$value;

//	p_newsaccept - Checkbox
			$value="";
				$value = GetData($data,"p_newsaccept", "Checkbox");
			$record["p_newsaccept_value"]=$value;

//	p_score - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_score", ""),"field=p%5Fscore".$keylink,"",MODE_PRINT);
			$record["p_score_value"]=$value;

//	p_scorehigh - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_scorehigh", ""),"field=p%5Fscorehigh".$keylink,"",MODE_PRINT);
			$record["p_scorehigh_value"]=$value;

//	p_games - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_games", ""),"field=p%5Fgames".$keylink,"",MODE_PRINT);
			$record["p_games_value"]=$value;

//	p_win - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_win", ""),"field=p%5Fwin".$keylink,"",MODE_PRINT);
			$record["p_win_value"]=$value;

//	p_mk - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_mk", ""),"field=p%5Fmk".$keylink,"",MODE_PRINT);
			$record["p_mk_value"]=$value;

//	p_born - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_born", ""),"field=p%5Fborn".$keylink,"",MODE_PRINT);
			$record["p_born_value"]=$value;

//	p_user - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_user", ""),"field=p%5Fuser".$keylink,"",MODE_PRINT);
			$record["p_user_value"]=$value;

//	p_pwd - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_pwd", ""),"field=p%5Fpwd".$keylink,"",MODE_PRINT);
			$record["p_pwd_value"]=$value;

//	p_ip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_ip", ""),"field=p%5Fip".$keylink,"",MODE_PRINT);
			$record["p_ip_value"]=$value;

//	p_datetime - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"p_datetime", "Short Date"),"field=p%5Fdatetime".$keylink,"",MODE_PRINT);
			$record["p_datetime_value"]=$value;

//	p_tscore - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_tscore", ""),"field=p%5Ftscore".$keylink,"",MODE_PRINT);
			$record["p_tscore_value"]=$value;

//	p_tkills - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_tkills", ""),"field=p%5Ftkills".$keylink,"",MODE_PRINT);
			$record["p_tkills_value"]=$value;
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$record["grid_recordheader"]=true;
			$record["grid_vrecord"]=true;
			$row["grid_record"]["data"][]=$record;
			
			if(function_exists("BeforeMoveNextPrint"))
				BeforeMoveNextPrint($data,$row,$col);
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowPrint"))
				{
					if(!BeforeProcessRowPrint($data))
						continue;
				}
				break;
			}
		}
		if($col<=$colsonpage)
		{
			$row["grid_record"]["data"][count($row["grid_record"]["data"])-1]["endrecord_block"]=false;
		}
		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		
		$rowinfo["data"][]=$row;
		
		if($all && $records>=30)
		{
			$page=array("grid_row" =>$rowinfo);
			$page["page"]=$pageindex;
			$pageindex++;
			$pages[] = $page;
			$records=0;
			$rowinfo=array();
		}
		
	}
	if(count($rowinfo))
	{
		$page=array("grid_row" =>$rowinfo);
		$page["page"]=$pageindex;
		$pages[] = $page;
	}
	
	for($i=0;$i<count($pages);$i++)
	{
	 	if($i<count($pages)-1)
			$pages[$i]["begin"]="<div name=page class=printpage>";
		else
		    $pages[$i]["begin"]="<div name=page>";
			
			$pages[$i]["maxpages"]=$maxpages;	
		$pages[$i]["end"]="</div>";
	}

	if(count($pages))
	{
		$pages[count($pages)-1]["totals_row"]=true;
	}
	$page=array("data"=>&$pages);
	$xt->assignbyref("page",$page);


	
//	display master table info
$mastertable=$_SESSION[$strTableName."_mastertable"];
$masterkeys=array();
if($mastertable=="teams")
{
//	include proper masterprint.php code
	include("include/teams_masterprint.php");
	$masterkeys[]=@$_SESSION[$strTableName."_masterkey1"];
	$params=array("detailtable"=>"player","keys"=>$masterkeys);
	$master=array();
	$master["func"]="DisplayMasterTableInfo_teams";
	$master["params"]=$params;
	$xt->assignbyref("showmasterfile",$master);
	$xt->assign("mastertable_block",true);
}

$strSQL=$_SESSION[$strTableName."_sql"];

	
$body=array();
$xt->assignbyref("body",$body);
$xt->assign("grid_block",true);

$xt->assign("p_active_fieldheadercolumn",true);
$xt->assign("p_active_fieldheader",true);
$xt->assign("p_active_fieldcolumn",true);
$xt->assign("p_active_fieldfootercolumn",true);
$xt->assign("p_s_id_fieldheadercolumn",true);
$xt->assign("p_s_id_fieldheader",true);
$xt->assign("p_s_id_fieldcolumn",true);
$xt->assign("p_s_id_fieldfootercolumn",true);
$xt->assign("p_first_fieldheadercolumn",true);
$xt->assign("p_first_fieldheader",true);
$xt->assign("p_first_fieldcolumn",true);
$xt->assign("p_first_fieldfootercolumn",true);
$xt->assign("p_name_fieldheadercolumn",true);
$xt->assign("p_name_fieldheader",true);
$xt->assign("p_name_fieldcolumn",true);
$xt->assign("p_name_fieldfootercolumn",true);
$xt->assign("p_adr_fieldheadercolumn",true);
$xt->assign("p_adr_fieldheader",true);
$xt->assign("p_adr_fieldcolumn",true);
$xt->assign("p_adr_fieldfootercolumn",true);
$xt->assign("p_zip_fieldheadercolumn",true);
$xt->assign("p_zip_fieldheader",true);
$xt->assign("p_zip_fieldcolumn",true);
$xt->assign("p_zip_fieldfootercolumn",true);
$xt->assign("p_country_fieldheadercolumn",true);
$xt->assign("p_country_fieldheader",true);
$xt->assign("p_country_fieldcolumn",true);
$xt->assign("p_country_fieldfootercolumn",true);
$xt->assign("p_mail_fieldheadercolumn",true);
$xt->assign("p_mail_fieldheader",true);
$xt->assign("p_mail_fieldcolumn",true);
$xt->assign("p_mail_fieldfootercolumn",true);
$xt->assign("p_mobile_fieldheadercolumn",true);
$xt->assign("p_mobile_fieldheader",true);
$xt->assign("p_mobile_fieldcolumn",true);
$xt->assign("p_mobile_fieldfootercolumn",true);
$xt->assign("p_newsaccept_fieldheadercolumn",true);
$xt->assign("p_newsaccept_fieldheader",true);
$xt->assign("p_newsaccept_fieldcolumn",true);
$xt->assign("p_newsaccept_fieldfootercolumn",true);
$xt->assign("p_score_fieldheadercolumn",true);
$xt->assign("p_score_fieldheader",true);
$xt->assign("p_score_fieldcolumn",true);
$xt->assign("p_score_fieldfootercolumn",true);
$xt->assign("p_scorehigh_fieldheadercolumn",true);
$xt->assign("p_scorehigh_fieldheader",true);
$xt->assign("p_scorehigh_fieldcolumn",true);
$xt->assign("p_scorehigh_fieldfootercolumn",true);
$xt->assign("p_games_fieldheadercolumn",true);
$xt->assign("p_games_fieldheader",true);
$xt->assign("p_games_fieldcolumn",true);
$xt->assign("p_games_fieldfootercolumn",true);
$xt->assign("p_win_fieldheadercolumn",true);
$xt->assign("p_win_fieldheader",true);
$xt->assign("p_win_fieldcolumn",true);
$xt->assign("p_win_fieldfootercolumn",true);
$xt->assign("p_mk_fieldheadercolumn",true);
$xt->assign("p_mk_fieldheader",true);
$xt->assign("p_mk_fieldcolumn",true);
$xt->assign("p_mk_fieldfootercolumn",true);
$xt->assign("p_born_fieldheadercolumn",true);
$xt->assign("p_born_fieldheader",true);
$xt->assign("p_born_fieldcolumn",true);
$xt->assign("p_born_fieldfootercolumn",true);
$xt->assign("p_user_fieldheadercolumn",true);
$xt->assign("p_user_fieldheader",true);
$xt->assign("p_user_fieldcolumn",true);
$xt->assign("p_user_fieldfootercolumn",true);
$xt->assign("p_pwd_fieldheadercolumn",true);
$xt->assign("p_pwd_fieldheader",true);
$xt->assign("p_pwd_fieldcolumn",true);
$xt->assign("p_pwd_fieldfootercolumn",true);
$xt->assign("p_ip_fieldheadercolumn",true);
$xt->assign("p_ip_fieldheader",true);
$xt->assign("p_ip_fieldcolumn",true);
$xt->assign("p_ip_fieldfootercolumn",true);
$xt->assign("p_datetime_fieldheadercolumn",true);
$xt->assign("p_datetime_fieldheader",true);
$xt->assign("p_datetime_fieldcolumn",true);
$xt->assign("p_datetime_fieldfootercolumn",true);
$xt->assign("p_tscore_fieldheadercolumn",true);
$xt->assign("p_tscore_fieldheader",true);
$xt->assign("p_tscore_fieldcolumn",true);
$xt->assign("p_tscore_fieldfootercolumn",true);
$xt->assign("p_tkills_fieldheadercolumn",true);
$xt->assign("p_tkills_fieldheader",true);
$xt->assign("p_tkills_fieldcolumn",true);
$xt->assign("p_tkills_fieldfootercolumn",true);

	$record_header=array("data"=>array());
	for($i=0;$i<$colsonpage;$i++)
	{
		$rheader=array();
		if($i<$colsonpage-1)
		{
			$rheader["endrecordheader_block"]=true;
		}
		$record_header["data"][]=$rheader;
	}
	$xt->assignbyref("record_header",$record_header);
	$xt->assign("grid_header",true);
	$xt->assign("grid_footer",true);


$templatefile = "player_print.htm";
	
if(function_exists("BeforeShowPrint"))
	BeforeShowPrint($xt,$templatefile);

if(!postvalue("pdf"))
	$xt->display($templatefile);
else
{

	$xt->load_template($templatefile);
	$page = $xt->fetch_loaded();
	$pagewidth=postvalue("width")*1.05;
	$pageheight=postvalue("height")*1.05;
	$landscape=false;
	if(postvalue("all"))
	{
		if($pagewidth>$pageheight)
		{
			$landscape=true;
			if($pagewidth/$pageheight<297/210)
				$pagewidth = 297/210*$pageheight;
		}
		else
		{
			if($pagewidth/$pageheight<210/297)
				$pagewidth = 210/297*$pageheight;
		}
	}
	include("plugins/page2pdf.php");
}

