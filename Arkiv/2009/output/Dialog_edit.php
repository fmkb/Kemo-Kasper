<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Dialog_variables.php");
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
$templatefile = ( $inlineedit ) ? "Dialog_inline_edit.htm" : "Dialog_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["d_id"]=postvalue("editid1");

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
//	processing d_from_p_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_d_from_p_id");
	$type=postvalue("type_d_from_p_id");
	if (in_assoc_array("type_d_from_p_id",$_POST) || in_assoc_array("value_d_from_p_id",$_POST) || in_assoc_array("value_d_from_p_id",$_FILES))	
	{
		$value=prepare_for_db("d_from_p_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["d_from_p_id"]=$value;
	}


//	processibng d_from_p_id - end
	}
//	processing d_to_p_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_d_to_p_id");
	$type=postvalue("type_d_to_p_id");
	if (in_assoc_array("type_d_to_p_id",$_POST) || in_assoc_array("value_d_to_p_id",$_POST) || in_assoc_array("value_d_to_p_id",$_FILES))	
	{
		$value=prepare_for_db("d_to_p_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["d_to_p_id"]=$value;
	}


//	processibng d_to_p_id - end
	}
//	processing d_message - start
	if(!$inlineedit)
	{
	$value = postvalue("value_d_message");
	$type=postvalue("type_d_message");
	if (in_assoc_array("type_d_message",$_POST) || in_assoc_array("value_d_message",$_POST) || in_assoc_array("value_d_message",$_FILES))	
	{
		$value=prepare_for_db("d_message",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["d_message"]=$value;
	}


//	processibng d_message - end
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
	$data["d_from_p_id"]=$evalues["d_from_p_id"];
	$data["d_to_p_id"]=$evalues["d_to_p_id"];
	$data["d_message"]=$evalues["d_message"];
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
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
			$bodyonload.="define('value_d_from_p_id','".$validatetype."','d_from_p_id');";
				$validatetype="";
			if($validatetype)
			$bodyonload.="define('value_d_to_p_id','".$validatetype."','d_to_p_id');";

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
	$includes.="var SUGGEST_TABLE='Dialog_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("d_from_p_id_fieldblock",true);
	$xt->assign("d_to_p_id_fieldblock",true);
	$xt->assign("d_message_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"Dialog_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["d_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"d_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='Dialog_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["d_id"]);

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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["d_id"]));


//	d_from_p_id - 

		$value="";
				$value=DisplayLookupWizard("d_from_p_id",$data["d_from_p_id"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_d_from_p_id",$value);
		$showValues[] = $value;
		$showFields[] = "d_from_p_id";
				$showRawValues[] = "";

//	d_to_p_id - 

		$value="";
				$value=DisplayLookupWizard("d_to_p_id",$data["d_to_p_id"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_d_to_p_id",$value);
		$showValues[] = $value;
		$showFields[] = "d_to_p_id";
				$showRawValues[] = "";

//	d_message - 

		$value="";
				$value = ProcessLargeText(GetData($data,"d_message", ""),"","",MODE_LIST);
//		$smarty->assign("show_d_message",$value);
		$showValues[] = $value;
		$showFields[] = "d_message";
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
$control_d_from_p_id=array();
$control_d_from_p_id["func"]="xt_buildeditcontrol";
$control_d_from_p_id["params"] = array();
$control_d_from_p_id["params"]["field"]="d_from_p_id";
$control_d_from_p_id["params"]["value"]=@$data["d_from_p_id"];
$control_d_from_p_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_d_from_p_id["params"]["mode"]="inline_edit";
else
	$control_d_from_p_id["params"]["mode"]="edit";
$xt->assignbyref("d_from_p_id_editcontrol",$control_d_from_p_id);
$control_d_to_p_id=array();
$control_d_to_p_id["func"]="xt_buildeditcontrol";
$control_d_to_p_id["params"] = array();
$control_d_to_p_id["params"]["field"]="d_to_p_id";
$control_d_to_p_id["params"]["value"]=@$data["d_to_p_id"];
$control_d_to_p_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_d_to_p_id["params"]["mode"]="inline_edit";
else
	$control_d_to_p_id["params"]["mode"]="edit";
$xt->assignbyref("d_to_p_id_editcontrol",$control_d_to_p_id);
$control_d_message=array();
$control_d_message["func"]="xt_buildeditcontrol";
$control_d_message["params"] = array();
$control_d_message["params"]["field"]="d_message";
$control_d_message["params"]["value"]=@$data["d_message"];
$control_d_message["params"]["id"]=$record_id;
if($inlineedit)
	$control_d_message["params"]["mode"]="inline_edit";
else
	$control_d_message["params"]["mode"]="edit";
$xt->assignbyref("d_message_editcontrol",$control_d_message);

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