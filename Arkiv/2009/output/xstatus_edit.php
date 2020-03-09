<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/xstatus_variables.php");
include("include/languages.php");


/////////////////////////////////////////////////////////////
//	check if logged in
/////////////////////////////////////////////////////////////
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

/////////////////////////////////////////////////////////////
//init variables
/////////////////////////////////////////////////////////////


$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readevalues=false;
$bodyonload="";


$body=array();
$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
$inlineedit = (@$_REQUEST["editType"]=="inline") ? true : false;
$templatefile = ( $inlineedit ) ? "xstatus_inline_edit.htm" : "xstatus_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["xs_id"]=postvalue("editid1");

/////////////////////////////////////////////////////////////
//	process entered data, read and save
/////////////////////////////////////////////////////////////

if(@$_POST["a"]=="edited")
{
	$strWhereClause=KeyWhere($keys);
	$strSQL = "update ".AddTableWrappers($strOriginalTableName)." set ";
	$evalues=array();
	$efilename_values=array();
	$files_delete=array();
	$files_move=array();
	$files_save=array();
//	processing xs_text - start
	$value = postvalue("value_xs_text");
	$type=postvalue("type_xs_text");
	if (in_assoc_array("type_xs_text",$_POST) || in_assoc_array("value_xs_text",$_POST) || in_assoc_array("value_xs_text",$_FILES))	
	{
		$value=prepare_for_db("xs_text",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["xs_text"]=$value;
	}


//	processibng xs_text - end

	foreach($efilename_values as $ekey=>$value)
		$evalues[$ekey]=$value;
//	do event
	$retval=true;
	if(function_exists("BeforeEdit"))
		$retval=BeforeEdit($evalues,$strWhereClause,$dataold,$keys,$usermessage,$inlineedit);
	if($retval)
	{		
//	construct SQL string
		foreach($evalues as $ekey=>$value)
		{
			$strSQL.=AddFieldWrappers($ekey)."=".add_db_quotes($ekey,$value).", ";
		}
		if(substr($strSQL,-2)==", ")
			$strSQL=substr($strSQL,0,strlen($strSQL)-2);
		$strSQL.=" where ".$strWhereClause;
		set_error_handler("edit_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
		if(!$error_happened)
		{
//	delete & move files
			foreach ($files_delete as $file)
			{
				if(file_exists($file))
					@unlink($file);
			}
			foreach($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			foreach($files_save as $file)
			{
				if(file_exists($file["filename"]))
						@unlink($file["filename"]);
				$th = fopen($file["filename"],"w");
				fwrite($th,$file["file"]);
				fclose($th);
			}
			
			if ( $inlineedit ) 
			{
				$status="UPDATED";
				$message="".mlang_message("RECORD_UPDATED")."";
				$IsSaved = true;
			} 
			else 
				$message="<div class=message><<< ".mlang_message("RECORD_UPDATED")." >>></div>";
			if($usermessage!="")
				$message = $usermessage;
//	after edit event
			if(function_exists("AfterEdit"))
			{
				foreach($dataold as $idx=>$val)
				{
					if(!array_key_exists($idx,$evalues))
						$evalues[$idx]=$val;
				}
				AfterEdit($evalues,KeyWhere($keys),$dataold,$keys,$inlineedit);
			}
		}
	}
	else
	{
		$readevalues=true;
		$message = $usermessage;
		$status="DECLINED";
	}
}

/////////////////////////////////////////////////////////////
//	read current values from the database
/////////////////////////////////////////////////////////////

$strWhereClause=KeyWhere($keys);

$strSQL=gSQLWhere($strWhereClause);

$strSQLbak = $strSQL;
//	Before Query event
if(function_exists("BeforeQueryEdit"))
	BeforeQueryEdit($strSQL,$strWhereClause);

if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);
LogInfo($strSQL);
$rs=db_query($strSQL,$conn);
$data=db_fetch_array($rs);

if($readevalues)
{
	$data["xs_text"]=$evalues["xs_text"];
}

/////////////////////////////////////////////////////////////
//	assign values to $xt class, prepare page for displaying
/////////////////////////////////////////////////////////////

include('libs/xtempl.php');
$xt = new Xtempl();

if ( !$inlineedit ) {
	//	include files
	$includes="";

	//	validation stuff
	$onsubmit="";

	if($bodyonload)
		$onsubmit="return validate();";

	$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\" src=\"include/onthefly.js\"></script>\r\n";
	if ($useAJAX) {
	$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
	}
	$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n";
	$includes .= "var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes(mlang_message("PLEASE_SELECT"))."';\r\n";
	if ($useAJAX) {
	$includes.="var SUGGEST_TABLE='xstatus_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("xs_text_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"xstatus_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["xs_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"xs_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='xstatus_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["xs_id"]);

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}

/////////////////////////////////////////////////////////////
//process readonly and auto-update fields
/////////////////////////////////////////////////////////////

$readonlyfields=array();



$linkdata="";

if ($useAJAX) 
{
	$record_id= postvalue("recordID");

	if ( $inlineedit ) 
	{

		$linkdata=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$linkdata);

		$xt->assignbyref("linkdata",$linkdata);
	} 
	else
	{
		$linkdata = "<script type=\"text/javascript\">\r\n".
		"$(document).ready(function(){ \r\n".
		$linkdata.
		"});</script>";
	}
} 
else 
{
}

$body["end"]="</form>".$linkdata.
"<script>".$bodyonload."</script>".
"<script>SetToFirstControl('editform');</script>";
$xt->assignbyref("body",$body);

/////////////////////////////////////////////////////////////
//	return new data to the List page or report an error
/////////////////////////////////////////////////////////////

if ($_REQUEST["a"]=="edited" && $inlineedit ) 
{
	if(!$data)
	{
		$data=$evalues;
		$HaveData=false;
	}
	//Preparation   view values

//	detail tables

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["xs_id"]));


//	xs_id - 

		$value="";
				$value = ProcessLargeText(GetData($data,"xs_id", ""),"","",MODE_LIST);
//		$smarty->assign("show_xs_id",$value);
		$showValues[] = $value;
		$showFields[] = "xs_id";
				$showRawValues[] = "";

//	xs_text - 

		$value="";
				$value = ProcessLargeText(GetData($data,"xs_text", ""),"","",MODE_LIST);
//		$smarty->assign("show_xs_text",$value);
		$showValues[] = $value;
		$showFields[] = "xs_text";
				$showRawValues[] = "";

/////////////////////////////////////////////////////////////
//	start inline output
/////////////////////////////////////////////////////////////

	echo "<textarea id=\"data\">";
	if($IsSaved)
	{
		if($HaveData)
			echo "saved";
		else
			echo "savnd";
		print_inline_array($showKeys);
		echo "\n";
		print_inline_array($showValues);
		echo "\n";
		print_inline_array($showFields);
		echo "\n";
		print_inline_array($showRawValues);
		echo "\n";
		print_inline_array($showDetailKeys,true);
		echo "\n";
		print_inline_array($showDetailKeys);
		echo "\n";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$usermessage);
	}
	else
	{
		if($status=="DECLINED")
			echo "decli";
		else
			echo "error";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$message);
	}
	echo "</textarea>";
	exit();
} 

/////////////////////////////////////////////////////////////
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
$control_xs_text=array();
$control_xs_text["func"]="xt_buildeditcontrol";
$control_xs_text["params"] = array();
$control_xs_text["params"]["field"]="xs_text";
$control_xs_text["params"]["value"]=@$data["xs_text"];
$control_xs_text["params"]["id"]=$record_id;
if($inlineedit)
	$control_xs_text["params"]["mode"]="inline_edit";
else
	$control_xs_text["params"]["mode"]="edit";
$xt->assignbyref("xs_text_editcontrol",$control_xs_text);

/////////////////////////////////////////////////////////////
//display the page
/////////////////////////////////////////////////////////////

if(function_exists("BeforeShowEdit"))
	BeforeShowEdit($xt,$templatefile);
$xt->display($templatefile);

function edit_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readevalues, $message, $status, $inlineedit, $error_happened;
	if ( $inlineedit ) 
		$message="".mlang_message("RECORD_NOT_UPDATED").". ".$errstr;
	else  
		$message="<div class=message><<< ".mlang_message("RECORD_NOT_UPDATED")." >>><br><br>".$errstr."</div>";
	$readevalues=true;
	$error_happened=true;
}

?>