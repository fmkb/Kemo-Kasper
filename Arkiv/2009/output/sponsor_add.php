<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/sponsor_variables.php");
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
	$templatefile = "sponsor_inline_add.htm";
else
	$templatefile = "sponsor_add.htm";

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
//	processing s_active - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_active");
	$type=postvalue("type_s_active");
	if (in_assoc_array("type_s_active",$_POST) || in_assoc_array("value_s_active",$_POST) || in_assoc_array("value_s_active",$_FILES))
	{
		$value=prepare_for_db("s_active",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_active"]=$value;
	}
	}
//	processibng s_active - end
//	processing s_name - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_name");
	$type=postvalue("type_s_name");
	if (in_assoc_array("type_s_name",$_POST) || in_assoc_array("value_s_name",$_POST) || in_assoc_array("value_s_name",$_FILES))
	{
		$value=prepare_for_db("s_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_name"]=$value;
	}
	}
//	processibng s_name - end
//	processing s_contact - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_contact");
	$type=postvalue("type_s_contact");
	if (in_assoc_array("type_s_contact",$_POST) || in_assoc_array("value_s_contact",$_POST) || in_assoc_array("value_s_contact",$_FILES))
	{
		$value=prepare_for_db("s_contact",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_contact"]=$value;
	}
	}
//	processibng s_contact - end
//	processing s_adr - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_adr");
	$type=postvalue("type_s_adr");
	if (in_assoc_array("type_s_adr",$_POST) || in_assoc_array("value_s_adr",$_POST) || in_assoc_array("value_s_adr",$_FILES))
	{
		$value=prepare_for_db("s_adr",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_adr"]=$value;
	}
	}
//	processibng s_adr - end
//	processing s_zip - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_zip");
	$type=postvalue("type_s_zip");
	if (in_assoc_array("type_s_zip",$_POST) || in_assoc_array("value_s_zip",$_POST) || in_assoc_array("value_s_zip",$_FILES))
	{
		$value=prepare_for_db("s_zip",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_zip"]=$value;
	}
	}
//	processibng s_zip - end
//	processing s_country - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_country");
	$type=postvalue("type_s_country");
	if (in_assoc_array("type_s_country",$_POST) || in_assoc_array("value_s_country",$_POST) || in_assoc_array("value_s_country",$_FILES))
	{
		$value=prepare_for_db("s_country",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_country"]=$value;
	}
	}
//	processibng s_country - end
//	processing s_phone1 - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_phone1");
	$type=postvalue("type_s_phone1");
	if (in_assoc_array("type_s_phone1",$_POST) || in_assoc_array("value_s_phone1",$_POST) || in_assoc_array("value_s_phone1",$_FILES))
	{
		$value=prepare_for_db("s_phone1",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_phone1"]=$value;
	}
	}
//	processibng s_phone1 - end
//	processing s_phone2 - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_phone2");
	$type=postvalue("type_s_phone2");
	if (in_assoc_array("type_s_phone2",$_POST) || in_assoc_array("value_s_phone2",$_POST) || in_assoc_array("value_s_phone2",$_FILES))
	{
		$value=prepare_for_db("s_phone2",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_phone2"]=$value;
	}
	}
//	processibng s_phone2 - end
//	processing s_total - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_total");
	$type=postvalue("type_s_total");
	if (in_assoc_array("type_s_total",$_POST) || in_assoc_array("value_s_total",$_POST) || in_assoc_array("value_s_total",$_FILES))
	{
		$value=prepare_for_db("s_total",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_total"]=$value;
	}
	}
//	processibng s_total - end
//	processing s_paid - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_paid");
	$type=postvalue("type_s_paid");
	if (in_assoc_array("type_s_paid",$_POST) || in_assoc_array("value_s_paid",$_POST) || in_assoc_array("value_s_paid",$_FILES))
	{
		$value=prepare_for_db("s_paid",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_paid"]=$value;
	}
	}
//	processibng s_paid - end
//	processing s_logo - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_logo");
	$type=postvalue("type_s_logo");
	if (in_assoc_array("type_s_logo",$_POST) || in_assoc_array("value_s_logo",$_POST) || in_assoc_array("value_s_logo",$_FILES))
	{
		$value=prepare_for_db("s_logo",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_logo"]=$value;
	}
	}
//	processibng s_logo - end
//	processing s_banner - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_banner");
	$type=postvalue("type_s_banner");
	if (in_assoc_array("type_s_banner",$_POST) || in_assoc_array("value_s_banner",$_POST) || in_assoc_array("value_s_banner",$_FILES))
	{
		$value=prepare_for_db("s_banner",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_banner"]=$value;
	}
	}
//	processibng s_banner - end
//	processing s_www - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_www");
	$type=postvalue("type_s_www");
	if (in_assoc_array("type_s_www",$_POST) || in_assoc_array("value_s_www",$_POST) || in_assoc_array("value_s_www",$_FILES))
	{
		$value=prepare_for_db("s_www",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_www"]=$value;
	}
	}
