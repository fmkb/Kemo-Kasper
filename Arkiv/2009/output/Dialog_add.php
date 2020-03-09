<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/Dialog_variables.php");
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
	$templatefile = "Dialog_inline_add.htm";
else
	$templatefile = "Dialog_add.htm";

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
//	processing d_datetime - start
	$value = postvalue("value_d_datetime");
	$type=postvalue("type_d_datetime");
	if (in_assoc_array("type_d_datetime",$_POST) || in_assoc_array("value_d_datetime",$_POST) || in_assoc_array("value_d_datetime",$_FILES))
	{
		$value=prepare_for_db("d_datetime",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["d_datetime"]=$value;
	}
//	processibng d_datetime - end
//	processing d_msg_type - start
	$value = postvalue("value_d_msg_type");
	$type=postvalue("type_d_msg_type");
	if (in_assoc_array("type_d_msg_type",$_POST) || in_assoc_array("value_d_msg_type",$_POST) || in_assoc_array("value_d_msg_type",$_FILES))
	{
		$value=prepare_for_db("d_msg_type",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["d_msg_type"]=$value;
	}
//	processibng d_msg_type - end
//	processing d_id - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_d_id");
	$type=postvalue("type_d_id");
	if (in_assoc_array("type_d_id",$_POST) || in_assoc_array("value_d_id",$_POST) || in_assoc_array("value_d_id",$_FILES))
	{
		$value=prepare_for_db("d_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["d_id"]=$value;
	}
	}
//	processibng d_id - end
//	processing d_from_p_id - start
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


		$avalues["d_from_p_id"]=$value;
	}
//	processibng d_from_p_id - end
//	processing d_to_p_id - start
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


		$avalues["d_to_p_id"]=$value;
	}
//	processibng d_to_p_id - end
//	processing d_message - start
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


		$avalues["d_message"]=$value;
	}
//	processibng d_message - end




