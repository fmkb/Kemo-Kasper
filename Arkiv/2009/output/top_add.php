<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/top_variables.php");
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
	$templatefile = "top_inline_add.htm";
else
	$templatefile = "top_add.htm";

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
//	processing t_user - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_t_user");
	$type=postvalue("type_t_user");
	if (in_assoc_array("type_t_user",$_POST) || in_assoc_array("value_t_user",$_POST) || in_assoc_array("value_t_user",$_FILES))
	{
		$value=prepare_for_db("t_user",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["t_user"]=$value;
	}
	}
//	processibng t_user - end
//	processing t_score - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_t_score");
	$type=postvalue("type_t_score");
	if (in_assoc_array("type_t_score",$_POST) || in_assoc_array("value_t_score",$_POST) || in_assoc_array("value_t_score",$_FILES))
	{
		$value=prepare_for_db("t_score",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["t_score"]=$value;
	}
	}
//	processibng t_score - end
//	processing t_datetime - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_t_datetime");
	$type=postvalue("type_t_datetime");
	if (in_assoc_array("type_t_datetime",$_POST) || in_assoc_array("value_t_datetime",$_POST) || in_assoc_array("value_t_datetime",$_FILES))
	{
		$value=prepare_for_db("t_datetime",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["t_datetime"]=$value;
	}
	}
//	processibng t_datetime - end
//	processing t_ip - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_t_ip");
	$type=postvalue("type_t_ip");
	if (in_assoc_array("type_t_ip",$_POST) || in_assoc_array("value_t_ip",$_POST) || in_assoc_array("value_t_ip",$_FILES))
	{
		$value=prepare_for_db("t_ip",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["t_ip"]=$value;
	}
	}
//	processibng t_ip - end
//	processing t_ts_id - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_t_ts_id");
	$type=postvalue("type_t_ts_id");
	if (in_assoc_array("type_t_ts_id",$_POST) || in_assoc_array("value_t_ts_id",$_POST) || in_assoc_array("value_t_ts_id",$_FILES))
	{
		$value=prepare_for_db("t_ts_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["t_ts_id"]=$value;
	}
	}
//	processibng t_ts_id - end
//	processing t_kills - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_t_kills");
	$type=postvalue("type_t_kills");
	if (in_assoc_array("type_t_kills",$_POST) || in_assoc_array("value_t_kills",$_POST) || in_assoc_array("value_t_kills",$_FILES))
	{
		$value=prepare_for_db("t_kills",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["t_kills"]=$value;
	}
	}
//	processibng t_kills - end




//	insert masterkey value if exists and if not specified
	if(@$_SESSION[$strTableName."_mastertable"]=="player")
	{
		$avalues["t_p_id"]=prepare_for_db("t_p_id",$_SESSION[$strTableName."_masterkey1"]);
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
						$keys["t_id"]=mysql_insert_id($conn);
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
		$copykeys["t_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["t_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["t_id"]="";
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
	$defvalues["t_user"]=@$avalues["t_user"];
	$defvalues["t_score"]=@$avalues["t_score"];
	$defvalues["t_datetime"]=@$avalues["t_datetime"];
	$defvalues["t_ip"]=@$avalues["t_ip"];
	$defvalues["t_ts_id"]=@$avalues["t_ts_id"];
	$defvalues["t_kills"]=@$avalues["t_kills"];
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
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_t_score_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_t_score','".$validatetype."','Score');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_t_datetime_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_t_datetime','".$validatetype."','Datetime');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_t_ts_id_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_t_ts_id','".$validatetype."','Team');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_t_kills_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_t_kills','".$validatetype."','Kills');";
			
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
	$includes.="var SUGGEST_TABLE='top_searchsuggest.php';\r\n";
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




	$xt->assign("t_user_fieldblock",true);
	$xt->assign("t_score_fieldblock",true);
	$xt->assign("t_datetime_fieldblock",true);
	$xt->assign("t_ip_fieldblock",true);
	$xt->assign("t_ts_id_fieldblock",true);
	$xt->assign("t_kills_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"top_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='top_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"top_add.php\" ".$onsubmit.">".
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
$control_t_user=array();
$control_t_user["func"]="xt_buildeditcontrol";
$control_t_user["params"] = array();
$control_t_user["params"]["field"]="t_user";
$control_t_user["params"]["value"]=@$defvalues["t_user"];
$control_t_user["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_t_user["params"]["mode"]="inline_add";
else
	$control_t_user["params"]["mode"]="add";
$xt->assignbyref("t_user_editcontrol",$control_t_user);
$control_t_score=array();
$control_t_score["func"]="xt_buildeditcontrol";
$control_t_score["params"] = array();
$control_t_score["params"]["field"]="t_score";
$control_t_score["params"]["value"]=@$defvalues["t_score"];
$control_t_score["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_t_score["params"]["mode"]="inline_add";
else
	$control_t_score["params"]["mode"]="add";
$xt->assignbyref("t_score_editcontrol",$control_t_score);
$control_t_datetime=array();
$control_t_datetime["func"]="xt_buildeditcontrol";
$control_t_datetime["params"] = array();
$control_t_datetime["params"]["field"]="t_datetime";
$control_t_datetime["params"]["value"]=@$defvalues["t_datetime"];
$control_t_datetime["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_t_datetime["params"]["mode"]="inline_add";
else
	$control_t_datetime["params"]["mode"]="add";
$xt->assignbyref("t_datetime_editcontrol",$control_t_datetime);
$control_t_ip=array();
$control_t_ip["func"]="xt_buildeditcontrol";
$control_t_ip["params"] = array();
$control_t_ip["params"]["field"]="t_ip";
$control_t_ip["params"]["value"]=@$defvalues["t_ip"];
$control_t_ip["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_t_ip["params"]["mode"]="inline_add";
else
	$control_t_ip["params"]["mode"]="add";
$xt->assignbyref("t_ip_editcontrol",$control_t_ip);
$control_t_ts_id=array();
$control_t_ts_id["func"]="xt_buildeditcontrol";
$control_t_ts_id["params"] = array();
$control_t_ts_id["params"]["field"]="t_ts_id";
$control_t_ts_id["params"]["value"]=@$defvalues["t_ts_id"];
$control_t_ts_id["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_t_ts_id["params"]["mode"]="inline_add";
else
	$control_t_ts_id["params"]["mode"]="add";
$xt->assignbyref("t_ts_id_editcontrol",$control_t_ts_id);
$control_t_kills=array();
$control_t_kills["func"]="xt_buildeditcontrol";
$control_t_kills["params"] = array();
$control_t_kills["params"]["field"]="t_kills";
$control_t_kills["params"]["value"]=@$defvalues["t_kills"];
$control_t_kills["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_t_kills["params"]["mode"]="inline_add";
else
	$control_t_kills["params"]["mode"]="add";
$xt->assignbyref("t_kills_editcontrol",$control_t_kills);

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
