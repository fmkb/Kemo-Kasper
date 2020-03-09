<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/top_variables.php");
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
			$keys["t_id"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["t_id"]=urldecode($arr[0]);
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
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["t_id"]));


//	t_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"t_id", ""),"field=t%5Fid".$keylink,"",MODE_PRINT);
			$record["t_id_value"]=$value;

//	t_user - 
			$value="";
				$value = ProcessLargeText(GetData($data,"t_user", ""),"field=t%5Fuser".$keylink,"",MODE_PRINT);
			$record["t_user_value"]=$value;

//	t_score - 
			$value="";
				$value = ProcessLargeText(GetData($data,"t_score", ""),"field=t%5Fscore".$keylink,"",MODE_PRINT);
			$record["t_score_value"]=$value;

//	t_datetime - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"t_datetime", "Short Date"),"field=t%5Fdatetime".$keylink,"",MODE_PRINT);
			$record["t_datetime_value"]=$value;

//	t_ip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"t_ip", ""),"field=t%5Fip".$keylink,"",MODE_PRINT);
			$record["t_ip_value"]=$value;

//	t_p_id - 
			$value="";
				$value=DisplayLookupWizard("t_p_id",$data["t_p_id"],$data,$keylink,MODE_PRINT);
			$record["t_p_id_value"]=$value;

//	t_ts_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"t_ts_id", ""),"field=t%5Fts%5Fid".$keylink,"",MODE_PRINT);
			$record["t_ts_id_value"]=$value;

//	t_kills - 
			$value="";
				$value = ProcessLargeText(GetData($data,"t_kills", ""),"field=t%5Fkills".$keylink,"",MODE_PRINT);
			$record["t_kills_value"]=$value;
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
if($mastertable=="player")
{
//	include proper masterprint.php code
	include("include/player_masterprint.php");
	$masterkeys[]=@$_SESSION[$strTableName."_masterkey1"];
	$params=array("detailtable"=>"top","keys"=>$masterkeys);
	$master=array();
	$master["func"]="DisplayMasterTableInfo_player";
	$master["params"]=$params;
	$xt->assignbyref("showmasterfile",$master);
	$xt->assign("mastertable_block",true);
}

$strSQL=$_SESSION[$strTableName."_sql"];

	
$body=array();
$xt->assignbyref("body",$body);
$xt->assign("grid_block",true);

$xt->assign("t_id_fieldheadercolumn",true);
$xt->assign("t_id_fieldheader",true);
$xt->assign("t_id_fieldcolumn",true);
$xt->assign("t_id_fieldfootercolumn",true);
$xt->assign("t_user_fieldheadercolumn",true);
$xt->assign("t_user_fieldheader",true);
$xt->assign("t_user_fieldcolumn",true);
$xt->assign("t_user_fieldfootercolumn",true);
$xt->assign("t_score_fieldheadercolumn",true);
$xt->assign("t_score_fieldheader",true);
$xt->assign("t_score_fieldcolumn",true);
$xt->assign("t_score_fieldfootercolumn",true);
$xt->assign("t_datetime_fieldheadercolumn",true);
$xt->assign("t_datetime_fieldheader",true);
$xt->assign("t_datetime_fieldcolumn",true);
$xt->assign("t_datetime_fieldfootercolumn",true);
$xt->assign("t_ip_fieldheadercolumn",true);
$xt->assign("t_ip_fieldheader",true);
$xt->assign("t_ip_fieldcolumn",true);
$xt->assign("t_ip_fieldfootercolumn",true);
$xt->assign("t_p_id_fieldheadercolumn",true);
$xt->assign("t_p_id_fieldheader",true);
$xt->assign("t_p_id_fieldcolumn",true);
$xt->assign("t_p_id_fieldfootercolumn",true);
$xt->assign("t_ts_id_fieldheadercolumn",true);
$xt->assign("t_ts_id_fieldheader",true);
$xt->assign("t_ts_id_fieldcolumn",true);
$xt->assign("t_ts_id_fieldfootercolumn",true);
$xt->assign("t_kills_fieldheadercolumn",true);
$xt->assign("t_kills_fieldheader",true);
$xt->assign("t_kills_fieldcolumn",true);
$xt->assign("t_kills_fieldfootercolumn",true);

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


$templatefile = "top_print.htm";
	
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

