<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/xcountries_variables.php");
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
$templatefile = "xcountries_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["xc_id"]=postvalue("editid1");

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
//	processing xc_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_xc_id");
	$type=postvalue("type_xc_id");
	if (in_assoc_array("type_xc_id",$_POST) || in_assoc_array("value_xc_id",$_POST) || in_assoc_array("value_xc_id",$_FILES))	
	{
		$value=prepare_for_db("xc_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["xc_id"]=$value;
	}

//	update key value
	$keys["xc_id"]=$value;

//	processibng xc_id - end
	}
//	processing xc_name - start
	if(!$inlineedit)
	{
	$value = postvalue("value_xc_name");
	$type=postvalue("type_xc_name");
	if (in_assoc_array("type_xc_name",$_POST) || in_assoc_array("value_xc_name",$_POST) || in_assoc_array("value_xc_name",$_FILES))	
	{
		$value=prepare_for_db("xc_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["xc_name"]=$value;
	}


//	processibng xc_name - end
	}
//	processing xc_currency - start
	if(!$inlineedit)
	{
	$value = postvalue("value_xc_currency");
	$type=postvalue("type_xc_currency");
	if (in_assoc_array("type_xc_currency",$_POST) || in_assoc_array("value_xc_currency",$_POST) || in_assoc_array("value_xc_currency",$_FILES))	
	{
		$value=prepare_for_db("xc_currency",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["xc_currency"]=$value;
	}


//	processibng xc_currency - end
	}
//	processing xc_code - start
	if(!$inlineedit)
	{
	$value = postvalue("value_xc_code");
	$type=postvalue("type_xc_code");
	if (in_assoc_array("type_xc_code",$_POST) || in_assoc_array("value_xc_code",$_POST) || in_assoc_array("value_xc_code",$_FILES))	
	{
		$value=prepare_for_db("xc_code",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["xc_code"]=$value;
	}


//	processibng xc_code - end
	}
//	processing xc_desc - start
	if(!$inlineedit)
	{
	$value = postvalue("value_xc_desc");
	$type=postvalue("type_xc_desc");
	if (in_assoc_array("type_xc_desc",$_POST) || in_assoc_array("value_xc_desc",$_POST) || in_assoc_array("value_xc_desc",$_FILES))	
	{
		$value=prepare_for_db("xc_desc",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["xc_desc"]=$value;
	}


//	processibng xc_desc - end
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
	$data["xc_id"]=$evalues["xc_id"];
	$data["xc_name"]=$evalues["xc_name"];
	$data["xc_currency"]=$evalues["xc_currency"];
	$data["xc_code"]=$evalues["xc_code"];
	$data["xc_desc"]=$evalues["xc_desc"];
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
	$includes.="var SUGGEST_TABLE='xcountries_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("xc_id_fieldblock",true);
	$xt->assign("xc_name_fieldblock",true);
	$xt->assign("xc_currency_fieldblock",true);
	$xt->assign("xc_code_fieldblock",true);
	$xt->assign("xc_desc_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"xcountries_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["xc_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"xc_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='xcountries_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["xc_id"]);

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
$control_xc_id=array();
$control_xc_id["func"]="xt_buildeditcontrol";
$control_xc_id["params"] = array();
$control_xc_id["params"]["field"]="xc_id";
$control_xc_id["params"]["value"]=@$data["xc_id"];
$control_xc_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_xc_id["params"]["mode"]="inline_edit";
else
	$control_xc_id["params"]["mode"]="edit";
$xt->assignbyref("xc_id_editcontrol",$control_xc_id);
$control_xc_name=array();
$control_xc_name["func"]="xt_buildeditcontrol";
$control_xc_name["params"] = array();
$control_xc_name["params"]["field"]="xc_name";
$control_xc_name["params"]["value"]=@$data["xc_name"];
$control_xc_name["params"]["id"]=$record_id;
if($inlineedit)
	$control_xc_name["params"]["mode"]="inline_edit";
else
	$control_xc_name["params"]["mode"]="edit";
$xt->assignbyref("xc_name_editcontrol",$control_xc_name);
$control_xc_currency=array();
$control_xc_currency["func"]="xt_buildeditcontrol";
$control_xc_currency["params"] = array();
$control_xc_currency["params"]["field"]="xc_currency";
$control_xc_currency["params"]["value"]=@$data["xc_currency"];
$control_xc_currency["params"]["id"]=$record_id;
if($inlineedit)
	$control_xc_currency["params"]["mode"]="inline_edit";
else
	$control_xc_currency["params"]["mode"]="edit";
$xt->assignbyref("xc_currency_editcontrol",$control_xc_currency);
$control_xc_code=array();
$control_xc_code["func"]="xt_buildeditcontrol";
$control_xc_code["params"] = array();
$control_xc_code["params"]["field"]="xc_code";
$control_xc_code["params"]["value"]=@$data["xc_code"];
$control_xc_code["params"]["id"]=$record_id;
if($inlineedit)
	$control_xc_code["params"]["mode"]="inline_edit";
else
	$control_xc_code["params"]["mode"]="edit";
$xt->assignbyref("xc_code_editcontrol",$control_xc_code);
$control_xc_desc=array();
$control_xc_desc["func"]="xt_buildeditcontrol";
$control_xc_desc["params"] = array();
$control_xc_desc["params"]["field"]="xc_desc";
$control_xc_desc["params"]["value"]=@$data["xc_desc"];
$control_xc_desc["params"]["id"]=$record_id;
if($inlineedit)
	$control_xc_desc["params"]["mode"]="inline_edit";
else
	$control_xc_desc["params"]["mode"]="edit";
$xt->assignbyref("xc_desc_editcontrol",$control_xc_desc);

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