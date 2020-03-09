<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
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
$body["begin"]="<form action=\"Q1_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("Q1_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=Q1.xls");

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
	header("Content-Disposition: attachment;Filename=Q1.doc");

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
	header("Content-Disposition: attachment;Filename=Q1.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_work"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_work",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_where"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_where",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_why"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_why",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_rating"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_rating",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_speakothers"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_speakothers",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_changedme"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_changedme",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_playoften"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_playoften",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_infolevel"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_infolevel",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_SNotice"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_SNotice",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_SNoticewhere"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_SNoticewhere",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_SNoticemainsponsor"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_SNoticemainsponsor",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_SNoticeteam"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_SNoticeteam",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_SNoticeother"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_SNoticeother",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_Shavechangedview"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_Shavechangedview",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_Sbuyproducts"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_Sbuyproducts",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_Snameothers"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_Snameothers",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_Comments"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_Comments",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Q1_p_id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Q1_p_id",""));
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
	header("Content-Disposition: attachment;Filename=Q1.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_work\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_where\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_why\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_rating\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_speakothers\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_changedme\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_playoften\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_infolevel\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_SNotice\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_SNoticewhere\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_SNoticemainsponsor\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_SNoticeteam\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_SNoticeother\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_Shavechangedview\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_Sbuyproducts\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_Snameothers\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_Comments\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Q1_p_id\"";
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
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_work",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_where",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_why",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_rating",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_speakothers",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_changedme",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_playoften",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_infolevel",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_SNotice",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_SNoticewhere",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_SNoticemainsponsor",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_SNoticeteam",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_SNoticeother",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_Shavechangedview",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_Sbuyproducts",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_Snameothers",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_Comments",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Q1_p_id",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_work").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_where").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_why").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_rating").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_speakothers").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_changedme").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_playoften").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_infolevel").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_SNotice").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_SNoticewhere").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_SNoticemainsponsor").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_SNoticeteam").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_SNoticeother").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_Shavechangedview").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_Sbuyproducts").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_Snameothers").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_Comments").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Q1_p_id").'</td>';
	}
	else
	{
		echo "<td>Q1_id</td>";
		echo "<td>Q1_work</td>";
		echo "<td>Q1_where</td>";
		echo "<td>Q1_why</td>";
		echo "<td>Q1_rating</td>";
		echo "<td>Q1_speakothers</td>";
		echo "<td>Q1_changedme</td>";
		echo "<td>Q1_playoften</td>";
		echo "<td>Q1_infolevel</td>";
		echo "<td>Q1_SNotice</td>";
		echo "<td>Q1_SNoticewhere</td>";
		echo "<td>Q1_SNoticemainsponsor</td>";
		echo "<td>Q1_SNoticeteam</td>";
		echo "<td>Q1_SNoticeother</td>";
		echo "<td>Q1_Shavechangedview</td>";
		echo "<td>Q1_Sbuyproducts</td>";
		echo "<td>Q1_Snameothers</td>";
		echo "<td>Q1_Comments</td>";
		echo "<td>Q1_p_id</td>";
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
			echo htmlspecialchars(GetData($row,"Q1_id",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_work",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_where",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_why",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_rating",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_speakothers",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_changedme",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_playoften",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_infolevel",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_SNotice",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_SNoticewhere",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_SNoticemainsponsor",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_SNoticeteam",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_SNoticeother",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_Shavechangedview",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_Sbuyproducts",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Q1_Snameothers",$format));
		else
			echo htmlspecialchars(GetData($row,"Q1_Snameothers",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Q1_Comments",$format));
		else
			echo htmlspecialchars(GetData($row,"Q1_Comments",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Q1_p_id",$format));
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
		$this->Rect($x,$this->y0,$colwidth["Q1_id"],$this->maxheight);
		$x+=$colwidth["Q1_id"];
		$this->Rect($x,$this->y0,$colwidth["Q1_work"],$this->maxheight);
		$x+=$colwidth["Q1_work"];
		$this->Rect($x,$this->y0,$colwidth["Q1_where"],$this->maxheight);
		$x+=$colwidth["Q1_where"];
		$this->Rect($x,$this->y0,$colwidth["Q1_why"],$this->maxheight);
		$x+=$colwidth["Q1_why"];
		$this->Rect($x,$this->y0,$colwidth["Q1_rating"],$this->maxheight);
		$x+=$colwidth["Q1_rating"];
		$this->Rect($x,$this->y0,$colwidth["Q1_speakothers"],$this->maxheight);
		$x+=$colwidth["Q1_speakothers"];
		$this->Rect($x,$this->y0,$colwidth["Q1_changedme"],$this->maxheight);
		$x+=$colwidth["Q1_changedme"];
		$this->Rect($x,$this->y0,$colwidth["Q1_playoften"],$this->maxheight);
		$x+=$colwidth["Q1_playoften"];
		$this->Rect($x,$this->y0,$colwidth["Q1_infolevel"],$this->maxheight);
		$x+=$colwidth["Q1_infolevel"];
		$this->Rect($x,$this->y0,$colwidth["Q1_SNotice"],$this->maxheight);
		$x+=$colwidth["Q1_SNotice"];
		$this->Rect($x,$this->y0,$colwidth["Q1_SNoticewhere"],$this->maxheight);
		$x+=$colwidth["Q1_SNoticewhere"];
		$this->Rect($x,$this->y0,$colwidth["Q1_SNoticemainsponsor"],$this->maxheight);
		$x+=$colwidth["Q1_SNoticemainsponsor"];
		$this->Rect($x,$this->y0,$colwidth["Q1_SNoticeteam"],$this->maxheight);
		$x+=$colwidth["Q1_SNoticeteam"];
		$this->Rect($x,$this->y0,$colwidth["Q1_SNoticeother"],$this->maxheight);
		$x+=$colwidth["Q1_SNoticeother"];
		$this->Rect($x,$this->y0,$colwidth["Q1_Shavechangedview"],$this->maxheight);
		$x+=$colwidth["Q1_Shavechangedview"];
		$this->Rect($x,$this->y0,$colwidth["Q1_Sbuyproducts"],$this->maxheight);
		$x+=$colwidth["Q1_Sbuyproducts"];
		$this->Rect($x,$this->y0,$colwidth["Q1_Snameothers"],$this->maxheight);
		$x+=$colwidth["Q1_Snameothers"];
		$this->Rect($x,$this->y0,$colwidth["Q1_Comments"],$this->maxheight);
		$x+=$colwidth["Q1_Comments"];
		$this->Rect($x,$this->y0,$colwidth["Q1_p_id"],$this->maxheight);
		$x+=$colwidth["Q1_p_id"];
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
		$this->Cell($colwidth["Q1_id"],$this->rowheight,"Q1_id",1,0,'C',1);
		$this->Cell($colwidth["Q1_work"],$this->rowheight,"Q1_work",1,0,'C',1);
		$this->Cell($colwidth["Q1_where"],$this->rowheight,"Q1_where",1,0,'C',1);
		$this->Cell($colwidth["Q1_why"],$this->rowheight,"Q1_why",1,0,'C',1);
		$this->Cell($colwidth["Q1_rating"],$this->rowheight,"Q1_rating",1,0,'C',1);
		$this->Cell($colwidth["Q1_speakothers"],$this->rowheight,"Q1_speakothers",1,0,'C',1);
		$this->Cell($colwidth["Q1_changedme"],$this->rowheight,"Q1_changedme",1,0,'C',1);
		$this->Cell($colwidth["Q1_playoften"],$this->rowheight,"Q1_playoften",1,0,'C',1);
		$this->Cell($colwidth["Q1_infolevel"],$this->rowheight,"Q1_infolevel",1,0,'C',1);
		$this->Cell($colwidth["Q1_SNotice"],$this->rowheight,"Q1_SNotice",1,0,'C',1);
		$this->Cell($colwidth["Q1_SNoticewhere"],$this->rowheight,"Q1_SNoticewhere",1,0,'C',1);
		$this->Cell($colwidth["Q1_SNoticemainsponsor"],$this->rowheight,"Q1_SNoticemainsponsor",1,0,'C',1);
		$this->Cell($colwidth["Q1_SNoticeteam"],$this->rowheight,"Q1_SNoticeteam",1,0,'C',1);
		$this->Cell($colwidth["Q1_SNoticeother"],$this->rowheight,"Q1_SNoticeother",1,0,'C',1);
		$this->Cell($colwidth["Q1_Shavechangedview"],$this->rowheight,"Q1_Shavechangedview",1,0,'C',1);
		$this->Cell($colwidth["Q1_Sbuyproducts"],$this->rowheight,"Q1_Sbuyproducts",1,0,'C',1);
		$this->Cell($colwidth["Q1_Snameothers"],$this->rowheight,"Q1_Snameothers",1,0,'C',1);
		$this->Cell($colwidth["Q1_Comments"],$this->rowheight,"Q1_Comments",1,0,'C',1);
		$this->Cell($colwidth["Q1_p_id"],$this->rowheight,"Q1_p_id",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/19;
	$colwidth=array();
    $colwidth["Q1_id"]=$defwidth;
    $colwidth["Q1_work"]=$defwidth;
    $colwidth["Q1_where"]=$defwidth;
    $colwidth["Q1_why"]=$defwidth;
    $colwidth["Q1_rating"]=$defwidth;
    $colwidth["Q1_speakothers"]=$defwidth;
    $colwidth["Q1_changedme"]=$defwidth;
    $colwidth["Q1_playoften"]=$defwidth;
    $colwidth["Q1_infolevel"]=$defwidth;
    $colwidth["Q1_SNotice"]=$defwidth;
    $colwidth["Q1_SNoticewhere"]=$defwidth;
    $colwidth["Q1_SNoticemainsponsor"]=$defwidth;
    $colwidth["Q1_SNoticeteam"]=$defwidth;
    $colwidth["Q1_SNoticeother"]=$defwidth;
    $colwidth["Q1_Shavechangedview"]=$defwidth;
    $colwidth["Q1_Sbuyproducts"]=$defwidth;
    $colwidth["Q1_Snameothers"]=$defwidth;
    $colwidth["Q1_Comments"]=$defwidth;
    $colwidth["Q1_p_id"]=$defwidth;
	
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
		$pdf->MultiCell($colwidth["Q1_id"],$rowheight,GetData($row,"Q1_id",""));
		$x+=$colwidth["Q1_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_work"],$rowheight,GetData($row,"Q1_work",""));
		$x+=$colwidth["Q1_work"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_where"],$rowheight,GetData($row,"Q1_where",""));
		$x+=$colwidth["Q1_where"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_why"],$rowheight,GetData($row,"Q1_why",""));
		$x+=$colwidth["Q1_why"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_rating"],$rowheight,GetData($row,"Q1_rating",""));
		$x+=$colwidth["Q1_rating"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_speakothers"],$rowheight,GetData($row,"Q1_speakothers",""));
		$x+=$colwidth["Q1_speakothers"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_changedme"],$rowheight,GetData($row,"Q1_changedme",""));
		$x+=$colwidth["Q1_changedme"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_playoften"],$rowheight,GetData($row,"Q1_playoften",""));
		$x+=$colwidth["Q1_playoften"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_infolevel"],$rowheight,GetData($row,"Q1_infolevel",""));
		$x+=$colwidth["Q1_infolevel"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_SNotice"],$rowheight,GetData($row,"Q1_SNotice",""));
		$x+=$colwidth["Q1_SNotice"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_SNoticewhere"],$rowheight,GetData($row,"Q1_SNoticewhere",""));
		$x+=$colwidth["Q1_SNoticewhere"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_SNoticemainsponsor"],$rowheight,GetData($row,"Q1_SNoticemainsponsor",""));
		$x+=$colwidth["Q1_SNoticemainsponsor"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_SNoticeteam"],$rowheight,GetData($row,"Q1_SNoticeteam",""));
		$x+=$colwidth["Q1_SNoticeteam"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_SNoticeother"],$rowheight,GetData($row,"Q1_SNoticeother",""));
		$x+=$colwidth["Q1_SNoticeother"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_Shavechangedview"],$rowheight,GetData($row,"Q1_Shavechangedview",""));
		$x+=$colwidth["Q1_Shavechangedview"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_Sbuyproducts"],$rowheight,GetData($row,"Q1_Sbuyproducts",""));
		$x+=$colwidth["Q1_Sbuyproducts"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_Snameothers"],$rowheight,GetData($row,"Q1_Snameothers",""));
		$x+=$colwidth["Q1_Snameothers"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_Comments"],$rowheight,GetData($row,"Q1_Comments",""));
		$x+=$colwidth["Q1_Comments"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Q1_p_id"],$rowheight,GetData($row,"Q1_p_id",""));
		$x+=$colwidth["Q1_p_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_id"],$pdf->maxheight);
		$x+=$colwidth["Q1_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_work"],$pdf->maxheight);
		$x+=$colwidth["Q1_work"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_where"],$pdf->maxheight);
		$x+=$colwidth["Q1_where"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_why"],$pdf->maxheight);
		$x+=$colwidth["Q1_why"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_rating"],$pdf->maxheight);
		$x+=$colwidth["Q1_rating"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_speakothers"],$pdf->maxheight);
		$x+=$colwidth["Q1_speakothers"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_changedme"],$pdf->maxheight);
		$x+=$colwidth["Q1_changedme"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_playoften"],$pdf->maxheight);
		$x+=$colwidth["Q1_playoften"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_infolevel"],$pdf->maxheight);
		$x+=$colwidth["Q1_infolevel"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_SNotice"],$pdf->maxheight);
		$x+=$colwidth["Q1_SNotice"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_SNoticewhere"],$pdf->maxheight);
		$x+=$colwidth["Q1_SNoticewhere"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_SNoticemainsponsor"],$pdf->maxheight);
		$x+=$colwidth["Q1_SNoticemainsponsor"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_SNoticeteam"],$pdf->maxheight);
		$x+=$colwidth["Q1_SNoticeteam"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_SNoticeother"],$pdf->maxheight);
		$x+=$colwidth["Q1_SNoticeother"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_Shavechangedview"],$pdf->maxheight);
		$x+=$colwidth["Q1_Shavechangedview"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_Sbuyproducts"],$pdf->maxheight);
		$x+=$colwidth["Q1_Sbuyproducts"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_Snameothers"],$pdf->maxheight);
		$x+=$colwidth["Q1_Snameothers"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_Comments"],$pdf->maxheight);
		$x+=$colwidth["Q1_Comments"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Q1_p_id"],$pdf->maxheight);
		$x+=$colwidth["Q1_p_id"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>