//	processibng s_www - end
//	processing s_mail - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_mail");
	$type=postvalue("type_s_mail");
	if (in_assoc_array("type_s_mail",$_POST) || in_assoc_array("value_s_mail",$_POST) || in_assoc_array("value_s_mail",$_FILES))
	{
		$value=prepare_for_db("s_mail",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_mail"]=$value;
	}
	}
//	processibng s_mail - end
//	processing s_cmt - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_s_cmt");
	$type=postvalue("type_s_cmt");
	if (in_assoc_array("type_s_cmt",$_POST) || in_assoc_array("value_s_cmt",$_POST) || in_assoc_array("value_s_cmt",$_FILES))
	{
		$value=prepare_for_db("s_cmt",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["s_cmt"]=$value;
	}
	}
//	processibng s_cmt - end





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
						$keys["s_id"]=mysql_insert_id($conn);
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
		$copykeys["s_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["s_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["s_id"]="";
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
	$defvalues["s_active"]=@$avalues["s_active"];
	$defvalues["s_name"]=@$avalues["s_name"];
	$defvalues["s_contact"]=@$avalues["s_contact"];
	$defvalues["s_adr"]=@$avalues["s_adr"];
	$defvalues["s_zip"]=@$avalues["s_zip"];
	$defvalues["s_phone1"]=@$avalues["s_phone1"];
	$defvalues["s_phone2"]=@$avalues["s_phone2"];
	$defvalues["s_total"]=@$avalues["s_total"];
	$defvalues["s_paid"]=@$avalues["s_paid"];
	$defvalues["s_www"]=@$avalues["s_www"];
	$defvalues["s_mail"]=@$avalues["s_mail"];
	$defvalues["s_cmt"]=@$avalues["s_cmt"];
	$defvalues["s_country"]=@$avalues["s_country"];
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
			$validatetype="";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_s_active_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_s_active','".$validatetype."','Status');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_s_zip_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_s_zip','".$validatetype."','Zip');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_s_total_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_s_total','".$validatetype."','Amount Sponsored');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_s_paid_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_s_paid','".$validatetype."','Amount Paid');";
			
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
	$includes.="var SUGGEST_TABLE='sponsor_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}




	$xt->assign("s_active_fieldblock",true);
	$xt->assign("s_name_fieldblock",true);
	$xt->assign("s_contact_fieldblock",true);
	$xt->assign("s_adr_fieldblock",true);
	$xt->assign("s_zip_fieldblock",true);
	$xt->assign("s_phone1_fieldblock",true);
	$xt->assign("s_phone2_fieldblock",true);
	$xt->assign("s_total_fieldblock",true);
	$xt->assign("s_paid_fieldblock",true);
	$xt->assign("s_logo_fieldblock",true);
	$xt->assign("s_banner_fieldblock",true);
	$xt->assign("s_www_fieldblock",true);
	$xt->assign("s_mail_fieldblock",true);
	$xt->assign("s_cmt_fieldblock",true);
	$xt->assign("s_country_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"sponsor_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='sponsor_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"sponsor_add.php\" ".$onsubmit.">".
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
$control_s_active=array();
$control_s_active["func"]="xt_buildeditcontrol";
$control_s_active["params"] = array();
$control_s_active["params"]["field"]="s_active";
$control_s_active["params"]["value"]=@$defvalues["s_active"];
$control_s_active["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_active["params"]["mode"]="inline_add";
else
	$control_s_active["params"]["mode"]="add";
$xt->assignbyref("s_active_editcontrol",$control_s_active);
$control_s_name=array();
$control_s_name["func"]="xt_buildeditcontrol";
$control_s_name["params"] = array();
$control_s_name["params"]["field"]="s_name";
$control_s_name["params"]["value"]=@$defvalues["s_name"];
$control_s_name["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_name["params"]["mode"]="inline_add";
else
	$control_s_name["params"]["mode"]="add";
$xt->assignbyref("s_name_editcontrol",$control_s_name);
$control_s_contact=array();
$control_s_contact["func"]="xt_buildeditcontrol";
$control_s_contact["params"] = array();
$control_s_contact["params"]["field"]="s_contact";
$control_s_contact["params"]["value"]=@$defvalues["s_contact"];
$control_s_contact["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_contact["params"]["mode"]="inline_add";
else
	$control_s_contact["params"]["mode"]="add";
$xt->assignbyref("s_contact_editcontrol",$control_s_contact);
$control_s_adr=array();
$control_s_adr["func"]="xt_buildeditcontrol";
$control_s_adr["params"] = array();
$control_s_adr["params"]["field"]="s_adr";
$control_s_adr["params"]["value"]=@$defvalues["s_adr"];
$control_s_adr["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_adr["params"]["mode"]="inline_add";
else
	$control_s_adr["params"]["mode"]="add";
$xt->assignbyref("s_adr_editcontrol",$control_s_adr);
$control_s_zip=array();
$control_s_zip["func"]="xt_buildeditcontrol";
$control_s_zip["params"] = array();
$control_s_zip["params"]["field"]="s_zip";
$control_s_zip["params"]["value"]=@$defvalues["s_zip"];
$control_s_zip["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_zip["params"]["mode"]="inline_add";
else
	$control_s_zip["params"]["mode"]="add";
$xt->assignbyref("s_zip_editcontrol",$control_s_zip);
$control_s_phone1=array();
$control_s_phone1["func"]="xt_buildeditcontrol";
$control_s_phone1["params"] = array();
$control_s_phone1["params"]["field"]="s_phone1";
$control_s_phone1["params"]["value"]=@$defvalues["s_phone1"];
$control_s_phone1["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_phone1["params"]["mode"]="inline_add";
else
	$control_s_phone1["params"]["mode"]="add";
$xt->assignbyref("s_phone1_editcontrol",$control_s_phone1);
$control_s_phone2=array();
$control_s_phone2["func"]="xt_buildeditcontrol";
$control_s_phone2["params"] = array();
$control_s_phone2["params"]["field"]="s_phone2";
$control_s_phone2["params"]["value"]=@$defvalues["s_phone2"];
$control_s_phone2["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_phone2["params"]["mode"]="inline_add";
else
	$control_s_phone2["params"]["mode"]="add";
$xt->assignbyref("s_phone2_editcontrol",$control_s_phone2);
$control_s_total=array();
$control_s_total["func"]="xt_buildeditcontrol";
$control_s_total["params"] = array();
$control_s_total["params"]["field"]="s_total";
$control_s_total["params"]["value"]=@$defvalues["s_total"];
$control_s_total["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_total["params"]["mode"]="inline_add";
else
	$control_s_total["params"]["mode"]="add";
$xt->assignbyref("s_total_editcontrol",$control_s_total);
$control_s_paid=array();
$control_s_paid["func"]="xt_buildeditcontrol";
$control_s_paid["params"] = array();
$control_s_paid["params"]["field"]="s_paid";
$control_s_paid["params"]["value"]=@$defvalues["s_paid"];
$control_s_paid["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_paid["params"]["mode"]="inline_add";
else
	$control_s_paid["params"]["mode"]="add";
$xt->assignbyref("s_paid_editcontrol",$control_s_paid);
$control_s_logo=array();
$control_s_logo["func"]="xt_buildeditcontrol";
$control_s_logo["params"] = array();
$control_s_logo["params"]["field"]="s_logo";
$control_s_logo["params"]["value"]=@$defvalues["s_logo"];
$control_s_logo["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_logo["params"]["mode"]="inline_add";
else
	$control_s_logo["params"]["mode"]="add";
$xt->assignbyref("s_logo_editcontrol",$control_s_logo);
$control_s_banner=array();
$control_s_banner["func"]="xt_buildeditcontrol";
$control_s_banner["params"] = array();
$control_s_banner["params"]["field"]="s_banner";
$control_s_banner["params"]["value"]=@$defvalues["s_banner"];
$control_s_banner["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_banner["params"]["mode"]="inline_add";
else
	$control_s_banner["params"]["mode"]="add";
$xt->assignbyref("s_banner_editcontrol",$control_s_banner);
$control_s_www=array();
$control_s_www["func"]="xt_buildeditcontrol";
$control_s_www["params"] = array();
$control_s_www["params"]["field"]="s_www";
$control_s_www["params"]["value"]=@$defvalues["s_www"];
$control_s_www["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_www["params"]["mode"]="inline_add";
else
	$control_s_www["params"]["mode"]="add";
$xt->assignbyref("s_www_editcontrol",$control_s_www);
$control_s_mail=array();
$control_s_mail["func"]="xt_buildeditcontrol";
$control_s_mail["params"] = array();
$control_s_mail["params"]["field"]="s_mail";
$control_s_mail["params"]["value"]=@$defvalues["s_mail"];
$control_s_mail["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_mail["params"]["mode"]="inline_add";
else
	$control_s_mail["params"]["mode"]="add";
$xt->assignbyref("s_mail_editcontrol",$control_s_mail);
$control_s_cmt=array();
$control_s_cmt["func"]="xt_buildeditcontrol";
$control_s_cmt["params"] = array();
$control_s_cmt["params"]["field"]="s_cmt";
$control_s_cmt["params"]["value"]=@$defvalues["s_cmt"];
$control_s_cmt["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_cmt["params"]["mode"]="inline_add";
else
	$control_s_cmt["params"]["mode"]="add";
$xt->assignbyref("s_cmt_editcontrol",$control_s_cmt);
$control_s_country=array();
$control_s_country["func"]="xt_buildeditcontrol";
$control_s_country["params"] = array();
$control_s_country["params"]["field"]="s_country";
$control_s_country["params"]["value"]=@$defvalues["s_country"];
$control_s_country["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_s_country["params"]["mode"]="inline_add";
else
	$control_s_country["params"]["mode"]="add";
$xt->assignbyref("s_country_editcontrol",$control_s_country);

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
