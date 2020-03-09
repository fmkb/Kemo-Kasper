<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Q1_variables.php");
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
			$keys["Q1_id"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["Q1_id"]=urldecode($arr[0]);
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
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["Q1_id"]));


//	Q1_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_id", ""),"field=Q1%5Fid".$keylink,"",MODE_PRINT);
			$record["Q1_id_value"]=$value;

//	Q1_work - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_work", ""),"field=Q1%5Fwork".$keylink,"",MODE_PRINT);
			$record["Q1_work_value"]=$value;

//	Q1_where - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_where", ""),"field=Q1%5Fwhere".$keylink,"",MODE_PRINT);
			$record["Q1_where_value"]=$value;

//	Q1_why - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_why", ""),"field=Q1%5Fwhy".$keylink,"",MODE_PRINT);
			$record["Q1_why_value"]=$value;

//	Q1_rating - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_rating", ""),"field=Q1%5Frating".$keylink,"",MODE_PRINT);
			$record["Q1_rating_value"]=$value;

//	Q1_speakothers - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_speakothers", ""),"field=Q1%5Fspeakothers".$keylink,"",MODE_PRINT);
			$record["Q1_speakothers_value"]=$value;

//	Q1_changedme - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_changedme", ""),"field=Q1%5Fchangedme".$keylink,"",MODE_PRINT);
			$record["Q1_changedme_value"]=$value;

//	Q1_playoften - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_playoften", ""),"field=Q1%5Fplayoften".$keylink,"",MODE_PRINT);
			$record["Q1_playoften_value"]=$value;

//	Q1_infolevel - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_infolevel", ""),"field=Q1%5Finfolevel".$keylink,"",MODE_PRINT);
			$record["Q1_infolevel_value"]=$value;

//	Q1_SNotice - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNotice", ""),"field=Q1%5FSNotice".$keylink,"",MODE_PRINT);
			$record["Q1_SNotice_value"]=$value;

//	Q1_SNoticewhere - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticewhere", ""),"field=Q1%5FSNoticewhere".$keylink,"",MODE_PRINT);
			$record["Q1_SNoticewhere_value"]=$value;

//	Q1_SNoticemainsponsor - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticemainsponsor", ""),"field=Q1%5FSNoticemainsponsor".$keylink,"",MODE_PRINT);
			$record["Q1_SNoticemainsponsor_value"]=$value;

//	Q1_SNoticeteam - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticeteam", ""),"field=Q1%5FSNoticeteam".$keylink,"",MODE_PRINT);
			$record["Q1_SNoticeteam_value"]=$value;

//	Q1_SNoticeother - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_SNoticeother", ""),"field=Q1%5FSNoticeother".$keylink,"",MODE_PRINT);
			$record["Q1_SNoticeother_value"]=$value;

//	Q1_Shavechangedview - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_Shavechangedview", ""),"field=Q1%5FShavechangedview".$keylink,"",MODE_PRINT);
			$record["Q1_Shavechangedview_value"]=$value;

//	Q1_Sbuyproducts - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_Sbuyproducts", ""),"field=Q1%5FSbuyproducts".$keylink,"",MODE_PRINT);
			$record["Q1_Sbuyproducts_value"]=$value;

//	Q1_Snameothers - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_Snameothers", ""),"field=Q1%5FSnameothers".$keylink,"",MODE_PRINT);
			$record["Q1_Snameothers_value"]=$value;

//	Q1_Comments - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_Comments", ""),"field=Q1%5FComments".$keylink,"",MODE_PRINT);
			$record["Q1_Comments_value"]=$value;

//	Q1_p_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Q1_p_id", ""),"field=Q1%5Fp%5Fid".$keylink,"",MODE_PRINT);
			$record["Q1_p_id_value"]=$value;
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

