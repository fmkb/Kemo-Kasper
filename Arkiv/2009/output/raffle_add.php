<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/raffle_variables.php");
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
	$templatefile = "raffle_inline_add.htm";
else
	$templatefile = "raffle_add.htm";

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
//	processing r_name - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_r_name");
	$type=postvalue("type_r_name");
	if (in_assoc_array("type_r_name",$_POST) || in_assoc_array("value_r_name",$_POST) || in_assoc_array("value_r_name",$_FILES))
	{
		$value=prepare_for_db("r_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["r_name"]=$value;
	}
	}
//	processibng r_name - end
//	processing r_img - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_r_img");
	$type=postvalue("type_r_img");
	if (in_assoc_array("type_r_img",$_POST) || in_assoc_array("value_r_img",$_POST) || in_assoc_array("value_r_img",$_FILES))
	{
		$value=prepare_for_db("r_img",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["r_img"]=$value;
	}
	}
//	processibng r_img - end
//	processing r_text - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_r_text");
	$type=postvalue("type_r_text");
	if (in_assoc_array("type_r_text",$_POST) || in_assoc_array("value_r_text",$_POST) || in_assoc_array("value_r_text",$_FILES))
	{
		$value=prepare_for_db("r_text",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["r_text"]=$value;
	}
	}
//	processibng r_text - end
//	processing r_date - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_r_date");
	$type=postvalue("type_r_date");
	if (in_assoc_array("type_r_date",$_POST) || in_assoc_array("value_r_date",$_POST) || in_assoc_array("value_r_date",$_FILES))
	{
		$value=prepare_for_db("r_date",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["r_date"]=$value;
	}
	}
//	processibng r_date - end




//	insert masterkey value if exists and if not specified
	if(@$_SESSION[$strTableName."_mastertable"]=="player")
	{
		$avalues["r_p_id"]=prepare_for_db("r_p_id",$_SESSION[$strTableName."_masterkey1"]);
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
						$keys["r_id"]=mysql_insert_id($conn);
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
		$copykeys["r_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["r_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["r_id"]="";
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
	$defvalues["r_name"]=@$avalues["r_name"];
	$defvalues["r_img"]=@$avalues["r_img"];
	$defvalues["r_text"]=@$avalues["r_text"];
	$defvalues["r_date"]=@$avalues["r_date"];
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
	$includes.="var SUGGEST_TABLE='raffle_searchsuggest.php';\r\n";
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




	$xt->assign("r_name_fieldblock",true);
	$xt->assign("r_img_fieldblock",true);
	$xt->assign("r_text_fieldblock",true);
	$xt->assign("r_date_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"raffle_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='raffle_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"raffle_add.php\" ".$onsubmit.">".
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
$control_r_name=array();
$control_r_name["func"]="xt_buildeditcontrol";
$control_r_name["params"] = array();
$control_r_name["params"]["field"]="r_name";
$control_r_name["params"]["value"]=@$defvalues["r_name"];
$control_r_name["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_r_name["params"]["mode"]="inline_add";
else
	$control_r_name["params"]["mode"]="add";
$xt->assignbyref("r_name_editcontrol",$control_r_name);
$control_r_img=array();
$control_r_img["func"]="xt_buildeditcontrol";
$control_r_img["params"] = array();
$control_r_img["params"]["field"]="r_img";
$control_r_img["params"]["value"]=@$defvalues["r_img"];
$control_r_img["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_r_img["params"]["mode"]="inline_add";
else
	$control_r_img["params"]["mode"]="add";
$xt->assignbyref("r_img_editcontrol",$control_r_img);
$control_r_text=array();
$control_r_text["func"]="xt_buildeditcontrol";
$control_r_text["params"] = array();
$control_r_text["params"]["field"]="r_text";
$control_r_text["params"]["value"]=@$defvalues["r_text"];
$control_r_text["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_r_text["params"]["mode"]="inline_add";
else
	$control_r_text["params"]["mode"]="add";
$xt->assignbyref("r_text_editcontrol",$control_r_text);
$control_r_date=array();
$control_r_date["func"]="xt_buildeditcontrol";
$control_r_date["params"] = array();
$control_r_date["params"]["field"]="r_date";
$control_r_date["params"]["value"]=@$defvalues["r_date"];
$control_r_date["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_r_date["params"]["mode"]="inline_add";
else
	$control_r_date["params"]["mode"]="add";
$xt->assignbyref("r_date_editcontrol",$control_r_date);

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
