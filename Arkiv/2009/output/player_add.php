<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/player_variables.php");
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
	$templatefile = "player_inline_add.htm";
else
	$templatefile = "player_add.htm";

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
//	processing p_active - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_active");
	$type=postvalue("type_p_active");
	if (in_assoc_array("type_p_active",$_POST) || in_assoc_array("value_p_active",$_POST) || in_assoc_array("value_p_active",$_FILES))
	{
		$value=prepare_for_db("p_active",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_active"]=$value;
	}
	}
//	processibng p_active - end
//	processing p_first - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_first");
	$type=postvalue("type_p_first");
	if (in_assoc_array("type_p_first",$_POST) || in_assoc_array("value_p_first",$_POST) || in_assoc_array("value_p_first",$_FILES))
	{
		$value=prepare_for_db("p_first",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_first"]=$value;
	}
	}
//	processibng p_first - end
//	processing p_name - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_name");
	$type=postvalue("type_p_name");
	if (in_assoc_array("type_p_name",$_POST) || in_assoc_array("value_p_name",$_POST) || in_assoc_array("value_p_name",$_FILES))
	{
		$value=prepare_for_db("p_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_name"]=$value;
	}
	}
//	processibng p_name - end
//	processing p_adr - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_adr");
	$type=postvalue("type_p_adr");
	if (in_assoc_array("type_p_adr",$_POST) || in_assoc_array("value_p_adr",$_POST) || in_assoc_array("value_p_adr",$_FILES))
	{
		$value=prepare_for_db("p_adr",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_adr"]=$value;
	}
	}
//	processibng p_adr - end
//	processing p_zip - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_zip");
	$type=postvalue("type_p_zip");
	if (in_assoc_array("type_p_zip",$_POST) || in_assoc_array("value_p_zip",$_POST) || in_assoc_array("value_p_zip",$_FILES))
	{
		$value=prepare_for_db("p_zip",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_zip"]=$value;
	}
	}
//	processibng p_zip - end
//	processing p_country - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_country");
	$type=postvalue("type_p_country");
	if (in_assoc_array("type_p_country",$_POST) || in_assoc_array("value_p_country",$_POST) || in_assoc_array("value_p_country",$_FILES))
	{
		$value=prepare_for_db("p_country",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_country"]=$value;
	}
	}
//	processibng p_country - end
//	processing p_mail - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_mail");
	$type=postvalue("type_p_mail");
	if (in_assoc_array("type_p_mail",$_POST) || in_assoc_array("value_p_mail",$_POST) || in_assoc_array("value_p_mail",$_FILES))
	{
		$value=prepare_for_db("p_mail",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_mail"]=$value;
	}
	}
//	processibng p_mail - end
//	processing p_mobile - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_mobile");
	$type=postvalue("type_p_mobile");
	if (in_assoc_array("type_p_mobile",$_POST) || in_assoc_array("value_p_mobile",$_POST) || in_assoc_array("value_p_mobile",$_FILES))
	{
		$value=prepare_for_db("p_mobile",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_mobile"]=$value;
	}
	}
//	processibng p_mobile - end
//	processing p_newsaccept - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_newsaccept");
	$type=postvalue("type_p_newsaccept");
	if (in_assoc_array("type_p_newsaccept",$_POST) || in_assoc_array("value_p_newsaccept",$_POST) || in_assoc_array("value_p_newsaccept",$_FILES))
	{
		$value=prepare_for_db("p_newsaccept",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_newsaccept"]=$value;
	}
	}
//	processibng p_newsaccept - end
//	processing p_win - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_win");
	$type=postvalue("type_p_win");
	if (in_assoc_array("type_p_win",$_POST) || in_assoc_array("value_p_win",$_POST) || in_assoc_array("value_p_win",$_FILES))
	{
		$value=prepare_for_db("p_win",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_win"]=$value;
	}
	}
//	processibng p_win - end
//	processing p_mk - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_mk");
	$type=postvalue("type_p_mk");
	if (in_assoc_array("type_p_mk",$_POST) || in_assoc_array("value_p_mk",$_POST) || in_assoc_array("value_p_mk",$_FILES))
	{
		$value=prepare_for_db("p_mk",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_mk"]=$value;
	}
	}
//	processibng p_mk - end
//	processing p_born - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_born");
	$type=postvalue("type_p_born");
	if (in_assoc_array("type_p_born",$_POST) || in_assoc_array("value_p_born",$_POST) || in_assoc_array("value_p_born",$_FILES))
	{
		$value=prepare_for_db("p_born",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_born"]=$value;
	}
	}
//	processibng p_born - end
//	processing p_user - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_user");
	$type=postvalue("type_p_user");
	if (in_assoc_array("type_p_user",$_POST) || in_assoc_array("value_p_user",$_POST) || in_assoc_array("value_p_user",$_FILES))
	{
		$value=prepare_for_db("p_user",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_user"]=$value;
	}
	}
