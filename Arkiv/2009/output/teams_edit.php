<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/teams_variables.php");
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
$templatefile = "teams_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["ts_id"]=postvalue("editid1");

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
//	processing ts_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_ts_id");
	$type=postvalue("type_ts_id");
	if (in_assoc_array("type_ts_id",$_POST) || in_assoc_array("value_ts_id",$_POST) || in_assoc_array("value_ts_id",$_FILES))	
	{
		$value=prepare_for_db("ts_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["ts_id"]=$value;
	}

//	update key value
	$keys["ts_id"]=$value;

//	processibng ts_id - end
	}
//	processing ts_name - start
	if(!$inlineedit)
	{
	$value = postvalue("value_ts_name");
	$type=postvalue("type_ts_name");
	if (in_assoc_array("type_ts_name",$_POST) || in_assoc_array("value_ts_name",$_POST) || in_assoc_array("value_ts_name",$_FILES))	
	{
		$value=prepare_for_db("ts_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["ts_name"]=$value;
	}


//	processibng ts_name - end
	}
//	processing ts_p0 - start
	if(!$inlineedit)
	{
	$value = postvalue("value_ts_p0");
	$type=postvalue("type_ts_p0");
	if (in_assoc_array("type_ts_p0",$_POST) || in_assoc_array("value_ts_p0",$_POST) || in_assoc_array("value_ts_p0",$_FILES))	
	{
		$value=prepare_for_db("ts_p0",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["ts_p0"]=$value;
	}


//	processibng ts_p0 - end
	}
//	processing ts_s_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_ts_s_id");
	$type=postvalue("type_ts_s_id");
	if (in_assoc_array("type_ts_s_id",$_POST) || in_assoc_array("value_ts_s_id",$_POST) || in_assoc_array("value_ts_s_id",$_FILES))	
	{
		$value=prepare_for_db("ts_s_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["ts_s_id"]=$value;
	}


//	processibng ts_s_id - end
	}

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
	$data["ts_id"]=$evalues["ts_id"];
	$data["ts_name"]=$evalues["ts_name"];
	$data["ts_p0"]=$evalues["ts_p0"];
	$data["ts_s_id"]=$evalues["ts_s_id"];
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
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n";
	$includes.="var TEXT_FIELDS_REQUIRED='".addslashes(mlang_message("FIELDS_REQUIRED"))."';\r\n";
	$includes.="var TEXT_FIELDS_ZIPCODES='".addslashes(mlang_message("FIELDS_ZIPCODES"))."';\r\n";
	$includes.="var TEXT_FIELDS_EMAILS='".addslashes(mlang_message("FIELDS_EMAILS"))."';\r\n";
	$includes.="var TEXT_FIELDS_NUMBERS='".addslashes(mlang_message("FIELDS_NUMBERS"))."';\r\n";
	$includes.="var TEXT_FIELDS_CURRENCY='".addslashes(mlang_message("FIELDS_CURRENCY"))."';\r\n";
	$includes.="var TEXT_FIELDS_PHONE='".addslashes(mlang_message("FIELDS_PHONE"))."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD1='".addslashes(mlang_message("FIELDS_PASSWORD1"))."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD2='".addslashes(mlang_message("FIELDS_PASSWORD2"))."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD3='".addslashes(mlang_message("FIELDS_PASSWORD3"))."';\r\n";
	$includes.="var TEXT_FIELDS_STATE='".addslashes(mlang_message("FIELDS_STATE"))."';\r\n";
	$includes.="var TEXT_FIELDS_SSN='".addslashes(mlang_message("FIELDS_SSN"))."';\r\n";
	$includes.="var TEXT_FIELDS_DATE='".addslashes(mlang_message("FIELDS_DATE"))."';\r\n";
	$includes.="var TEXT_FIELDS_TIME='".addslashes(mlang_message("FIELDS_TIME"))."';\r\n";
	$includes.="var TEXT_FIELDS_CC='".addslashes(mlang_message("FIELDS_CC"))."';\r\n";
	$includes.="var TEXT_FIELDS_SSN='".addslashes(mlang_message("FIELDS_SSN"))."';\r\n";
	$includes.="</script>\r\n";
		  		$validatetype="IsNumeric";
			$validatetype.="IsRequired";
		if($validatetype)
			$bodyonload.="define('value_ts_id','".$validatetype."','Team ID');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_ts_p0','".$validatetype."','Status');";
				$validatetype="";
			if($validatetype)
			$bodyonload.="define('value_ts_s_id','".$validatetype."','Sponsor');";

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
	$includes.="var SUGGEST_TABLE='teams_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("ts_id_fieldblock",true);
	$xt->assign("ts_name_fieldblock",true);
	$xt->assign("ts_p0_fieldblock",true);
	$xt->assign("ts_s_id_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"teams_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["ts_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"ts_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='teams_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["ts_id"]);

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
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
$control_ts_id=array();
$control_ts_id["func"]="xt_buildeditcontrol";
$control_ts_id["params"] = array();
$control_ts_id["params"]["field"]="ts_id";
$control_ts_id["params"]["value"]=@$data["ts_id"];
$control_ts_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_ts_id["params"]["mode"]="inline_edit";
else
	$control_ts_id["params"]["mode"]="edit";
$xt->assignbyref("ts_id_editcontrol",$control_ts_id);
$control_ts_name=array();
$control_ts_name["func"]="xt_buildeditcontrol";
$control_ts_name["params"] = array();
$control_ts_name["params"]["field"]="ts_name";
$control_ts_name["params"]["value"]=@$data["ts_name"];
$control_ts_name["params"]["id"]=$record_id;
if($inlineedit)
	$control_ts_name["params"]["mode"]="inline_edit";
else
	$control_ts_name["params"]["mode"]="edit";
$xt->assignbyref("ts_name_editcontrol",$control_ts_name);
$control_ts_p0=array();
$control_ts_p0["func"]="xt_buildeditcontrol";
$control_ts_p0["params"] = array();
$control_ts_p0["params"]["field"]="ts_p0";
$control_ts_p0["params"]["value"]=@$data["ts_p0"];
$control_ts_p0["params"]["id"]=$record_id;
if($inlineedit)
	$control_ts_p0["params"]["mode"]="inline_edit";
else
	$control_ts_p0["params"]["mode"]="edit";
$xt->assignbyref("ts_p0_editcontrol",$control_ts_p0);
$control_ts_s_id=array();
$control_ts_s_id["func"]="xt_buildeditcontrol";
$control_ts_s_id["params"] = array();
$control_ts_s_id["params"]["field"]="ts_s_id";
$control_ts_s_id["params"]["value"]=@$data["ts_s_id"];
$control_ts_s_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_ts_s_id["params"]["mode"]="inline_edit";
else
	$control_ts_s_id["params"]["mode"]="edit";
$xt->assignbyref("ts_s_id_editcontrol",$control_ts_s_id);

/////////////////////////////////////////////////////////////
//display the page
/////////////////////////////////////////////////////////////

if(function_exists("BeforeShowEdit"))
	BeforeShowEdit($xt,$templatefile);
$xt->display($templatefile);

function edit_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readevalues, $message, $status, $inlineedit, $error_happened;
		$message="<div class=message><<< ".mlang_message("RECORD_NOT_UPDATED")." >>><br><br>".$errstr."</div>";
	$readevalues=true;
	$error_happened=true;
}

?>