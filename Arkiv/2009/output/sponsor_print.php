<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/sponsor_variables.php");
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
			$keys["s_id"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["s_id"]=urldecode($arr[0]);
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
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["s_id"]));


//	s_active - 
			$value="";
				$value=DisplayLookupWizard("s_active",$data["s_active"],$data,$keylink,MODE_PRINT);
			$record["s_active_value"]=$value;

//	s_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_name", ""),"field=s%5Fname".$keylink,"",MODE_PRINT);
			$record["s_name_value"]=$value;

//	s_contact - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_contact", ""),"field=s%5Fcontact".$keylink,"",MODE_PRINT);
			$record["s_contact_value"]=$value;

//	s_adr - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_adr", ""),"field=s%5Fadr".$keylink,"",MODE_PRINT);
			$record["s_adr_value"]=$value;

//	s_zip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_zip", ""),"field=s%5Fzip".$keylink,"",MODE_PRINT);
			$record["s_zip_value"]=$value;

//	s_country - 
			$value="";
				$value=DisplayLookupWizard("s_country",$data["s_country"],$data,$keylink,MODE_PRINT);
			$record["s_country_value"]=$value;

//	s_phone1 - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_phone1", ""),"field=s%5Fphone1".$keylink,"",MODE_PRINT);
			$record["s_phone1_value"]=$value;

//	s_phone2 - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_phone2", ""),"field=s%5Fphone2".$keylink,"",MODE_PRINT);
			$record["s_phone2_value"]=$value;

//	s_total - Number
			$value="";
				$value = ProcessLargeText(GetData($data,"s_total", "Number"),"field=s%5Ftotal".$keylink,"",MODE_PRINT);
			$record["s_total_value"]=$value;

//	s_paid - Number
			$value="";
				$value = ProcessLargeText(GetData($data,"s_paid", "Number"),"field=s%5Fpaid".$keylink,"",MODE_PRINT);
			$record["s_paid_value"]=$value;

//	s_logo - File-based Image
			$value="";
				if(CheckImageExtension($data["s_logo"])) 
			{
						$value="<img";
										$value.=" border=0";
				$value.=" src=\"".htmlspecialchars(AddLinkPrefix("s_logo",$data["s_logo"]))."\">";
			}
			$record["s_logo_value"]=$value;

//	s_banner - File-based Image
			$value="";
				if(CheckImageExtension($data["s_banner"])) 
			{
						$value="<img";
										$value.=" border=0";
				$value.=" src=\"".htmlspecialchars(AddLinkPrefix("s_banner",$data["s_banner"]))."\">";
			}
			$record["s_banner_value"]=$value;

//	s_www - Hyperlink
			$value="";
				$value = GetData($data,"s_www", "Hyperlink");
			$record["s_www_value"]=$value;

//	s_mail - Email Hyperlink
			$value="";
				$value = GetData($data,"s_mail", "Email Hyperlink");
			$record["s_mail_value"]=$value;

//	s_cmt - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_cmt", ""),"field=s%5Fcmt".$keylink,"",MODE_PRINT);
			$record["s_cmt_value"]=$value;
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

$xt->assign("s_active_fieldheadercolumn",true);
$xt->assign("s_active_fieldheader",true);
$xt->assign("s_active_fieldcolumn",true);
$xt->assign("s_active_fieldfootercolumn",true);
$xt->assign("s_name_fieldheadercolumn",true);
$xt->assign("s_name_fieldheader",true);
$xt->assign("s_name_fieldcolumn",true);
$xt->assign("s_name_fieldfootercolumn",true);
$xt->assign("s_contact_fieldheadercolumn",true);
$xt->assign("s_contact_fieldheader",true);
$xt->assign("s_contact_fieldcolumn",true);
$xt->assign("s_contact_fieldfootercolumn",true);
$xt->assign("s_adr_fieldheadercolumn",true);
$xt->assign("s_adr_fieldheader",true);
$xt->assign("s_adr_fieldcolumn",true);
$xt->assign("s_adr_fieldfootercolumn",true);
$xt->assign("s_zip_fieldheadercolumn",true);
$xt->assign("s_zip_fieldheader",true);
$xt->assign("s_zip_fieldcolumn",true);
$xt->assign("s_zip_fieldfootercolumn",true);
$xt->assign("s_country_fieldheadercolumn",true);
$xt->assign("s_country_fieldheader",true);
$xt->assign("s_country_fieldcolumn",true);
$xt->assign("s_country_fieldfootercolumn",true);
$xt->assign("s_phone1_fieldheadercolumn",true);
$xt->assign("s_phone1_fieldheader",true);
$xt->assign("s_phone1_fieldcolumn",true);
$xt->assign("s_phone1_fieldfootercolumn",true);
$xt->assign("s_phone2_fieldheadercolumn",true);
$xt->assign("s_phone2_fieldheader",true);
$xt->assign("s_phone2_fieldcolumn",true);
$xt->assign("s_phone2_fieldfootercolumn",true);
$xt->assign("s_total_fieldheadercolumn",true);
$xt->assign("s_total_fieldheader",true);
$xt->assign("s_total_fieldcolumn",true);
$xt->assign("s_total_fieldfootercolumn",true);
$xt->assign("s_paid_fieldheadercolumn",true);
$xt->assign("s_paid_fieldheader",true);
$xt->assign("s_paid_fieldcolumn",true);
$xt->assign("s_paid_fieldfootercolumn",true);
$xt->assign("s_logo_fieldheadercolumn",true);
$xt->assign("s_logo_fieldheader",true);
$xt->assign("s_logo_fieldcolumn",true);
$xt->assign("s_logo_fieldfootercolumn",true);
$xt->assign("s_banner_fieldheadercolumn",true);
$xt->assign("s_banner_fieldheader",true);
$xt->assign("s_banner_fieldcolumn",true);
$xt->assign("s_banner_fieldfootercolumn",true);
$xt->assign("s_www_fieldheadercolumn",true);
$xt->assign("s_www_fieldheader",true);
$xt->assign("s_www_fieldcolumn",true);
$xt->assign("s_www_fieldfootercolumn",true);
$xt->assign("s_mail_fieldheadercolumn",true);
$xt->assign("s_mail_fieldheader",true);
$xt->assign("s_mail_fieldcolumn",true);
$xt->assign("s_mail_fieldfootercolumn",true);
$xt->assign("s_cmt_fieldheadercolumn",true);
$xt->assign("s_cmt_fieldheader",true);
$xt->assign("s_cmt_fieldcolumn",true);
$xt->assign("s_cmt_fieldfootercolumn",true);

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


$templatefile = "sponsor_print.htm";
	
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