//	processibng p_user - end
//	processing p_pwd - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_p_pwd");
	$type=postvalue("type_p_pwd");
	if (in_assoc_array("type_p_pwd",$_POST) || in_assoc_array("value_p_pwd",$_POST) || in_assoc_array("value_p_pwd",$_FILES))
	{
		$value=prepare_for_db("p_pwd",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["p_pwd"]=$value;
	}
	}
//	processibng p_pwd - end




//	insert masterkey value if exists and if not specified
	if(@$_SESSION[$strTableName."_mastertable"]=="teams")
	{
		$avalues["p_s_id"]=prepare_for_db("p_s_id",$_SESSION[$strTableName."_masterkey1"]);
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
						$keys["p_id"]=mysql_insert_id($conn);
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
		$copykeys["p_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["p_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["p_id"]="";
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
	$defvalues["p_active"]=@$avalues["p_active"];
	$defvalues["p_first"]=@$avalues["p_first"];
	$defvalues["p_name"]=@$avalues["p_name"];
	$defvalues["p_adr"]=@$avalues["p_adr"];
	$defvalues["p_zip"]=@$avalues["p_zip"];
	$defvalues["p_mail"]=@$avalues["p_mail"];
	$defvalues["p_newsaccept"]=@$avalues["p_newsaccept"];
	$defvalues["p_win"]=@$avalues["p_win"];
	$defvalues["p_mk"]=@$avalues["p_mk"];
	$defvalues["p_born"]=@$avalues["p_born"];
	$defvalues["p_user"]=@$avalues["p_user"];
	$defvalues["p_pwd"]=@$avalues["p_pwd"];
	$defvalues["p_country"]=@$avalues["p_country"];
	$defvalues["p_mobile"]=@$avalues["p_mobile"];
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
				$linkdata.="define_fly('value_p_active_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_p_active','".$validatetype."','Status');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_p_zip_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_p_zip','".$validatetype."','Zip');";
			
		}
			$validatetype="";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_p_newsaccept_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_p_newsaccept','".$validatetype."','Newsaccept');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_p_win_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_p_win','".$validatetype."','Winner');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_p_mobile_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_p_mobile','".$validatetype."','Mobile');";
			
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
	$includes.="var SUGGEST_TABLE='player_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}




	$xt->assign("p_active_fieldblock",true);
	$xt->assign("p_first_fieldblock",true);
	$xt->assign("p_name_fieldblock",true);
	$xt->assign("p_adr_fieldblock",true);
	$xt->assign("p_zip_fieldblock",true);
	$xt->assign("p_mail_fieldblock",true);
	$xt->assign("p_newsaccept_fieldblock",true);
	$xt->assign("p_win_fieldblock",true);
	$xt->assign("p_mk_fieldblock",true);
	$xt->assign("p_born_fieldblock",true);
	$xt->assign("p_user_fieldblock",true);
	$xt->assign("p_pwd_fieldblock",true);
	$xt->assign("p_country_fieldblock",true);
	$xt->assign("p_mobile_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"player_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='player_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"player_add.php\" ".$onsubmit.">".
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
$control_p_active=array();
$control_p_active["func"]="xt_buildeditcontrol";
$control_p_active["params"] = array();
$control_p_active["params"]["field"]="p_active";
$control_p_active["params"]["value"]=@$defvalues["p_active"];
$control_p_active["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_active["params"]["mode"]="inline_add";
else
	$control_p_active["params"]["mode"]="add";
$xt->assignbyref("p_active_editcontrol",$control_p_active);
$control_p_first=array();
$control_p_first["func"]="xt_buildeditcontrol";
$control_p_first["params"] = array();
$control_p_first["params"]["field"]="p_first";
$control_p_first["params"]["value"]=@$defvalues["p_first"];
$control_p_first["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_first["params"]["mode"]="inline_add";
else
	$control_p_first["params"]["mode"]="add";
$xt->assignbyref("p_first_editcontrol",$control_p_first);
$control_p_name=array();
$control_p_name["func"]="xt_buildeditcontrol";
$control_p_name["params"] = array();
$control_p_name["params"]["field"]="p_name";
$control_p_name["params"]["value"]=@$defvalues["p_name"];
$control_p_name["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_name["params"]["mode"]="inline_add";
else
	$control_p_name["params"]["mode"]="add";
$xt->assignbyref("p_name_editcontrol",$control_p_name);
$control_p_adr=array();
$control_p_adr["func"]="xt_buildeditcontrol";
$control_p_adr["params"] = array();
$control_p_adr["params"]["field"]="p_adr";
$control_p_adr["params"]["value"]=@$defvalues["p_adr"];
$control_p_adr["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_adr["params"]["mode"]="inline_add";
else
	$control_p_adr["params"]["mode"]="add";
$xt->assignbyref("p_adr_editcontrol",$control_p_adr);
$control_p_zip=array();
$control_p_zip["func"]="xt_buildeditcontrol";
$control_p_zip["params"] = array();
$control_p_zip["params"]["field"]="p_zip";
$control_p_zip["params"]["value"]=@$defvalues["p_zip"];
$control_p_zip["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_zip["params"]["mode"]="inline_add";
else
	$control_p_zip["params"]["mode"]="add";
$xt->assignbyref("p_zip_editcontrol",$control_p_zip);
$control_p_mail=array();
$control_p_mail["func"]="xt_buildeditcontrol";
$control_p_mail["params"] = array();
$control_p_mail["params"]["field"]="p_mail";
$control_p_mail["params"]["value"]=@$defvalues["p_mail"];
$control_p_mail["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_mail["params"]["mode"]="inline_add";
else
	$control_p_mail["params"]["mode"]="add";
$xt->assignbyref("p_mail_editcontrol",$control_p_mail);
$control_p_newsaccept=array();
$control_p_newsaccept["func"]="xt_buildeditcontrol";
$control_p_newsaccept["params"] = array();
$control_p_newsaccept["params"]["field"]="p_newsaccept";
$control_p_newsaccept["params"]["value"]=@$defvalues["p_newsaccept"];
$control_p_newsaccept["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_newsaccept["params"]["mode"]="inline_add";
else
	$control_p_newsaccept["params"]["mode"]="add";
$xt->assignbyref("p_newsaccept_editcontrol",$control_p_newsaccept);
$control_p_win=array();
$control_p_win["func"]="xt_buildeditcontrol";
$control_p_win["params"] = array();
$control_p_win["params"]["field"]="p_win";
$control_p_win["params"]["value"]=@$defvalues["p_win"];
$control_p_win["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_win["params"]["mode"]="inline_add";
else
	$control_p_win["params"]["mode"]="add";
$xt->assignbyref("p_win_editcontrol",$control_p_win);
$control_p_mk=array();
$control_p_mk["func"]="xt_buildeditcontrol";
$control_p_mk["params"] = array();
$control_p_mk["params"]["field"]="p_mk";
$control_p_mk["params"]["value"]=@$defvalues["p_mk"];
$control_p_mk["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_mk["params"]["mode"]="inline_add";
else
	$control_p_mk["params"]["mode"]="add";
$xt->assignbyref("p_mk_editcontrol",$control_p_mk);
$control_p_born=array();
$control_p_born["func"]="xt_buildeditcontrol";
$control_p_born["params"] = array();
$control_p_born["params"]["field"]="p_born";
$control_p_born["params"]["value"]=@$defvalues["p_born"];
$control_p_born["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_born["params"]["mode"]="inline_add";
else
	$control_p_born["params"]["mode"]="add";
$xt->assignbyref("p_born_editcontrol",$control_p_born);
$control_p_user=array();
$control_p_user["func"]="xt_buildeditcontrol";
$control_p_user["params"] = array();
$control_p_user["params"]["field"]="p_user";
$control_p_user["params"]["value"]=@$defvalues["p_user"];
$control_p_user["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_user["params"]["mode"]="inline_add";
else
	$control_p_user["params"]["mode"]="add";
$xt->assignbyref("p_user_editcontrol",$control_p_user);
$control_p_pwd=array();
$control_p_pwd["func"]="xt_buildeditcontrol";
$control_p_pwd["params"] = array();
$control_p_pwd["params"]["field"]="p_pwd";
$control_p_pwd["params"]["value"]=@$defvalues["p_pwd"];
$control_p_pwd["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_pwd["params"]["mode"]="inline_add";
else
	$control_p_pwd["params"]["mode"]="add";
$xt->assignbyref("p_pwd_editcontrol",$control_p_pwd);
$control_p_country=array();
$control_p_country["func"]="xt_buildeditcontrol";
$control_p_country["params"] = array();
$control_p_country["params"]["field"]="p_country";
$control_p_country["params"]["value"]=@$defvalues["p_country"];
$control_p_country["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_country["params"]["mode"]="inline_add";
else
	$control_p_country["params"]["mode"]="add";
$xt->assignbyref("p_country_editcontrol",$control_p_country);
$control_p_mobile=array();
$control_p_mobile["func"]="xt_buildeditcontrol";
$control_p_mobile["params"] = array();
$control_p_mobile["params"]["field"]="p_mobile";
$control_p_mobile["params"]["value"]=@$defvalues["p_mobile"];
$control_p_mobile["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_p_mobile["params"]["mode"]="inline_add";
else
	$control_p_mobile["params"]["mode"]="add";
$xt->assignbyref("p_mobile_editcontrol",$control_p_mobile);

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
