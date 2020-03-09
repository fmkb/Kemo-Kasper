<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/news_variables.php");
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
	$templatefile = "news_inline_add.htm";
else
	$templatefile = "news_add.htm";

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
//	processing n_active - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_active");
	$type=postvalue("type_n_active");
	if (in_assoc_array("type_n_active",$_POST) || in_assoc_array("value_n_active",$_POST) || in_assoc_array("value_n_active",$_FILES))
	{
		$value=prepare_for_db("n_active",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_active"]=$value;
	}
	}
//	processibng n_active - end
//	processing n_start - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_start");
	$type=postvalue("type_n_start");
	if (in_assoc_array("type_n_start",$_POST) || in_assoc_array("value_n_start",$_POST) || in_assoc_array("value_n_start",$_FILES))
	{
		$value=prepare_for_db("n_start",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_start"]=$value;
	}
	}
//	processibng n_start - end
//	processing n_end - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_end");
	$type=postvalue("type_n_end");
	if (in_assoc_array("type_n_end",$_POST) || in_assoc_array("value_n_end",$_POST) || in_assoc_array("value_n_end",$_FILES))
	{
		$value=prepare_for_db("n_end",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_end"]=$value;
	}
	}
//	processibng n_end - end
//	processing n_date - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_date");
	$type=postvalue("type_n_date");
	if (in_assoc_array("type_n_date",$_POST) || in_assoc_array("value_n_date",$_POST) || in_assoc_array("value_n_date",$_FILES))
	{
		$value=prepare_for_db("n_date",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_date"]=$value;
	}
	}
//	processibng n_date - end
//	processing n_head - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_head");
	$type=postvalue("type_n_head");
	if (in_assoc_array("type_n_head",$_POST) || in_assoc_array("value_n_head",$_POST) || in_assoc_array("value_n_head",$_FILES))
	{
		$value=prepare_for_db("n_head",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_head"]=$value;
	}
	}
//	processibng n_head - end
//	processing n_teaser - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_teaser");
	$type=postvalue("type_n_teaser");
	if (in_assoc_array("type_n_teaser",$_POST) || in_assoc_array("value_n_teaser",$_POST) || in_assoc_array("value_n_teaser",$_FILES))
	{
		$value=prepare_for_db("n_teaser",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_teaser"]=$value;
	}
	}
//	processibng n_teaser - end
//	processing n_text - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_text");
	$type=postvalue("type_n_text");
	if (in_assoc_array("type_n_text",$_POST) || in_assoc_array("value_n_text",$_POST) || in_assoc_array("value_n_text",$_FILES))
	{
		$value=prepare_for_db("n_text",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_text"]=$value;
	}
	}
