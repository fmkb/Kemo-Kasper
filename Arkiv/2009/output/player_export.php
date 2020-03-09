<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
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
$body["begin"]="<form action=\"player_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("player_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=player.xls");

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
	header("Content-Disposition: attachment;Filename=player.doc");

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
	header("Content-Disposition: attachment;Filename=player.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_active"));
		echo "<".$field.">";
/*		
		if(strlen($row["p_active"]))
		{
			$strdata = make_db_value("p_active",$row["p_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["p_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"p_active", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("p_active",$row["p_active"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_s_id"));
		echo "<".$field.">";
/*		
		if(strlen($row["p_s_id"]))
		{
			$strdata = make_db_value("p_s_id",$row["p_s_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="concat('(',ts_id,') ',ts_name)";
			$LookupSQL.=" FROM `teams` WHERE `ts_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["p_s_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"p_s_id", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("p_s_id",$row["p_s_id"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_first"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_first",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_name"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_name",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_adr"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_adr",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_zip"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_zip",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_country"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_country",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_mail"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_mail",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_mobile"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_mobile",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_newsaccept"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_newsaccept",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_score"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_score",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_scorehigh"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_scorehigh",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_games"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_games",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_win"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_win",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_mk"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_mk",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_born"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_born",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_user"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_user",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_pwd"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_pwd",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_ip"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_ip",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_datetime"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_datetime",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_tscore"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_tscore",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("p_tkills"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"p_tkills",""));
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
	header("Content-Disposition: attachment;Filename=player.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_active\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_s_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_first\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_name\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_adr\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_zip\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_country\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_mail\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_mobile\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_newsaccept\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_score\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_scorehigh\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_games\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_win\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_mk\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_born\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_user\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_pwd\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_ip\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_datetime\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_tscore\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"p_tkills\"";
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
		if(strlen($row["p_active"]))
		{
			$strdata = make_db_value("p_active",$row["p_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["p_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"p_active", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("p_active",$row["p_active"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["p_s_id"]))
		{
			$strdata = make_db_value("p_s_id",$row["p_s_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="concat('(',ts_id,') ',ts_name)";
			$LookupSQL.=" FROM `teams` WHERE `ts_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["p_s_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"p_s_id", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("p_s_id",$row["p_s_id"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_first",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_name",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_adr",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_zip",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_country",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"p_mail",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_mobile",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"p_newsaccept",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_score",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_scorehigh",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_games",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_win",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_mk",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_born",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_user",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_pwd",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_ip",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_datetime",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_tscore",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"p_tkills",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_active").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_s_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_first").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_name").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_adr").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_zip").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_country").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_mail").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_mobile").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_newsaccept").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_score").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_scorehigh").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_games").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_win").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_mk").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_born").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_user").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_pwd").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_ip").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_datetime").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_tscore").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("p_tkills").'</td>';
	}
	else
	{
		echo "<td>p_active</td>";
		echo "<td>p_s_id</td>";
		echo "<td>p_first</td>";
		echo "<td>p_name</td>";
		echo "<td>p_adr</td>";
		echo "<td>p_zip</td>";
		echo "<td>p_country</td>";
		echo "<td>p_mail</td>";
		echo "<td>p_mobile</td>";
		echo "<td>p_newsaccept</td>";
		echo "<td>p_score</td>";
		echo "<td>p_scorehigh</td>";
		echo "<td>p_games</td>";
		echo "<td>p_win</td>";
		echo "<td>p_mk</td>";
		echo "<td>p_born</td>";
		echo "<td>p_user</td>";
		echo "<td>p_pwd</td>";
		echo "<td>p_ip</td>";
		echo "<td>p_datetime</td>";
		echo "<td>p_tscore</td>";
		echo "<td>p_tkills</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	echo '<td>';
		if(strlen($row["p_active"]))
		{
/*
			$strdata = make_db_value("p_active",$row["p_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["p_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"p_active", "");
*/			
			$strValue = DisplayLookupWizard("p_active",$row["p_active"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	echo '<td>';
		if(strlen($row["p_s_id"]))
		{
/*
			$strdata = make_db_value("p_s_id",$row["p_s_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="concat('(',ts_id,') ',ts_name)";
			$LookupSQL.=" FROM `teams` WHERE `ts_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["p_s_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"p_s_id", "");
*/			
			$strValue = DisplayLookupWizard("p_s_id",$row["p_s_id"],$row,"",MODE_EXPORT);
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
			echo PrepareForExcel(GetData($row,"p_first",$format));
		else
			echo htmlspecialchars(GetData($row,"p_first",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_name",$format));
		else
			echo htmlspecialchars(GetData($row,"p_name",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_adr",$format));
		else
			echo htmlspecialchars(GetData($row,"p_adr",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_zip",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_country",$format));
		else
			echo htmlspecialchars(GetData($row,"p_country",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format=FORMAT_NONE;
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_mail",$format));
		else
			echo htmlspecialchars(GetData($row,"p_mail",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_mobile",$format));
	echo '</td>';
	echo '<td>';

		$format=FORMAT_NONE;
			echo htmlspecialchars(GetData($row,"p_newsaccept",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_score",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_scorehigh",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_games",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_win",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_mk",$format));
		else
			echo htmlspecialchars(GetData($row,"p_mk",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_born",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_user",$format));
		else
			echo htmlspecialchars(GetData($row,"p_user",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_pwd",$format));
		else
			echo htmlspecialchars(GetData($row,"p_pwd",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_ip",$format));
		else
			echo htmlspecialchars(GetData($row,"p_ip",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"p_datetime",$format));
		else
			echo htmlspecialchars(GetData($row,"p_datetime",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_tscore",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"p_tkills",$format));
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
		$this->Rect($x,$this->y0,$colwidth["p_active"],$this->maxheight);
		$x+=$colwidth["p_active"];
		$this->Rect($x,$this->y0,$colwidth["p_s_id"],$this->maxheight);
		$x+=$colwidth["p_s_id"];
		$this->Rect($x,$this->y0,$colwidth["p_first"],$this->maxheight);
		$x+=$colwidth["p_first"];
		$this->Rect($x,$this->y0,$colwidth["p_name"],$this->maxheight);
		$x+=$colwidth["p_name"];
		$this->Rect($x,$this->y0,$colwidth["p_adr"],$this->maxheight);
		$x+=$colwidth["p_adr"];
		$this->Rect($x,$this->y0,$colwidth["p_zip"],$this->maxheight);
		$x+=$colwidth["p_zip"];
		$this->Rect($x,$this->y0,$colwidth["p_country"],$this->maxheight);
		$x+=$colwidth["p_country"];
		$this->Rect($x,$this->y0,$colwidth["p_mail"],$this->maxheight);
		$x+=$colwidth["p_mail"];
		$this->Rect($x,$this->y0,$colwidth["p_mobile"],$this->maxheight);
		$x+=$colwidth["p_mobile"];
		$this->Rect($x,$this->y0,$colwidth["p_newsaccept"],$this->maxheight);
		$x+=$colwidth["p_newsaccept"];
		$this->Rect($x,$this->y0,$colwidth["p_score"],$this->maxheight);
		$x+=$colwidth["p_score"];
		$this->Rect($x,$this->y0,$colwidth["p_scorehigh"],$this->maxheight);
		$x+=$colwidth["p_scorehigh"];
		$this->Rect($x,$this->y0,$colwidth["p_games"],$this->maxheight);
		$x+=$colwidth["p_games"];
		$this->Rect($x,$this->y0,$colwidth["p_win"],$this->maxheight);
		$x+=$colwidth["p_win"];
		$this->Rect($x,$this->y0,$colwidth["p_mk"],$this->maxheight);
		$x+=$colwidth["p_mk"];
		$this->Rect($x,$this->y0,$colwidth["p_born"],$this->maxheight);
		$x+=$colwidth["p_born"];
		$this->Rect($x,$this->y0,$colwidth["p_user"],$this->maxheight);
		$x+=$colwidth["p_user"];
		$this->Rect($x,$this->y0,$colwidth["p_pwd"],$this->maxheight);
		$x+=$colwidth["p_pwd"];
		$this->Rect($x,$this->y0,$colwidth["p_ip"],$this->maxheight);
		$x+=$colwidth["p_ip"];
		$this->Rect($x,$this->y0,$colwidth["p_datetime"],$this->maxheight);
		$x+=$colwidth["p_datetime"];
		$this->Rect($x,$this->y0,$colwidth["p_tscore"],$this->maxheight);
		$x+=$colwidth["p_tscore"];
		$this->Rect($x,$this->y0,$colwidth["p_tkills"],$this->maxheight);
		$x+=$colwidth["p_tkills"];
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
		$this->Cell($colwidth["p_active"],$this->rowheight,"Status",1,0,'C',1);
		$this->Cell($colwidth["p_s_id"],$this->rowheight,"Team",1,0,'C',1);
		$this->Cell($colwidth["p_first"],$this->rowheight,"First name",1,0,'C',1);
		$this->Cell($colwidth["p_name"],$this->rowheight,"Last Name",1,0,'C',1);
		$this->Cell($colwidth["p_adr"],$this->rowheight,"Adress",1,0,'C',1);
		$this->Cell($colwidth["p_zip"],$this->rowheight,"Zip",1,0,'C',1);
		$this->Cell($colwidth["p_country"],$this->rowheight,"Country",1,0,'C',1);
		$this->Cell($colwidth["p_mail"],$this->rowheight,"Email",1,0,'C',1);
		$this->Cell($colwidth["p_mobile"],$this->rowheight,"Mobile",1,0,'C',1);
		$this->Cell($colwidth["p_newsaccept"],$this->rowheight,"Newsaccept",1,0,'C',1);
		$this->Cell($colwidth["p_score"],$this->rowheight,"Score",1,0,'C',1);
		$this->Cell($colwidth["p_scorehigh"],$this->rowheight,"Scorehigh",1,0,'C',1);
		$this->Cell($colwidth["p_games"],$this->rowheight,"Games Played",1,0,'C',1);
		$this->Cell($colwidth["p_win"],$this->rowheight,"Winner",1,0,'C',1);
		$this->Cell($colwidth["p_mk"],$this->rowheight,"MQ",1,0,'C',1);
		$this->Cell($colwidth["p_born"],$this->rowheight,"Born",1,0,'C',1);
		$this->Cell($colwidth["p_user"],$this->rowheight,"Username",1,0,'C',1);
		$this->Cell($colwidth["p_pwd"],$this->rowheight,"Password",1,0,'C',1);
		$this->Cell($colwidth["p_ip"],$this->rowheight,"IP",1,0,'C',1);
		$this->Cell($colwidth["p_datetime"],$this->rowheight,"Datetime",1,0,'C',1);
		$this->Cell($colwidth["p_tscore"],$this->rowheight,"Competetion Score",1,0,'C',1);
		$this->Cell($colwidth["p_tkills"],$this->rowheight,"Competetion Kills",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/22;
	$colwidth=array();
    $colwidth["p_active"]=$defwidth;
    $colwidth["p_s_id"]=$defwidth;
    $colwidth["p_first"]=$defwidth;
    $colwidth["p_name"]=$defwidth;
    $colwidth["p_adr"]=$defwidth;
    $colwidth["p_zip"]=$defwidth;
    $colwidth["p_country"]=$defwidth;
    $colwidth["p_mail"]=$defwidth;
    $colwidth["p_mobile"]=$defwidth;
    $colwidth["p_newsaccept"]=$defwidth;
    $colwidth["p_score"]=$defwidth;
    $colwidth["p_scorehigh"]=$defwidth;
    $colwidth["p_games"]=$defwidth;
    $colwidth["p_win"]=$defwidth;
    $colwidth["p_mk"]=$defwidth;
    $colwidth["p_born"]=$defwidth;
    $colwidth["p_user"]=$defwidth;
    $colwidth["p_pwd"]=$defwidth;
    $colwidth["p_ip"]=$defwidth;
    $colwidth["p_datetime"]=$defwidth;
    $colwidth["p_tscore"]=$defwidth;
    $colwidth["p_tkills"]=$defwidth;
	
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
		if(strlen($row["p_active"]))
		{
/*			$strdata = make_db_value("p_active",$row["p_active"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`xs_text`";
			$LookupSQL.=" FROM `xstatus` WHERE `xs_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["p_active"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["p_active"],$rowheight,GetDataInt($lookupvalue,$row,"p_active", ""));
*/				
				
			$value = DisplayLookupWizard("p_active",$row["p_active"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["p_active"],$rowheight,$value);
		}
		$x+=$colwidth["p_active"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["p_s_id"]))
		{
/*			$strdata = make_db_value("p_s_id",$row["p_s_id"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="concat('(',ts_id,') ',ts_name)";
			$LookupSQL.=" FROM `teams` WHERE `ts_id` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["p_s_id"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["p_s_id"],$rowheight,GetDataInt($lookupvalue,$row,"p_s_id", ""));
*/				
				
			$value = DisplayLookupWizard("p_s_id",$row["p_s_id"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["p_s_id"],$rowheight,$value);
		}
		$x+=$colwidth["p_s_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_first"],$rowheight,GetData($row,"p_first",""));
		$x+=$colwidth["p_first"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_name"],$rowheight,GetData($row,"p_name",""));
		$x+=$colwidth["p_name"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_adr"],$rowheight,GetData($row,"p_adr",""));
		$x+=$colwidth["p_adr"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_zip"],$rowheight,GetData($row,"p_zip",""));
		$x+=$colwidth["p_zip"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_country"],$rowheight,GetData($row,"p_country",""));
		$x+=$colwidth["p_country"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_mail"],$rowheight,GetData($row,"p_mail","Email Hyperlink"));
		$x+=$colwidth["p_mail"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_mobile"],$rowheight,GetData($row,"p_mobile",""));
		$x+=$colwidth["p_mobile"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_newsaccept"],$rowheight,GetData($row,"p_newsaccept","Checkbox"));
		$x+=$colwidth["p_newsaccept"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_score"],$rowheight,GetData($row,"p_score",""));
		$x+=$colwidth["p_score"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_scorehigh"],$rowheight,GetData($row,"p_scorehigh",""));
		$x+=$colwidth["p_scorehigh"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_games"],$rowheight,GetData($row,"p_games",""));
		$x+=$colwidth["p_games"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_win"],$rowheight,GetData($row,"p_win",""));
		$x+=$colwidth["p_win"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_mk"],$rowheight,GetData($row,"p_mk",""));
		$x+=$colwidth["p_mk"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_born"],$rowheight,GetData($row,"p_born",""));
		$x+=$colwidth["p_born"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_user"],$rowheight,GetData($row,"p_user",""));
		$x+=$colwidth["p_user"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_pwd"],$rowheight,GetData($row,"p_pwd",""));
		$x+=$colwidth["p_pwd"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_ip"],$rowheight,GetData($row,"p_ip",""));
		$x+=$colwidth["p_ip"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_datetime"],$rowheight,GetData($row,"p_datetime","Short Date"));
		$x+=$colwidth["p_datetime"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_tscore"],$rowheight,GetData($row,"p_tscore",""));
		$x+=$colwidth["p_tscore"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["p_tkills"],$rowheight,GetData($row,"p_tkills",""));
		$x+=$colwidth["p_tkills"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["p_active"],$pdf->maxheight);
		$x+=$colwidth["p_active"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_s_id"],$pdf->maxheight);
		$x+=$colwidth["p_s_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_first"],$pdf->maxheight);
		$x+=$colwidth["p_first"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_name"],$pdf->maxheight);
		$x+=$colwidth["p_name"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_adr"],$pdf->maxheight);
		$x+=$colwidth["p_adr"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_zip"],$pdf->maxheight);
		$x+=$colwidth["p_zip"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_country"],$pdf->maxheight);
		$x+=$colwidth["p_country"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_mail"],$pdf->maxheight);
		$x+=$colwidth["p_mail"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_mobile"],$pdf->maxheight);
		$x+=$colwidth["p_mobile"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_newsaccept"],$pdf->maxheight);
		$x+=$colwidth["p_newsaccept"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_score"],$pdf->maxheight);
		$x+=$colwidth["p_score"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_scorehigh"],$pdf->maxheight);
		$x+=$colwidth["p_scorehigh"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_games"],$pdf->maxheight);
		$x+=$colwidth["p_games"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_win"],$pdf->maxheight);
		$x+=$colwidth["p_win"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_mk"],$pdf->maxheight);
		$x+=$colwidth["p_mk"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_born"],$pdf->maxheight);
		$x+=$colwidth["p_born"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_user"],$pdf->maxheight);
		$x+=$colwidth["p_user"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_pwd"],$pdf->maxheight);
		$x+=$colwidth["p_pwd"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_ip"],$pdf->maxheight);
		$x+=$colwidth["p_ip"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_datetime"],$pdf->maxheight);
		$x+=$colwidth["p_datetime"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_tscore"],$pdf->maxheight);
		$x+=$colwidth["p_tscore"];
		$pdf->Rect($x,$pdf->y0,$colwidth["p_tkills"],$pdf->maxheight);
		$x+=$colwidth["p_tkills"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>