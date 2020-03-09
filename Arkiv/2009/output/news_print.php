<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/news_variables.php");
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
			$keys["n_id"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["n_id"]=urldecode($arr[0]);
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
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["n_id"]));


//	n_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"n_id", ""),"field=n%5Fid".$keylink,"",MODE_PRINT);
			$record["n_id_value"]=$value;

//	n_active - 
			$value="";
				$value = ProcessLargeText(GetData($data,"n_active", ""),"field=n%5Factive".$keylink,"",MODE_PRINT);
			$record["n_active_value"]=$value;

//	n_start - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"n_start", "Short Date"),"field=n%5Fstart".$keylink,"",MODE_PRINT);
			$record["n_start_value"]=$value;

//	n_end - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"n_end", "Short Date"),"field=n%5Fend".$keylink,"",MODE_PRINT);
			$record["n_end_value"]=$value;

//	n_date - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"n_date", "Short Date"),"field=n%5Fdate".$keylink,"",MODE_PRINT);
			$record["n_date_value"]=$value;

//	n_head - 
			$value="";
				$value = ProcessLargeText(GetData($data,"n_head", ""),"field=n%5Fhead".$keylink,"",MODE_PRINT);
			$record["n_head_value"]=$value;

//	n_text - 
			$value="";
				$value = ProcessLargeText(GetData($data,"n_text", ""),"field=n%5Ftext".$keylink,"",MODE_PRINT);
			$record["n_text_value"]=$value;

//	n_file - 
			$value="";
				$value = ProcessLargeText(GetData($data,"n_file", ""),"field=n%5Ffile".$keylink,"",MODE_PRINT);
			$record["n_file_value"]=$value;

//	n_type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"n_type", ""),"field=n%5Ftype".$keylink,"",MODE_PRINT);
			$record["n_type_value"]=$value;
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


	

$strSQL=$_SESSION[$strTableName."_sql"];

	
$body=array();
$xt->assignbyref("body",$body);
$xt->assign("grid_block",true);

$xt->assign("n_id_fieldheadercolumn",true);
$xt->assign("n_id_fieldheader",true);
$xt->assign("n_id_fieldcolumn",true);
$xt->assign("n_id_fieldfootercolumn",true);
$xt->assign("n_active_fieldheadercolumn",true);
$xt->assign("n_active_fieldheader",true);
$xt->assign("n_active_fieldcolumn",true);
$xt->assign("n_active_fieldfootercolumn",true);
$xt->assign("n_start_fieldheadercolumn",true);
$xt->assign("n_start_fieldheader",true);
$xt->assign("n_start_fieldcolumn",true);
$xt->assign("n_start_fieldfootercolumn",true);
$xt->assign("n_end_fieldheadercolumn",true);
$xt->assign("n_end_fieldheader",true);
$xt->assign("n_end_fieldcolumn",true);
$xt->assign("n_end_fieldfootercolumn",true);
$xt->assign("n_date_fieldheadercolumn",true);
$xt->assign("n_date_fieldheader",true);
$xt->assign("n_date_fieldcolumn",true);
$xt->assign("n_date_fieldfootercolumn",true);
$xt->assign("n_head_fieldheadercolumn",true);
$xt->assign("n_head_fieldheader",true);
$xt->assign("n_head_fieldcolumn",true);
$xt->assign("n_head_fieldfootercolumn",true);
$xt->assign("n_text_fieldheadercolumn",true);
$xt->assign("n_text_fieldheader",true);
$xt->assign("n_text_fieldcolumn",true);
$xt->assign("n_text_fieldfootercolumn",true);
$xt->assign("n_file_fieldheadercolumn",true);
$xt->assign("n_file_fieldheader",true);
$xt->assign("n_file_fieldcolumn",true);
$xt->assign("n_file_fieldfootercolumn",true);
$xt->assign("n_type_fieldheadercolumn",true);
$xt->assign("n_type_fieldheader",true);
$xt->assign("n_type_fieldcolumn",true);
$xt->assign("n_type_fieldfootercolumn",true);

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


$templatefile = "news_print.htm";
	
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

