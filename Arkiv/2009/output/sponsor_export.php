<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
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

$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessExport"))
	BeforeProcessExport($conn);

$strWhereClause="";

$options = "1";
if (@$_REQUEST["a"]!="") 
{
	$options = "";
	
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


	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
	
	$_SESSION[$strTableName."_SelectedSQL"] = $strSQL;
	$_SESSION[$strTableName."_SelectedWhere"] = $sWhere;
}

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


$mypage=1;
if(@$_REQUEST["type"])
{
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);

	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryExport"))
		BeforeQueryExport($strSQL,$strWhereClause,$strOrderBy);
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

	if(!ini_get("safe_mode"))
		set_time_limit(300);
	
	if(@$_REQUEST["type"]=="excel")
		ExportToExcel();
	else if(@$_REQUEST["type"]=="word")
		ExportToWord();
	else if(@$_REQUEST["type"]=="xml")
		ExportToXML();
	else if(@$_REQUEST["type"]=="csv")
		ExportToCSV();
	else if(@$_REQUEST["type"]=="pdf")
		ExportToPDF();

	db_close($conn);
	return;
}

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

include('libs/xtempl.php');
$xt = new Xtempl();
if($options)
{
	$xt->assign("rangeheader_block",true);
	$xt->assign("range_block",true);
}
$body=array();
$body["begin"]="<form action=\"sponsor_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("sponsor_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=sponsor.xls");

	echo "<html>";
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";
	
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToWord()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-word");
	header("Content-Disposition: attachment;Filename=sponsor.doc");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToXML()
{
	global $nPageSize,$rs,$strTableName,$conn;
	header("Content-type: text/xml");
	header("Content-Disposition: attachment;Filename=sponsor.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_active"));
		echo "<".$field.">";