//	insert masterkey value if exists and if not specified
	if(@$_SESSION[$strTableName."_mastertable"]=="player")
	{
		$avalues["d_from_p_id"]=prepare_for_db("d_from_p_id",$_SESSION[$strTableName."_masterkey1"]);
	}

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
						$keys["d_id"]=mysql_insert_id($conn);
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
		$copykeys["d_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["d_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["d_id"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else if(!count($defvalues))
{
	$defvalues["d_from_p_id"]=@$_SESSION[$strTableName."_masterkey1"];
}
if($inlineedit==ADD_ONTHEFLY)
{
}
if($readavalues)
{
	$defvalues["d_id"]=@$avalues["d_id"];
	$defvalues["d_from_p_id"]=@$avalues["d_from_p_id"];
	$defvalues["d_to_p_id"]=@$avalues["d_to_p_id"];
	$defvalues["d_message"]=@$avalues["d_message"];
	$defvalues["d_datetime"]=@$avalues["d_datetime"];
	$defvalues["d_msg_type"]=@$avalues["d_msg_type"];
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
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
	
	if($inlineedit!=ADD_ONTHEFLY)
	{
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
	}
	else
	{
		$includes.="var TEXT_INLINE_FIELD_REQUIRED='".jsreplace(mlang_message("INLINE_FIELD_REQUIRED"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_ZIPCODE='".jsreplace(mlang_message("INLINE_FIELD_ZIPCODE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_EMAIL='".jsreplace(mlang_message("INLINE_FIELD_EMAIL"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_NUMBER='".jsreplace(mlang_message("INLINE_FIELD_NUMBER"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CURRENCY='".jsreplace(mlang_message("INLINE_FIELD_CURRENCY"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PHONE='".jsreplace(mlang_message("INLINE_FIELD_PHONE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD1='".jsreplace(mlang_message("INLINE_FIELD_PASSWORD1"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD2='".jsreplace(mlang_message("INLINE_FIELD_PASSWORD2"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_STATE='".jsreplace(mlang_message("INLINE_FIELD_STATE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace(mlang_message("INLINE_FIELD_SSN"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_DATE='".jsreplace(mlang_message("INLINE_FIELD_DATE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_TIME='".jsreplace(mlang_message("INLINE_FIELD_TIME"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CC='".jsreplace(mlang_message("INLINE_FIELD_CC"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace(mlang_message("INLINE_FIELD_SSN"))."';\r\n";
	}
		  		$validatetype="IsNumeric";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_d_id_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_d_id','".$validatetype."','d_id');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_d_from_p_id_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_d_from_p_id','".$validatetype."','From');";
			
		}
			$validatetype="";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_d_to_p_id_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_d_to_p_id','".$validatetype."','To');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_d_msg_type_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_d_msg_type','".$validatetype."','Type');";
			
		}

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
	$includes.="var SUGGEST_TABLE='Dialog_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}
		//	include datepicker files
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";
	else
		$arr_includes[]="include/calendar.js";




	$xt->assign("d_id_fieldblock",true);
	$xt->assign("d_from_p_id_fieldblock",true);
	$xt->assign("d_to_p_id_fieldblock",true);
	$xt->assign("d_message_fieldblock",true);
	$xt->assign("d_datetime_fieldblock",true);
	$xt->assign("d_msg_type_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"Dialog_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='Dialog_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"Dialog_add.php\" ".$onsubmit.">".
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


if ( @$_POST["a"]=="added" && $inlineedit==ADD_INLINE ) 
{

	//Preparation   view values
	//	get current values and show edit controls

	$data=0;
	if(count($keys))
	{

		$where=KeyWhere($keys);
			$strSQL = gSQLWhere($where);

		LogInfo($strSQL);

		$rs=db_query($strSQL,$conn);
		$data=db_fetch_array($rs);
	}
	if(!$data)
	{
		$data=$avalues;
		$HaveData=false;
	}

	//check if correct values added

	
	
	$showKeys[] = htmlspecialchars($keys["d_id"]);

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["d_id"]));

	////////////////////////////////////////////
	//	d_datetime - Datetime
		$value="";
				$value = ProcessLargeText(GetData($data,"d_datetime", "Datetime"),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "d_datetime";
				$showRawValues[] = "";
	////////////////////////////////////////////
	//	d_msg_type - 
		$value="";
				$value = ProcessLargeText(GetData($data,"d_msg_type", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "d_msg_type";
				$showRawValues[] = "";
	////////////////////////////////////////////
	//	d_from_p_id - 
		$value="";
				$value=DisplayLookupWizard("d_from_p_id",$data["d_from_p_id"],$data,$keylink,MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "d_from_p_id";
				$showRawValues[] = "";
	////////////////////////////////////////////
	//	d_to_p_id - 
		$value="";
				$value=DisplayLookupWizard("d_to_p_id",$data["d_to_p_id"],$data,$keylink,MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "d_to_p_id";
				$showRawValues[] = "";
	////////////////////////////////////////////
	//	d_message - 
		$value="";
				$value = ProcessLargeText(GetData($data,"d_message", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "d_message";
				$showRawValues[] = "";
}

if ( @$_POST["a"]=="added" && $inlineedit==ADD_INLINE ) 
{
	echo "<textarea id=\"data\">";
	if($IsSaved && count($showValues))
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
$control_d_id=array();
$control_d_id["func"]="xt_buildeditcontrol";
$control_d_id["params"] = array();
$control_d_id["params"]["field"]="d_id";
$control_d_id["params"]["value"]=@$defvalues["d_id"];
$control_d_id["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_d_id["params"]["mode"]="inline_add";
else
	$control_d_id["params"]["mode"]="add";
$xt->assignbyref("d_id_editcontrol",$control_d_id);
$control_d_from_p_id=array();
$control_d_from_p_id["func"]="xt_buildeditcontrol";
$control_d_from_p_id["params"] = array();
$control_d_from_p_id["params"]["field"]="d_from_p_id";
$control_d_from_p_id["params"]["value"]=@$defvalues["d_from_p_id"];
$control_d_from_p_id["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_d_from_p_id["params"]["mode"]="inline_add";
else
	$control_d_from_p_id["params"]["mode"]="add";
$xt->assignbyref("d_from_p_id_editcontrol",$control_d_from_p_id);
$control_d_to_p_id=array();
$control_d_to_p_id["func"]="xt_buildeditcontrol";
$control_d_to_p_id["params"] = array();
$control_d_to_p_id["params"]["field"]="d_to_p_id";
$control_d_to_p_id["params"]["value"]=@$defvalues["d_to_p_id"];
$control_d_to_p_id["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_d_to_p_id["params"]["mode"]="inline_add";
else
	$control_d_to_p_id["params"]["mode"]="add";
$xt->assignbyref("d_to_p_id_editcontrol",$control_d_to_p_id);
$control_d_message=array();
$control_d_message["func"]="xt_buildeditcontrol";
$control_d_message["params"] = array();
$control_d_message["params"]["field"]="d_message";
$control_d_message["params"]["value"]=@$defvalues["d_message"];
$control_d_message["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_d_message["params"]["mode"]="inline_add";
else
	$control_d_message["params"]["mode"]="add";
$xt->assignbyref("d_message_editcontrol",$control_d_message);
$control_d_datetime=array();
$control_d_datetime["func"]="xt_buildeditcontrol";
$control_d_datetime["params"] = array();
$control_d_datetime["params"]["field"]="d_datetime";
$control_d_datetime["params"]["value"]=@$defvalues["d_datetime"];
$control_d_datetime["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_d_datetime["params"]["mode"]="inline_add";
else
	$control_d_datetime["params"]["mode"]="add";
$xt->assignbyref("d_datetime_editcontrol",$control_d_datetime);
$control_d_msg_type=array();
$control_d_msg_type["func"]="xt_buildeditcontrol";
$control_d_msg_type["params"] = array();
$control_d_msg_type["params"]["field"]="d_msg_type";
$control_d_msg_type["params"]["value"]=@$defvalues["d_msg_type"];
$control_d_msg_type["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_d_msg_type["params"]["mode"]="inline_add";
else
	$control_d_msg_type["params"]["mode"]="add";
$xt->assignbyref("d_msg_type_editcontrol",$control_d_msg_type);

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
