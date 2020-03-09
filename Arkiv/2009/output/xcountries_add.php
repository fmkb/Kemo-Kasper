<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/xcountries_variables.php");
include("include/languages.php");


//	check if logged in

if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readavalues=false;


$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
if(@$_REQUEST["editType"]=="inline")
	$inlineedit=ADD_INLINE;
elseif(@$_REQUEST["editType"]=="onthefly")
	$inlineedit=ADD_ONTHEFLY;
else
	$inlineedit=ADD_SIMPLE;
$keys=array();
if($inlineedit==ADD_INLINE)
	$templatefile = "xcountries_inline_add.htm";
else
	$templatefile = "xcountries_add.htm";

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessAdd"))
	BeforeProcessAdd($conn);

include('libs/xtempl.php');
$xt = new Xtempl();

// insert new record if we have to

if(@$_POST["a"]=="added")
{
	$afilename_values=array();
	$avalues=array();
	$files_move=array();
//	processing xc_id - start
	if($inlineedit!=ADD_INLINE)
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


		$avalues["xc_id"]=$value;
	}
	}
//	processibng xc_id - end
//	processing xc_name - start
	if($inlineedit!=ADD_INLINE)
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


		$avalues["xc_name"]=$value;
	}
	}
//	processibng xc_name - end
//	processing xc_currency - start
	if($inlineedit!=ADD_INLINE)
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


		$avalues["xc_currency"]=$value;
	}
	}
//	processibng xc_currency - end
//	processing xc_code - start
	if($inlineedit!=ADD_INLINE)
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


		$avalues["xc_code"]=$value;
	}
	}
//	processibng xc_code - end
//	processing xc_desc - start
	if($inlineedit!=ADD_INLINE)
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


		$avalues["xc_desc"]=$value;
	}
	}
//	processibng xc_desc - end





if($inlineedit==ADD_ONTHEFLY)
{
}


//	add filenames to values
	foreach($afilename_values as $akey=>$value)
		$avalues[$akey]=$value;
//	make SQL string
	$strSQL = "insert into ".AddTableWrappers($strOriginalTableName)." ";
	$strFields="(";
	$strValues="(";
	
