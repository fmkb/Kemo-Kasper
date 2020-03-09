<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
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
$body["begin"]="<form action=\"news_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("news_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=news.xls");

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
	header("Content-Disposition: attachment;Filename=news.doc");

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
	header("Content-Disposition: attachment;Filename=news.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_active"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_active",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_start"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_start",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_end"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_end",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_date"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_date",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_head"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_head",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_text"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_text",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_file"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_file",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("n_type"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"n_type",""));
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
	header("Content-Disposition: attachment;Filename=news.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_active\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_start\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_end\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_date\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_head\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_text\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_file\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"n_type\"";
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
		$outstr.='"'.htmlspecialchars(GetData($row,"n_id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_active",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_start",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_end",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_date",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_head",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_text",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_file",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"n_type",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_active").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_start").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_end").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_date").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_head").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_text").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_file").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("n_type").'</td>';
	}
	else
	{
		echo "<td>n_id</td>";
		echo "<td>n_active</td>";
		echo "<td>n_start</td>";
		echo "<td>n_end</td>";
		echo "<td>n_date</td>";
		echo "<td>n_head</td>";
		echo "<td>n_text</td>";
		echo "<td>n_file</td>";
		echo "<td>n_type</td>";
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
			echo htmlspecialchars(GetData($row,"n_id",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"n_active",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"n_start",$format));
		else
			echo htmlspecialchars(GetData($row,"n_start",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"n_end",$format));
		else
			echo htmlspecialchars(GetData($row,"n_end",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"n_date",$format));
		else
			echo htmlspecialchars(GetData($row,"n_date",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"n_head",$format));
		else
			echo htmlspecialchars(GetData($row,"n_head",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"n_text",$format));
		else
			echo htmlspecialchars(GetData($row,"n_text",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"n_file",$format));
		else
			echo htmlspecialchars(GetData($row,"n_file",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"n_type",$format));
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
		$this->Rect($x,$this->y0,$colwidth["n_id"],$this->maxheight);
		$x+=$colwidth["n_id"];
		$this->Rect($x,$this->y0,$colwidth["n_active"],$this->maxheight);
		$x+=$colwidth["n_active"];
		$this->Rect($x,$this->y0,$colwidth["n_start"],$this->maxheight);
		$x+=$colwidth["n_start"];
		$this->Rect($x,$this->y0,$colwidth["n_end"],$this->maxheight);
		$x+=$colwidth["n_end"];
		$this->Rect($x,$this->y0,$colwidth["n_date"],$this->maxheight);
		$x+=$colwidth["n_date"];
		$this->Rect($x,$this->y0,$colwidth["n_head"],$this->maxheight);
		$x+=$colwidth["n_head"];
		$this->Rect($x,$this->y0,$colwidth["n_text"],$this->maxheight);
		$x+=$colwidth["n_text"];
		$this->Rect($x,$this->y0,$colwidth["n_file"],$this->maxheight);
		$x+=$colwidth["n_file"];
		$this->Rect($x,$this->y0,$colwidth["n_type"],$this->maxheight);
		$x+=$colwidth["n_type"];
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
		$this->Cell($colwidth["n_id"],$this->rowheight,"ID",1,0,'C',1);
		$this->Cell($colwidth["n_active"],$this->rowheight,"n_active",1,0,'C',1);
		$this->Cell($colwidth["n_start"],$this->rowheight,"Start",1,0,'C',1);
		$this->Cell($colwidth["n_end"],$this->rowheight,"End",1,0,'C',1);
		$this->Cell($colwidth["n_date"],$this->rowheight,"Date",1,0,'C',1);
		$this->Cell($colwidth["n_head"],$this->rowheight,"Headline",1,0,'C',1);
		$this->Cell($colwidth["n_text"],$this->rowheight,"Text",1,0,'C',1);
		$this->Cell($colwidth["n_file"],$this->rowheight,"File upload",1,0,'C',1);
		$this->Cell($colwidth["n_type"],$this->rowheight,"News Type",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/9;
	$colwidth=array();
    $colwidth["n_id"]=$defwidth;
    $colwidth["n_active"]=$defwidth;
    $colwidth["n_start"]=$defwidth;
    $colwidth["n_end"]=$defwidth;
    $colwidth["n_date"]=$defwidth;
    $colwidth["n_head"]=$defwidth;
    $colwidth["n_text"]=$defwidth;
    $colwidth["n_file"]=$defwidth;
    $colwidth["n_type"]=$defwidth;
	
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
		$pdf->MultiCell($colwidth["n_id"],$rowheight,GetData($row,"n_id",""));
		$x+=$colwidth["n_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_active"],$rowheight,GetData($row,"n_active",""));
		$x+=$colwidth["n_active"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_start"],$rowheight,GetData($row,"n_start","Short Date"));
		$x+=$colwidth["n_start"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_end"],$rowheight,GetData($row,"n_end","Short Date"));
		$x+=$colwidth["n_end"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_date"],$rowheight,GetData($row,"n_date","Short Date"));
		$x+=$colwidth["n_date"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_head"],$rowheight,GetData($row,"n_head",""));
		$x+=$colwidth["n_head"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_text"],$rowheight,GetData($row,"n_text",""));
		$x+=$colwidth["n_text"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_file"],$rowheight,GetData($row,"n_file",""));
		$x+=$colwidth["n_file"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["n_type"],$rowheight,GetData($row,"n_type",""));
		$x+=$colwidth["n_type"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["n_id"],$pdf->maxheight);
		$x+=$colwidth["n_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_active"],$pdf->maxheight);
		$x+=$colwidth["n_active"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_start"],$pdf->maxheight);
		$x+=$colwidth["n_start"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_end"],$pdf->maxheight);
		$x+=$colwidth["n_end"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_date"],$pdf->maxheight);
		$x+=$colwidth["n_date"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_head"],$pdf->maxheight);
		$x+=$colwidth["n_head"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_text"],$pdf->maxheight);
		$x+=$colwidth["n_text"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_file"],$pdf->maxheight);
		$x+=$colwidth["n_file"];
		$pdf->Rect($x,$pdf->y0,$colwidth["n_type"],$pdf->maxheight);
		$x+=$colwidth["n_type"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>