//	processibng n_text - end
//	processing n_file - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_file");
	$type=postvalue("type_n_file");
	if (in_assoc_array("type_n_file",$_POST) || in_assoc_array("value_n_file",$_POST) || in_assoc_array("value_n_file",$_FILES))
	{
		$value=prepare_for_db("n_file",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{
		if($value)
		{
				$ext = CheckImageExtension($_FILES["file_n_file"]["name"]);
			$contents = myfile_get_contents($_FILES["file_n_file"]['tmp_name']);
			$thumb = CreateThumbnail($contents,150,$ext);
			$file = GetUploadFolder("n_file")."th_".$value;
			if(file_exists($file))
					@unlink($file);
			$th = fopen($file,"w");
			fwrite($th,$thumb);
			fclose($th);
		}


		$avalues["n_file"]=$value;
	}
	}
//	processibng n_file - end
//	processing n_type - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_type");
	$type=postvalue("type_n_type");
	if (in_assoc_array("type_n_type",$_POST) || in_assoc_array("value_n_type",$_POST) || in_assoc_array("value_n_type",$_FILES))
	{
		$value=prepare_for_db("n_type",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_type"]=$value;
	}
	}
//	processibng n_type - end
//	processing n_country - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_n_country");
	$type=postvalue("type_n_country");
	if (in_assoc_array("type_n_country",$_POST) || in_assoc_array("value_n_country",$_POST) || in_assoc_array("value_n_country",$_FILES))
	{
		$value=prepare_for_db("n_country",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["n_country"]=$value;
	}
	}
//	processibng n_country - end





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
						$keys["n_id"]=mysql_insert_id($conn);
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
		$copykeys["n_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["n_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["n_id"]="";
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
	$defvalues["n_active"]=@$avalues["n_active"];
	$defvalues["n_start"]=@$avalues["n_start"];
	$defvalues["n_end"]=@$avalues["n_end"];
	$defvalues["n_date"]=@$avalues["n_date"];
	$defvalues["n_head"]=@$avalues["n_head"];
	$defvalues["n_text"]=@$avalues["n_text"];
	$defvalues["n_type"]=@$avalues["n_type"];
	$defvalues["n_teaser"]=@$avalues["n_teaser"];
	$defvalues["n_country"]=@$avalues["n_country"];
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
				$linkdata.="define_fly('value_n_active_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_n_active','".$validatetype."','Active');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_n_type_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_n_type','".$validatetype."','News Type');";
			
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
	$includes.="var SUGGEST_TABLE='news_searchsuggest.php';\r\n";
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
		



	$xt->assign("n_active_fieldblock",true);
	$xt->assign("n_start_fieldblock",true);
	$xt->assign("n_end_fieldblock",true);
	$xt->assign("n_date_fieldblock",true);
	$xt->assign("n_head_fieldblock",true);
	$xt->assign("n_text_fieldblock",true);
	$xt->assign("n_file_fieldblock",true);
	$xt->assign("n_type_fieldblock",true);
	$xt->assign("n_teaser_fieldblock",true);
	$xt->assign("n_country_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"news_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='news_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"news_add.php\" ".$onsubmit.">".
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
$control_n_active=array();
$control_n_active["func"]="xt_buildeditcontrol";
$control_n_active["params"] = array();
$control_n_active["params"]["field"]="n_active";
$control_n_active["params"]["value"]=@$defvalues["n_active"];
$control_n_active["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_active["params"]["mode"]="inline_add";
else
	$control_n_active["params"]["mode"]="add";
$xt->assignbyref("n_active_editcontrol",$control_n_active);
$control_n_start=array();
$control_n_start["func"]="xt_buildeditcontrol";
$control_n_start["params"] = array();
$control_n_start["params"]["field"]="n_start";
$control_n_start["params"]["value"]=@$defvalues["n_start"];
$control_n_start["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_start["params"]["mode"]="inline_add";
else
	$control_n_start["params"]["mode"]="add";
$xt->assignbyref("n_start_editcontrol",$control_n_start);
$control_n_end=array();
$control_n_end["func"]="xt_buildeditcontrol";
$control_n_end["params"] = array();
$control_n_end["params"]["field"]="n_end";
$control_n_end["params"]["value"]=@$defvalues["n_end"];
$control_n_end["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_end["params"]["mode"]="inline_add";
else
	$control_n_end["params"]["mode"]="add";
$xt->assignbyref("n_end_editcontrol",$control_n_end);
$control_n_date=array();
$control_n_date["func"]="xt_buildeditcontrol";
$control_n_date["params"] = array();
$control_n_date["params"]["field"]="n_date";
$control_n_date["params"]["value"]=@$defvalues["n_date"];
$control_n_date["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_date["params"]["mode"]="inline_add";
else
	$control_n_date["params"]["mode"]="add";
$xt->assignbyref("n_date_editcontrol",$control_n_date);
$control_n_head=array();
$control_n_head["func"]="xt_buildeditcontrol";
$control_n_head["params"] = array();
$control_n_head["params"]["field"]="n_head";
$control_n_head["params"]["value"]=@$defvalues["n_head"];
$control_n_head["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_head["params"]["mode"]="inline_add";
else
	$control_n_head["params"]["mode"]="add";
$xt->assignbyref("n_head_editcontrol",$control_n_head);
$control_n_text=array();
$control_n_text["func"]="xt_buildeditcontrol";
$control_n_text["params"] = array();
$control_n_text["params"]["field"]="n_text";
$control_n_text["params"]["value"]=@$defvalues["n_text"];
$control_n_text["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_text["params"]["mode"]="inline_add";
else
	$control_n_text["params"]["mode"]="add";
$xt->assignbyref("n_text_editcontrol",$control_n_text);
$control_n_file=array();
$control_n_file["func"]="xt_buildeditcontrol";
$control_n_file["params"] = array();
$control_n_file["params"]["field"]="n_file";
$control_n_file["params"]["value"]=@$defvalues["n_file"];
$control_n_file["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_file["params"]["mode"]="inline_add";
else
	$control_n_file["params"]["mode"]="add";
$xt->assignbyref("n_file_editcontrol",$control_n_file);
$control_n_type=array();
$control_n_type["func"]="xt_buildeditcontrol";
$control_n_type["params"] = array();
$control_n_type["params"]["field"]="n_type";
$control_n_type["params"]["value"]=@$defvalues["n_type"];
$control_n_type["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_type["params"]["mode"]="inline_add";
else
	$control_n_type["params"]["mode"]="add";
$xt->assignbyref("n_type_editcontrol",$control_n_type);
$control_n_teaser=array();
$control_n_teaser["func"]="xt_buildeditcontrol";
$control_n_teaser["params"] = array();
$control_n_teaser["params"]["field"]="n_teaser";
$control_n_teaser["params"]["value"]=@$defvalues["n_teaser"];
$control_n_teaser["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_teaser["params"]["mode"]="inline_add";
else
	$control_n_teaser["params"]["mode"]="add";
$xt->assignbyref("n_teaser_editcontrol",$control_n_teaser);
$control_n_country=array();
$control_n_country["func"]="xt_buildeditcontrol";
$control_n_country["params"] = array();
$control_n_country["params"]["field"]="n_country";
$control_n_country["params"]["value"]=@$defvalues["n_country"];
$control_n_country["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_n_country["params"]["mode"]="inline_add";
else
	$control_n_country["params"]["mode"]="add";
$xt->assignbyref("n_country_editcontrol",$control_n_country);

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