//	before Add event
	$retval = true;
	if(function_exists("BeforeAdd"))
		$retval=BeforeAdd($avalues,$usermessage,$inlineedit);
	if($retval)
	{
		foreach($avalues as $akey=>$value)
		{
			$strFields.=AddFieldWrappers($akey).", ";
			$strValues.=add_db_quotes($akey,$value).", ";
		}
		if(substr($strFields,-2)==", ")
			$strFields=substr($strFields,0,strlen($strFields)-2);
		if(substr($strValues,-2)==", ")
			$strValues=substr($strValues,0,strlen($strValues)-2);
		$strSQL.=$strFields.") values ".$strValues.")";
		LogInfo($strSQL);
		set_error_handler("add_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
//	move files
		if(!$error_happened)
		{
			foreach ($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			if ( $inlineedit==ADD_INLINE ) 
			{
				$status="ADDED";
				$message="".mlang_message("RECORD_ADDED")."";
				$IsSaved = true;
			} 
			else
				$message="<div class=message><<< ".mlang_message("RECORD_ADDED")." >>></div>";
			if($usermessage!="")
				$message = $usermessage;
if($inlineedit==ADD_INLINE || $inlineedit==ADD_ONTHEFLY || function_exists("AfterAdd"))
{

	$failed_inline_add = false;
		if(array_key_exists("xc_id",$avalues))
		$keys["xc_id"]=$avalues["xc_id"];
	else
		$failed_inline_add = true;
}	

//	after edit event
			if(function_exists("AfterAdd"))
			{
				foreach($keys as $idx=>$val)
					$avalues[$idx]=$val;
				AfterAdd($avalues,$keys,$inlineedit);
			}
		}
	}
	else
	{
		$message = $usermessage;
		$status="DECLINED";
		$readavalues=true;
	}
}

$defvalues=array();


//	copy record
if(array_key_exists("copyid1",$_REQUEST) || array_key_exists("editid1",$_REQUEST))
{
	$copykeys=array();
	if(array_key_exists("copyid1",$_REQUEST))
	{
		$copykeys["xc_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["xc_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["xc_id"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else if(!count($defvalues))
{
}
if($inlineedit==ADD_ONTHEFLY)
{
}
if($readavalues)
{
	$defvalues["xc_id"]=@$avalues["xc_id"];
	$defvalues["xc_name"]=@$avalues["xc_name"];
	$defvalues["xc_currency"]=@$avalues["xc_currency"];
	$defvalues["xc_code"]=@$avalues["xc_code"];
	$defvalues["xc_desc"]=@$avalues["xc_desc"];
}

/*
foreach($defvalues as $key=>$value)
	$smarty->assign("value_".GoodFieldName($key),$value);
*/

$linkdata="";
$includes="";
$arr_includes=array();
$bodyonload="";
	
if ( $inlineedit!=ADD_INLINE ) 
{
	//	include files

	//	validation stuff
	$onsubmit="";
	$needvalidate=false;

	if($needvalidate)
	{
		if($inlineedit==ADD_ONTHEFLY)
			$onsubmit="return validate_fly(this);";
		else
			$onsubmit="return validate();";
//		$bodyonload="onload=\"".$bodyonload."\"";
	}

	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/onthefly.js\"></script>\r\n";
		if ($useAJAX) 
			$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\">\r\n";
	}
	$includes.="var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes(mlang_message("PLEASE_SELECT"))."';\r\n";
	if ($useAJAX) {
	$includes.="var SUGGEST_TABLE='xcountries_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}




	$xt->assign("xc_id_fieldblock",true);
	$xt->assign("xc_name_fieldblock",true);
	$xt->assign("xc_currency_fieldblock",true);
	$xt->assign("xc_code_fieldblock",true);
	$xt->assign("xc_desc_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"xcountries_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='xcountries_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"xcountries_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">".
		"<input type=hidden name=\"editType\" value=\"onthefly\">".
		"<input type=hidden name=\"table\" value=\"".postvalue("table")."\">".
		"<input type=hidden name=\"field\" value=\"".postvalue("field")."\">".
		"<input type=hidden name=\"category\" value=\"".postvalue("category")."\">".
		"<input type=hidden name=\"id\" value=\"".postvalue("id")."\">";
		$xt->assign("cancelbutton_attrs","onclick=\"RemoveFlyDiv('".substr(postvalue("id"),3)."');\"");
		$xt->assign("cancel_button",true);
	}
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
}

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}
//$xt->assign("status",$status);

$readonlyfields=array();

//	show readonly fields


$record_id= postvalue("recordID");

if ($useAJAX) 
{
	if($inlineedit==ADD_ONTHEFLY)
		$record_id= postvalue("id");

	if ( $inlineedit==ADD_INLINE ) 
	{
		$linkdata=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$linkdata);

	} 
	else 
	{
		$linkdata.="SetToFirstControl('".$formname."');";
		if($inlineedit==ADD_SIMPLE)
		{
			$linkdata = "<script type=\"text/javascript\">\r\n".
			"$(document).ready(function(){ \r\n".
			$linkdata.
			"});</script>";
		}
		else
		{
			$linkdata=$includes."\r\n".$linkdata;
			$includes="var s;";
			foreach($arr_includes as $file)
			{
				$includes.="s = document.createElement('script');s.src = '".$file."';\r\n".
				"document.getElementsByTagName('HEAD')[0].appendChild(s);\r\n";
			}			
			$linkdata=$includes."\r\n".$linkdata;

			if(!@$_POST["a"]=="added")
			{
				$linkdata = str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$linkdata);
				echo $linkdata;
				echo "\n";
			}
			else if(@$_POST["a"]=="added" && ($error_happened || $status=="DECLINED"))
			{
				echo "<textarea id=\"data\">decli";
				echo htmlspecialchars($linkdata);
				echo "</textarea>";
			}

		}
	}
} 
else 
{
}

if($inlineedit!=ADD_ONTHEFLY)
{
	$body["end"]="</form>".$linkdata.
	"<script>".$bodyonload."</script>";
	
	$xt->assign("body",$body);
	$xt->assign("flybody",true);
}
else
{
	$xt->assign("flybody",$body);
	$xt->assign("body",true);
}




if(@$_POST["a"]=="added" && $inlineedit==ADD_ONTHEFLY && !$error_happened && $status!="DECLINED")
{
	$LookupSQL="";
	if($LookupSQL)
		$LookupSQL.=" from ".AddTableWrappers($strOriginalTableName);

	$data=0;
	if(count($keys) && $LookupSQL)
	{
		$where=KeyWhere($keys);
		$LookupSQL.=" where ".$where;
		$rs=db_query($LookupSQL,$conn);
		$data=db_fetch_numarray($rs);
	}
	if(!$data)
	{
		$data=array(@$avalues[$linkfield],@$avalues[$dispfield]);
	}
	echo "<textarea id=\"data\">";
	echo "added";
	print_inline_array($data);
	echo "</textarea>";
	exit();
}


/////////////////////////////////////////////////////////////
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
$control_xc_id=array();
$control_xc_id["func"]="xt_buildeditcontrol";
$control_xc_id["params"] = array();
$control_xc_id["params"]["field"]="xc_id";
$control_xc_id["params"]["value"]=@$defvalues["xc_id"];
$control_xc_id["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_xc_id["params"]["mode"]="inline_add";
else
	$control_xc_id["params"]["mode"]="add";
$xt->assignbyref("xc_id_editcontrol",$control_xc_id);
$control_xc_name=array();
$control_xc_name["func"]="xt_buildeditcontrol";
$control_xc_name["params"] = array();
$control_xc_name["params"]["field"]="xc_name";
$control_xc_name["params"]["value"]=@$defvalues["xc_name"];
$control_xc_name["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_xc_name["params"]["mode"]="inline_add";
else
	$control_xc_name["params"]["mode"]="add";
$xt->assignbyref("xc_name_editcontrol",$control_xc_name);
$control_xc_currency=array();
$control_xc_currency["func"]="xt_buildeditcontrol";
$control_xc_currency["params"] = array();
$control_xc_currency["params"]["field"]="xc_currency";
$control_xc_currency["params"]["value"]=@$defvalues["xc_currency"];
$control_xc_currency["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_xc_currency["params"]["mode"]="inline_add";
else
	$control_xc_currency["params"]["mode"]="add";
$xt->assignbyref("xc_currency_editcontrol",$control_xc_currency);
$control_xc_code=array();
$control_xc_code["func"]="xt_buildeditcontrol";
$control_xc_code["params"] = array();
$control_xc_code["params"]["field"]="xc_code";
$control_xc_code["params"]["value"]=@$defvalues["xc_code"];
$control_xc_code["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_xc_code["params"]["mode"]="inline_add";
else
	$control_xc_code["params"]["mode"]="add";
$xt->assignbyref("xc_code_editcontrol",$control_xc_code);
$control_xc_desc=array();
$control_xc_desc["func"]="xt_buildeditcontrol";
$control_xc_desc["params"] = array();
$control_xc_desc["params"]["field"]="xc_desc";
$control_xc_desc["params"]["value"]=@$defvalues["xc_desc"];
$control_xc_desc["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_xc_desc["params"]["mode"]="inline_add";
else
	$control_xc_desc["params"]["mode"]="add";
$xt->assignbyref("xc_desc_editcontrol",$control_xc_desc);

$xt->assign("style_block",true);

if(function_exists("BeforeShowAdd"))
	BeforeShowAdd($xt,$templatefile);


if($inlineedit==ADD_ONTHEFLY)
{
	$xt->load_template($templatefile);
	$xt->display_loaded("style_block");
	$xt->display_loaded("flybody");
}
else
	$xt->display($templatefile);

function add_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readavalues, $message, $status, $inlineedit, $error_happened;
	if ( $inlineedit!=ADD_SIMPLE ) 
		$message="".mlang_message("RECORD_NOT_ADDED").". ".$errstr;
	else  
		$message="<div class=message><<< ".mlang_message("RECORD_NOT_ADDED")." >>><br><br>".$errstr."</div>";
	$readavalues=true;
	$error_happened=true;
}
?>