/*		
		if(strlen($row["s_active"]))
		{
			$strdata = make_db_value("s_active",$row["s_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["s_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"s_active", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("s_active",$row["s_active"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_name"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_name",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_contact"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_contact",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_adr"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_adr",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_zip"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_zip",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_country"));
		echo "<".$field.">";
/*		
		if(strlen($row["s_country"]))
		{
			$strdata = make_db_value("s_country",$row["s_country"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xc_name`";
			$LookupSQL.=" FROM `xcountries` WHERE `xc_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["s_country"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"s_country", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("s_country",$row["s_country"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_phone1"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_phone1",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_phone2"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_phone2",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_total"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_total",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_paid"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_paid",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_logo"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_logo",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_banner"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_banner",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_www"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_www",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_mail"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_mail",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("s_cmt"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"s_cmt",""));
		echo "</".$field.">\r\n";
		echo "</row>\r\n";
		$i++;
		$row=db_fetch_array($rs);
	}
	echo "</table>\r\n";
}

function ExportToCSV()
{
	global $rs,$nPageSize,$strTableName,$conn;
	header("Content-type: application/csv");
	header("Content-Disposition: attachment;Filename=sponsor.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_active\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_name\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_contact\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_adr\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_zip\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_country\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_phone1\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_phone2\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_total\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_paid\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_logo\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_banner\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_www\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_mail\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"s_cmt\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["s_active"]))
		{
			$strdata = make_db_value("s_active",$row["s_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["s_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"s_active", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("s_active",$row["s_active"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_name",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_contact",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_adr",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_zip",$format)).'"';
		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["s_country"]))
		{
			$strdata = make_db_value("s_country",$row["s_country"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xc_name`";
			$LookupSQL.=" FROM `xcountries` WHERE `xc_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["s_country"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"s_country", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("s_country",$row["s_country"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_phone1",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_phone2",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Number";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_total",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Number";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_paid",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"s_logo",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"s_banner",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"s_www",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"s_mail",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"s_cmt",$format)).'"';
		echo $outstr;
		echo "\r\n";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

//	display totals
	$first=true;

}


function WriteTableData()
{
	global $rs,$nPageSize,$strTableName,$conn;
	if(!($row=db_fetch_array($rs)))
		return;
// write header
	echo "<tr>";
	if($_REQUEST["type"]=="excel")
	{
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_active").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_name").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_contact").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_adr").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_zip").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_country").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_phone1").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_phone2").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_total").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_paid").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_logo").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_banner").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_www").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_mail").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("s_cmt").'</td>';
	}
	else
	{
		echo "<td>s_active</td>";
		echo "<td>s_name</td>";
		echo "<td>s_contact</td>";
		echo "<td>s_adr</td>";
		echo "<td>s_zip</td>";
		echo "<td>s_country</td>";
		echo "<td>s_phone1</td>";
		echo "<td>s_phone2</td>";
		echo "<td>s_total</td>";
		echo "<td>s_paid</td>";
		echo "<td>s_logo</td>";
		echo "<td>s_banner</td>";
		echo "<td>s_www</td>";
		echo "<td>s_mail</td>";
		echo "<td>s_cmt</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	echo '<td>';
		if(strlen($row["s_active"]))
		{
/*
			$strdata = make_db_value("s_active",$row["s_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["s_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"s_active", "");
*/			
			$strValue = DisplayLookupWizard("s_active",$row["s_active"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_name",$format));
		else
			echo htmlspecialchars(GetData($row,"s_name",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_contact",$format));
		else
			echo htmlspecialchars(GetData($row,"s_contact",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_adr",$format));
		else
			echo htmlspecialchars(GetData($row,"s_adr",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"s_zip",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["s_country"]))
		{
/*
			$strdata = make_db_value("s_country",$row["s_country"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xc_name`";
			$LookupSQL.=" FROM `xcountries` WHERE `xc_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["s_country"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"s_country", "");
*/			
			$strValue = DisplayLookupWizard("s_country",$row["s_country"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_phone1",$format));
		else
			echo htmlspecialchars(GetData($row,"s_phone1",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_phone2",$format));
		else
			echo htmlspecialchars(GetData($row,"s_phone2",$format));
	echo '</td>';
	echo '<td>';

		$format="Number";
			echo htmlspecialchars(GetData($row,"s_total",$format));
	echo '</td>';
	echo '<td>';

		$format="Number";
			echo htmlspecialchars(GetData($row,"s_paid",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format=FORMAT_NONE;
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_logo",$format));
		else
			echo htmlspecialchars(GetData($row,"s_logo",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format=FORMAT_NONE;
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_banner",$format));
		else
			echo htmlspecialchars(GetData($row,"s_banner",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format=FORMAT_NONE;
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_www",$format));
		else
			echo htmlspecialchars(GetData($row,"s_www",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format=FORMAT_NONE;
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_mail",$format));
		else
			echo htmlspecialchars(GetData($row,"s_mail",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"s_cmt",$format));
		else
			echo htmlspecialchars(GetData($row,"s_cmt",$format));
	echo '</td>';
		echo "</tr>";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

}

function XMLNameEncode($strValue)
{	
	$search=array(" ","#","'","/","\\","(",")",",","[","]","+","\"","-","_","|","}","{","=");
	return str_replace($search,"",$strValue);
}

function PrepareForExcel($str)
{
	$ret = htmlspecialchars($str);
	if (substr($ret,0,1)== "=") 
		$ret = "&#61;".substr($ret,1);
	return $ret;

}




function ExportToPDF()
{
	global $nPageSize,$rs,$strTableName,$conn;
		global $colwidth,$leftmargin;
	if(!($row=db_fetch_array($rs)))
		return;


	include("libs/fpdf.php");

	class PDF extends FPDF
	{
	//Current column
		var $col=0;
	//Ordinate of column start
		var $y0;
		var $maxheight;

	function AcceptPageBreak()
	{
		global $colwidth,$leftmargin;
		if($this->y0+$this->rowheight>$this->PageBreakTrigger)
			return true;
		$x=$leftmargin;
		if($this->maxheight<$this->PageBreakTrigger-$this->y0)
			$this->maxheight=$this->PageBreakTrigger-$this->y0;
		$this->Rect($x,$this->y0,$colwidth["s_active"],$this->maxheight);
		$x+=$colwidth["s_active"];
		$this->Rect($x,$this->y0,$colwidth["s_name"],$this->maxheight);
		$x+=$colwidth["s_name"];
		$this->Rect($x,$this->y0,$colwidth["s_contact"],$this->maxheight);
		$x+=$colwidth["s_contact"];
		$this->Rect($x,$this->y0,$colwidth["s_adr"],$this->maxheight);
		$x+=$colwidth["s_adr"];
		$this->Rect($x,$this->y0,$colwidth["s_zip"],$this->maxheight);
		$x+=$colwidth["s_zip"];
		$this->Rect($x,$this->y0,$colwidth["s_country"],$this->maxheight);
		$x+=$colwidth["s_country"];
		$this->Rect($x,$this->y0,$colwidth["s_phone1"],$this->maxheight);
		$x+=$colwidth["s_phone1"];
		$this->Rect($x,$this->y0,$colwidth["s_phone2"],$this->maxheight);
		$x+=$colwidth["s_phone2"];
		$this->Rect($x,$this->y0,$colwidth["s_total"],$this->maxheight);
		$x+=$colwidth["s_total"];
		$this->Rect($x,$this->y0,$colwidth["s_paid"],$this->maxheight);
		$x+=$colwidth["s_paid"];
		$this->Rect($x,$this->y0,$colwidth["s_logo"],$this->maxheight);
		$x+=$colwidth["s_logo"];
		$this->Rect($x,$this->y0,$colwidth["s_banner"],$this->maxheight);
		$x+=$colwidth["s_banner"];
		$this->Rect($x,$this->y0,$colwidth["s_www"],$this->maxheight);
		$x+=$colwidth["s_www"];
		$this->Rect($x,$this->y0,$colwidth["s_mail"],$this->maxheight);
		$x+=$colwidth["s_mail"];
		$this->Rect($x,$this->y0,$colwidth["s_cmt"],$this->maxheight);
		$x+=$colwidth["s_cmt"];
		$this->maxheight = $this->rowheight;
//	draw frame	
		return true;
	}

	function Header()
	{
		global $colwidth,$leftmargin;
	    //Page header
		$this->SetFillColor(192);
		$this->SetX($leftmargin);
		$this->Cell($colwidth["s_active"],$this->rowheight,"Status",1,0,'C',1);
		$this->Cell($colwidth["s_name"],$this->rowheight,"Name",1,0,'C',1);
		$this->Cell($colwidth["s_contact"],$this->rowheight,"Contact",1,0,'C',1);
		$this->Cell($colwidth["s_adr"],$this->rowheight,"Address",1,0,'C',1);
		$this->Cell($colwidth["s_zip"],$this->rowheight,"Zip",1,0,'C',1);
		$this->Cell($colwidth["s_country"],$this->rowheight,"Country",1,0,'C',1);
		$this->Cell($colwidth["s_phone1"],$this->rowheight,"Phone 1",1,0,'C',1);
		$this->Cell($colwidth["s_phone2"],$this->rowheight,"Phone 2",1,0,'C',1);
		$this->Cell($colwidth["s_total"],$this->rowheight,"Amount Sponsored",1,0,'C',1);
		$this->Cell($colwidth["s_paid"],$this->rowheight,"Amount Paid",1,0,'C',1);
		$this->Cell($colwidth["s_logo"],$this->rowheight,"Banner Ad 468x60",1,0,'C',1);
		$this->Cell($colwidth["s_banner"],$this->rowheight,"Logo",1,0,'C',1);
		$this->Cell($colwidth["s_www"],$this->rowheight,"WWW",1,0,'C',1);
		$this->Cell($colwidth["s_mail"],$this->rowheight,"Email",1,0,'C',1);
		$this->Cell($colwidth["s_cmt"],$this->rowheight,"Comment",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/15;
	$colwidth=array();
    $colwidth["s_active"]=$defwidth;
    $colwidth["s_name"]=$defwidth;
    $colwidth["s_contact"]=$defwidth;
    $colwidth["s_adr"]=$defwidth;
    $colwidth["s_zip"]=$defwidth;
    $colwidth["s_country"]=$defwidth;
    $colwidth["s_phone1"]=$defwidth;
    $colwidth["s_phone2"]=$defwidth;
    $colwidth["s_total"]=$defwidth;
    $colwidth["s_paid"]=$defwidth;
    $colwidth["s_logo"]=$defwidth;
    $colwidth["s_banner"]=$defwidth;
    $colwidth["s_www"]=$defwidth;
    $colwidth["s_mail"]=$defwidth;
    $colwidth["s_cmt"]=$defwidth;
	
	$pdf->AddFont('CourierNewPSMT','','courcp1252.php');
	$pdf->rowheight=$rowheight;
	
	$pdf->SetFont('CourierNewPSMT','',8);
	$pdf->AddPage();
	

	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		$pdf->maxheight=$rowheight;
		$x=$leftmargin;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["s_active"]))
		{
/*			$strdata = make_db_value("s_active",$row["s_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["s_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["s_active"],$rowheight,GetDataInt($lookupvalue,$row,"s_active", ""));
*/				
				
			$value = DisplayLookupWizard("s_active",$row["s_active"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["s_active"],$rowheight,$value);
		}
		$x+=$colwidth["s_active"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_name"],$rowheight,GetData($row,"s_name",""));
		$x+=$colwidth["s_name"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_contact"],$rowheight,GetData($row,"s_contact",""));
		$x+=$colwidth["s_contact"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_adr"],$rowheight,GetData($row,"s_adr",""));
		$x+=$colwidth["s_adr"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_zip"],$rowheight,GetData($row,"s_zip",""));
		$x+=$colwidth["s_zip"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["s_country"]))
		{
/*			$strdata = make_db_value("s_country",$row["s_country"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xc_name`";
			$LookupSQL.=" FROM `xcountries` WHERE `xc_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["s_country"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["s_country"],$rowheight,GetDataInt($lookupvalue,$row,"s_country", ""));
*/				
				
			$value = DisplayLookupWizard("s_country",$row["s_country"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["s_country"],$rowheight,$value);
		}
		$x+=$colwidth["s_country"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_phone1"],$rowheight,GetData($row,"s_phone1",""));
		$x+=$colwidth["s_phone1"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_phone2"],$rowheight,GetData($row,"s_phone2",""));
		$x+=$colwidth["s_phone2"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_total"],$rowheight,GetData($row,"s_total","Number"));
		$x+=$colwidth["s_total"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_paid"],$rowheight,GetData($row,"s_paid","Number"));
		$x+=$colwidth["s_paid"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$height=0;
		$pdf->Image(AddLinkPrefix("s_logo",$row["s_logo"]),$pdf->GetX()+1,$pdf->GetY()+1,$colwidth["s_logo"]-2,$height);
		$pdf->SetX($pdf->GetX()+$colwidth["s_logo"]);
		$pdf->SetY($pdf->y0+$height+2);
		$x+=$colwidth["s_logo"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$height=0;
		$pdf->Image(AddLinkPrefix("s_banner",$row["s_banner"]),$pdf->GetX()+1,$pdf->GetY()+1,$colwidth["s_banner"]-2,$height);
		$pdf->SetX($pdf->GetX()+$colwidth["s_banner"]);
		$pdf->SetY($pdf->y0+$height+2);
		$x+=$colwidth["s_banner"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_www"],$rowheight,GetData($row,"s_www","Hyperlink"));
		$x+=$colwidth["s_www"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_mail"],$rowheight,GetData($row,"s_mail","Email Hyperlink"));
		$x+=$colwidth["s_mail"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["s_cmt"],$rowheight,GetData($row,"s_cmt",""));
		$x+=$colwidth["s_cmt"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["s_active"],$pdf->maxheight);
		$x+=$colwidth["s_active"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_name"],$pdf->maxheight);
		$x+=$colwidth["s_name"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_contact"],$pdf->maxheight);
		$x+=$colwidth["s_contact"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_adr"],$pdf->maxheight);
		$x+=$colwidth["s_adr"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_zip"],$pdf->maxheight);
		$x+=$colwidth["s_zip"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_country"],$pdf->maxheight);
		$x+=$colwidth["s_country"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_phone1"],$pdf->maxheight);
		$x+=$colwidth["s_phone1"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_phone2"],$pdf->maxheight);
		$x+=$colwidth["s_phone2"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_total"],$pdf->maxheight);
		$x+=$colwidth["s_total"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_paid"],$pdf->maxheight);
		$x+=$colwidth["s_paid"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_logo"],$pdf->maxheight);
		$x+=$colwidth["s_logo"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_banner"],$pdf->maxheight);
		$x+=$colwidth["s_banner"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_www"],$pdf->maxheight);
		$x+=$colwidth["s_www"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_mail"],$pdf->maxheight);
		$x+=$colwidth["s_mail"];
		$pdf->Rect($x,$pdf->y0,$colwidth["s_cmt"],$pdf->maxheight);
		$x+=$colwidth["s_cmt"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>