$xt->assign("Q1_id_fieldheadercolumn",true);
$xt->assign("Q1_id_fieldheader",true);
$xt->assign("Q1_id_fieldcolumn",true);
$xt->assign("Q1_id_fieldfootercolumn",true);
$xt->assign("Q1_work_fieldheadercolumn",true);
$xt->assign("Q1_work_fieldheader",true);
$xt->assign("Q1_work_fieldcolumn",true);
$xt->assign("Q1_work_fieldfootercolumn",true);
$xt->assign("Q1_where_fieldheadercolumn",true);
$xt->assign("Q1_where_fieldheader",true);
$xt->assign("Q1_where_fieldcolumn",true);
$xt->assign("Q1_where_fieldfootercolumn",true);
$xt->assign("Q1_why_fieldheadercolumn",true);
$xt->assign("Q1_why_fieldheader",true);
$xt->assign("Q1_why_fieldcolumn",true);
$xt->assign("Q1_why_fieldfootercolumn",true);
$xt->assign("Q1_rating_fieldheadercolumn",true);
$xt->assign("Q1_rating_fieldheader",true);
$xt->assign("Q1_rating_fieldcolumn",true);
$xt->assign("Q1_rating_fieldfootercolumn",true);
$xt->assign("Q1_speakothers_fieldheadercolumn",true);
$xt->assign("Q1_speakothers_fieldheader",true);
$xt->assign("Q1_speakothers_fieldcolumn",true);
$xt->assign("Q1_speakothers_fieldfootercolumn",true);
$xt->assign("Q1_changedme_fieldheadercolumn",true);
$xt->assign("Q1_changedme_fieldheader",true);
$xt->assign("Q1_changedme_fieldcolumn",true);
$xt->assign("Q1_changedme_fieldfootercolumn",true);
$xt->assign("Q1_playoften_fieldheadercolumn",true);
$xt->assign("Q1_playoften_fieldheader",true);
$xt->assign("Q1_playoften_fieldcolumn",true);
$xt->assign("Q1_playoften_fieldfootercolumn",true);
$xt->assign("Q1_infolevel_fieldheadercolumn",true);
$xt->assign("Q1_infolevel_fieldheader",true);
$xt->assign("Q1_infolevel_fieldcolumn",true);
$xt->assign("Q1_infolevel_fieldfootercolumn",true);
$xt->assign("Q1_SNotice_fieldheadercolumn",true);
$xt->assign("Q1_SNotice_fieldheader",true);
$xt->assign("Q1_SNotice_fieldcolumn",true);
$xt->assign("Q1_SNotice_fieldfootercolumn",true);
$xt->assign("Q1_SNoticewhere_fieldheadercolumn",true);
$xt->assign("Q1_SNoticewhere_fieldheader",true);
$xt->assign("Q1_SNoticewhere_fieldcolumn",true);
$xt->assign("Q1_SNoticewhere_fieldfootercolumn",true);
$xt->assign("Q1_SNoticemainsponsor_fieldheadercolumn",true);
$xt->assign("Q1_SNoticemainsponsor_fieldheader",true);
$xt->assign("Q1_SNoticemainsponsor_fieldcolumn",true);
$xt->assign("Q1_SNoticemainsponsor_fieldfootercolumn",true);
$xt->assign("Q1_SNoticeteam_fieldheadercolumn",true);
$xt->assign("Q1_SNoticeteam_fieldheader",true);
$xt->assign("Q1_SNoticeteam_fieldcolumn",true);
$xt->assign("Q1_SNoticeteam_fieldfootercolumn",true);
$xt->assign("Q1_SNoticeother_fieldheadercolumn",true);
$xt->assign("Q1_SNoticeother_fieldheader",true);
$xt->assign("Q1_SNoticeother_fieldcolumn",true);
$xt->assign("Q1_SNoticeother_fieldfootercolumn",true);
$xt->assign("Q1_Shavechangedview_fieldheadercolumn",true);
$xt->assign("Q1_Shavechangedview_fieldheader",true);
$xt->assign("Q1_Shavechangedview_fieldcolumn",true);
$xt->assign("Q1_Shavechangedview_fieldfootercolumn",true);
$xt->assign("Q1_Sbuyproducts_fieldheadercolumn",true);
$xt->assign("Q1_Sbuyproducts_fieldheader",true);
$xt->assign("Q1_Sbuyproducts_fieldcolumn",true);
$xt->assign("Q1_Sbuyproducts_fieldfootercolumn",true);
$xt->assign("Q1_Snameothers_fieldheadercolumn",true);
$xt->assign("Q1_Snameothers_fieldheader",true);
$xt->assign("Q1_Snameothers_fieldcolumn",true);
$xt->assign("Q1_Snameothers_fieldfootercolumn",true);
$xt->assign("Q1_Comments_fieldheadercolumn",true);
$xt->assign("Q1_Comments_fieldheader",true);
$xt->assign("Q1_Comments_fieldcolumn",true);
$xt->assign("Q1_Comments_fieldfootercolumn",true);
$xt->assign("Q1_p_id_fieldheadercolumn",true);
$xt->assign("Q1_p_id_fieldheader",true);
$xt->assign("Q1_p_id_fieldcolumn",true);
$xt->assign("Q1_p_id_fieldfootercolumn",true);

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


$templatefile = "Q1_print.htm";
	
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

