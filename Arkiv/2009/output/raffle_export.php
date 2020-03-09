<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/raffle_variables.php");
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
			$keys["r_id"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["r_id"]=urldecode($arr[0]);
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
$body["begin"]="<form action=\"raffle_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("raffle_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=raffle.xls");

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
	header("Content-Disposition: attachment;Filename=raffle.doc");

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
	header("Content-Disposition: attachment;Filename=raffle.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("r_id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"r_id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("r_name"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"r_name",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("r_img"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"r_img",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("r_text"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"r_text",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("r_date"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"r_date",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("r_p_id"));
		echo "<".$field.">";
/*		
		if(strlen($row["r_p_id"]))
		{
			$strdata = make_db_value("r_p_id",$row["r_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["r_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"r_p_id", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("r_p_id",$row["r_p_id"],$row,"",MODE_EXPORT));
		
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
	header("Content-Disposition: attachment;Filename=raffle.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"r_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"r_name\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"r_img\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"r_text\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"r_date\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"r_p_id\"";
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
		$outstr.='"'.htmlspecialchars(GetData($row,"r_id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"r_name",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"r_img",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"r_text",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"r_date",$format)).'"';
		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["r_p_id"]))
		{
			$strdata = make_db_value("r_p_id",$row["r_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["r_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"r_p_id", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("r_p_id",$row["r_p_id"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("r_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("r_name").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("r_img").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("r_text").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("r_date").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("r_p_id").'</td>';
	}
	else
	{
		echo "<td>r_id</td>";
		echo "<td>r_name</td>";
		echo "<td>r_img</td>";
		echo "<td>r_text</td>";
		echo "<td>r_date</td>";
		echo "<td>r_p_id</td>";
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
			echo htmlspecialchars(GetData($row,"r_id",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"r_name",$format));
		else
			echo htmlspecialchars(GetData($row,"r_name",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"r_img",$format));
		else
			echo htmlspecialchars(GetData($row,"r_img",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"r_text",$format));
		else
			echo htmlspecialchars(GetData($row,"r_text",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"r_date",$format));
		else
			echo htmlspecialchars(GetData($row,"r_date",$format));
	echo '</td>';
	echo '<td>';
		if(strlen($row["r_p_id"]))
		{
/*
			$strdata = make_db_value("r_p_id",$row["r_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["r_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"r_p_id", "");
*/			
			$strValue = DisplayLookupWizard("r_p_id",$row["r_p_id"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
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
		$this->Rect($x,$this->y0,$colwidth["r_id"],$this->maxheight);
		$x+=$colwidth["r_id"];
		$this->Rect($x,$this->y0,$colwidth["r_name"],$this->maxheight);
		$x+=$colwidth["r_name"];
		$this->Rect($x,$this->y0,$colwidth["r_img"],$this->maxheight);
		$x+=$colwidth["r_img"];
		$this->Rect($x,$this->y0,$colwidth["r_text"],$this->maxheight);
		$x+=$colwidth["r_text"];
		$this->Rect($x,$this->y0,$colwidth["r_date"],$this->maxheight);
		$x+=$colwidth["r_date"];
		$this->Rect($x,$this->y0,$colwidth["r_p_id"],$this->maxheight);
		$x+=$colwidth["r_p_id"];
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
		$this->Cell($colwidth["r_id"],$this->rowheight,"r_id",1,0,'C',1);
		$this->Cell($colwidth["r_name"],$this->rowheight,"Name",1,0,'C',1);
		$this->Cell($colwidth["r_img"],$this->rowheight,"Image",1,0,'C',1);
		$this->Cell($colwidth["r_text"],$this->rowheight,"Description",1,0,'C',1);
		$this->Cell($colwidth["r_date"],$this->rowheight,"Date",1,0,'C',1);
		$this->Cell($colwidth["r_p_id"],$this->rowheight,"Player",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/6;
	$colwidth=array();
    $colwidth["r_id"]=$defwidth;
    $colwidth["r_name"]=$defwidth;
    $colwidth["r_img"]=$defwidth;
    $colwidth["r_text"]=$defwidth;
    $colwidth["r_date"]=$defwidth;
    $colwidth["r_p_id"]=$defwidth;
	
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
		$pdf->MultiCell($colwidth["r_id"],$rowheight,GetData($row,"r_id",""));
		$x+=$colwidth["r_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["r_name"],$rowheight,GetData($row,"r_name",""));
		$x+=$colwidth["r_name"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["r_img"],$rowheight,GetData($row,"r_img",""));
		$x+=$colwidth["r_img"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["r_text"],$rowheight,GetData($row,"r_text",""));
		$x+=$colwidth["r_text"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["r_date"],$rowheight,GetData($row,"r_date","Short Date"));
		$x+=$colwidth["r_date"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["r_p_id"]))
		{
/*			$strdata = make_db_value("r_p_id",$row["r_p_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`p_mail`";
			$LookupSQL.=" FROM `player` WHERE `p_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["r_p_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["r_p_id"],$rowheight,GetDataInt($lookupvalue,$row,"r_p_id", ""));
*/				
				
			$value = DisplayLookupWizard("r_p_id",$row["r_p_id"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["r_p_id"],$rowheight,$value);
		}
		$x+=$colwidth["r_p_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["r_id"],$pdf->maxheight);
		$x+=$colwidth["r_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["r_name"],$pdf->maxheight);
		$x+=$colwidth["r_name"];
		$pdf->Rect($x,$pdf->y0,$colwidth["r_img"],$pdf->maxheight);
		$x+=$colwidth["r_img"];
		$pdf->Rect($x,$pdf->y0,$colwidth["r_text"],$pdf->maxheight);
		$x+=$colwidth["r_text"];
		$pdf->Rect($x,$pdf->y0,$colwidth["r_date"],$pdf->maxheight);
		$x+=$colwidth["r_date"];
		$pdf->Rect($x,$pdf->y0,$colwidth["r_p_id"],$pdf->maxheight);
		$x+=$colwidth["r_p_id"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>