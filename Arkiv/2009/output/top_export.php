<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
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
$body["begin"]="<form action=\"top_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("top_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=top.xls");

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
	header("Content-Disposition: attachment;Filename=top.doc");

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
	header("Content-Disposition: attachment;Filename=top.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"t_id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_user"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"t_user",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_score"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"t_score",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_datetime"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"t_datetime",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_ip"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"t_ip",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_p_id"));
		echo "<".$field.">";
/*		
		if(strlen($row["t_p_id"]))
		{
			$strdata = make_db_value("t_p_id",$row["t_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["t_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"t_p_id", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("t_p_id",$row["t_p_id"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_ts_id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"t_ts_id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("t_kills"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"t_kills",""));
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
	header("Content-Disposition: attachment;Filename=top.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_user\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_score\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_datetime\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_ip\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_p_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_ts_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"t_kills\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"t_id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"t_user",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"t_score",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"t_datetime",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"t_ip",$format)).'"';
		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["t_p_id"]))
		{
			$strdata = make_db_value("t_p_id",$row["t_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["t_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"t_p_id", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("t_p_id",$row["t_p_id"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"t_ts_id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"t_kills",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_user").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_score").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_datetime").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_ip").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_p_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_ts_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("t_kills").'</td>';
	}
	else
	{
		echo "<td>t_id</td>";
		echo "<td>t_user</td>";
		echo "<td>t_score</td>";
		echo "<td>t_datetime</td>";
		echo "<td>t_ip</td>";
		echo "<td>t_p_id</td>";
		echo "<td>t_ts_id</td>";
		echo "<td>t_kills</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"t_id",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"t_user",$format));
		else
			echo htmlspecialchars(GetData($row,"t_user",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"t_score",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"t_datetime",$format));
		else
			echo htmlspecialchars(GetData($row,"t_datetime",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"t_ip",$format));
		else
			echo htmlspecialchars(GetData($row,"t_ip",$format));
	echo '</td>';
	echo '<td>';
		if(strlen($row["t_p_id"]))
		{
/*
			$strdata = make_db_value("t_p_id",$row["t_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["t_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"t_p_id", "");
*/			
			$strValue = DisplayLookupWizard("t_p_id",$row["t_p_id"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"t_ts_id",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"t_kills",$format));
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
		$this->Rect($x,$this->y0,$colwidth["t_id"],$this->maxheight);
		$x+=$colwidth["t_id"];
		$this->Rect($x,$this->y0,$colwidth["t_user"],$this->maxheight);
		$x+=$colwidth["t_user"];
		$this->Rect($x,$this->y0,$colwidth["t_score"],$this->maxheight);
		$x+=$colwidth["t_score"];
		$this->Rect($x,$this->y0,$colwidth["t_datetime"],$this->maxheight);
		$x+=$colwidth["t_datetime"];
		$this->Rect($x,$this->y0,$colwidth["t_ip"],$this->maxheight);
		$x+=$colwidth["t_ip"];
		$this->Rect($x,$this->y0,$colwidth["t_p_id"],$this->maxheight);
		$x+=$colwidth["t_p_id"];
		$this->Rect($x,$this->y0,$colwidth["t_ts_id"],$this->maxheight);
		$x+=$colwidth["t_ts_id"];
		$this->Rect($x,$this->y0,$colwidth["t_kills"],$this->maxheight);
		$x+=$colwidth["t_kills"];
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
		$this->Cell($colwidth["t_id"],$this->rowheight,"ID",1,0,'C',1);
		$this->Cell($colwidth["t_user"],$this->rowheight,"User",1,0,'C',1);
		$this->Cell($colwidth["t_score"],$this->rowheight,"Score",1,0,'C',1);
		$this->Cell($colwidth["t_datetime"],$this->rowheight,"Datetime",1,0,'C',1);
		$this->Cell($colwidth["t_ip"],$this->rowheight,"IP",1,0,'C',1);
		$this->Cell($colwidth["t_p_id"],$this->rowheight,"Player",1,0,'C',1);
		$this->Cell($colwidth["t_ts_id"],$this->rowheight,"Team",1,0,'C',1);
		$this->Cell($colwidth["t_kills"],$this->rowheight,"Kills",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/8;
	$colwidth=array();
    $colwidth["t_id"]=$defwidth;
    $colwidth["t_user"]=$defwidth;
    $colwidth["t_score"]=$defwidth;
    $colwidth["t_datetime"]=$defwidth;
    $colwidth["t_ip"]=$defwidth;
    $colwidth["t_p_id"]=$defwidth;
    $colwidth["t_ts_id"]=$defwidth;
    $colwidth["t_kills"]=$defwidth;
	
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
		$pdf->MultiCell($colwidth["t_id"],$rowheight,GetData($row,"t_id",""));
		$x+=$colwidth["t_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["t_user"],$rowheight,GetData($row,"t_user",""));
		$x+=$colwidth["t_user"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["t_score"],$rowheight,GetData($row,"t_score",""));
		$x+=$colwidth["t_score"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["t_datetime"],$rowheight,GetData($row,"t_datetime","Short Date"));
		$x+=$colwidth["t_datetime"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["t_ip"],$rowheight,GetData($row,"t_ip",""));
		$x+=$colwidth["t_ip"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["t_p_id"]))
		{
/*			$strdata = make_db_value("t_p_id",$row["t_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["t_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["t_p_id"],$rowheight,GetDataInt($lookupvalue,$row,"t_p_id", ""));
*/				
				
			$value = DisplayLookupWizard("t_p_id",$row["t_p_id"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["t_p_id"],$rowheight,$value);
		}
		$x+=$colwidth["t_p_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["t_ts_id"],$rowheight,GetData($row,"t_ts_id",""));
		$x+=$colwidth["t_ts_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["t_kills"],$rowheight,GetData($row,"t_kills",""));
		$x+=$colwidth["t_kills"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["t_id"],$pdf->maxheight);
		$x+=$colwidth["t_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["t_user"],$pdf->maxheight);
		$x+=$colwidth["t_user"];
		$pdf->Rect($x,$pdf->y0,$colwidth["t_score"],$pdf->maxheight);
		$x+=$colwidth["t_score"];
		$pdf->Rect($x,$pdf->y0,$colwidth["t_datetime"],$pdf->maxheight);
		$x+=$colwidth["t_datetime"];
		$pdf->Rect($x,$pdf->y0,$colwidth["t_ip"],$pdf->maxheight);
		$x+=$colwidth["t_ip"];
		$pdf->Rect($x,$pdf->y0,$colwidth["t_p_id"],$pdf->maxheight);
		$x+=$colwidth["t_p_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["t_ts_id"],$pdf->maxheight);
		$x+=$colwidth["t_ts_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["t_kills"],$pdf->maxheight);
		$x+=$colwidth["t_kills"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>